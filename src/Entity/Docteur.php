<?php

namespace App\Entity;

use App\Repository\DocteurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=DocteurRepository::class)
 */
class Docteur implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"docteur"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"docteur"})
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
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"docteur"})
     */
    private $rpps;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"docteur"})
     */
    private $cin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"docteur"})
     */
    private $email_professionnel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"docteur"})
     */
    private $sepicialite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"docteur"})
     */
    private $langues;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"docteur"})
     */
    private $nom_etab;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"docteur"})
     */
    private $num_etab;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"docteur"})
     */
    private $ville_etab;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"docteur"})
     */
    private $email_etab;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"docteur"})
     */
    private $adresse_etab;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="Docteur", cascade={"persist", "remove"})
     * @Groups({"docteur"})
     */
    private $user;

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

    public function getSepicialite(): ?string
    {
        return $this->sepicialite;
    }

    public function setSepicialite(?string $sepicialite): self
    {
        $this->sepicialite = $sepicialite;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setDocteur(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getDocteur() !== $this) {
            $user->setDocteur($this);
        }

        $this->user = $user;

        return $this;
    }
}
