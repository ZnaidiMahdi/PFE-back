<?php

namespace App\Controller;

use App\Entity\Access;
use App\Repository\AccessRepository;
use App\Repository\DocteurRepository;
use App\Repository\PatientsDocteursRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use phpDocumentor\Reflection\Types\False_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use FOS\RestBundle\Controller\Annotations as Rest;


class AccessController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }



    public function handleAccess(Request $request, PatientsDocteursRepository $patientsDocteursRepository)
    {
        $docteur_id = $request->get('docteur_id');
        $patient_id = $request->get('patient_id');
        $isGranted = $request->get('is_granted') == 'yes' ? true : false  ;
    }



    /**
     * @OA\Tag(name="Docteur")
     * @Route("/api/refus/patient", name="refus_patient", methods={"PATCH"})
     * @return View
     * @throws Exception
     * @OA\Response(
     *     response=200,
     *     description="Refuser un patient ",
     * )
     * @OA\Parameter(
     *     name="docteur_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="patient_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     */
    public function refusPatient(Request $request, PatientsDocteursRepository $patientsDocteursRepository)
    {

        $docteur_id = $request->get('docteur_id');
        $patient_id = $request->get('patient_id');

        $patient_docteur = $patientsDocteursRepository->findOneBy(['docteur' => $docteur_id, 'patient'=> $patient_id]);

        if($patient_docteur){
            $this->entityManager->remove($patient_docteur);
            $this->entityManager->flush();

            return $this->view('Le patient est refusé par le docteur', Response::HTTP_OK);
        } else {
            return $this->view('l\'identifiant du docteur ou du patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Docteur")
     * @Route("/api/list/access", name="listaccess", methods={"POST"})
     * @return View
     * @OA\Response(
     *     response=200,
     *     description="Liste des accées ",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @Rest\View (serializerGroups={"access"})
     */
    public function listaccess(Request $request ,AccessRepository $accessRepository, DocteurRepository $docteurRepository)
    {

        $username = $request->get('username');
        $docteur = $docteurRepository->findOneBy(['email' => $username]);

        // header('Content-Type: application/json; charset=utf-8');
        //die(get_class($docteur));

        if ($docteur) {
            $access = $accessRepository->findBy(['docteur' => $docteur->getId(), 'isGranted' => false]);
            return $this->view($access);
        } else {
            return $this->view('Le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

}


