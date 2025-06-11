<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductAutocompleteController extends AbstractController
{
    #[Route('/autocomplete/product', name: 'autocomplete_product')]
    public function __invoke(Request $request, ProductRepository $productRepo): JsonResponse
    {
        $query = $request->query->get('query', '');

        $products = $productRepo->createQueryBuilder('p')
            ->where('p.name LIKE :q')
            ->setParameter('q', '%' . $query . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return new JsonResponse(array_map(fn(Product $product) => [
            'value' => $product->getId(),
            'label' => $product->getName(),
            'data' => [
                'prix' => $product->getPurchasePrice(),
            ],
        ], $products));
    }
}
