<?php

namespace App\Controller;

use App\Entity\Sondage;
use App\Form\SondageType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(ManagerRegistry $manager): Response
    {
        // On récupère tous les sondages pour les afficher sur la page d'accueil
        return $this->render('home/index.html.twig', [
            'sondages' => $manager->getRepository(Sondage::class)->findAll(),
        ]);
    }

    #[Route('/sondage/add', name:'add_sondage', methods:["GET", "POST"])]
    public function addSondage(ManagerRegistry $manager, Request $request) :Response
    {
        $sondage = new Sondage;
        $form = $this->createForm(SondageType::class, $sondage);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->getRepository(Sondage::class)->add($sondage, true);

            return $this->redirectToRoute("home");
        }

        return $this->renderForm("home/add.html.twig", [
            'form' => $form
        ]);
    }

    #[Route('/sondage/{id}', name:'single_sondage', methods:['GET'], requirements:['id' => "\d+"])]
    public function single(Sondage $sondage) :Response
    {
        return $this->render('home/single.html.twig', [
            'sondage' => $sondage
        ]);
    }
}
