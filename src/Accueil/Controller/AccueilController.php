<?php

namespace App\Accueil\Controller;


use App\Repository\PatientRepository;
use App\Shared\Entity\Dossierpatient;
use App\Shared\Entity\Patient;
use App\Shared\Form\PatientForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\WorkflowInterface;

final class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    #[Route('/nouveauconsultation', name: 'app__new_consultation', methods: ['GET', 'POST'])]
    public function newconsultation(Request $request, EntityManagerInterface $entityManager, PatientRepository $patientRepository, Registry $registry): Response
    {

        $patients = $patientRepository->findAll();

        $patient = new Patient();
        $lastpatient = $patientRepository->findLastPatient();
        $year = date('Y');
        if($lastpatient){
            $numpatient = $lastpatient->getNumero();
            $parts = explode('/', $numpatient);
            if (count($parts) === 2) {
                $suffix = $parts[1];
                $suffixInt = (int) $suffix;
                $suffixInt++;
                $newSuffix = str_pad($suffixInt, strlen($suffix), '0', STR_PAD_LEFT);
                $newNumero =  $year.'00000' . '/' . $newSuffix;
            }
        }else{
            $newNumero = $year.'00000/0001';
        }

        $patient->setNumero($newNumero);

        $form= $this->createForm(PatientForm::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $dossierpatient = new Dossierpatient();
            $dossierpatient->setPatient($patient);
            $dossierpatient->setTypeparcours('consultation');
            $dossierpatient->setDatedebut(new \DateTime());
            $patient->setNumero($newNumero);
            $entityManager->persist($patient);
            $workflow = $registry->get($dossierpatient,'parcours_patient');
            if($workflow->can($dossierpatient,'commencer_consultation')){
                $workflow->apply($dossierpatient,'commencer_consultation');
            }

            $entityManager->persist($dossierpatient);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Patient enregistré!'
            );

            return $this->redirectToRoute('app_shared_entity_patient_show', ['id'=>$patient->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('accueil/nouveauconsultation.html.twig', [
            'patient' => $patient,
            'patients'=> $patients,
            'form' => $form,
        ]);
    }
}
