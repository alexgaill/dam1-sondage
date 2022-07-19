<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReponseController extends AbstractController
{
    #[Route('/reponse', name: 'app_reponse', methods:["GET", "POST"])]
    public function index(ManagerRegistry $manager, Request $request): Response
    {
        $reponse = new Reponse;
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setScore(0);
            $manager->getRepository(Reponse::class)->add($reponse, true);
            return $this->redirectToRoute('app_reponse');
        }
        return $this->renderForm('reponse/index.html.twig', [
            'reponses' => $manager->getRepository(Reponse::class)->findAll(),
            'form' => $form
        ]);
    }

    #[Route('/reponse/upVote/{id}', name:'upVote', methods:['GET'], requirements:['id' => "\d+"])]
    public function upVote(ManagerRegistry $manager, Reponse $reponse) :Response
    {
        $reponse->setScore($reponse->getScore() +1);
        $manager->getRepository(Reponse::class)->add($reponse, true);

        return $this->redirectToRoute('single_sondage', ['id' => $reponse->getQuestion()->getSondage()->getId()]);
    }

    #[Route('/reponse/{id}/remove', name:'delete_reponse', methods:['GET'], requirements:['id' => "\d+"])]
    public function delete(Reponse $reponse, ManagerRegistry $manager) :Response
    {
        $manager->getRepository(Reponse::class)->remove($reponse, true);

        return $this->redirectToRoute('app_reponse');
    }
}
