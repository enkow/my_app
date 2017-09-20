<?php
/**
 * Test controller.
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Test;
use AppBundle\Form\TestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TestController.
 *
 * @package AppBundle\Controller\Admin
 *
 * @Route("/admin/test")
 */
class TestController extends Controller
{
    /**
     * Template prefix
     *
     * @var $prefix
     */
    protected $prefix = 'admin/test';

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
     *     name="admin_test_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="test_index_paginated",
     * )
     * @Method("GET")
     */
    public function indexAction($page)
    {
        $tests = $this->get('app.repository.test')->findAll();

        return $this->view('index', compact('tests'));
    }

    /**
     * View action.
     *
     * @param Test $test Test entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_test_view",
     * )
     * @Method("GET")
     */
    public function viewAction(Test $test)
    {
        return $this->view('view', compact('test'));
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
     *     name="admin_test_add",
     * )
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $test = new Test();
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $test->setMax($this->get('app.repository.test')->getMaxPoints($test));
            $this->get('app.repository.test')->save($test);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('admin_test_index');
        }

        return $this->view('add', compact('test', 'form'));
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Test                    $test    Test entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}/edit",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_test_edit",
     * )
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Test $test)
    {
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $test->setMax($this->get('app.repository.test')->getMaxPoints($test));
            $this->get('app.repository.test')->save($test);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('admin_test_index');
        }

        return $this->view('edit', compact('test', 'form'));
    }

    /**
     * Active action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param Test                                      $test    Test
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}/active",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_test_active",
     * )
     * @Method({"GET", "POST"})
     */
    public function activeAction(Request $request, Test $test)
    {
        if ($test->getEnd()) {
            $this->addFlash('danger', 'message.already_actived');

            return $this->redirectToRoute('admin_test_index');
        }
        $form = $this->createForm(FormType::class, $test);
        $form->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid()) || $request->query->get('q') == 1) {
            $this->get('app.repository.test')->active($test);
            $this->addFlash('success', 'message.actived_successfully');

            return $this->redirectToRoute('admin_test_index');
        }

        return $this->view('active', compact('test', 'form'));
    }
}
