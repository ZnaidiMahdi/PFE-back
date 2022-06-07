<?php

namespace App\Controller;

use App\Entity\Docteur;
use App\Entity\Patient;
use App\Entity\User;
use App\Repository\NewUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class AdminController extends AbstractFOSRestController
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
     * @OA\Tag(name="Admin")
     * @Route("/api/valider/patient", name="valider_patient", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="valider un patient pour pouvoir se connecter",
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @return View
     */
    public function validerPatient(Request $request, NewUserRepository $newUserRepositoryr)
    {
        $id = $request->get('id');
        $new_user = $newUserRepositoryr->findOneBy(['id' => $id]);

        if($new_user) {

            $user = new User();

            $user->setEmail($new_user->getEmail());
            $user->setPassword($new_user->getPassword());
            $user->setRoles($new_user->getRoles());
            $user->setNom($new_user->getNom());
            $user->setPrenom($new_user->getPrenom());
            $user->setDateNaissance($new_user->getDateNaissance());
            $user->setNumTel($new_user->getNumTel());
            $user->setVille($new_user->getVille());
            $user->setAdresse($new_user->getAdresse());
            $user->setCodePostal($new_user->getCodePostal());
            $user->setSexe($new_user->getSexe());
            if($new_user->getPhoto()){
                $user->setPhoto($new_user->getPhoto());
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $patient = new Patient();

            $patient->setEmail($new_user->getEmail());
            $patient->setPassword($new_user->getPassword());
            $patient->setRoles($new_user->getRoles());
            $patient->setCodeSecuriteSociale($new_user->getCodeSecuriteSociale());
            $patient->setStatutSociale($new_user->getStatutSociale());
            $patient->setNbrEnfant($new_user->getNbrEnfant());
            $patient->setUser($user);

            $this->entityManager->persist($patient);
            $this->entityManager->flush();

            $this->entityManager->remove($new_user);
            $this->entityManager->flush();

            return $this->view($patient, Response::HTTP_OK)->setContext((new Context())->setGroups(['patient']));
        } else {
            return $this->view('l`utilisateur n`existe pas', Response::HTTP_CONFLICT);
        }
    }

    /**
     * @OA\Tag(name="Admin")
     * @Route("/api/valider/docteur", name="valider_docteur", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="valider un docteur pour pouvoir se connecter",
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @return View
     */
    public function validerDocteur(Request $request, NewUserRepository $newUserRepositoryr)
    {
        $id = $request->get('id');
        $new_user = $newUserRepositoryr->findOneBy(['id' => $id]);

        if($new_user) {

            $user = new User();

            $user->setEmail($new_user->getEmail());
            $user->setPassword($new_user->getPassword());
            $user->setRoles($new_user->getRoles());
            $user->setNom($new_user->getNom());
            $user->setPrenom($new_user->getPrenom());
            $user->setDateNaissance($new_user->getDateNaissance());
            $user->setNumTel($new_user->getNumTel());
            $user->setVille($new_user->getVille());
            $user->setAdresse($new_user->getAdresse());
            $user->setCodePostal($new_user->getCodePostal());
            $user->setSexe($new_user->getSexe());
            if($new_user->getPhoto()){
                $user->setPhoto($new_user->getPhoto());
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $docteur = new Docteur();

            $docteur->setEmail($new_user->getEmail());
            $docteur->setPassword($new_user->getPassword());
            $docteur->setRoles($new_user->getRoles());
            $docteur->setRpps($new_user->getRpps());
            $docteur->setCin($new_user->getCin());
            $docteur->setEmailProfessionnel($new_user->getEmailProfessionnel());
            $docteur->setSepicialite($new_user->getSpecialite());
            $docteur->setLangues($new_user->getLangues());
            $docteur->setNomEtab($new_user->getNomEtab());
            $docteur->setNumEtab($new_user->getNumEtab());
            $docteur->setVilleEtab($new_user->getVille());
            $docteur->setEmailEtab($new_user->getEmailEtab());
            $docteur->setAdresseEtab($new_user->getAdresseEtab());
            $docteur->setUser($user);

            $this->entityManager->persist($docteur);
            $this->entityManager->flush();

            $this->entityManager->remove($new_user);
            $this->entityManager->flush();

            return $this->view($docteur, Response::HTTP_OK)->setContext((new Context())->setGroups(['docteur']));
        } else {
            return $this->view('l`utilisateur n`existe pas', Response::HTTP_CONFLICT);
        }
    }


    /**
     * @OA\Tag(name="Admin")
     * @Route("/api/supprimer/utilisateur", name="supprimer_utilisateur", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="supprimer un utilisateur en attente",
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @return View
     */
    public function supprimerUtilisateur(Request $request, NewUserRepository $newUserRepositoryr)
    {
        $id = $request->get('id');
        $new_user = $newUserRepositoryr->findOneBy(['id' => $id]);

        if($new_user) {

            $this->entityManager->remove($new_user);
            $this->entityManager->flush();

            return $this->view('l`utilisateur est supprimé avec succès', Response::HTTP_OK);
        } else {
            return $this->view('l`utilisateur n`existe pas', Response::HTTP_CONFLICT);
        }
    }

    /**
     * @OA\Tag(name="Admin")
     * @Route("/api/list/patients/en_attente", name="list_patients_en_attente", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="list des patients en attente",
     * )
     * @return View
     */
    public function listPatientsEnAttente(NewUserRepository $newUserRepositoryr)
    {
        $patients = $newUserRepositoryr->findBy(['is_patient' => 1]);
        return $this->view($patients, Response::HTTP_OK);
    }

    /**
     * @OA\Tag(name="Admin")
     * @Route("/api/list/docteurs/en_attente", name="list_docteurs_en_attente", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="list des docteurs en attente",
     * )
     * @return View
     */
    public function listDocteursEnAttente(NewUserRepository $newUserRepositoryr)
    {
        $patients = $newUserRepositoryr->findBy(['is_docteur' => 1]);
        return $this->view($patients, Response::HTTP_OK);
    }
}
