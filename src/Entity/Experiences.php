<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExperiencesRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ExperiencesRepository::class)
 */
#[ApiResource()]
class Experiences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["user:read", "user:write" ])]
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["user:read", "user:write" ])]
    private $company;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["user:read", "user:write" ])]
    private $city;

    /**
     * @ORM\Column(type="datetime", nullable ="true")
     */
    #[Groups(["user:read", "user:write" ])]
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable ="true")
     */
    #[Groups(["user:read", "user:write" ])]
    private $endDate;

    /**
     * @ORM\Column(type="text")
     */
    #[Groups(["user:read", "user:write" ])]
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Cv::class, inversedBy="experiences")
     */
    private $cv;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCv(): ?Cv
    {
        return $this->cv;
    }

    public function setCv(?Cv $cv): self
    {
        $this->cv = $cv;

        return $this;
    }
}
