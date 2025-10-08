<?php

namespace App\Entity;

use App\Repository\ProspectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProspectionRepository::class)]
class Prospection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $localisation = null;

    #[ORM\Column(length: 255)]
    private ?string $speculation = null;

    #[ORM\Column(length: 255)]
    private ?string $sujet = null;

    #[ORM\Column(length: 255)]
    private ?string $souche = null;

    #[ORM\Column(length: 255)]
    private ?string $ravitallement = null;

    #[ORM\Column(length: 255)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createtAt = null;

    #[ORM\ManyToOne(inversedBy: 'prospections')]
    private ?Agence $agence = null;

    #[ORM\ManyToOne(inversedBy: 'prospections')]
    private ?user $user = null;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getSpeculation(): ?string
    {
        return $this->speculation;
    }

    public function setSpeculation(string $speculation): static
    {
        $this->speculation = $speculation;

        return $this;
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

    public function getSouche(): ?string
    {
        return $this->souche;
    }

    public function setSouche(string $souche): static
    {
        $this->souche = $souche;

        return $this;
    }

    public function getRavitallement(): ?string
    {
        return $this->ravitallement;
    }

    public function setRavitallement(string $ravitallement): static
    {
        $this->ravitallement = $ravitallement;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

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

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }
}
