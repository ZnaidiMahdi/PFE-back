<?php

namespace App\Controller;

use App\Entity\QuestionReponse;
use App\Entity\Traitement;
use App\Repository\DocteurRepository;
use App\Repository\PatientRepository;
use App\Repository\QuestionReponseRepository;
use App\Repository\TraitementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use OpenApi\Annotations as OA;


class QuestionReponseController extends AbstractFOSRestController
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
     * @OA\Tag(name="Question")
     * @Route("/api/ajout/question", name="ajout_question", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Ajout une question",
     * )
     * @OA\Parameter(
     *     name="username",
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
     *     name="question",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @return View
     * @throws Exception
     */
    public function ajoutQuestion(Request $request, PatientRepository $patientRepository)
    {
        $email = $request->get('username');
        $titre = $request->get('titre');
        $question = $request->get('question');
        $patient = $patientRepository->findOneBy(['email' => $email]);

        if ($patient) {

            $question_reponse = new QuestionReponse();

            $question_reponse->setQuestion($question);
            $question_reponse->setTitre($titre);
            $question_reponse->setPatient($patient);
            $question_reponse->setDate(new \DateTime());

            $this->entityManager->persist($question_reponse);
            $this->entityManager->flush();

            return $this->view($question_reponse, Response::HTTP_OK)->setContext((new Context())->setGroups(['question_reponse']));
        } else {
            return $this->view('le patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Question")
     * @Route("/api/ajout/reponse", name="ajout_reponse", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Ajout une ajout",
     * )
     * @OA\Parameter(
     *     name="id_question",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="reponse",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @return View
     * @throws Exception
     */
    public function ajoutReponse(Request $request, DocteurRepository $docteurRepository, QuestionReponseRepository $questionReponseRepository)
    {
        $id_question = $request->get('id_question');
        $question_reponse = $questionReponseRepository->findOneBy(['id' => $id_question]);
        $email = $request->get('username');
        $reponse= $request->get('reponse');
        $docteur = $docteurRepository->findOneBy(['email' => $email]);

        if ($docteur) {

            $question_reponse->setReponse($reponse);
            $question_reponse->setDocteur($docteur);

            $this->entityManager->persist($question_reponse);
            $this->entityManager->flush();

            return $this->view($question_reponse, Response::HTTP_OK)->setContext((new Context())->setGroups(['question_reponse']));
        } else {
            return $this->view('le docteur n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }
}
