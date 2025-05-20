<?php

namespace App\Consultation\Controller;

use App\Consultation\Entity\Ordonnance;
use App\Consultation\Form\OrdonnanceForm;
use App\Repository\DossierpatientRepository;
use App\Repository\OrdonnanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ordonnance')]
final class OrdonnanceController extends AbstractController
{
    #[Route(name: 'app_consultation_entity_ordonnance_index', methods: ['GET'])]
    public function index(OrdonnanceRepository $ordonnanceRepository, DossierpatientRepository $dossierpatientRepository): Response
    {
        $ordonnance = $dossierpatientRepository->findBy(['etatparcours'=>'consultation']);
        return $this->render('consultation/index.html.twig', [
            'ordonnances' => $ordonnance,
        ]);
    }

    #[Route('/new/{dossierpatient}', name: 'app_consultation_entity_ordonnance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,$dossierpatient, DossierpatientRepository $dossierpatientRepository): Response
    {
        $ordonnance = new Ordonnance();
        $ordonnance->setDossierpatient($dossierpatientRepository->find($dossierpatient));
        $form = $this->createForm(OrdonnanceForm::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($ordonnance->getLigne() as $ligne) {
                $ligne->setOrdonnance($ordonnance);
                $entityManager->persist($ligne);
            }


            foreach($ordonnance->getLigneanalyse() as $ligneanalysis) {
                $ligneanalysis->setOrdonnance($ordonnance);
                $entityManager->persist($ligneanalysis);
            }

            $entityManager->persist($ordonnance);
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_entity_ordonnance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('consultation/ordonnance/new.html.twig', [
            'ordonnance' => $ordonnance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_entity_ordonnance_show', methods: ['GET'])]
    public function show(Ordonnance $ordonnance): Response
    {
        return $this->render('consultation/ordonnance/show.html.twig', [
            'ordonnance' => $ordonnance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_consultation_entity_ordonnance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ordonnance $ordonnance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrdonnanceForm::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_entity_ordonnance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('consultation/ordonnance/edit.html.twig', [
            'ordonnance' => $ordonnance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_entity_ordonnance_delete', methods: ['POST'])]
    public function delete(Request $request, Ordonnance $ordonnance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ordonnance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ordonnance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_consultation_entity_ordonnance_index', [], Response::HTTP_SEE_OTHER);
    }
}
