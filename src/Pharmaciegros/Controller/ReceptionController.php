<?php

namespace App\Pharmaciegros\Controller;

use App\Pharmaciegros\Entity\Reception;
use App\Pharmaciegros\Form\ReceptionForm;
use App\Repository\PurchaseorderRepository;
use App\Repository\ReceptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pharmaciegros/reception')]
final class ReceptionController extends AbstractController
{
    #[Route(name: 'app_pharmaciegros_entity_reception_index', methods: ['GET'])]
    public function index(PurchaseorderRepository $purchaseorderRepository): Response
    {
        $bondecommande = $purchaseorderRepository->findBy(['status'=>'envoye']);

        return $this->render('pharmaciegros/reception/index.html.twig', [
            'bondecommande' => $bondecommande
        ]);
    }

    #[Route('/new/{bondecommande}', name: 'app_pharmaciegros_entity_reception_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $bondecommande, PurchaseorderRepository $purchaseorderRepository): Response
    {
        $reception = new Reception();
        $reception->setReceiveddate(new \DateTime());
        $form = $this->createForm(ReceptionForm::class, $reception);
        $reception->setReceiveddate(new \DateTime());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reception->getPurchaseorder($purchaseorderRepository->find($bondecommande));
            $entityManager->persist($reception);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_reception_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/reception/new.html.twig', [
            'reception' => $reception,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_reception_show', methods: ['GET'])]
    public function show(Reception $reception): Response
    {
        return $this->render('pharmaciegros/reception/show.html.twig', [
            'reception' => $reception,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pharmaciegros_entity_reception_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reception $reception, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReceptionForm::class, $reception);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_reception_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/reception/edit.html.twig', [
            'reception' => $reception,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_reception_delete', methods: ['POST'])]
    public function delete(Request $request, Reception $reception, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reception->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reception);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pharmaciegros_entity_reception_index', [], Response::HTTP_SEE_OTHER);
    }
}
