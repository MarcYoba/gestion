<?php

namespace App\Entity;

use App\Repository\FactureARepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureARepository::class)]
class FactureA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'factureAs')]
    #[ORM\JoinColumn(name: 'produit_id', referencedColumnName: 'id')]
    private ?ProduitA $produit = null;

    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'factureAs')]
    private ?Clients $client = null;

    #[ORM\ManyToOne(inversedBy: 'factureAs')]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\ManyToOne(inversedBy: 'factureAs')]
    private ?VenteA $vente = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'factureAs')]
    private ?Agence $agence = null;

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

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): static
    {
        $this->quantite = $quantite;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): static
    {
        $this->client = $client;

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

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getVente(): ?VenteA
    {
        return $this->vente;
    }

    public function setVente(?VenteA $vente): static
    {
        $this->vente = $vente;

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

    public function getAgence(): ?agence
    {
        return $this->agence;
    }

    public function setAgence(?agence $agence): static
    {
        $this->agence = $agence;

        return $this;
    }

}
