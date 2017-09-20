<?php
/**
 * Auth Controller
 */

namespace AppBundle\Controller\User;

use AppBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\AuthType;
use AppBundle\Form\AdminEditType;
use AppBundle\Form\RegisterType;
use AppBundle\Repository\UserRepository;
use AppBundle\Entity\User;

/**
 * Class AuthController
 *
 * @package AppBundle\Controller\Admin
 */
class AuthController extends Controller
{
    /**
     * Template prefix
     *
     * @var $prefix
     */
    protected $prefix = 'user/auth';

    /**
     * Login action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/login",
     *     name="auth_login"
     * )
     * @Method("GET")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->view('login', compact('lastUsername', 'error'));
    }

    /**
     * Login check action
     *
     * @Route(
     *     "/login_check",
     *     name="auth_login_check"
     * )
     * @Method("POST")
     */
    public function loginCheckAction()
    {
    }

    /**
     * Logout action
     *
     * @Route(
     *     "/logout",
     *     name="auth_logout"
     * )
     * @Method("GET")
     */
    public function logoutAction()
    {
    }

    /**
     * Register action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/register",
     *     name="auth_register"
     * )
     * @Method({"GET", "POST"})
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user, ['validation_groups' => 'user-register']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $form->get('password')->getData());
            $role = $this->get('app.repository.role')->findOneByName('ROLE_USER');
            $templating = $this->get('templating');
            exit;
            $message = $this->get('app.repository.user')->createUser($templating, $user, $role, $password);
            $this->get('mailer')->send($message);
            $this->addFlash('success', 'message.registration_completed');

            return $this->redirectToRoute('auth_login');
        }

        return $this->view('register', compact('form'));
    }

    /**
     * Confirm action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param string                                    $token   Reset token
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/register/{token}",
     *     requirements={"token": "[0-9a-z]*"},
     *     name="auth_confirm"
     * )
     * @Method("GET")
     */
    public function confirmAction(Request $request, $token)
    {
        $user = $this->get('app.repository.user')->findOneByConfirmToken($token);
        if (!$user) {
            $this->addFlash('danger', 'message.invalid_token');

            return $this->redirectToRoute('auth_login');
        }

        $this->get('app.repository.user')->confirmUser($user);
        $this->addFlash('success', 'message.account_confirmed');

        return $this->redirectToRoute('auth_login');
    }

    /**
     * Reset password action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/reset",
     *     name="auth_reset"
     * )
     * @Method({"GET", "POST"})
     */
    public function resetAction(Request $request)
    {
        $form = $this->createForm(AuthType::class)->remove('username')->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->get('app.repository.user')->findOneByEmail($form->get('email')->getData());

            if ($user) {
                $templating = $this->get('templating');
                $message = $this->get('app.repository.user')->resetPassword($templating, $user);
                $this->get('mailer')->send($message);
            }
            $this->addFlash('success', 'message.reset_sent');

            return $this->redirectToRoute('auth_login');
        }

        return $this->view('reset', compact('form'));
    }

    /**
     * Change password action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param string                                    $token   Reset token
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/change/{token}",
     *     requirements={"token": "[0-9a-z]*"},
     *     name="auth_change"
     * )
     * @Method({"GET", "POST"})
     */
    public function changeAction(Request $request, $token)
    {
        $user = $this->get('app.repository.user')->findOneByResetToken($token);

        if (!$user) {
            $this->addFlash('danger', 'message.invalid_token');

            return $this->redirectToRoute('auth_login');
        }

        $form = $this->createForm(AdminEditType::class, null, ['validation_groups' => 'user-reset']);
        $form->remove('username')->remove('email')->remove('current');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $form->get('password')->getData());
            $this->get('app.repository.user')->changePassword($user, $password);
            if ($user->getApproved()) {
                $this->authenticateUser($user);
            }

            $this->addFlash('success', 'message.password_success');

            return $this->redirectToRoute('homepage');
        }

        return $this->view('change', compact('form'));
    }

    /**
     * Edit profile action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/edit/profile",
     *     name="auth_edit"
     * )
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(AdminEditType::class, $user);
        $form->remove('username')->remove('email');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->get('security.password_encoder')->isPasswordValid($user, $form->get('current')->getData())) {
                $this->addFlash('danger', 'message.wrong_current_password');

                return $this->redirectToRoute('admin_auth_edit');
            }
            $password = $form->get('password')->getData();
            $password = $password ? $this->get('security.password_encoder')->encodePassword($user, $password): null;

            $this->get('app.repository.user')->editProfile($user, $password);
            $this->addFlash('success', 'message.edit_success');

            return $this->redirectToRoute('auth_edit');
        }

        return $this->view('edit', compact('form'));
    }

    /**
     * AutoAuthenticator
     *
     * @param User $user User
     */
    private function authenticateUser(User $user)
    {
        $providerKey = 'user';
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $user->setLastLogin(new \DateTime());

        $this->get('security.token_storage')->setToken($token);
        $this->get('app.repository.user')->save($user);
    }
}
