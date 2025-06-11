<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductAutocompleteController extends AbstractController
{
    #[Route('/product/autocomplete', name: 'app_product_autocomplete')]
    public function index(): Response
    {
        return $this->render('product_autocomplete/index.html.twig', [
            'controller_name' => 'ProductAutocompleteController',
        ]);
    }
}
