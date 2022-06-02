<?php

namespace App\Controller;

use App\Entity\Traitement;
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


class TraitementController extends AbstractFOSRestController
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
     * @OA\Tag(name="Traitement")
     * @Route("/api/ajout/traitement", name="ajout_traitement", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Ajout un traitement pour un patient",
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
     *     name="postologie_trait",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="commentaire",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="date_trait",
     *     in="query",
     *     example="20-02-2000",
     *     @OA\Schema(type="datetime")
     * )
     * @return View
     * @throws \Exception
     */
    public function ajoutTraitement(Request $request, TraitementRepository $traitementRepository, PatientRepository $patientRepository)
    {
        $email = $request->get('username');
        $nom = $request->get('nom');
        $postologie_trait = $request->get('postologie_trait');
        $commentaire = $request->get('commentaire');
        $date_trait = $request->get('date_trait');
        $patient = $patientRepository->findOneBy(['email' => $email]);

        if ($patient) {

            $traitement = new Traitement();

            $traitement->setNom($nom);
            $traitement->setPosologieTrait($postologie_trait);
            $traitement->setCommentaire($commentaire);
            $traitement->setDateTrait(new \DateTime($date_trait));
            $traitement->setPatient($patient);

            $this->entityManager->persist($traitement);
            $this->entityManager->flush();

            return $this->view($traitement, Response::HTTP_OK)->setContext((new Context())->setGroups(['traitement']));
        } else {
            return $this->view('le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Traitement")
     * @Route("/api/list/traitements", name="list_traitements", methods={"POST"})
     * @return View
     * @OA\Response(
     *     response=200,
     *     description="list des traitements pour un patient",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     */
    public function listTraitements(Request $request, TraitementRepository $traitementRepository, PatientRepository $patientRepository)
    {

        $email = $request->get('username');
        $patient = $patientRepository->findOneBy(['email' => $email]);

        if ($patient) {
            $traitements = $traitementRepository->findBy(['patient' => $patient->getId()]);
            return $this->view($traitements, Response::HTTP_OK)->setContext((new Context())->setGroups(['traitement']));
        } else {
            return $this->view('Le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }
}
