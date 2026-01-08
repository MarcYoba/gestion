<?php

namespace App\Entity;

use App\Repository\AchatARepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchatARepository::class)]
class AchatA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'achatAs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProduitA $produit = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'achatAs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FournisseurA $forunisseur = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'achatAs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'achatAs')]
    private ?Agence $agence = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?array $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?ProduitA
    {
        return $this->produit;
    }

    public function setProduit(?ProduitA $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getForunisseur(): ?FournisseurA
    {
        return $this->forunisseur;
    }

    public function setForunisseur(?FournisseurA $forunisseur): static
    {
        $this->forunisseur = $forunisseur;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?array
    {
        return $this->image;
    }

    public function setImage(?array $image): static
    {
        $this->image = $image;

        return $this;
    }
}
