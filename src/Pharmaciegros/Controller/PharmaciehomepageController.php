<?php

namespace App\Pharmaciegros\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PharmaciehomepageController extends AbstractController
{
    #[Route('/pharmaciehomepage', name: 'app_pharmaciehomepage')]
    public function index(): Response
    {
        return $this->render('pharmaciegros/index.html.twig', [
            'controller_name' => 'PharmaciehomepageController',
        ]);
    }

    #[Route('/pharmaciehomepage/dashboard', name: 'app_pharmaciehomepage_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('pharmaciegros/dashboard.html.twig', [
            'controller_name' => 'PharmaciehomepageController',
        ]);
    }


}
