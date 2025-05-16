<?php

namespace App\Economat\Controller;

use App\Economat\Entity\Paiement;
use App\Economat\Form\PaiementForm;
use App\Repository\DossierpatientRepository;
use App\Repository\FactureRepository;
use App\Repository\LignefactureRepository;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\Registry;

#[Route('economat/paiement')]
final class PaiementController extends AbstractController
{
    #[Route(name: 'app_economat_entity_paiement_index', methods: ['GET'])]
    public function index(PaiementRepository $paiementRepository, FactureRepository $factureRepository): Response
    {
        // TODO non payer et economat no izy
        $factures = $factureRepository->findBy(['estpaye'=> false]);

        return $this->render('economat/index.html.twig', [
            'factures' => $factures
        ]);
    }

    #[Route('/new/{facture}', name: 'app_economat_entity_paiement_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        $facture,
        FactureRepository $factureRepository,
        LignefactureRepository $lignefactureRepository,
        Registry $registry,
        DossierpatientRepository $dossierpatientRepository
    ): Response
    {
        $paiement = new Paiement();
        $facture = $factureRepository->find($facture);

        $form = $this->createForm(PaiementForm::class, $paiement);
        $form->handleRequest($request);
        $montantfacture = $facture->getTotal();
        $resteapayer = $facture->getResteAPayer();
        $paye = $facture->getTotalPaye();
        $entierpaye = $facture->isEntierementPayee();
        $detailfacture = $lignefactureRepository->findBy(['facture'=>$facture->getId()]);

        if ($form->isSubmitted() && $form->isValid()) {

            $paiement->setFacture($facture);
            $entityManager->persist($paiement);

            if($paiement->getMontant() == $resteapayer || $facture->isEntierementPayee() === true){
                $facture->setEstpaye(true);
                $dossier = $facture->getDossierpatient()->getId();
                $dossierpatient = $dossierpatientRepository->find($dossier);
                $workflow = $registry->get($dossierpatient,'parcours_patient');
                if($workflow->can($dossierpatient,'paiement_consultation_ok')){
                    $workflow->apply($dossierpatient,'paiement_consultation_ok');
                }
            }

            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_economat_entity_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('economat/paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
            'montantfacture' => $montantfacture,
            'resteapayer' => $resteapayer,
            'paye' => $paye,
            'detailfacture' => $detailfacture
        ]);
    }

    #[Route('/{id}', name: 'app_economat_entity_paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
        return $this->render('economat/paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_economat_entity_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaiementForm::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_economat_entity_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('economat/paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_economat_entity_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paiement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($paiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_economat_entity_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}
