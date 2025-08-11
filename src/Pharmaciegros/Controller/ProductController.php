<?php

namespace App\Pharmaciegros\Controller;

use App\Pharmaciegros\Entity\Product;
use App\Pharmaciegros\Form\ProductForm;
use App\Pharmaciegros\Service\StockManager;
use App\Repository\CategoryRepository;
use App\Repository\LocationRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pharmaciegros/product')]
final class ProductController extends AbstractController
{
    #[Route(name: 'app_pharmaciegros_entity_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, LocationRepository $locationRepository, StockManager $stockManager): Response
    {
        $categories = $categoryRepository->findAllWithProducts();
        $pharmaciedegros = $locationRepository->findOneBy(['name'=>'Pharmacie']);

        foreach ($categories as $category) {
            foreach ($category->getProducts() as $product) {
                $stock = $stockManager->getStockActuel($product, $pharmaciedegros);
                $product->stockAtPharmacy = $stock;
            }
        }

        return $this->render('pharmaciegros/product/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/new', name: 'app_pharmaciegros_entity_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/nouveau-rapide', name: 'product_quick_create')]
    public function quickCreate(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductForm::class, $product, [
            'action' => $this->generateUrl('product_quick_create'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            return new JsonResponse([
                'id' => $product->getId(),
                'name' => $product->getName(),
            ]);
        }

        return $this->render('pharmaciegros/product/_quick_create_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/stockmini', name: 'app_pharmaciegros_stock_min')]
    public function stockmin(ProductRepository $productRepository): Response
    {
        $categories = $productRepository->findProduitsSousStockMin();
        dd($categories);
        return $this->render('pharmaciegros/product/stockmini.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('pharmaciegros/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pharmaciegros_entity_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pharmaciegros_entity_product_index', [], Response::HTTP_SEE_OTHER);
    }




}
