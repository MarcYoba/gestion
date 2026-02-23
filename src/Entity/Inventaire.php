<?php

namespace App\Entity;

use App\Repository\InventaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventaireRepository::class)]
class Inventaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\Column]
    private ?float $inventaire = null;

    #[ORM\ManyToOne(inversedBy: 'inventaireAs')]
    private ?ProduitA $produit = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createtAt = null;

    #[ORM\ManyToOne(inversedBy: 'inventaireAs')]
    private ?User $user = null;

    #[ORM\Column]
    private ?float $ecart = null;

    #[ORM\Column(length: 255)]
    private ?string $justificatif = null;

    #[ORM\ManyToOne(inversedBy: 'inventaireAs')]
    private ?Agence $agence = null;

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getInventaire(): ?float
    {
        return $this->inventaire;
    }

    public function setInventaire(float $inventaire): static
    {
        $this->inventaire = $inventaire;

        return $this;
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

    public function getCreatetAt(): ?\DateTimeInterface
    {
        return $this->createtAt;
    }

    public function setCreatetAt(\DateTimeInterface $createtAt): static
    {
        $this->createtAt = $createtAt;

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

    public function getEcart(): ?float
    {
        return $this->ecart;
    }

    public function setEcart(float $ecart): static
    {
        $this->ecart = $ecart;

        return $this;
    }

    public function getJustificatif(): ?string
    {
        return $this->justificatif;
    }

    public function setJustificatif(string $justificatif): static
    {
        $this->justificatif = $justificatif;

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
}
