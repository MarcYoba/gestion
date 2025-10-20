<?php

namespace App\Entity;

use App\Repository\BalanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BalanceRepository::class)]
class Balance
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
    private ?string $intitule = null;

    #[ORM\Column]
    private ?float $SoldeInitialDebit = null;

    #[ORM\Column]
    private ?float $SoldeInitialCredit = null;

    #[ORM\Column]
    private ?float $MouvementDebit = null;

    #[ORM\Column]
    private ?float $MouvementCredit = null;

    #[ORM\Column]
    private ?float $SoldeFinalDebit = null;

    #[ORM\Column]
    private ?float $SoldFinalCredit = null;

    #[ORM\Column]
    private ?float $SoldeGlobal = null;

    #[ORM\ManyToOne(inversedBy: 'balances')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'balances')]
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

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getSoldeInitialDebit(): ?float
    {
        return $this->SoldeInitialDebit;
    }

    public function setSoldeInitialDebit(float $SoldeInitialDebit): static
    {
        $this->SoldeInitialDebit = $SoldeInitialDebit;

        return $this;
    }

    public function getSoldeInitialCredit(): ?float
    {
        return $this->SoldeInitialCredit;
    }

    public function setSoldeInitialCredit(float $SoldeInitialCredit): static
    {
        $this->SoldeInitialCredit = $SoldeInitialCredit;

        return $this;
    }

    public function getMouvementDebit(): ?float
    {
        return $this->MouvementDebit;
    }

    public function setMouvementDebit(float $MouvementDebit): static
    {
        $this->MouvementDebit = $MouvementDebit;

        return $this;
    }

    public function getMouvementCredit(): ?float
    {
        return $this->MouvementCredit;
    }

    public function setMouvementCredit(float $MouvementCredit): static
    {
        $this->MouvementCredit = $MouvementCredit;

        return $this;
    }

    public function getSoldeFinalDebit(): ?float
    {
        return $this->SoldeFinalDebit;
    }

    public function setSoldeFinalDebit(float $SoldeFinalDebit): static
    {
        $this->SoldeFinalDebit = $SoldeFinalDebit;

        return $this;
    }

    public function getSoldFinalCredit(): ?float
    {
        return $this->SoldFinalCredit;
    }

    public function setSoldFinalCredit(float $SoldFinalCredit): static
    {
        $this->SoldFinalCredit = $SoldFinalCredit;

        return $this;
    }

    public function getSoldeGlobal(): ?float
    {
        return $this->SoldeGlobal;
    }

    public function setSoldeGlobal(float $SoldeGlobal): static
    {
        $this->SoldeGlobal = $SoldeGlobal;

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
