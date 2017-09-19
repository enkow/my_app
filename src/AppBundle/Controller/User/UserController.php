<?php
/**
 * User controller.
 */

namespace AppBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Test;

/**
 * Class UserController.
 *
 * @package AppBundle\Controller
 *
 */
class UserController extends Controller
{
    /**
     * Index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/",
     *     name="user",
     * )
     * @Method("GET")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $test = $this->get('app.repository.test')->findAvailableTest($user);

        return $this->render('user/dashboard/index.html.twig', compact('test'));
    }

    /**
     * Render test.
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}/test",
     *     name="user_test",
     * )
     * @Method("GET")
     */
    public function renderTestAction(Test $test)
    {
        if (!in_array($_SERVER["REMOTE_ADDR"], json_decode($test->getIp()))) {
            $this->addFlash('danger', 'message.cant_start_test');

            return $this->redirectToRoute('user');
        }
        $user = $this->getUser();
        $available = $this->get('app.repository.test')->findAvailableTest($user);
        if (!$available || $available->getId() != $test->getId()) {
            $this->addFlash('danger', 'message.cant_start_test');

            return $this->redirectToRoute('user');
        }

        return $this->render('user/dashboard/test.html.twig', compact('test'));
    }
}
