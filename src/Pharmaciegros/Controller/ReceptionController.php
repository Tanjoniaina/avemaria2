<?php

namespace App\Pharmaciegros\Controller;

use App\Pharmaciegros\Entity\Reception;
use App\Pharmaciegros\Form\ReceptionForm;
use App\Repository\ReceptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/pharmaciegros/entity/reception')]
final class ReceptionController extends AbstractController
{
    #[Route(name: 'app_pharmaciegros_entity_reception_index', methods: ['GET'])]
    public function index(ReceptionRepository $receptionRepository): Response
    {
        return $this->render('app/pharmaciegros/entity/reception/index.html.twig', [
            'receptions' => $receptionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pharmaciegros_entity_reception_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reception = new Reception();
        $form = $this->createForm(ReceptionForm::class, $reception);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reception);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_reception_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('app/pharmaciegros/entity/reception/new.html.twig', [
            'reception' => $reception,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_reception_show', methods: ['GET'])]
    public function show(Reception $reception): Response
    {
        return $this->render('app/pharmaciegros/entity/reception/show.html.twig', [
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

        return $this->render('app/pharmaciegros/entity/reception/edit.html.twig', [
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
