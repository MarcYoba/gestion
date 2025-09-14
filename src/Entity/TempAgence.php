<?php

namespace App\Entity;

use App\Repository\TempAgenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TempAgenceRepository::class)]
class TempAgence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tempAgences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'tempAgences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agence $agence = null;

    #[ORM\Column]
    private ?bool $generale = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;

        return $this;
    }

    public function isGenerale(): ?bool
    {
        return $this->generale;
    }

    public function setGenerale(bool $generale): static
    {
        $this->generale = $generale;

        return $this;
    }
}
