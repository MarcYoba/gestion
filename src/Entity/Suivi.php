<?php

namespace App\Entity;

use App\Repository\SuiviRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SuiviRepository::class)]
class Suivi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'suivis')]
    private ?Clients $client = null;

    #[ORM\ManyToOne(inversedBy: 'suivis')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'suivis')]
    private ?Agence $agence = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $jour = null;

    #[ORM\Column(length: 255)]
    private ?string $observation = null;

    #[ORM\Column(length: 255)]
    private ?string $conduite = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createtAd = null;

    #[ORM\Column(length: 255)]
    private ?string $docteur = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;

        return $this;
    }

    public function getJour(): ?\DateTimeInterface
    {
        return $this->jour;
    }

    public function setJour(\DateTimeInterface $jour): static
    {
        $this->jour = $jour;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(string $observation): static
    {
        $this->observation = $observation;

        return $this;
    }

    public function getConduite(): ?string
    {
        return $this->conduite;
    }

    public function setConduite(string $conduite): static
    {
        $this->conduite = $conduite;

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

    public function getCreatetAd(): ?\DateTimeInterface
    {
        return $this->createtAd;
    }

    public function setCreatetAd(\DateTimeInterface $createtAd): static
    {
        $this->createtAd = $createtAd;

        return $this;
    }

    public function getDocteur(): ?string
    {
        return $this->docteur;
    }

    public function setDocteur(string $docteur): static
    {
        $this->docteur = $docteur;

        return $this;
    }
}
