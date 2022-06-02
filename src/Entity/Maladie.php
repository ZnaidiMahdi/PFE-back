<?php

namespace App\Entity;

use App\Repository\MaladieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=MaladieRepository::class)
 */
class Maladie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"maladie"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"maladie"})
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"maladie"})
     */
    private $date_debut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"maladie"})
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="maladies")
     * @Groups({"maladie"})
     */
    private $patient;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(?\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getPatient(): ?patient
    {
        return $this->patient;
    }

    public function setPatient(?patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }
}
