<?php

namespace App\Controller;

use App\Repository\DocteurRepository;
use App\Repository\PatientRepository;
use App\Repository\TestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Swift_Mailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ListController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/list", name="app_lists", methods={"GET"})
     * @return View
     */
    public function getListAction(TestRepository $testRepository)
    {
        $data = $testRepository->findAll();
        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @param Swift_Mailer $mailer
     * @param int $length
     * @param DocteurRepository $docteurRepository
     * @param PatientRepository $patientRepository
     * @return View
     * @Route("api/password/reset", name="reset_password", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Envoie mot de passe",
     * )
     * @OA\Parameter(
     *     name="email",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     */
    public function resetPassword(Request $request, UserRepository $userRepository, Swift_Mailer $mailer, int $length = 12, DocteurRepository  $docteurRepository, PatientRepository $patientRepository)
    {

        $email = $request->get('email');
        $user = $userRepository->findOneBy(['email' => $email]);
        if ($user) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $new_password = '';
            for ($i = 0; $i < $length; $i++) {
                $new_password .= $characters[rand(0, $charactersLength - 1)];
            }
            $body = 'Votre nouveau mot de passe est : <b>' . $new_password . '</b><br> 
                 Pour le changer il suffit de se connecter, d\'aller à ton espace personnel et de choisir un nouveau';
            $message = (new \Swift_Message('Réinsialisation du mot de passe'))
                ->setFrom(['espacenumeriquedesante@hotmail.com' => 'Espace numérique de santé'] )
                ->setTo($email)
                ->setBody($body)
                ->setContentType('text/html');
            $mailer->send($message);

            $docteur = $docteurRepository->findOneBy(['email' => $email]);
            if ($docteur) {
                $docteur->setPassword($this->passwordEncoder->encodePassword($docteur, $new_password));
                $this->entityManager->persist($docteur);

            }
            $patient = $patientRepository->findOneBy(['email' => $email]);
            if ($patient) {
                $patient->setPassword($this->passwordEncoder->encodePassword($patient, $new_password));
                $this->entityManager->persist($patient);
            }

            $user->setPassword($this->passwordEncoder->encodePassword($user, $new_password));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->view('envoyé avec succés', Response::HTTP_OK);
        } else {
            return $this->view('adresse non existante', Response::HTTP_NOT_FOUND);
        }
    }

}
