<?php
/**
 * Presence controller.
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Entity\Presence;
use AppBundle\Form\PresenceType;
use AppBundle\Form\ActivePresenceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PresenceController.
 *
 * @package AppBundle\Controller\Admin
 *
 * @Route("/admin/attendance-list")
 */
class PresenceController extends Controller
{
    /**
     * Template prefix
     *
     * @var $prefix
     */
    protected $prefix = 'admin/presence';

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
     *     name="admin_presence_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="presence_index_paginated",
     * )
     * @Method("GET")
     */
    public function indexAction($page)
    {
        $year = $this->get('app.repository.year')->findOneByActive(1);
        $presences = $this->get('app.repository.presence')->findByYear($year);

        return $this->view('index', compact('presences', 'groups'));
    }

    /**
     * Active action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request  HTTP Request
     * @param \AppBundle\Entity\Presence                $presence Presence entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}/active",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_presence_active",
     * )
     * @Method({"GET", "POST"})
     */
    public function activeAction(Request $request, Presence $presence)
    {
        $form = $this->createForm(ActivePresenceType::class, $presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.repository.presence')->active($presence);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('admin_presence_index');
        }

        return $this->view('active', compact('presence', 'form'));
    }

    /**
     * Change status action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Presence                     $presence     Presence entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{user}/{presence}/change",
     *     requirements={"user": "[1-9]\d*"},
     *     requirements={"presence": "[1-9]\d*"},
     *     name="admin_presence_change",
     * )
     * @Method("POST")
     */
    public function changeStatusAction(Request $request, User $user, Presence $presence)
    {
        $status = $request->request->get('status');
        if (!$status || $status < 0 || $status > 3) {
            $this->addFlash('danger', 'message.choose_correct_status');

            return $this->redirectToRoute('admin_presence_view', ['id' => $presence->getId()]);
        }

        $this->get('app.repository.presence')->save($presence);
        $this->addFlash('success', 'message.updated_successfully');

        return $this->redirectToRoute('admin_presence_index');


        return $this->view('edit', compact('presence', 'form'));
    }

    /**
     * View action.
     *
     * @param Presence $presence Presence entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_presence_view",
     * )
     * @Method("GET")
     */
    public function viewAction(Presence $presence)
    {
        $year = $this->get('app.repository.year')->findOneByActive(1);
        $repo = $this->get('app.repository.user');
        $users = $repo->findByYear($year);

        return $this->view('view', compact('presence', 'users', 'repo'));
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
     *     name="admin_presence_add",
     * )
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $year = $this->get('app.repository.year')->findOneByActive(1);
        if (!$year) {
            $this->addFlash('danger', 'message.any_year_must_be_active');

            return $this->redirectToRoute('admin_presence_view', ['id' => $presence->getId()]);
        }
        $presence = new Presence();
        $form = $this->createForm(PresenceType::class, $presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $presence->setYear($year);
            $this->get('app.repository.presence')->save($presence);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('admin_presence_view', ['id' => $presence->getId()]);
        }

        return $this->view('add', compact('presence', 'form'));
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Presence                     $presence     Presence entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}/edit",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_presence_edit",
     * )
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Presence $presence)
    {
        $form = $this->createForm(PresenceType::class, $presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.repository.presence')->save($presence);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('admin_presence_index');
        }

        return $this->view('edit', compact('presence', 'form'));
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Presence                     $presence     Presence entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}/delete",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_presence_delete",
     * )
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Presence $presence)
    {
        $form = $this->createForm(FormType::class, $presence);
        $form->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid()) || $request->query->get('q') == 1) {
            $this->get('app.repository.presence')->delete($presence);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('admin_presence_index');
        }

        return $this->view('delete', compact('presence', 'form'));
    }
}
