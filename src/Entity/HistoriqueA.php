<?php

namespace App\Entity;

use App\Repository\HistoriqueARepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueARepository::class)]
class HistoriqueA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'historiqueAs')]
    private ?ProduitA $produitA = null;

    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createtAd = null;

    #[ORM\Column(length: 255)]
    private ?string $heurecameroun = null;

    #[ORM\Column(length: 255)]
    private ?string $heureserver = null;

    #[ORM\ManyToOne(inversedBy: 'historiqueAs')]
    private ?Agence $agence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduitA(): ?ProduitA
    {
        return $this->produitA;
    }

    public function setProduitA(?ProduitA $produitA): static
    {
        $this->produitA = $produitA;

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

    public function getCreatetAd(): ?\DateTimeInterface
    {
        return $this->createtAd;
    }

    public function setCreatetAd(\DateTimeInterface $createtAd): static
    {
        $this->createtAd = $createtAd;

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

    public function setHeureserver(string $heureserver): static
    {
        $this->heureserver = $heureserver;

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
