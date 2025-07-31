<?php

namespace App\Pharmaciegros\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/autocomplete/product', name: 'autocomplete_product')]
class ProductAutocompleteController extends AbstractController
{
    #[Route('', name: '_search')]
    public function __invoke(Request $request, ProductRepository $productRepo): JsonResponse
    {
        $query = $request->query->get('query', '');
        $products = $productRepo->createQueryBuilder('p')
            ->where('p.name LIKE :q')
            ->setParameter('q', '%' . $query . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return new JsonResponse(array_map(fn($product) => [
            'value' => $product->getId(),
            'label' => $product->getName(),
            'data' => [
                'prix' => $product->getPurchasePrice(),
            ],
        ], $products));
    }
}
