<?php

namespace App\Entity;

use App\Repository\EmpruntARepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntARepository::class)]
class EmpruntA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(length: 255)]
    private ?string $durre = null;

    #[ORM\Column]
    private ?float $tauxinteretdebiteur = null;

    #[ORM\Column]
    private ?float $tauxannueleffectifglobal = null;

    #[ORM\Column]
    private ?float $couttotal = null;

    #[ORM\Column(length: 255)]
    private ?string $garantie = null;

    #[ORM\Column(length: 255)]
    private ?string $identitepreteur = null;

    #[ORM\Column(length: 255)]
    private ?string $emprunteur = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts')]
    private ?Agence $agence = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createtAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDurre(): ?string
    {
        return $this->durre;
    }

    public function setDurre(string $durre): static
    {
        $this->durre = $durre;

        return $this;
    }

    public function getTauxinteretdebiteur(): ?float
    {
        return $this->tauxinteretdebiteur;
    }

    public function setTauxinteretdebiteur(float $tauxinteretdebiteur): static
    {
        $this->tauxinteretdebiteur = $tauxinteretdebiteur;

        return $this;
    }

    public function getTauxannueleffectifglobal(): ?float
    {
        return $this->tauxannueleffectifglobal;
    }

    public function setTauxannueleffectifglobal(float $tauxannueleffectifglobal): static
    {
        $this->tauxannueleffectifglobal = $tauxannueleffectifglobal;

        return $this;
    }

    public function getCouttotal(): ?float
    {
        return $this->couttotal;
    }

    public function setCouttotal(float $couttotal): static
    {
        $this->couttotal = $couttotal;

        return $this;
    }

    public function getGarantie(): ?string
    {
        return $this->garantie;
    }

    public function setGarantie(string $garantie): static
    {
        $this->garantie = $garantie;

        return $this;
    }

    public function getIdentitepreteur(): ?string
    {
        return $this->identitepreteur;
    }

    public function setIdentitepreteur(string $identitepreteur): static
    {
        $this->identitepreteur = $identitepreteur;

        return $this;
    }

    public function getEmprunteur(): ?string
    {
        return $this->emprunteur;
    }

    public function setEmprunteur(string $emprunteur): static
    {
        $this->emprunteur = $emprunteur;

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
