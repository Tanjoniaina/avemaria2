<?php

namespace App\Pharmaciegros\Controller;

use App\Pharmaciegros\Entity\Purchaseorder;
use App\Pharmaciegros\Form\PurchaseorderForm;
use App\Repository\PurchaseorderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pharmaciegros/purchaseorder')]
final class PurchaseorderController extends AbstractController
{
    #[Route(name: 'app_pharmaciegros_entity_purchaseorder_index', methods: ['GET'])]
    public function index(PurchaseorderRepository $purchaseorderRepository): Response
    {
        return $this->render('pharmaciegros/purchaseorder/index.html.twig', [
            'purchaseorders' => $purchaseorderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pharmaciegros_entity_purchaseorder_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $purchaseorder = new Purchaseorder();
        $form = $this->createForm(PurchaseorderForm::class, $purchaseorder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($purchaseorder);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_purchaseorder_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/purchaseorder/new.html.twig', [
            'purchaseorder' => $purchaseorder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_purchaseorder_show', methods: ['GET'])]
    public function show(Purchaseorder $purchaseorder): Response
    {
        return $this->render('pharmaciegros/purchaseorder/show.html.twig', [
            'purchaseorder' => $purchaseorder,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pharmaciegros_entity_purchaseorder_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Purchaseorder $purchaseorder, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PurchaseorderForm::class, $purchaseorder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_purchaseorder_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/purchaseorder/edit.html.twig', [
            'purchaseorder' => $purchaseorder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_purchaseorder_delete', methods: ['POST'])]
    public function delete(Request $request, Purchaseorder $purchaseorder, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$purchaseorder->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($purchaseorder);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pharmaciegros_entity_purchaseorder_index', [], Response::HTTP_SEE_OTHER);
    }
}
