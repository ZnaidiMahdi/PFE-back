<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\User;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PatientController extends AbstractFOSRestController
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
     * @Route("/registre/patient", name="registre_patient")
     * @return View
     * @throws Exception
     */
    public function registrePatient(Request $request, UserRepository $userRepository)
    {
        $list_emails = $userRepository->listEmails();
        $email = $request->get('email');

        if (in_array($email, $list_emails)) {

            return $this->view('adresse email existe', Response::HTTP_CONFLICT);

        } else {
            $password = $request->get('password');
            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $date_naissance = $request->get('date_naissance');
            $num_tel = $request->get('num_tel');
            $ville = $request->get('ville');
            $adresse = $request->get('adresse');
            $code_postal = $request->get('code_postal');
            $sexe = $request->get('sexe');
            $photo = $request->get('photo');
            $code_securite_sociale = $request->get('code_securite_sociale');
            $profession = $request->get('profession');
            $statut_sociale = $request->get('statut_sociale');
            $nbr_enfant = $request->get('nbr_enfant');

            $user = new User();

            $user->setEmail($email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setRoles(["ROLE_PATIENT"]);
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setDateNaissance(new \DateTime($date_naissance));
            $user->setNumTel($num_tel);
            $user->setVille($ville);
            $user->setAdresse($adresse);
            $user->setCodePostal($code_postal);
            $user->setSexe($sexe);
            $user->setPhoto($photo);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $patient = new Patient();

            $patient->setEmail($email);
            $patient->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $patient->setRoles(["ROLE_PATIENT"]);
            $patient->setCodeSecuriteSociale($code_securite_sociale);
            $patient->setProfession($profession);
            $patient->setStatutSociale($statut_sociale);
            $patient->setNbrEnfant($nbr_enfant);
            $patient->setUser($user);

            $this->entityManager->persist($patient);
            $this->entityManager->flush();

            return $this->view($patient, Response::HTTP_OK)->setContext((new Context())->setGroups(['patient']));
        }
    }

    /**
     * @Route("/api/info/patient", name="ajout_info_patient")
     * @return View
     */
    public function ajoutInfoPatient(PatientRepository $patientRepository, Request $request)
    {
        $patient_id = $request->get('patient_id');
        $patient = $patientRepository->findOneBy(['id' => $patient_id]);

        if ($patient) {

            $poids = $request->get('poids');
            $taille = $request->get('taille');
            $temperature = $request->get('temperature');
            $frequence_cardiaque = $request->get('frequence_cardiaque');
            $tension_arterielle = $request->get('tension_arterielle');
            $glycemie = $request->get('glycemie');

            $patient->setPoids($poids);
            $patient->setTaille($taille);
            $patient->setTemperature($temperature);
            $patient->setFrequenceCardiaque($frequence_cardiaque);
            $patient->setTensionArterielle($tension_arterielle);
            $patient->setGlycemie($glycemie);

            $this->entityManager->persist($patient);
            $this->entityManager->flush();

            return $this->view($patient, Response::HTTP_OK)->setContext((new Context())->setGroups(['patient']));

        } else {

            return $this->view('patient n`\existe pas', Response::HTTP_CONFLICT);

        }
    }
}
