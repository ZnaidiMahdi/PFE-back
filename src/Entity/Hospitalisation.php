<?php

namespace App\Entity;

use App\Repository\HospitalisationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=HospitalisationRepository::class)
 */
class Hospitalisation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"hospitalisation"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"hospitalisation"})
     */
    private $motif;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"hospitalisation"})
     */
    private $date_debut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"hospitalisation"})
     */
    private $duree;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"hospitalisation"})
     */
    private $heure;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"hospitalisation"})
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=patient::class, inversedBy="hospitalisations")
     * @Groups({"hospitalisation"})
     */
    private $patient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): self
    {
        $this->motif = $motif;

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

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getHeure(): ?int
    {
        return $this->heure;
    }

    public function setHeure(?int $heure): self
    {
        $this->heure = $heure;

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
