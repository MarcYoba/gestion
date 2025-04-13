<?php

namespace App\Entity;

use App\Repository\ProduitARepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitARepository::class)]
class ProduitA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'produitAs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $prixvente = null;

    #[ORM\Column]
    private ?float $prixachat = null;

    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\Column]
    private ?float $gain = null;

    #[ORM\Column]
    private ?float $stockdebut = null;

    #[ORM\Column(length: 100)]
    private ?string $cathegorie = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrixvente(): ?float
    {
        return $this->prixvente;
    }

    public function setPrixvente(float $prixvente): static
    {
        $this->prixvente = $prixvente;

        return $this;
    }

    public function getPrixachat(): ?float
    {
        return $this->prixachat;
    }

    public function setPrixachat(float $prixachat): static
    {
        $this->prixachat = $prixachat;

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

    public function getGain(): ?float
    {
        return $this->gain;
    }

    public function setGain(float $gain): static
    {
        $this->gain = $gain;

        return $this;
    }

    public function getStockdebut(): ?float
    {
        return $this->stockdebut;
    }

    public function setStockdebut(float $stockdebut): static
    {
        $this->stockdebut = $stockdebut;

        return $this;
    }

    public function getCathegorie(): ?string
    {
        return $this->cathegorie;
    }

    public function setCathegorie(string $cathegorie): static
    {
        $this->cathegorie = $cathegorie;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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
