<?php

namespace App\Entity;

use App\Repository\VaccinationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=VaccinationRepository::class)
 */
class Vaccination
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"vaccination"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"vaccination"})
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"vaccination"})
     */
    private $date_vaccination;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"vaccination"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"vaccination"})
     */
    private $lot_vaccination;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"vaccination"})
     */
    private $nom_vaccinateur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"vaccination"})
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=patient::class, inversedBy="vaccinations")
     * @Groups({"vaccination"})
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

    public function getDateVaccination(): ?\DateTimeInterface
    {
        return $this->date_vaccination;
    }

    public function setDateVaccination(?\DateTimeInterface $date_vaccination): self
    {
        $this->date_vaccination = $date_vaccination;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLotVaccination(): ?string
    {
        return $this->lot_vaccination;
    }

    public function setLotVaccination(?string $lot_vaccination): self
    {
        $this->lot_vaccination = $lot_vaccination;

        return $this;
    }

    public function getNomVaccinateur(): ?string
    {
        return $this->nom_vaccinateur;
    }

    public function setNomVaccinateur(?string $nom_vaccinateur): self
    {
        $this->nom_vaccinateur = $nom_vaccinateur;

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
