<?php

namespace App\Pharmaciegros\Controller;

use App\Pharmaciegros\Entity\Stockmovement;
use App\Pharmaciegros\Entity\Transfer;
use App\Pharmaciegros\Form\TransferForm;
use App\Pharmaciegros\Service\StockManager;
use App\Repository\TransferLineRepository;
use App\Repository\TransferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pharmaciegros/transfer')]
final class TransferController extends AbstractController
{
    #[Route(name: 'app_pharmaciegros_entity_transfer_index', methods: ['GET'])]
    public function index(TransferRepository $transferRepository): Response
    {
        return $this->render('pharmaciegros/transfer/index.html.twig', [
            'transfers' => $transferRepository->findAll(),
        ]);
    }

    #[Route('/newtransfert', name: 'app_pharmaciegros_entity_transfer_new', methods: ['GET', 'POST'])]
    public function newtransfert(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transfer = new Transfer();
        $form = $this->createForm(TransferForm::class, $transfer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($transfer->getLigne() as $ligne) {
                $ligne->setTransfert($transfer);
                $ligne->setProduct($ligne->getProduct());
                $ligne->setQuantity($ligne->getQuantity());
                $entityManager->persist($ligne);
            }

            $transfer->setType('TRANSFERT');
            $entityManager->persist($transfer);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_transfer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/transfer/new.html.twig', [
            'transfer' => $transfer,
            'form' => $form,
        ]);
    }

    #[Route('/newrequest', name: 'app_pharmaciegros_entity_request_new', methods: ['GET', 'POST'])]
    public function newrequest(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transfer = new Transfer();
        $form = $this->createForm(TransferForm::class, $transfer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($transfer->getLigne() as $ligne) {
                $ligne->setTransfert($transfer);
                $ligne->setProduct($ligne->getProduct());
                $ligne->setQuantity($ligne->getQuantity());
                $entityManager->persist($ligne);
            }
            $transfer->setType('REQUEST');
            $entityManager->persist($transfer);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_transfer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/transfer/new.html.twig', [
            'transfer' => $transfer,
            'form' => $form,
        ]);
    }

    #[Route('/transfert/attentevalidation', name: 'transfert_enattente')]
    public function Transfetenattente(TransferRepository $transferRepository): Response
    {
        //TODO atao specifique isaky ny service
        $transfert = $transferRepository->findBy(['status'=>'Envoyé','type'=>'TRANSFERT']);

        return $this->render('pharmaciegros/transfer/enattente.html.twig', [
            'transfert' => $transfert,
        ]);
    }

    #[Route('/requeest/attentevalidation', name: 'request_enattente')]
    public function Requestenattente(TransferRepository $transferRepository): Response
    {
        //TODO atao specifique isaky ny service
        $transfert = $transferRepository->findBy(['status'=>'Envoyé','type'=>'REQUEST']);

        return $this->render('pharmaciegros/transfer/enattente.html.twig', [
            'transfert' => $transfert,
        ]);
    }


    #[Route('/transfert/{id}/valider', name: 'transfert_valider')]
    public function validertransfer(Transfer $transfert, StockManager $stockManager): Response
    {
        $transfert->setStatus('Reçu');
        $stockManager->applyTransfert($transfert);

        return $this->redirectToRoute('app_pharmaciegros_entity_transfer_index');
    }

    #[Route('/request/{id}/valider', name: 'request_valider')]
    public function validerrequest(Transfer $transfert, StockManager $stockManager): Response
    {
        $transfert->setStatus('Reçu');
        $stockManager->applyRequest($transfert);

        return $this->redirectToRoute('app_pharmaciegros_entity_transfer_index');
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_transfer_show', methods: ['GET'])]
    public function show(Transfer $transfer, TransferLineRepository $transferlineRepository): Response
    {

        $transferline = $transferlineRepository->findBy(['transfert'=>$transfer]);

        return $this->render('pharmaciegros/transfer/show.html.twig', [
            'transfer' => $transfer,
            'transferline' => $transferline
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pharmaciegros_entity_transfer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transfer $transfer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransferForm::class, $transfer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_transfer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/transfer/edit.html.twig', [
            'transfer' => $transfer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_transfer_delete', methods: ['POST'])]
    public function delete(Request $request, Transfer $transfer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transfer->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($transfer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pharmaciegros_entity_transfer_index', [], Response::HTTP_SEE_OTHER);
    }
}
