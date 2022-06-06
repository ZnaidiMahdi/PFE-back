<?php

namespace App\Controller;

use App\Entity\Allergie;
use App\Entity\Hospitalisation;
use App\Repository\AllergieRepository;
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


class AllergieController extends AbstractFOSRestController
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
     * @OA\Tag(name="Allergie")
     * @Route("/api/ajout/allergie", name="ajout_allergie", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Ajout une allergie pour un patient",
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
     *     name="commentaire",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @return View
     * @throws \Exception
     */
    public function ajoutAllergie(Request $request, PatientRepository $patientRepository)
    {
        $email = $request->get('username');
        $nom = $request->get('nom');
        $commentaire = $request->get('commentaire');
        $patient = $patientRepository->findOneBy(['email' => $email]);

        if ($patient) {

            $allergie = new Allergie();

            $allergie->setNom($nom);
            $allergie->setCommentaire($commentaire);
            $allergie->setPatient($patient);

            $this->entityManager->persist($allergie);
            $this->entityManager->flush();

            return $this->view($allergie, Response::HTTP_OK)->setContext((new Context())->setGroups(['allergie']));
        } else {
            return $this->view('le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Allergie")
     * @Route("/api/list/allergies", name="list_allergies", methods={"POST"})
     * @return View
     * @OA\Response(
     *     response=200,
     *     description="list des allergies pour un patient",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     */
    public function listAllergie(Request $request, AllergieRepository $allergieRepository, PatientRepository $patientRepository)
    {

        $email = $request->get('username');
        $patient = $patientRepository->findOneBy(['email' => $email]);

        if ($patient) {
            $allergies = $allergieRepository->findBy(['patient' => $patient->getId()]);
            return $this->view($allergies, Response::HTTP_OK)->setContext((new Context())->setGroups(['allergie']));
        } else {
            return $this->view('Le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }
}
