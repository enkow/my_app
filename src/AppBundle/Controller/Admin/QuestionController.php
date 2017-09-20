<?php
/**
 * Question controller.
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class QuestionController.
 *
 * @package AppBundle\Controller\Admin
 *
 * @Route("/admin/question")
 */
class QuestionController extends Controller
{
    /**
     * Template prefix
     *
     * @var $prefix
     */
    protected $prefix = 'admin/question';

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
     *     name="admin_question_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="question_index_paginated",
     * )
     * @Method("GET")
     */
    public function indexAction($page)
    {
        $questions = $this->get('app.repository.question')->findAll();

        return $this->view('index', compact('questions'));
    }

    /**
     * View action.
     *
     * @param Question $question Question entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_question_view",
     * )
     * @Method("GET")
     */
    public function viewAction(Question $question)
    {
        return $this->view('view', compact('question'));
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
     *     name="admin_question_add",
     * )
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.repository.question')->save($question);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('admin_question_index');
        }

        return $this->view('add', compact('question', 'form'));
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request  HTTP Request
     * @param \AppBundle\Entity\Question                $question Question entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}/edit",
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_question_edit",
     * )
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Question $question)
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.repository.question')->save($question);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('admin_question_index');
        }

        return $this->view('edit', compact('question', 'form'));
    }
}
