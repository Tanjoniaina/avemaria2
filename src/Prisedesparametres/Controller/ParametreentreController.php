<?php

namespace App\Prisedesparametres\Controller;

use App\Prisedesparametres\Entity\Parametreentre;
use App\Repository\DossierpatientRepository;
use App\Repository\ParametreentreRepository;
use App\Shared\Controller\ParametreentreForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\Registry;

#[Route('/prisedeparametres')]
final class ParametreentreController extends AbstractController
{
    #[Route(name: 'app_prisedesparametres_index', methods: ['GET'])]
    public function index(ParametreentreRepository $parametreentreRepository,DossierpatientRepository $dossierpatientRepository): Response
    {
        $dossier = $dossierpatientRepository->findBy(['etatparcours'=>'prise_parametres']);

        return $this->render('prisedesparametres/index.html.twig', [
            'dossierpatient' => $dossier
        ]);
    }

    #[Route('/new/{dossierpatient}', name: 'app_parametreentre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,$dossierpatient, Registry $registry, DossierpatientRepository $dossierpatientRepository): Response
    {
        $parametreentre = new Parametreentre();
        $form = $this->createForm(ParametreentreForm::class, $parametreentre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dossier = $dossierpatientRepository->find($dossierpatient);
            $workflow = $registry->get($dossier,'parcours_patient');
            if($workflow->can($dossier,'prise_parametres_ok')){
                $workflow->apply($dossier,'prise_parametres_ok');
            }
            $parametreentre->setDossierpatient($dossier);
            $entityManager->persist($parametreentre);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Paramètre du Patient enregistré!'
            );

            return $this->redirectToRoute('app_prisedesparametres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prisedesparametres/parametreentre/new.html.twig', [
            'parametreentre' => $parametreentre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_parametreentre_show', methods: ['GET'])]
    public function show(Parametreentre $parametreentre): Response
    {
        return $this->render('app/prisedesparametres/entity/parametreentre/show.html.twig', [
            'parametreentre' => $parametreentre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_parametreentre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Parametreentre $parametreentre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParametreentreForm::class, $parametreentre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_parametreentre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prisedesparametres/parametreentre/edit.html.twig', [
            'parametreentre' => $parametreentre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_parametreentre_delete', methods: ['POST'])]
    public function delete(Request $request, Parametreentre $parametreentre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parametreentre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($parametreentre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_parametreentre_index', [], Response::HTTP_SEE_OTHER);
    }
}
