<?php

namespace App\Controller;

use App\Entity\Hospitalisation;
use App\Repository\HospitalisationRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;


class HospitalisationController extends AbstractFOSRestController
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
     * @OA\Tag(name="Hospitalisation")
     * @Route("/api/ajout/hospitalisation", name="ajout_hospitalisation", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Ajout une hospitalisation pour un patient",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="motif",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="duree",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="heure",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="commentaire",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="date_debut",
     *     in="query",
     *     example="20-02-2000",
     *     @OA\Schema(type="datetime")
     * )
     * @return View
     * @throws \Exception
     */
    public function ajoutHospitalisation(Request $request, PatientRepository $patientRepository)
    {
        $email = $request->get('username');
        $motif = $request->get('motif');
        $duree = $request->get('duree');
        $heure = $request->get('heure');
        $commentaire = $request->get('commentaire');
        $date_debut = $request->get('date_debut');
        $patient = $patientRepository->findOneBy(['email' => $email]);

        if ($patient) {

            $hospitalisation = new Hospitalisation();

            $hospitalisation->setMotif($motif);
            $hospitalisation->setDuree($duree);
            $hospitalisation->setHeure($heure);
            $hospitalisation->setDateDebut(new \DateTime($date_debut));
            $hospitalisation->setCommentaire($commentaire);
            $hospitalisation->setPatient($patient);
            $hospitalisation->setAuteur('patient');

            $this->entityManager->persist($hospitalisation);
            $this->entityManager->flush();

            return $this->view($hospitalisation, Response::HTTP_OK)->setContext((new Context())->setGroups(['hospitalisation']));
        } else {
            return $this->view('le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Hospitalisation")
     * @Route("/api/list/hospitalisation", name="list_hospitalisations", methods={"POST"})
     * @return View
     * @OA\Response(
     *     response=200,
     *     description="list des hospitalisations pour un patient",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     */
    public function listHospitalisations(Request $request, HospitalisationRepository $hospitalisationRepository, PatientRepository $patientRepository)
    {

        $email = $request->get('username');
        $patient = $patientRepository->findOneBy(['email' => $email]);

        if ($patient) {
            $hospitalisations = $hospitalisationRepository->findBy(['patient' => $patient->getId(), 'auteur' => 'patient']);
            return $this->view($hospitalisations, Response::HTTP_OK)->setContext((new Context())->setGroups(['hospitalisation']));
        } else {
            return $this->view('Le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }
}
