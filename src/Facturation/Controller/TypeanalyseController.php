<?php

namespace App\Facturation\Controller;

use App\Facturation\Entity\Typeanalyse;
use App\Facturation\Form\TypeanalyseForm;
use App\Repository\TypeanalyseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/typeanalyse')]
final class TypeanalyseController extends AbstractController
{
    #[Route(name: 'app_facturation_entity_typeanalyse_index', methods: ['GET'])]
    public function index(TypeanalyseRepository $typeanalyseRepository): Response
    {
        return $this->render('facturation/typeanalyse/index.html.twig', [
            'typeanalyses' => $typeanalyseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_facturation_entity_typeanalyse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeanalyse = new Typeanalyse();
        $form = $this->createForm(TypeanalyseForm::class, $typeanalyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeanalyse);
            $entityManager->flush();

            return $this->redirectToRoute('app_facturation_entity_typeanalyse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facturation/typeanalyse/new.html.twig', [
            'typeanalyse' => $typeanalyse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facturation_entity_typeanalyse_show', methods: ['GET'])]
    public function show(Typeanalyse $typeanalyse): Response
    {
        return $this->render('facturation/typeanalyse/show.html.twig', [
            'typeanalyse' => $typeanalyse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facturation_entity_typeanalyse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Typeanalyse $typeanalyse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeanalyseForm::class, $typeanalyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facturation_entity_typeanalyse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facturation/typeanalyse/edit.html.twig', [
            'typeanalyse' => $typeanalyse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facturation_entity_typeanalyse_delete', methods: ['POST'])]
    public function delete(Request $request, Typeanalyse $typeanalyse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeanalyse->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($typeanalyse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_facturation_entity_typeanalyse_index', [], Response::HTTP_SEE_OTHER);
    }
}
