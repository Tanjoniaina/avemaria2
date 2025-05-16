<?php

namespace App\Shared\Controller;

use App\Repository\TarifacteRepository;
use App\Shared\Entity\Tarifacte;
use App\Shared\Form\TarifacteForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tarifacte')]
final class TarifacteController extends AbstractController
{
    #[Route(name: 'app_shared_entity_tarifacte_index', methods: ['GET'])]
    public function index(TarifacteRepository $tarifacteRepository): Response
    {
        return $this->render('shared/tarifacte/index.html.twig', [
            'tarifactes' => $tarifacteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_shared_entity_tarifacte_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tarifacte = new Tarifacte();
        $form = $this->createForm(TarifacteForm::class, $tarifacte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tarifacte);
            $entityManager->flush();

            return $this->redirectToRoute('app_shared_entity_tarifacte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shared/tarifacte/new.html.twig', [
            'tarifacte' => $tarifacte,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shared_entity_tarifacte_show', methods: ['GET'])]
    public function show(Tarifacte $tarifacte): Response
    {
        return $this->render('shared/tarifacte/show.html.twig', [
            'tarifacte' => $tarifacte,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_shared_entity_tarifacte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tarifacte $tarifacte, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TarifacteForm::class, $tarifacte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_shared_entity_tarifacte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shared/tarifacte/edit.html.twig', [
            'tarifacte' => $tarifacte,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shared_entity_tarifacte_delete', methods: ['POST'])]
    public function delete(Request $request, Tarifacte $tarifacte, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tarifacte->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tarifacte);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_shared_entity_tarifacte_index', [], Response::HTTP_SEE_OTHER);
    }
}
