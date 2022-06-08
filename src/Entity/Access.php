<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AccessRepository::class)
 */
class Access
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"access" })
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"access" })
     */
    private $isGranted;

    /**
     * @ORM\ManyToOne(targetEntity=Docteur::class, inversedBy="access")
     * @Groups({"access" })
     */
    private $docteur;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="access")
     * @Groups({"access" })
     */
    private $patient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsGranted(): ?bool
    {
        return $this->isGranted;
    }

    public function setIsGranted(?bool $isGranted): self
    {
        $this->isGranted = $isGranted;

        return $this;
    }

    public function getDocteur(): ?Docteur
    {
        return $this->docteur;
    }

    public function setDocteur(?Docteur $docteur): self
    {
        $this->docteur = $docteur;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }


}
