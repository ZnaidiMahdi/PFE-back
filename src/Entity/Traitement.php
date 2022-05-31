<?php

namespace App\Entity;

use App\Repository\TraitementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=TraitementRepository::class)
 */
class Traitement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"traitement"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"traitement"})
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"traitement"})
     */
    private $date_trait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"traitement"})
     */
    private $posologie_trait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"traitement"})
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=patient::class, inversedBy="traitements")
     * @Groups({"traitement"})
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

    public function getDateTrait(): ?\DateTimeInterface
    {
        return $this->date_trait;
    }

    public function setDateTrait(?\DateTimeInterface $date_trait): self
    {
        $this->date_trait = $date_trait;

        return $this;
    }

    public function getPosologieTrait(): ?string
    {
        return $this->posologie_trait;
    }

    public function setPosologieTrait(?string $posologie_trait): self
    {
        $this->posologie_trait = $posologie_trait;

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
