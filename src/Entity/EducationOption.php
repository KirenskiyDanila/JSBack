<?php

namespace App\Entity;

use App\Repository\EducationOptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EducationOptionRepository::class)]
class EducationOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['options'])]
    private ?string $institution = null;

    #[ORM\Column(length: 255)]
    #[Groups(['options'])]
    private ?string $faculty = null;

    #[ORM\Column(length: 255)]
    #[Groups(['options'])]
    private ?string $specialization = null;

    #[ORM\Column(length: 255)]
    #[Groups(['options'])]
    private ?string $endYear = null;

    #[ORM\ManyToOne(inversedBy: 'educationOption')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Resume $resume = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    public function setInstitution(string $institution): self
    {
        $this->institution = $institution;

        return $this;
    }

    public function getFaculty(): ?string
    {
        return $this->faculty;
    }

    public function setFaculty(string $faculty): self
    {
        $this->faculty = $faculty;

        return $this;
    }

    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    public function setSpecialization(string $specialization): self
    {
        $this->specialization = $specialization;

        return $this;
    }

    public function getEndYear(): ?string
    {
        return $this->endYear;
    }

    public function setEndYear(string $endYear): self
    {
        $this->endYear = $endYear;

        return $this;
    }

    public function getResume(): ?Resume
    {
        return $this->resume;
    }

    public function setResume(?Resume $resume): self
    {
        $this->resume = $resume;

        return $this;
    }
}