<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ExperienceRepository::class)
 */
class Experience
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"experience"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"experience"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"experience"})
     */
    private $hopital;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"experience"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Docteur::class, inversedBy="experiences")
     * @Groups({"experience"})
     */
    private $docteur;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getHopital(): ?string
    {
        return $this->hopital;
    }

    public function setHopital(?string $hopital): self
    {
        $this->hopital = $hopital;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
}
