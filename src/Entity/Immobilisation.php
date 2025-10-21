<?php

namespace App\Entity;

use App\Repository\ImmobilisationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImmobilisationRepository::class)]
class Immobilisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Classe = null;

    #[ORM\Column]
    private ?int $Compte = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateAcquisition = null;

    #[ORM\Column]
    private ?float $PrixAcquisition = null;

    #[ORM\Column]
    private ?float $CumulN1 = null;

    #[ORM\Column]
    private ?float $DotationN = null;

    #[ORM\Column]
    private ?float $CessionsSorties = null;

    #[ORM\Column]
    private ?float $CumulN = null;

    #[ORM\Column]
    private ?float $ValeurNetN = null;

    #[ORM\ManyToOne(inversedBy: 'immobilisations')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'immobilisations')]
    private ?Agence $agence = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createtAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClasse(): ?int
    {
        return $this->Classe;
    }

    public function setClasse(int $Classe): static
    {
        $this->Classe = $Classe;

        return $this;
    }

    public function getCompte(): ?int
    {
        return $this->Compte;
    }

    public function setCompte(int $Compte): static
    {
        $this->Compte = $Compte;

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

    public function getDateAcquisition(): ?\DateTimeInterface
    {
        return $this->DateAcquisition;
    }

    public function setDateAcquisition(\DateTimeInterface $DateAcquisition): static
    {
        $this->DateAcquisition = $DateAcquisition;

        return $this;
    }

    public function getPrixAcquisition(): ?float
    {
        return $this->PrixAcquisition;
    }

    public function setPrixAcquisition(float $PrixAcquisition): static
    {
        $this->PrixAcquisition = $PrixAcquisition;

        return $this;
    }

    public function getCumulN1(): ?float
    {
        return $this->CumulN1;
    }

    public function setCumulN1(float $CumulN1): static
    {
        $this->CumulN1 = $CumulN1;

        return $this;
    }

    public function getDotationN(): ?float
    {
        return $this->DotationN;
    }

    public function setDotationN(float $DotationN): static
    {
        $this->DotationN = $DotationN;

        return $this;
    }

    public function getCessionsSorties(): ?float
    {
        return $this->CessionsSorties;
    }

    public function setCessionsSorties(float $CessionsSorties): static
    {
        $this->CessionsSorties = $CessionsSorties;

        return $this;
    }

    public function getCumulN(): ?float
    {
        return $this->CumulN;
    }

    public function setCumulN(float $CumulN): static
    {
        $this->CumulN = $CumulN;

        return $this;
    }

    public function getValeurNetN(): ?float
    {
        return $this->ValeurNetN;
    }

    public function setValeurNetN(float $ValeurNetN): static
    {
        $this->ValeurNetN = $ValeurNetN;

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

    public function getCreatetAt(): ?\DateTimeInterface
    {
        return $this->createtAt;
    }

    public function setCreatetAt(\DateTimeInterface $createtAt): static
    {
        $this->createtAt = $createtAt;

        return $this;
    }
}
