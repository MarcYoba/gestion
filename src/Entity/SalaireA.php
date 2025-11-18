<?php

namespace App\Entity;

use App\Repository\SalaireARepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalaireARepository::class)]
class SalaireA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createtAt = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'salaireAs')]
    private ?Employer $employer = null;

    #[ORM\Column]
    private ?float $salaireBrut = null;

    #[ORM\Column]
    private ?float $cotisationSociales = null;

    #[ORM\Column]
    private ?float $impots = null;

    #[ORM\ManyToOne(inversedBy: 'salaireAs')]
    private ?Agence $agence = null;

    #[ORM\ManyToOne(inversedBy: 'salaireAs')]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCreatetAt(): ?\DateTimeInterface
    {
        return $this->createtAt;
    }

    public function setCreatetAt(\DateTimeInterface $createtAt): static
    {
        $this->createtAt = $createtAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getEmployer(): ?Employer
    {
        return $this->employer;
    }

    public function setEmployer(?Employer $employer): static
    {
        $this->employer = $employer;

        return $this;
    }

    public function getSalaireBrut(): ?float
    {
        return $this->salaireBrut;
    }

    public function setSalaireBrut(float $salaireBrut): static
    {
        $this->salaireBrut = $salaireBrut;

        return $this;
    }

    public function getCotisationSociales(): ?float
    {
        return $this->cotisationSociales;
    }

    public function setCotisationSociales(float $cotisationSociales): static
    {
        $this->cotisationSociales = $cotisationSociales;

        return $this;
    }

    public function getImpots(): ?float
    {
        return $this->impots;
    }

    public function setImpots(float $impots): static
    {
        $this->impots = $impots;

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
