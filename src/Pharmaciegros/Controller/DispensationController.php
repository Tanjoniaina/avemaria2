<?php

namespace App\Pharmaciegros\Controller;

use App\Pharmaciegros\Entity\Dispensation;
use App\Pharmaciegros\Form\DispensationForm;
use App\Repository\DispensationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/pharmaciegros/entity/dispensation')]
final class DispensationController extends AbstractController
{
    #[Route(name: 'app_pharmaciegros_entity_dispensation_index', methods: ['GET'])]
    public function index(DispensationRepository $dispensationRepository): Response
    {
        return $this->render('pharmaciegros/dispensation/index.html.twig', [
            'dispensations' => $dispensationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pharmaciegros_entity_dispensation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dispensation = new Dispensation();
        $form = $this->createForm(DispensationForm::class, $dispensation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dispensation);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_dispensation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/dispensation/new.html.twig', [
            'dispensation' => $dispensation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_dispensation_show', methods: ['GET'])]
    public function show(Dispensation $dispensation): Response
    {
        return $this->render('app/pharmaciegros/entity/dispensation/show.html.twig', [
            'dispensation' => $dispensation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pharmaciegros_entity_dispensation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dispensation $dispensation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DispensationForm::class, $dispensation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_dispensation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/dispensation/edit.html.twig', [
            'dispensation' => $dispensation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_dispensation_delete', methods: ['POST'])]
    public function delete(Request $request, Dispensation $dispensation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dispensation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dispensation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pharmaciegros_entity_dispensation_index', [], Response::HTTP_SEE_OTHER);
    }
}
