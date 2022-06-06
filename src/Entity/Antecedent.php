<?php

namespace App\Entity;

use App\Repository\AntecedentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=AntecedentRepository::class)
 */
class Antecedent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"antecedent"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"antecedent"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"antecedent"})
     */
    private $lien_familial;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"antecedent"})
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=patient::class, inversedBy="antecedents")
     * @Groups({"antecedent"})
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

    public function getLienFamilial(): ?string
    {
        return $this->lien_familial;
    }

    public function setLienFamilial(?string $lien_familial): self
    {
        $this->lien_familial = $lien_familial;

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
