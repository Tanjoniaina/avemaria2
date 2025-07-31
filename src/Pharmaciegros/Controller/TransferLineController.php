<?php

namespace App\Pharmaciegros\Controller;

use App\Form\Pharmaciegros\Entity\TransferLineForm;
use App\Pharmaciegros\Entity\TransferLine;
use App\Repository\TransferLineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pharmaciegros/transferline')]
final class TransferLineController extends AbstractController
{
    #[Route(name: 'app_pharmaciegros_entity_transfer_line_index', methods: ['GET'])]
    public function index(TransferLineRepository $transferLineRepository): Response
    {
        return $this->render('app/pharmaciegros/entity/transfer_line/index.html.twig', [
            'transfer_lines' => $transferLineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pharmaciegros_entity_transfer_line_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transferLine = new TransferLine();
        $form = $this->createForm(TransferLineForm::class, $transferLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transferLine);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_transfer_line_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('app/pharmaciegros/entity/transfer_line/new.html.twig', [
            'transfer_line' => $transferLine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_transfer_line_show', methods: ['GET'])]
    public function show(TransferLine $transferLine): Response
    {
        return $this->render('app/pharmaciegros/entity/transfer_line/show.html.twig', [
            'transfer_line' => $transferLine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pharmaciegros_entity_transfer_line_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TransferLine $transferLine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransferLineForm::class, $transferLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_transfer_line_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('app/pharmaciegros/entity/transfer_line/edit.html.twig', [
            'transfer_line' => $transferLine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_transfer_line_delete', methods: ['POST'])]
    public function delete(Request $request, TransferLine $transferLine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transferLine->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($transferLine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pharmaciegros_entity_transfer_line_index', [], Response::HTTP_SEE_OTHER);
    }
}
