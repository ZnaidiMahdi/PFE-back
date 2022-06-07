<?php

namespace App\Controller;

use App\Entity\Maladie;
use App\Repository\MaladieRepository;
use App\Repository\PatientRepository;
use App\Repository\TraitementRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class MaladieController extends AbstractFOSRestController
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
     * @OA\Tag(name="Maladie")
     * @Route("/api/ajout/maladie", name="ajout_maladie", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Ajout une maladie pour un patient",
     * )
     * @OA\Parameter(
     *     name="username",
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
    public function ajoutMaladie(Request $request, TraitementRepository $traitementRepository, PatientRepository $patientRepository)
    {
        $email = $request->get('username');
        $nom = $request->get('nom');
        $commentaire = $request->get('commentaire');
        $date_debut = $request->get('date_debut');
        $patient = $patientRepository->findOneBy(['email' => $email]);

        if ($patient) {

            $maladie = new Maladie();

            $maladie->setNom($nom);
            $maladie->setCommentaire($commentaire);
            $maladie->setDateDebut(new \DateTime($date_debut));
            $maladie->setPatient($patient);

            $this->entityManager->persist($maladie);
            $this->entityManager->flush();

            return $this->view($maladie, Response::HTTP_OK)->setContext((new Context())->setGroups(['maladie']));
        } else {
            return $this->view('le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Maladie")
     * @Route("/api/list/maladies", name="list_maladies", methods={"POST"})
     * @return View
     * @OA\Response(
     *     response=200,
     *     description="list des maladies pour un patient",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     */
    public function listMaladies(Request $request, MaladieRepository $maladieRepository, PatientRepository $patientRepository)
    {

        $email = $request->get('username');
        $patient = $patientRepository->findOneBy(['email' => $email]);

        if ($patient) {
            $maladies = $maladieRepository->findBy(['patient' => $patient->getId()]);
            return $this->view($maladies, Response::HTTP_OK)->setContext((new Context())->setGroups(['maladie']));
        } else {
            return $this->view('Le patient n\'existe pas', Response::HTTP_OK);
        }
    }
}
