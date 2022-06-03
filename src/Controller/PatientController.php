<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\PatientsDocteurs;
use App\Entity\User;
use App\Repository\DocteurRepository;
use App\Repository\PatientRepository;
use App\Repository\PatientsDocteursRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use OpenApi\Annotations as OA;


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
     * @OA\Tag(name="Patient")
     * @Route("/api/registre/patient", name="registre_patient", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Inscription pour un patient",
     * )
     * @OA\Parameter(
     *     name="nom",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="prenom",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="email",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="password",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="date_naissance",
     *     in="query",
     *     example="20-02-2000",
     *     @OA\Schema(type="datetime")
     * )
     * @OA\Parameter(
     *     name="num_tel",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="ville",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="adresse",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="code_postal",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="sexe",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="photo",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="code_securite_sociale",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="statut_sociale",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="nbr_enfant",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @return View
     * @throws Exception
     */
    public function registrePatient(Request $request, UserRepository $userRepository, FileUploader $fileUploader)
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
            $code_securite_sociale = $request->get('code_securite_sociale');
            $statut_sociale = $request->get('statut_sociale');
            $nbr_enfant = $request->get('nbr_enfant');

            $files = $fileUploader->upload($request);
            $photo = $files['photo'];

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
            $patient->setPassword($this->passwordEncoder->encodePassword($patient, $password));
            $patient->setRoles(["ROLE_PATIENT"]);
            $patient->setCodeSecuriteSociale($code_securite_sociale);
            $patient->setStatutSociale($statut_sociale);
            $patient->setNbrEnfant($nbr_enfant);
            $patient->setUser($user);

            $this->entityManager->persist($patient);
            $this->entityManager->flush();

            return $this->view($patient, Response::HTTP_OK)->setContext((new Context())->setGroups(['patient']));
        }
    }

    /**
     * @OA\Tag(name="Patient")
     * @Route("/api/info/patient", name="ajout_info_patient", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Ajouter des informations pour le patient ",
     * )
     * @OA\Parameter(
     *     name="patient_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="taille",
     *     in="query",
     *     @OA\Schema(type="float")
     * )
     * @OA\Parameter(
     *     name="poids",
     *     in="query",
     *     @OA\Schema(type="float")
     * )
     * @OA\Parameter(
     *     name="temperature",
     *     in="query",
     *     @OA\Schema(type="float")
     * )
     * @OA\Parameter(
     *     name="frequence_cardiaque",
     *     in="query",
     *     @OA\Schema(type="float")
     * )
     * @OA\Parameter(
     *     name="tension_arterielle",
     *     in="query",
     *     @OA\Schema(type="float")
     * )
     * @OA\Parameter(
     *     name="glycemie",
     *     in="query",
     *     @OA\Schema(type="float")
     * )
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

    /**
     * @OA\Tag(name="Patient")
     * @Route("/api/profile/patient", name="profile_patient", methods={"POST"})
     * @return View
     * @throws Exception
     * @OA\Response(
     *     response=200,
     *     description="Informations profile patient ",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     */
    public function getProfilePatient(Request $request, PatientRepository $patientRepository)
    {

        $email = $request->get('username');
        $patient = $patientRepository->findOneBy(['email' => $email]);

        if($patient){
            return $this->view($patient, Response::HTTP_OK)->setContext((new Context())->setGroups(['patient']));
        } else {
            return $this->view('patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Patient")
     * @Route("/api/delete/patient", name="delete_patient", methods={"DELETE"})
     * @return View
     * @throws Exception
     * @OA\Response(
     *     response=200,
     *     description="Suppression d'un patient ",
     * )
     * @OA\Parameter(
     *     name="patient_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     */
    public function deletePatient(Request $request, PatientRepository $patientRepository, PatientsDocteursRepository $patientsDocteursRepository)
    {
        $patient_id = $request->get('patient_id');
        $patient = $patientRepository->findOneBy(['id' => $patient_id]);

        if($patient){
            $user = $patient->getUser();
            $patient_docteurs = $patientsDocteursRepository->findBy(['patient' => $patient_id]);
            foreach ($patient_docteurs as $p){
                $this->entityManager->remove($p);
            }
            $this->entityManager->remove($user);
            $this->entityManager->remove($patient);
            $this->entityManager->flush();

            return $this->view('Le patient est supprimé avec succès', Response::HTTP_OK);
        } else {
            return $this->view('Ce patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }



    /**
     * @OA\Tag(name="Patient")
     * @Route("/api/update/patient", name="update_patient", methods={"PATCH"})
     * @OA\Response(
     *     response=200,
     *     description="Modification des informations pour un patient",
     * )
     * @OA\Parameter(
     *     name="patient_id",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="nom",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="prenom",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="password",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="date_naissance",
     *     in="query",
     *     example="20-02-2000",
     *     @OA\Schema(type="datetime")
     * )
     * @OA\Parameter(
     *     name="num_tel",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="ville",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="adresse",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="code_postal",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="sexe",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="photo",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="code_securite_sociale",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="statut_sociale",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="nbr_enfant",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @return View
     * @throws Exception
     */
    public function updatePatient(Request $request, PatientRepository $patientRepository)
    {

        $patient_id = $request->get('patient_id');
        $patient = $patientRepository->findOneBy(['id' => $patient_id]);

        if($patient){

            $password = $request->get('password');
            $nom = $request->get('nom', $patient->getUser()->getNom());
            $prenom = $request->get('prenom', $patient->getUser()->getPrenom());
            $date_naissance = $request->get('date_naissance', $patient->getUser()->getDateNaissance());
            $num_tel = $request->get('num_tel', $patient->getUser()->getNumTel());
            $ville = $request->get('ville', $patient->getUser()->getVille());
            $adresse = $request->get('adresse', $patient->getUser()->getAdresse());
            $code_postal = $request->get('code_postal', $patient->getUser()->getCodePostal());
            $sexe = $request->get('sexe', $patient->getUser()->getSexe());
            $photo = $request->get('photo', $patient->getUser()->getPhoto());
            $code_securite_sociale = $request->get('code_securite_sociale', $patient->getCodeSecuriteSociale());
            $statut_sociale = $request->get('statut_sociale', $patient->getStatutSociale());
            $nbr_enfant = $request->get('nbr_enfant', $patient->getNbrEnfant());

            $user = $patient->getUser();

            if($password){
                $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
                $patient->setPassword($this->passwordEncoder->encodePassword($patient, $password));
            }
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setDateNaissance(new \DateTime($date_naissance));
            $user->setNumTel($num_tel);
            $user->setVille($ville);
            $user->setAdresse($adresse);
            $user->setCodePostal($code_postal);
            $user->setSexe($sexe);
            $user->setPhoto($photo);

            $patient->setCodeSecuriteSociale($code_securite_sociale);
            $patient->setStatutSociale($statut_sociale);
            $patient->setNbrEnfant($nbr_enfant);

            $this->entityManager->persist($user);
            $this->entityManager->persist($patient);
            $this->entityManager->flush();

            return $this->view($patient, Response::HTTP_OK)->setContext((new Context())->setGroups(['patient']));
        } else {
            return $this->view('Ce patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Patient")
     * @Route("/api/affect/docteur", name="affect_docteur", methods={"POST"})
     * @return View
     * @throws Exception
     * @OA\Response(
     *     response=200,
     *     description="Selectionner un docteur pour un patient ",
     * )
     * @OA\Parameter(
     *     name="patient_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="docteur_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     */
    public function affectDocteur(Request $request, PatientRepository $patientRepository, DocteurRepository $docteurRepository)
    {
        $patient_id = $request->get('patient_id');
        $patient = $patientRepository->findOneBy(['id' => $patient_id]);

        $docteur_id = $request->get('docteur_id');
        $docteur = $docteurRepository->findOneBy(['id' => $docteur_id]);

        if($patient){

            if($docteur){

                $patient_docteur = new PatientsDocteurs();

                $patient_docteur->setPatient($patient);
                $patient_docteur->setDocteur($docteur);
                $patient_docteur->setIsAccepted(0);

                $this->entityManager->persist($patient_docteur);
                $this->entityManager->flush();

                return $this->view('Le docteur est affecté avec succès', Response::HTTP_OK);
            } else {
                return $this->view('Ce docteur n\'existe pas', Response::HTTP_NOT_FOUND);
            }

        } else {
            return $this->view('Ce patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }
}
