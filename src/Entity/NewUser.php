<?php

namespace App\Entity;

use App\Repository\NewUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NewUserRepository::class)
 */
class NewUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_naissance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $num_tel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_securite_sociale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profession;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statut_sociale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nbr_enfant;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $poids;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $taille;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $temperature;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $frequence_cardiaque;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tension_arterielle;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $glycemie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rpps;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email_professionnel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $specialite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $langues;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom_etab;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $num_etab;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville_etab;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email_etab;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse_etab;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_validate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_patient;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_docteur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(?\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->num_tel;
    }

    public function setNumTel(?string $num_tel): self
    {
        $this->num_tel = $num_tel;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(?int $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCodeSecuriteSociale(): ?string
    {
        return $this->code_securite_sociale;
    }

    public function setCodeSecuriteSociale(?string $code_securite_sociale): self
    {
        $this->code_securite_sociale = $code_securite_sociale;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getStatutSociale(): ?string
    {
        return $this->statut_sociale;
    }

    public function setStatutSociale(?string $statut_sociale): self
    {
        $this->statut_sociale = $statut_sociale;

        return $this;
    }

    public function getNbrEnfant(): ?string
    {
        return $this->nbr_enfant;
    }

    public function setNbrEnfant(?string $nbr_enfant): self
    {
        $this->nbr_enfant = $nbr_enfant;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(?float $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(?float $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getFrequenceCardiaque(): ?float
    {
        return $this->frequence_cardiaque;
    }

    public function setFrequenceCardiaque(?float $frequence_cardiaque): self
    {
        $this->frequence_cardiaque = $frequence_cardiaque;

        return $this;
    }

    public function getTensionArterielle(): ?float
    {
        return $this->tension_arterielle;
    }

    public function setTensionArterielle(?float $tension_arterielle): self
    {
        $this->tension_arterielle = $tension_arterielle;

        return $this;
    }

    public function getGlycemie(): ?float
    {
        return $this->glycemie;
    }

    public function setGlycemie(?float $glycemie): self
    {
        $this->glycemie = $glycemie;

        return $this;
    }

    public function getRpps(): ?int
    {
        return $this->rpps;
    }

    public function setRpps(?int $rpps): self
    {
        $this->rpps = $rpps;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(?int $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getEmailProfessionnel(): ?string
    {
        return $this->email_professionnel;
    }

    public function setEmailProfessionnel(?string $email_professionnel): self
    {
        $this->email_professionnel = $email_professionnel;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getLangues(): ?string
    {
        return $this->langues;
    }

    public function setLangues(?string $langues): self
    {
        $this->langues = $langues;

        return $this;
    }

    public function getNomEtab(): ?string
    {
        return $this->nom_etab;
    }

    public function setNomEtab(?string $nom_etab): self
    {
        $this->nom_etab = $nom_etab;

        return $this;
    }

    public function getNumEtab(): ?string
    {
        return $this->num_etab;
    }

    public function setNumEtab(?string $num_etab): self
    {
        $this->num_etab = $num_etab;

        return $this;
    }

    public function getVilleEtab(): ?string
    {
        return $this->ville_etab;
    }

    public function setVilleEtab(?string $ville_etab): self
    {
        $this->ville_etab = $ville_etab;

        return $this;
    }

    public function getEmailEtab(): ?string
    {
        return $this->email_etab;
    }

    public function setEmailEtab(?string $email_etab): self
    {
        $this->email_etab = $email_etab;

        return $this;
    }

    public function getAdresseEtab(): ?string
    {
        return $this->adresse_etab;
    }

    public function setAdresseEtab(?string $adresse_etab): self
    {
        $this->adresse_etab = $adresse_etab;

        return $this;
    }

    public function isIsValidate(): ?bool
    {
        return $this->is_validate;
    }

    public function setIsValidate(bool $is_validate): self
    {
        $this->is_validate = $is_validate;

        return $this;
    }

    public function isIsPatient(): ?bool
    {
        return $this->is_patient;
    }

    public function setIsPatient(?bool $is_patient): self
    {
        $this->is_patient = $is_patient;

        return $this;
    }

    public function isIsDocteur(): ?bool
    {
        return $this->is_docteur;
    }

    public function setIsDocteur(?bool $is_docteur): self
    {
        $this->is_docteur = $is_docteur;

        return $this;
    }
}
