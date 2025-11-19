<?php

namespace App\Entity;

use App\Repository\SocialesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SocialesRepository::class)]
class Sociales
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroCotisation = null;

    #[ORM\Column(length: 255)]
    private ?string $profession = null;

    #[ORM\Column(length: 255)]
    private ?string $cni = null;

    #[ORM\Column(length: 255)]
    private ?string $du = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $parent = null;

    #[ORM\Column(length: 255)]
    private ?string $identite = null;

    #[ORM\Column(length: 255)]
    private ?string $delivree = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createtAt = null;

    #[ORM\ManyToOne(inversedBy: 'sociales')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'sociales')]
    private ?Agence $agence = null;

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

    public function getNumeroCotisation(): ?string
    {
        return $this->numeroCotisation;
    }

    public function setNumeroCotisation(string $numeroCotisation): static
    {
        $this->numeroCotisation = $numeroCotisation;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getCni(): ?string
    {
        return $this->cni;
    }

    public function setCni(string $cni): static
    {
        $this->cni = $cni;

        return $this;
    }

    public function getDu(): ?string
    {
        return $this->du;
    }

    public function setDu(string $du): static
    {
        $this->du = $du;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function setParent(string $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function getIdentite(): ?string
    {
        return $this->identite;
    }

    public function setIdentite(string $identite): static
    {
        $this->identite = $identite;

        return $this;
    }

    public function getDelivree(): ?string
    {
        return $this->delivree;
    }

    public function setDelivree(string $delivree): static
    {
        $this->delivree = $delivree;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

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
