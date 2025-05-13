<?php

namespace App\Accueil\Controller;


use App\Shared\Entity\Dossierpatient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    #[Route('/nouveauconsultation', name: 'app__new_consultation', methods: ['GET', 'POST'])]
    public function newconsultation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dossierpatient = new Dossierpatient();

        dd('ato alo');
        return $this->redirectToRoute('app_accueil');
    }
}
