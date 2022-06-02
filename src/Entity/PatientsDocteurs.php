<?php

namespace App\Entity;

use App\Repository\PatientsDocteursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PatientsDocteursRepository::class)
 */
class PatientsDocteurs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_accepted;

    /**
     * @ORM\ManyToOne(targetEntity=Docteur::class, inversedBy="patients")
     */
    private $docteur;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="docteurs")
     */
    private $patient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsAccepted(): ?bool
    {
        return $this->is_accepted;
    }

    public function setIsAccepted(?bool $is_accepted): self
    {
        $this->is_accepted = $is_accepted;

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
}
