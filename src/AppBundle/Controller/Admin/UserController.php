<?php
/**
 * User controller.
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\SearchType;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController.
 *
 * @package AppBundle\Controller\Admin
 *
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * Template prefix
     *
     * @var $prefix
     */
    protected $prefix = 'admin/user';

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param integer                                   $page    Current page number
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/",
     *     defaults={"page": 1},
     *     name="admin_user_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="user_index_paginated",
     * )
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, $page)
    {
        $users = [];
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $string = $form->get('q')->getData();
            $users = $this->get('app.repository.user')->findByString($string);
        }

        return $this->view('index', compact('users', 'form'));
    }

    /**
     * Not approved action.
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/not-approved",
     *     name="admin_user_not_approved",
     * )
     * @Method("GET")
     */
    public function notApprovedAction()
    {
        $users = $this->get('app.repository.user')->findByApproved(0);

        return $this->view('approved', compact('users'));
    }

    /**
     * Approve action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\User                    $user    User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}/approve",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_user_approve",
     * )
     * @Method({"GET", "POST"})
     */
    public function approveAction(Request $request, User $user)
    {
        $form = $this->createForm(FormType::class, $user);
        $form->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid()) || $request->query->get('q') == 1) {
            $this->get('app.repository.user')->approve($user);
            $this->addFlash('success', 'message.approved_successfully');

            return $this->redirectToRoute('admin_user_not_approved');
        }

        return $this->view('approve', compact('user', 'form'));
    }

    /**
     * View action.
     *
     * @param User $user User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_user_view",
     * )
     * @Method("GET")
     */
    public function viewAction(User $user)
    {
        $users = $this->get('app.repository.user')->findAll();

        return $this->view('view', compact('user', 'users'));
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\User                    $user    User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}/edit",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_user_edit",
     * )
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user, ['validation_groups' => 'admin-edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.repository.user')->save($user);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->view('edit', compact('user', 'form'));
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\User                    $user    User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}/delete",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_user_delete",
     * )
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createForm(FormType::class, $user);
        $form->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid()) || $request->query->get('q') == 1) {
            $this->get('app.repository.user')->delete($user);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->view('delete', compact('user', 'form'));
    }
}
