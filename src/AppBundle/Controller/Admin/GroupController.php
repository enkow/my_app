<?php
/**
 * Group controller.
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Group;
use AppBundle\Form\GroupType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GroupController.
 *
 * @package AppBundle\Controller\Admin
 *
 * @Route("/admin/group")
 */
class GroupController extends Controller
{
    /**
     * Template prefix
     *
     * @var $prefix
     */
    protected $prefix = 'admin/group';

    /**
     * Index action.
     *
     * @param integer $page Current page number
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/",
     *     defaults={"page": 1},
     *     name="admin_group_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="group_index_paginated",
     * )
     * @Method("GET")
     */
    public function indexAction($page)
    {
        $groups = $this->get('app.repository.group')->findAll();

        return $this->view('index', compact('groups'));
    }

    /**
     * View action.
     *
     * @param Group $group Group entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_group_view",
     * )
     * @Method("GET")
     */
    public function viewAction(Group $group)
    {
        $users = [];

        return $this->view('view', compact('group', 'users'));
    }

    /**
     * Add action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/add",
     *     name="admin_group_add",
     * )
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.repository.group')->save($group);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('admin_group_index');
        }

        return $this->view('add', compact('group', 'form'));
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Group                   $group   Group entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}/edit",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_group_edit",
     * )
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Group $group)
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.repository.group')->save($group);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('admin_group_index');
        }

        return $this->view('edit', compact('group', 'form'));
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Group                   $group   Group entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}/delete",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_group_delete",
     * )
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Group $group)
    {
        $form = $this->createForm(FormType::class, $group);
        $form->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid()) || $request->query->get('q') == 1) {
            $this->get('app.repository.group')->delete($group);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('admin_group_index');
        }

        return $this->view('delete', compact('group', 'form'));
    }
}
