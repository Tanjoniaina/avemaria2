<?php

namespace App\Facturation\Controller;

use App\Facturation\Entity\Facture;
use App\Facturation\Entity\Lignefacture;
use App\Facturation\Form\FactureForm;
use App\Repository\DossierpatientRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/facturation')]
final class FactureController extends AbstractController
{
    #[Route(name: 'app_facturation_entity_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository, DossierpatientRepository $dossierpatientRepository): Response
    {
        $patientconsultation = $dossierpatientRepository->findEnFacturation(['facturation_consultation'],'consultation');
        $patienthospitalisation = $dossierpatientRepository->findEnFacturation(['prescription','prise_parametres'],'hospitalisation');

        return $this->render('facturation/index.html.twig', [
            'patientconsultation' => $patientconsultation,
            'patienthospitalisation' => $patienthospitalisation
        ]);
    }

    #[Route('/new/{dossierpatient}', name: 'app_facturation_entity_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $dossierpatient, DossierpatientRepository $dossierpatientRepository): Response
    {

        $facture = new Facture();
        $ligne1 =  new Lignefacture();
        $ligne1->setDescription('test');
        $ligne1->getMontant(12345);
        $facture->addLigne($ligne1);
        $facture->setDossierpatient($dossierpatientRepository->find($dossierpatient));

        $form = $this->createForm(FactureForm::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $facture->setDatefacture(new \DateTime());
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_facturation_entity_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facturation/facture/new.html.twig', [
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
