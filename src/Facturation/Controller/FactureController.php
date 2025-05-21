<?php

namespace App\Facturation\Controller;

use App\Facturation\Entity\Facture;
use App\Facturation\Entity\Lignefacture;
use App\Facturation\Form\FactureForm;
use App\Facturation\Form\FacturegeneraleForm;
use App\Repository\DossierpatientRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\Registry;

#[Route('/facturation')]
final class FactureController extends AbstractController
{
    #[Route(name: 'app_facturation_entity_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository, DossierpatientRepository $dossierpatientRepository): Response
    {
        $patientconsultation = $dossierpatientRepository->findEnFacturation(['facturation_consultation'],'consultation');
        $patienthospitalisation = $dossierpatientRepository->findEnFacturation(['prescription','prise_parametres'],'hospitalisation');
        $patientapresconsultation = $dossierpatientRepository->findEnFacturation(['facturation_post_consultation'],'consultation');

        return $this->render('facturation/index.html.twig', [
            'patientconsultation' => $patientconsultation,
            'patienthospitalisation' => $patienthospitalisation,
            'patientapresconsultation' => $patientapresconsultation
        ]);
    }

    #[Route('/new/consultation/{dossierpatient}', name: 'app_facturation_entity_facture_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        $dossierpatient,
        DossierpatientRepository $dossierpatientRepository,
        Registry $registry,
        FactureRepository $factureRepository
    ): Response
    {
        $facture = new Facture();
        $facture->setDossierpatient($dossierpatientRepository->find($dossierpatient));

        $lastfacture = $factureRepository->findLastFacture();
        $year = date('Y');
        if($lastfacture){
            $numfacture = $lastfacture->getNumero();
            $parts = explode('/', $numfacture);
            if (count($parts) === 2) {
                $suffix = $parts[1];
                $suffixInt = (int) $suffix;
                $suffixInt++;
                $newSuffix = str_pad($suffixInt, strlen($suffix), '0', STR_PAD_LEFT);
                $newFacture =  'FAC-'.$year . '/' . $newSuffix;
            }
        }else{
            $newFacture = 'FAC-'.$year.'/00001';
        }

        $facture->setNumero($newFacture);

        $form = $this->createForm(FactureForm::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($facture->getLigne() as $ligne) {
                $ligne->setFacture($facture);
                $ligne->setDescription($ligne->getTarifacte()->getNom());
                $ligne->setType('consultation');
                $ligne->setReferenceid($ligne->getTarifacte()->getId());
                $entityManager->persist($ligne);
            }
            $dossier = $dossierpatientRepository->find($dossierpatient);
            $workflow = $registry->get($dossier,'parcours_patient');
            if($workflow->can($dossier,'facturation_consultation_ok')){
                $workflow->apply($dossier,'facturation_consultation_ok');
            }
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_facturation_entity_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facturation/facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/new/apresconsultation/{dossierpatient}', name: 'app_facturation_entity_facture_newapresconsultation', methods: ['GET', 'POST'])]
    public function newapresconsultation(
        Request $request,
        EntityManagerInterface $entityManager,
        $dossierpatient,
        DossierpatientRepository $dossierpatientRepository,
        Registry $registry,
        FactureRepository $factureRepository
    ): Response
    {
        $facture = new Facture();
        $dossierpatient = $dossierpatientRepository->find($dossierpatient);
        $facture->setDossierpatient($dossierpatient);

        $lastfacture = $factureRepository->findLastFacture();
        $year = date('Y');
        if($lastfacture){
            $numfacture = $lastfacture->getNumero();
            $parts = explode('/', $numfacture);
            if (count($parts) === 2) {
                $suffix = $parts[1];
                $suffixInt = (int) $suffix;
                $suffixInt++;
                $newSuffix = str_pad($suffixInt, strlen($suffix), '0', STR_PAD_LEFT);
                $newFacture =  'FAC-'.$year . '/' . $newSuffix;
            }
        }else{
            $newFacture = 'FAC-'.$year.'/00001';
        }

        $facture->setNumero($newFacture);

        foreach ($dossierpatient->getOrdonnances() as $ordonnance) {
            foreach ($ordonnance->getLigne() as $ligne){
                $lignefacture = new Lignefacture();
                $lignefacture->setDescription($ligne->getMedicament()->getNom());
                $lignefacture->setMontant($ligne->getMedicament()->getMontant());
                $lignefacture->setType('Medicament');
                $lignefacture->setReferenceid($ligne->getMedicament()->getId());
                $facture->addLigne($lignefacture);
            }

            foreach ($ordonnance->getLigneanalyse() as $ligneanalyse){
                $lignefacture = new Lignefacture();
                $lignefacture->setDescription($ligneanalyse->getTypeanalyse()->getNom());
                $lignefacture->setMontant($ligneanalyse->getTypeanalyse()->getMontant());
                $lignefacture->setType('Analyse');
                $lignefacture->setReferenceid($ligneanalyse->getTypeanalyse()->getId());
                $facture->addLigne($lignefacture);
            }
        }

        $form = $this->createForm(FacturegeneraleForm::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($facture->getLigne() as $ligne) {
                $ligne->setFacture($facture);
                $entityManager->persist($ligne);
            }

            $workflow = $registry->get($dossierpatient,'parcours_patient');
            if($workflow->can($dossierpatient,'paiement_post_consultation')){
                $workflow->apply($dossierpatient,'paiement_post_consultation');
            }

            $entityManager->persist($facture);
            $entityManager->flush();


            return $this->redirectToRoute('app_facturation_entity_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facturation/facture/newfacturegenerale.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facturation_entity_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        return $this->render('facturation/facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facturation_entity_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureForm::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facturation_entity_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facturation/facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facturation_entity_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_facturation_entity_facture_index', [], Response::HTTP_SEE_OTHER);
    }
}
