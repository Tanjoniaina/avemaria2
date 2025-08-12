<?php

namespace App\Pharmaciegros\Controller;

use App\Pharmaciegros\Entity\Stockmovement;
use App\Pharmaciegros\Form\StockmovementForm;
use App\Repository\StockmovementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pharmaciegros/stockmovement')]
final class StockmovementController extends AbstractController
{
    #[Route(name: 'app_pharmaciegros_entity_stockmovement_index', methods: ['GET'])]
    public function index(StockmovementRepository $stockmovementRepository): Response
    {
        return $this->render('pharmaciegros/stockmovement/index.html.twig', [
            'stockmovements' => $stockmovementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pharmaciegros_entity_stockmovement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stockmovement = new Stockmovement();
        $form = $this->createForm(StockmovementForm::class, $stockmovement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stockmovement);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_stockmovement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/stockmovement/new.html.twig', [
            'stockmovement' => $stockmovement,
            'form' => $form,
        ]);
    }

    #[Route('/ajustement', name: 'app_pharmaciegros_entity_stockmovement_ajustement', methods: ['GET', 'POST'])]
    public function ajustement(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stockmovement = new Stockmovement();
        $form = $this->createForm(StockmovementForm::class, $stockmovement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location = $this->getUser()->getLocation();
            $stockmovement->setLocation($location);
            $entityManager->persist($stockmovement);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_stockmovement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/stockmovement/new.html.twig', [
            'stockmovement' => $stockmovement,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_pharmaciegros_entity_stockmovement_show', methods: ['GET'])]
    public function show(Stockmovement $stockmovement): Response
    {
        return $this->render('pharmaciegros/stockmovement/show.html.twig', [
            'stockmovement' => $stockmovement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pharmaciegros_entity_stockmovement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stockmovement $stockmovement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StockmovementForm::class, $stockmovement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_stockmovement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/stockmovement/edit.html.twig', [
            'stockmovement' => $stockmovement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_stockmovement_delete', methods: ['POST'])]
    public function delete(Request $request, Stockmovement $stockmovement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stockmovement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($stockmovement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pharmaciegros_entity_stockmovement_index', [], Response::HTTP_SEE_OTHER);
    }
}
