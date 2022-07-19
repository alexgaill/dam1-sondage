<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuestionController extends AbstractController
{
    #[Route('/questions', name: 'app_question', methods:["GET", "POST"])]
    public function index(ManagerRegistry $manager, Request $request): Response
    {
        $question = new Question;
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->getRepository(Question::class)->add($question, true);

            return $this->redirectToRoute('app_question');
        }

        return $this->renderForm('question/index.html.twig', [
            'questions' => $manager->getRepository(Question::class)->findAll(),
            'form' => $form
        ]);
    }

    #[Route('/question/{id}/update', name:'update_question')]
    public function update(Question $question, ManagerRegistry $manager, Request $request): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->getRepository(Question::class)->add($question, true);
            return $this->redirectToRoute('app_question');
        }

        return $this->renderForm('question/update.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/question/{id}/remove', name:'delete_question', methods:['GET'], requirements:['id' => "\d+"])]
    public function delete(Question $question, ManagerRegistry $manager) :Response
    {
        $manager->getRepository(Question::class)->remove($question, true);

        return $this->redirectToRoute('app_question');
    }
}
