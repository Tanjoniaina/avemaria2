<?php

namespace App\Pharmaciegros\Controller;

use App\Pharmaciegros\Entity\Payment;
use App\Pharmaciegros\Form\PaymentForm;
use App\Repository\InvoiceRepository;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pharmaciegros/payment')]
final class PaymentController extends AbstractController
{
    #[Route(name: 'app_pharmaciegros_entity_payment_index', methods: ['GET'])]
    public function index(PaymentRepository $paymentRepository,InvoiceRepository $invoiceRepository): Response
    {
        $facture = $invoiceRepository->findEnAttenteDePaiement();
        return $this->render('pharmaciegros/payment/index.html.twig', [
            'payments' => $paymentRepository->findAll(),
            'facture' => $facture
        ]);
    }

    #[Route('/new/{invoice}', name: 'app_pharmaciegros_entity_payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $invoice): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentForm::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_payment_show', methods: ['GET'])]
    public function show(Payment $payment): Response
    {
        return $this->render('pharmaciegros/payment/show.html.twig', [
            'payment' => $payment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pharmaciegros_entity_payment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaymentForm::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmaciegros_entity_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pharmaciegros/payment/edit.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pharmaciegros_entity_payment_delete', methods: ['POST'])]
    public function delete(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($payment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pharmaciegros_entity_payment_index', [], Response::HTTP_SEE_OTHER);
    }
}
