<?php

namespace App\Pharmaciegros\Controller;

use App\Pharmaciegros\Entity\Purchaseorderline;
use App\Pharmaciegros\Form\PurchaseorderlineForm;
use App\Repository\PurchaseorderlineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/pharmaciegros/entity/purchaseorderline')]
final class PurchaseorderlineController extends AbstractController
{
    #[Route(name: 'app_pharmaciegros_entity_purchaseorderline_index', methods: ['GET'])]
    public function index(PurchaseorderlineRepository $purchaseorderlineRepository): Response
    {
        return $this->render('app/pharmaciegros/entity/purchaseorderline/index.html.twig', [
            'purchaseorderlines' => $purchaseorderlineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pharmaciegros_entity_purchaseorderline_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $purchaseorderline = new Purchaseorderline();
        $form = $this->createForm(PurchaseorderlineForm::class, $purchaseorderline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($purchaseorderline);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_purchaseorderline_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('app/pharmaciegros/entity/purchaseorderline/new.html.twig', [
            'purchaseorderline' => $purchaseorderline,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_purchaseorderline_show', methods: ['GET'])]
    public function show(Purchaseorderline $purchaseorderline): Response
    {
        return $this->render('app/pharmaciegros/entity/purchaseorderline/show.html.twig', [
            'purchaseorderline' => $purchaseorderline,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pharmaciegros_entity_purchaseorderline_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Purchaseorderline $purchaseorderline, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PurchaseorderlineForm::class, $purchaseorderline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_purchaseorderline_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('app/pharmaciegros/entity/purchaseorderline/edit.html.twig', [
            'purchaseorderline' => $purchaseorderline,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_purchaseorderline_delete', methods: ['POST'])]
    public function delete(Request $request, Purchaseorderline $purchaseorderline, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$purchaseorderline->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($purchaseorderline);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pharmaciegros_entity_purchaseorderline_index', [], Response::HTTP_SEE_OTHER);
    }
}
