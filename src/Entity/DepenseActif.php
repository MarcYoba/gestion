<?php

namespace App\Entity;

use App\Repository\DepenseActifRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepenseActifRepository::class)]
class DepenseActif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createtAd = null;

    #[ORM\ManyToOne(inversedBy: 'depenseActifs')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'depenseActifs')]
    private ?Agence $agence = null;

    #[ORM\ManyToOne(inversedBy: 'depenseActifs')]
    private ?Actif $actif = null;

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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

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

    public function getActif(): ?Actif
    {
        return $this->actif;
    }

    public function setActif(?Actif $actif): static
    {
        $this->actif = $actif;

        return $this;
    }
}
