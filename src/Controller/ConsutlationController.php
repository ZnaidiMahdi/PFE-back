<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Repository\ConsultationRepository;
use App\Repository\DocteurRepository;
use App\Repository\PatientRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class ConsutlationController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @OA\Tag(name="Consultation")
     * @Route("/api/ajout/consultation", name="ajout_consultation", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="ajouter une consultation",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="email_patient",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="titre",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="diagnostic",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="document",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     */
    public function ajoutConsultation(Request $request, DocteurRepository $docteurRepository, PatientRepository $patientRepository, FileUploader $fileUploader)
    {

        $email = $request->get('username');
        $docteur = $docteurRepository->findOneBy(['email' => $email]);
        $email_patient = $request->get('email_patient');
        $patient = $patientRepository->findOneBy(['email' => $email_patient]);
        $titre = $request->get('titre');
        $diagnostic = $request->get('diagnostic');
        $document = $request->get('document');


        $files = $fileUploader->upload($request);
        $document = $files['document'];

        if(!$docteur){
            return $this->view('l\'identifiant du patient ou du patient n\'existe pas', Response::HTTP_NOT_FOUND);
        } else if (!$patient) {
            return $this->view('l\'identifiant du docteur ou du patient n\'existe pas', Response::HTTP_NOT_FOUND);
        } else {
            $consultation = new Consultation();

            $consultation->setDate(new \DateTime());
            $consultation->setDocteur($docteur);
            $consultation->setPatient($patient);
            $consultation->setTitre($titre);
            $consultation->setDiagnostic($diagnostic);
            $consultation->setDocument($document);

            $this->entityManager->persist($consultation);
            $this->entityManager->flush();

            return $this->view('la consultation est ajoutée avec succès', Response::HTTP_OK);
        }
    }
}
