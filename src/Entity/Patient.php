<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 */
class Patient implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"patient"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"patient"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="patient", cascade={"persist", "remove"})
     * @Groups({"patient"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"patient"})
     */
    private $code_securite_sociale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"patient"})
     */
    private $profession;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"patient"})
     */
    private $statut_sociale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"patient"})
     */
    private $nbr_enfant;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"patient"})
     */
    private $poids;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"patient"})
     */
    private $taille;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"patient"})
     */
    private $temperature;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"patient"})
     */
    private $frequence_cardiaque;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"patient"})
     */
    private $tension_arterielle;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"patient"})
     */
    private $glycemie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setPatient(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getPatient() !== $this) {
            $user->setPatient($this);
        }

        $this->user = $user;

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

    public function setTemperature(float $temperature): self
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
}
