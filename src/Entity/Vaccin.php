<?php

namespace App\Entity;

use App\Repository\VaccinRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VaccinRepository::class)]
class Vaccin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sujet = null;

    #[ORM\Column(length: 255)]
    private ?string $age = null;

    #[ORM\Column(length: 255)]
    private ?string $typeSujet = null;

    #[ORM\ManyToOne(inversedBy: 'vaccins')]
    private ?Clients $client = null;

    #[ORM\ManyToOne(inversedBy: 'vaccins')]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createtAD = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateRapel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateVaccin = null;

    #[ORM\Column(length: 255)]
    private ?string $typeVaccin = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column]
    private ?float $montantNet = null;

    #[ORM\Column]
    private ?float $resteMontant = null;

    #[ORM\Column(length: 255)]
    private ?string $lieux = null;

    #[ORM\Column]
    private ?int $rappel = null;

    #[ORM\ManyToOne(inversedBy: 'vaccins')]
    private ?Agence $agence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): static
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getTypeSujet(): ?string
    {
        return $this->typeSujet;
    }

    public function setTypeSujet(string $typeSujet): static
    {
        $this->typeSujet = $typeSujet;

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

    public function getCreatetAD(): ?\DateTimeInterface
    {
        return $this->createtAD;
    }

    public function setCreatetAD(\DateTimeInterface $createtAD): static
    {
        $this->createtAD = $createtAD;

        return $this;
    }

    public function getDateRapel(): ?\DateTimeInterface
    {
        return $this->dateRapel;
    }

    public function setDateRapel(\DateTimeInterface $dateRapel): static
    {
        $this->dateRapel = $dateRapel;

        return $this;
    }

    public function getDateVaccin(): ?\DateTimeInterface
    {
        return $this->dateVaccin;
    }

    public function setDateVaccin(\DateTimeInterface $dateVaccin): static
    {
        $this->dateVaccin = $dateVaccin;

        return $this;
    }

    public function getTypeVaccin(): ?string
    {
        return $this->typeVaccin;
    }

    public function setTypeVaccin(string $typeVaccin): static
    {
        $this->typeVaccin = $typeVaccin;

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

    public function getMontantNet(): ?float
    {
        return $this->montantNet;
    }

    public function setMontantNet(float $montantNet): static
    {
        $this->montantNet = $montantNet;

        return $this;
    }

    public function getResteMontant(): ?float
    {
        return $this->resteMontant;
    }

    public function setResteMontant(float $resteMontant): static
    {
        $this->resteMontant = $resteMontant;

        return $this;
    }

    public function getLieux(): ?string
    {
        return $this->lieux;
    }

    public function setLieux(string $lieux): static
    {
        $this->lieux = $lieux;

        return $this;
    }

    public function getRappel(): ?int
    {
        return $this->rappel;
    }

    public function setRappel(int $rappel): static
    {
        $this->rappel = $rappel;

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
