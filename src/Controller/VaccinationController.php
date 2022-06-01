<?php

namespace App\Controller;

use App\Entity\Vaccination;
use App\Repository\PatientRepository;
use App\Repository\VaccinationRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class VaccinationController extends AbstractFOSRestController
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
     * @OA\Tag(name="Vaccination")
     * @Route("/api/ajout/vaccination", name="ajout_vaccination", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Ajout une vaccination pour un patient",
     * )
     * @OA\Parameter(
     *     name="patient_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="nom",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="type",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="lot_vaccination",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="nom_vaccinateur",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="commentaire",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="date_vaccination",
     *     in="query",
     *     example="20-02-2000",
     *     @OA\Schema(type="datetime")
     * )
     * @return View
     * @throws \Exception
     */
    public function ajoutVaccination(Request $request, VaccinationRepository $vaccinationRepository, PatientRepository $patientRepository)
    {
        $patient_id = $request->get('patient_id');
        $nom = $request->get('nom');
        $type = $request->get('type');
        $lot_vaccination = $request->get('lot_vaccination');
        $nom_vaccinateur = $request->get('nom_vaccinateur');
        $commentaire = $request->get('commentaire');
        $date_vaccination = $request->get('date_vaccination');
        $patient = $patientRepository->findOneBy(['id' => $patient_id]);

        if ($patient) {

            $vaccination = new Vaccination();

            $vaccination->setNom($nom);
            $vaccination->setType($type);
            $vaccination->setLotVaccination($lot_vaccination);
            $vaccination->setNomVaccinateur($nom_vaccinateur);
            $vaccination->setCommentaire($commentaire);
            $vaccination->setDateVaccination(new \DateTime($date_vaccination));
            $vaccination->setPatient($patient);

            $this->entityManager->persist($vaccination);
            $this->entityManager->flush();

            return $this->view($vaccination, Response::HTTP_OK)->setContext((new Context())->setGroups(['vaccination']));
        } else {
            return $this->view('le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Vaccination")
     * @Route("/api/list/vaccinations", name="list_$vaccinations", methods={"POST"})
     * @return View
     * @OA\Response(
     *     response=200,
     *     description="list des vaccinations pour un patient",
     * )
     * @OA\Parameter(
     *     name="patient_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     */
    public function listVaccinations(Request $request, VaccinationRepository $vaccinationRepository, PatientRepository $patientRepository)
    {

        $patient_id = $request->get('patient_id');
        $patient = $patientRepository->findOneBy(['id' => $patient_id]);

        if ($patient) {
            $vaccinations = $vaccinationRepository->findBy(['patient' => $patient_id]);
            return $this->view($vaccinations, Response::HTTP_OK)->setContext((new Context())->setGroups(['vaccination']));
        } else {
            return $this->view('Le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }
}
