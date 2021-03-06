<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Repository\DocteurRepository;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;


class ExperienceController extends AbstractFOSRestController
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
     * @OA\Tag(name="Experience")
     * @Route("/api/ajout/experience", name="ajout_experience", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Ajout d'une experience pour un docteur",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="hopital",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="description",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="date",
     *     in="query",
     *     example="20-02-2000",
     *     @OA\Schema(type="datetime")
     * )
     * @return View
     * @throws \Exception
     */
    public function ajoutExperience(Request $request, ExperienceRepository $experienceRepository, DocteurRepository $docteurRepository)
    {
        $username = $request->get('username');
        $docteur = $docteurRepository->findOneBy(['email' => $username]);

        if ($docteur) {
            $date = $request->get('date');
            $description = $request->get('description');
            $hopital = $request->get('hopital');

        $experience = new Experience();

        $experience->setDate(new \DateTime($date));
        $experience->setDescription($description);
        $experience->setHopital($hopital);
        $experience->setDocteur($docteur);

        $this->entityManager->persist($experience);
        $this->entityManager->flush();

        return $this->view($experience, Response::HTTP_OK)->setContext((new Context())->setGroups(['experience']));
        } else {
            return $this->view('docteur n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Experience")
     * @Route("/api/list/experiences", name="list_experiences", methods={"POST"})
     * @return View
     * @OA\Response(
     *     response=200,
     *     description="list des experiences pour un docteur",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     */
    public function listExperiences(Request $request, ExperienceRepository $experienceRepository, DocteurRepository $docteurRepository)
    {

        $username = $request->get('username');
        $docteur = $docteurRepository->findOneBy(['email' => $username]);

        if ($docteur) {
            $experiences = $experienceRepository->findBy(['docteur' => $docteur->getId()]);
            return $this->view($experiences, Response::HTTP_OK)->setContext((new Context())->setGroups(['experience']));
        } else {
            return $this->view('docteur n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Experience")
     * @Route("/api/delete/experience", name="delete_experience", methods={"POST"})
     * @return View
     * @OA\Response(
     *     response=200,
     *     description="Suppression d'une experience ",
     * )
     * @OA\Parameter(
     *     name="experience_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     */
    public function deleteExperience(Request $request, ExperienceRepository $experienceRepository)
    {
        $experience_id = $request->get('experience_id');
        $experience = $experienceRepository->findOneBy(['id' => $experience_id]);

        if ($experience) {

            $docteur = $experience->getDocteur();
            $docteur->removeExperience($experience);
            $this->entityManager->remove($experience);
            $this->entityManager->flush();

            return $this->view('L\'experience est supprim??e avec succ??s', Response::HTTP_OK);
        } else {
            return $this->view('Cette experience n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Experience")
     * @Route("/api/update/experience", name="update_experience", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Modification d'une experience pour un docteur",
     * )
     * @OA\Parameter(
     *     name="experience_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="hopital",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     *
     * @OA\Parameter(
     *     name="description",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="date",
     *     in="query",
     *     example="20-02-2000",
     *     @OA\Schema(type="datetime")
     * )
     * @return View
     * @throws \Exception
     */
    public function updateExperience(Request $request, ExperienceRepository $experienceRepository)
    {

        $experience_id = $request->get('experience_id');
        $experience = $experienceRepository->findOneBy(['id' => $experience_id]);

        if($experience) {

            $date = $request->get('date', $experience->getDate());
            $description = $request->get('description', $experience->getDescription());
            $hopital = $request->get('hopital', $experience->getHopital());

            $experience->setDate(new \DateTime($date));
            $experience->setDescription($description);
            $experience->setHopital($hopital);

            $this->entityManager->persist($experience);
            $this->entityManager->flush();

            return $this->view($experience, Response::HTTP_OK)->setContext((new Context())->setGroups(['experience']));
        } else {
            return $this->view('Cette experience n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }


}
