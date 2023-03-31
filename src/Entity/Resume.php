<?php

namespace App\Entity;

use App\Repository\ResumeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ResumeRepository::class)]
class Resume
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['resume'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resume'])]
    private ?string $profession = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resume'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resume'])]
    private ?string $photo = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resume'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resume'])]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resume'])]
    private ?string $dateOfBirth = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resume'])]
    private ?string $education = null;

    #[ORM\OneToMany(mappedBy: 'resume', targetEntity: EducationOption::class)]
    private Collection $educationOption;

    #[ORM\Column(length: 255)]
    #[Groups(['resume'])]
    private ?string $salary = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resume'])]
    private ?string $skills = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resume'])]
    private ?string $about = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resume'])]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resume'])]
    private ?string $email = null;

    public function __construct()
    {
        $this->educationOption = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(string $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getEducation(): ?string
    {
        return $this->education;
    }

    public function setEducation(string $education): self
    {
        $this->education = $education;

        return $this;
    }

    /**
     * @return Collection<int, EducationOption>
     */
    public function getEducationOption(): Collection
    {
        return $this->educationOption;
    }

    public function addEducationOption(EducationOption $educationOption): self
    {
        if (!$this->educationOption->contains($educationOption)) {
            $this->educationOption[] = $educationOption;
            $educationOption->setResume($this);
        }

        return $this;
    }

    public function removeEducationOption(EducationOption $educationOption): self
    {
        if ($this->educationOption->removeElement($educationOption)) {
            // set the owning side to null (unless already changed)
            if ($educationOption->getResume() === $this) {
                $educationOption->setResume(null);
            }
        }

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(string $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getSkills(): ?string
    {
        return $this->skills;
    }

    public function setSkills(string $skills): self
    {
        $this->skills = $skills;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(string $about): self
    {
        $this->about = $about;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}