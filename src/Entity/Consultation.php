<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ConsultationRepository::class)
 */
class Consultation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"consultation" })
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"consultation" })
     */
    private $titre;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"consultation" })
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"consultation" })
     */
    private $diagnostic;

    /**
     * @ORM\ManyToOne(targetEntity=Docteur::class, inversedBy="consultations")
     * @Groups({"consultation" })
     */
    private $docteur;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="consultations")
     * @Groups({"consultation" })
     */
    private $patient;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"consultation" })
     */
    private $document;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDiagnostic(): ?string
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(?string $diagnostic): self
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    public function getDocteur(): ?docteur
    {
        return $this->docteur;
    }

    public function setDocteur(?docteur $docteur): self
    {
        $this->docteur = $docteur;

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

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): self
    {
        $this->document = $document;

        return $this;
    }
}
