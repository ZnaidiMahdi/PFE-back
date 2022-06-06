<?php

namespace App\Controller;

use App\Entity\Antecedent;
use App\Repository\AntecedentRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;


class AntecedentController extends AbstractFOSRestController
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
     * @OA\Tag(name="Antecedent")
     * @Route("/api/ajout/antecedent", name="ajout_antecedent", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Ajout un antecedent pour un patient",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="nom",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="lien_familial",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="commentaire",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @return View
     * @throws \Exception
     */
    public function ajoutAntecedent(Request $request, PatientRepository $patientRepository)
    {
        $email = $request->get('username');
        $nom = $request->get('nom');
        $lien_familial = $request->get('lien_familial');
        $commentaire = $request->get('commentaire');
        $patient = $patientRepository->findOneBy(['email' => $email]);

        if ($patient) {

            $antecedent = new Antecedent();

            $antecedent->setNom($nom);
            $antecedent->setLienFamilial($lien_familial);
            $antecedent->setCommentaire($commentaire);
            $antecedent->setPatient($patient);

            $this->entityManager->persist($antecedent);
            $this->entityManager->flush();

            return $this->view($antecedent, Response::HTTP_OK)->setContext((new Context())->setGroups(['antecedent']));
        } else {
            return $this->view('le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Antecedent")
     * @Route("/api/list/antecedents", name="list_antecedents", methods={"POST"})
     * @return View
     * @OA\Response(
     *     response=200,
     *     description="list des antecedents pour un patient",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     */
    public function listAntecedents(Request $request, AntecedentRepository $antecedentRepository, PatientRepository $patientRepository)
    {

        $email = $request->get('username');
        $patient = $patientRepository->findOneBy(['email' => $email]);

        if ($patient) {
            $antecedents = $antecedentRepository->findBy(['patient' => $patient->getId()]);
            return $this->view($antecedents, Response::HTTP_OK)->setContext((new Context())->setGroups(['antecedent']));
        } else {
            return $this->view('Le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }
}
