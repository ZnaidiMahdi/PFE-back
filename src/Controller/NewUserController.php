<?php

namespace App\Controller;

use App\Entity\NewUser;
use App\Entity\Patient;
use App\Entity\User;
use App\Repository\NewUserRepository;
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


class NewUserController extends AbstractFOSRestController
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
     * @Route("/api/registre/patient", name="pre-inscription_patient", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Pre-inscription pour un patient",
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
     *     @OA\Schema(type="file")
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
    public function preInscriptionPatient(Request $request, UserRepository $userRepository, FileUploader $fileUploader)
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

            $user = new NewUser();

            $user->setEmail($email);
            $user->setPassword($this->passwordEncoder->encodePassword(new Patient(), $password));
            $user->setRoles(["ROLE_PATIENT"]);
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setDateNaissance(new \DateTime($date_naissance));
            $user->setNumTel($num_tel);
            $user->setVille($ville);
            $user->setAdresse($adresse);
            $user->setCodePostal($code_postal);
            $user->setIsPatient(1);
            $user->setIsDocteur(0);
            $user->setSexe($sexe);
            if (array_key_exists('photo', $files)) {
                $photo = $files['photo'];
                $user->setPhoto($photo);
            }
            $user->setCodeSecuriteSociale($code_securite_sociale);
            $user->setStatutSociale($statut_sociale);
            $user->setNbrEnfant($nbr_enfant);
            $user->setIsValidate(0);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->view($user, Response::HTTP_OK);
        }
    }

    /**
     * @OA\Tag(name="Docteur")
     * @Route("/api/registre/docteur", name="pre-inscription_docteur", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Pre-inscription pour un docteur",
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
     *     @OA\Schema(type="file")
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
     **/
    public function preInscrptionDocteur(Request $request, UserRepository $userRepository, FileUploader $fileUploader)
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
            $rpps = $request->get('rpps');
            $cin = $request->get('cin');
            $email_professionnel = $request->get('email_professionnel');
            $sepicialite = $request->get('sepicialite');
            $langues = $request->get('langues');
            $nom_etab = $request->get('nom_etab');
            $num_etab = $request->get('num_etab');
            $ville_etab = $request->get('ville_etab');
            $email_etab = $request->get('email_etab');
            $adresse_etab = $request->get('adresse_etab');

            $files = $fileUploader->upload($request);

            $user = new NewUser();

            $user->setEmail($email);
            $user->setPassword($this->passwordEncoder->encodePassword(new User(), $password));
            $user->setRoles(["ROLE_DOCTEUR"]);
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setDateNaissance(new \DateTime($date_naissance));
            $user->setNumTel($num_tel);
            $user->setVille($ville);
            $user->setAdresse($adresse);
            $user->setCodePostal($code_postal);
            $user->setSexe($sexe);
            $user->setIsPatient(1);
            $user->setIsDocteur(0);

            if (array_key_exists('photo', $files)) {
                $photo = $files['photo'];
                $user->setPhoto($photo);
            }
            $user->setIsValidate(0);
            $user->setRpps($rpps);
            $user->setCin($cin);
            $user->setEmailProfessionnel($email_professionnel);
            $user->setSpecialite($sepicialite);
            $user->setLangues($langues);
            $user->setNomEtab($nom_etab);
            $user->setNumEtab($num_etab);
            $user->setVilleEtab($ville_etab);
            $user->setEmailEtab($email_etab);
            $user->setAdresseEtab($adresse_etab);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->view($user, Response::HTTP_OK);
        }
    }
}
