<?php

namespace App\Entity;

use App\Repository\HistoriqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueRepository::class)]
class Historique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'historiques')]
    private ?Produit $produit = null;

    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $heurecameroun = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $heureserver = null;

    #[ORM\ManyToOne(inversedBy: 'historiques')]
    private ?Agence $agance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getHeurecameroun(): ?string
    {
        return $this->heurecameroun;
    }

    public function setHeurecameroun(string $heurecameroun): static
    {
        $this->heurecameroun = $heurecameroun;

        return $this;
    }

    public function getHeureserver(): ?string
    {
        return $this->heureserver;
    }

    public function setHeureserver(?string $heureserver): static
    {
        $this->heureserver = $heureserver;

        return $this;
    }

    public function getAgance(): ?Agence
    {
        return $this->agance;
    }

    public function setAgance(?Agence $agance): static
    {
        $this->agance = $agance;

        return $this;
    }
}
