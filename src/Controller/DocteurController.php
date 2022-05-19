<?php

namespace App\Controller;

use App\Entity\Docteur;
use App\Entity\Patient;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/registre/docteur", name="registre_docteur")
     * @return View
     */
    public function registreDocteur(Request $request, UserRepository $userRepository)
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

            $docteur = new Docteur();

            $docteur->setEmail($email);
            $docteur->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $docteur->setRoles(["ROLE_DOCTEUR"]);
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
            $docteur->setUser($user);

            $this->entityManager->persist($docteur);
            $this->entityManager->flush();

            return $this->view($docteur, Response::HTTP_OK)->setContext((new Context())->setGroups(['docteur']));
        }
    }
}
