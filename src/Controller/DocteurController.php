<?php

namespace App\Controller;

use App\Entity\Access;
use App\Entity\Docteur;
use App\Entity\Hospitalisation;
use App\Entity\Traitement;
use App\Entity\User;
use App\Repository\AccessRepository;
use App\Repository\DocteurRepository;
use App\Repository\HospitalisationRepository;
use App\Repository\PatientRepository;
use App\Repository\PatientsDocteursRepository;
use App\Repository\TraitementRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use OpenApi\Annotations as OA;


class DocteurController extends AbstractFOSRestController
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
     * @OA\Tag(name="Docteur")
     * @Route("/api/profile/docteur", name="profile_docteur", methods={"POST"})
     * @return View
     * @throws Exception
     * @OA\Response(
     *     response=200,
     *     description="Informations profile docteur ",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     */
    public function getProfileDocteur(Request $request, DocteurRepository $docteurRepository)
    {

        $email = $request->get('username');
        if($email){
            $docteur = $docteurRepository->findOneBy(['email' => $email]);
        }else{
            $id = $request->get('id');
            $docteur = $docteurRepository->find($id);
        }


        if($docteur){
            return $this->view($docteur, Response::HTTP_OK)->setContext((new Context())->setGroups(['docteur']));
        } else {
            return $this->view('docteur n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Docteur")
     * @Route("/api/delete/docteur", name="delete_docteur", methods={"DELETE"})
     * @return View
     * @throws Exception
     * @OA\Response(
     *     response=200,
     *     description="Suppression d'un docteur ",
     * )
     * @OA\Parameter(
     *     name="docteur_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     */
    public function deleteDocteur(Request $request, DocteurRepository $docteurRepository, PatientsDocteursRepository $patientsDocteursRepository)
    {

        $docteur_id = $request->get('docteur_id');
        $docteur = $docteurRepository->findOneBy(['id' => $docteur_id]);

        if($docteur){
            $patient_docteurs = $patientsDocteursRepository->findBy(['docteur' => $docteur_id]);
            foreach ($patient_docteurs as $d){
                $this->entityManager->remove($d);
            }
            $user = $docteur->getUser();
            $this->entityManager->remove($user);
            $this->entityManager->remove($docteur);
            $this->entityManager->flush();
            return $this->view('Le docteur est supprimé avec succès', Response::HTTP_OK);
        } else {
            return $this->view('Ce docteur n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }


    /**
     * @OA\Tag(name="Docteur")
     * @Route("/api/update/docteur", name="update_docteur", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Modification des informations pour un docteur",
     * )
     * @OA\Parameter(
     *     name="username",
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
     *
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
     *     name="rpps",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="cin",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="email_professionnel",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="sepicialite",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="langues",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="nom_etab",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="num_etab",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="ville_etab",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="email_etab",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="adresse_etab",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @return View
     * @throws Exception
     */
    public function updateDocteur(Request $request, DocteurRepository $docteurRepository)
    {

        $email = $request->get('username');
        $docteur = $docteurRepository->findOneBy(['email' => $email]);

        if($docteur){

            $password = $request->get('password');
            $nom = $request->get('nom', $docteur->getUser()->getNom());
            $prenom = $request->get('prenom', $docteur->getUser()->getPrenom());
            //$date_naissance = $request->get('date_naissance', $docteur->getUser()->getDateNaissance());
            $num_tel = $request->get('num_tel', $docteur->getUser()->getNumTel());
            $ville = $request->get('ville', $docteur->getUser()->getVille());
            $adresse = $request->get('adresse', $docteur->getUser()->getAdresse());
            $code_postal = $request->get('code_postal', $docteur->getUser()->getCodePostal());
            $sexe = $request->get('sexe', $docteur->getUser()->getSexe());
            $photo = $request->get('photo', $docteur->getUser()->getPhoto());
            $rpps = $request->get('rpps', $docteur->getRpps());
            $cin = $request->get('cin', $docteur->getCin());
            $email_professionnel = $request->get('email_professionnel', $docteur->getEmailProfessionnel());
            $sepicialite = $request->get('sepicialite', $docteur->getSepicialite());
            $langues = $request->get('langues', $docteur->getLangues());
            $nom_etab = $request->get('nom_etab', $docteur->getNomEtab());
            $num_etab = $request->get('num_etab', $docteur->getNumEtab());
            $ville_etab = $request->get('ville_etab', $docteur->getVilleEtab());
            $email_etab = $request->get('email_etab', $docteur->getEmailEtab());
            $adresse_etab = $request->get('adresse_etab', $docteur->getAdresseEtab());

            $user = $docteur->getUser();

            if($password){
                $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
                $docteur->setPassword($this->passwordEncoder->encodePassword($docteur, $password));
            }
            $user->setNom($nom);
            $user->setPrenom($prenom);
            //$user->setDateNaissance(new \DateTime($date_naissance));
            $user->setNumTel($num_tel);
            $user->setVille($ville);
            $user->setAdresse($adresse);
            $user->setCodePostal($code_postal);
            $user->setSexe($sexe);
            $user->setPhoto($photo);

            $docteur->setRpps($rpps);
            $docteur->setCin($cin);
            $docteur->setEmailProfessionnel($email_professionnel);
            $docteur->setSepicialite($sepicialite);
            $docteur->setLangues($langues);
            $docteur->setNomEtab($nom_etab);
            $docteur->setNumEtab($num_etab);
            $docteur->setVilleEtab($ville_etab);
            $docteur->setEmailEtab($email_etab);
            $docteur->setAdresseEtab($adresse_etab);

            $this->entityManager->persist($user);
            $this->entityManager->persist($docteur);
            $this->entityManager->flush();

            return $this->view($docteur, Response::HTTP_OK)->setContext((new Context())->setGroups(['docteur']));
        } else {
            return $this->view('Ce docteur n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Docteur")
     * @Route("/api/accept/patient", name="accept_patient", methods={"POST"})
     * @return View
     * @throws Exception
     * @OA\Response(
     *     response=200,
     *     description="Accepter un patient ",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="patient_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     */
    public function accepterPatient(Request $request, AccessRepository $accessRepository, DocteurRepository $docteurRepository)
    {

        $email = $request->get('username');
        $patient_id = $request->get('patient_id');
        $docteur = $docteurRepository->findOneBy(['email' => $email]);
        $patient = $docteurRepository->findOneBy(['id' => $patient_id]);
        $patient_docteur = $accessRepository->findOneBy(['docteur' => $docteur, 'patient'=> $patient]);

        if($patient_docteur){
            $patient_docteur->setIsGranted(1);
            $this->entityManager->persist($patient_docteur);
            $this->entityManager->flush();

            return $this->view('Le patient est accepté avec succès', Response::HTTP_OK);
        } else {
            return $this->view('l\'identifiant du docteur ou du patient n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Docteur")
     * @Route("/api/refus/patient", name="refus_patient", methods={"POST"})
     * @return View
     * @throws Exception
     * @OA\Response(
     *     response=200,
     *     description="Refuser un patient ",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="patient_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     */
    public function refusPatient(Request $request, AccessRepository $accessRepository, DocteurRepository $docteurRepository)
    {

        $email = $request->get('username');
        $patient_id = $request->get('patient_id');
        $docteur = $docteurRepository->findOneBy(['email' => $email]);
        $patient = $docteurRepository->findOneBy(['id' => $patient_id]);
        $patient_docteur = $accessRepository->findOneBy(['docteur' => $docteur, 'patient'=> $patient]);

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
     * @Route("/api/list/docteurs", name="list_docteurs", methods={"POST"})
     * @return View
     * @throws Exception
     * @OA\Response(
     *     response=200,
     *     description="list des docteurs",
     * )
     * @Rest\View(serializerGroups={"docteur"})
     **/
    public function listDocteurs(DocteurRepository $docteurRepository)
    {;
        $docteurs = $docteurRepository->findAll();
        return $this->view($docteurs, Response::HTTP_OK);
    }

    /**
     * @OA\Tag(name="RechercheDocteur")
     * @Route("/api/recherche/docteurs", name="list_docteurs", methods={"POST"})
     * @return View
     * @throws Exception
     * @OA\Response(
     *     response=200,
     *     description="list des docteurs",
     * )
     *  @Rest\View(serializerGroups={"docteur"})
     **/
    public function RechercheDocteurs(Request $request,DocteurRepository $docteurRepository)
    {
        $ville = $request->get('ville');
        $specialite = $request->get('specialite');
        $docteurs = $docteurRepository->findBy(["ville_etab"=>$ville,"sepicialite"=>$specialite]);
        return $this->view($docteurs, Response::HTTP_OK);
    }
    /**
     * @OA\Tag(name="AccessPatient")
     * @Route("/api/new/access", name="access_patient", methods={"POST"})
     * @return bool
     * @throws Exception
     * @OA\Response(
     *     response=200,
     *     description="demande d access pour un patient",
     * )
     * @OA\Parameter(
     *     name="docteur_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     */

    public function newAcess(Request $request, AccessRepository $accessRepository, PatientRepository $patientRepository, DocteurRepository $docteurRepository)
    {
        $docteur_id = $request->get('docteur_id');
        $username = $request->get('username');

        $patient = $patientRepository->findOneBy(['email' => $username]);
        $docteur = $docteurRepository->findOneBy(['id' => $docteur_id]);

        $access = $accessRepository->findOneBy(['docteur' => $docteur->getId(), 'patient'=> $patient->getId()]);

        if(!$access){
            $access = new Access();
            $access->setDocteur($docteur)->setPatient($patient)->setIsGranted(false);
            $this->entityManager->persist($access);
            $this->entityManager->flush();
            return true;

        }
        return false;
    }

    /**
     * @OA\Tag(name="Docteur")
     * @Route("/api/ajout/hospitalisation/par/docteur", name="ajout_hospitalisation_par_docteurr", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Ajout une hospitalisation pour un patient par un docteur",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="email_patient",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="motif",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="duree",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="heure",
     *     in="query",
     *     @OA\Schema(type="integer")
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
     * @throws Exception
     */
    public function ajoutHospitalisationParDocteur(Request $request, DocteurRepository $docteurRepository, AccessRepository $accessRepository)
    {
        $email = $request->get('username');
        $email_patient = $request->get('email_patient');
        $docteur = $docteurRepository->findOneBy(['email' => $email]);
        $patient = $docteurRepository->findOneBy(['email' => $email_patient]);
        $patient_docteur = $accessRepository->findOneBy(['docteur' => $docteur, 'patient'=> $patient]);

        if($patient_docteur){

            $motif = $request->get('motif');
            $duree = $request->get('duree');
            $heure = $request->get('heure');
            $commentaire = $request->get('commentaire');
            $date_debut = $request->get('date_debut');

            $hospitalisation = new Hospitalisation();

            $hospitalisation->setMotif($motif);
            $hospitalisation->setDuree($duree);
            $hospitalisation->setHeure($heure);
            $hospitalisation->setDateDebut(new \DateTime($date_debut));
            $hospitalisation->setCommentaire($commentaire);
            $hospitalisation->setPatient($patient_docteur->getPatient());
            $hospitalisation->setAuteur('docteur');

            $this->entityManager->persist($hospitalisation);
            $this->entityManager->flush();

            return $this->view($hospitalisation, Response::HTTP_OK)->setContext((new Context())->setGroups(['hospitalisation']));
        } else {
            return $this->view('le patient n\'est pas affecté au docteur ou n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Docteur")
     * @Route("/api/list/hospitalisation/par/docteur", name="list_hospitalisations_par_docteur", methods={"POST"})
     * @return View
     * @OA\Response(
     *     response=200,
     *     description="list des hospitalisations pour un patient ajouté par un docteur",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="email_patient",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     */
    public function listHospitalisationsParDocteur(Request $request, HospitalisationRepository $hospitalisationRepository, PatientRepository $patientRepository, DocteurRepository $docteurRepository, AccessRepository $accessRepository)
    {

        $email = $request->get('username');
        $email_patient = $request->get('email_patient');
        $docteur = $docteurRepository->findOneBy(['email' => $email]);
        $patient = $docteurRepository->findOneBy(['email' => $email_patient]);
        $patient_docteur = $accessRepository->findOneBy(['docteur' => $docteur, 'patient'=> $patient]);

        if($patient_docteur){

            $patient = $patient_docteur->getPatient();
            $hospitalisations = $hospitalisationRepository->findBy(['patient' => $patient->getId() , 'auteur' => 'docteur']);
            return $this->view($hospitalisations, Response::HTTP_OK)->setContext((new Context())->setGroups(['hospitalisation']));
        } else {
            return $this->view('le patient n\'est pas affecté au docteur ou n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Docteur")
     * @Route("/api/ajout/traitement/par/docteur", name="ajout_traitement_par_docteur", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Ajout un traitement pour un patient par un docteur",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="email_patient",
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
    public function ajoutTraitement(Request $request, TraitementRepository $traitementRepository, PatientRepository $patientRepository, DocteurRepository $docteurRepository, AccessRepository $accessRepository)
    {
        $email = $request->get('username');
        $email_patient = $request->get('email_patient');
        $docteur = $docteurRepository->findOneBy(['email' => $email]);
        $patient = $docteurRepository->findOneBy(['email' => $email_patient]);
        $patient_docteur = $accessRepository->findOneBy(['docteur' => $docteur, 'patient'=> $patient]);

        $nom = $request->get('nom');
        $postologie_trait = $request->get('postologie_trait');
        $commentaire = $request->get('commentaire');
        $date_trait = $request->get('date_trait');

        if($patient_docteur){

            $traitement = new Traitement();

            $traitement->setNom($nom);
            $traitement->setPosologieTrait($postologie_trait);
            $traitement->setCommentaire($commentaire);
            $traitement->setDateTrait(new \DateTime($date_trait));
            $traitement->setPatient($patient_docteur->getPatient());
            $traitement->setAuteur('docteur');

            $this->entityManager->persist($traitement);
            $this->entityManager->flush();

            return $this->view($traitement, Response::HTTP_OK)->setContext((new Context())->setGroups(['traitement']));
        } else {
            return $this->view('le patient n\'est pas affecté au docteur ou n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Tag(name="Docteur")
     * @Route("/api/list/traitements/par/docteur", name="list_traitements_par_docteur", methods={"POST"})
     * @return View
     * @OA\Response(
     *     response=200,
     *     description="list des traitements pour un patient par docteur",
     * )
     * @OA\Parameter(
     *     name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="email_patient",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     */
    public function listTraitementsParDocteur(Request $request, TraitementRepository $traitementRepository, PatientRepository $patientRepository, DocteurRepository $docteurRepository, AccessRepository $accessRepository)
    {

        $email = $request->get('username');
        $email_patient = $request->get('email_patient');
        $docteur = $docteurRepository->findOneBy(['email' => $email]);
        $patient = $docteurRepository->findOneBy(['email' => $email_patient]);
        $patient_docteur = $accessRepository->findOneBy(['docteur' => $docteur, 'patient'=> $patient]);

        if($patient_docteur){
            $traitements = $traitementRepository->findBy(['patient' => $patient->getId(), 'auteur' => 'docteur']);
            return $this->view($traitements, Response::HTTP_OK)->setContext((new Context())->setGroups(['traitement']));
        } else {
            return $this->view('le patient n\'est pas affecté au docteur ou n\'existe pas', Response::HTTP_NOT_FOUND);
        }
    }
}
