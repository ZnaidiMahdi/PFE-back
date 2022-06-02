<?php

namespace App\Entity;

use App\Repository\QuestionReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=QuestionReponseRepository::class)
 */
class QuestionReponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"question_reponse" })
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"question_reponse" })
     */
    private $titre;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"question_reponse" })
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"question_reponse" })
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"question_reponse" })
     */
    private $reponse;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="questionReponses")
     * @Groups({"question_reponse" })
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity=dOCTEUR::class, inversedBy="questionReponses")
     * @Groups({"question_reponse" })
     */
    private $docteur;

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

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(?string $reponse): self
    {
        $this->reponse = $reponse;

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

    public function getDocteur(): ?dOCTEUR
    {
        return $this->docteur;
    }

    public function setDocteur(?dOCTEUR $docteur): self
    {
        $this->docteur = $docteur;

        return $this;
    }
}
