<?php

namespace App\Entity;

use App\Repository\InventaireCaisseARepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventaireCaisseARepository::class)]
class InventaireCaisseA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'inventaireCaisseAs')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'inventaireCaisseAs')]
    private ?Agence $agence = null;

    #[ORM\Column]
    private ?float $vente = null;

    #[ORM\Column]
    private ?float $caisse = null;

    #[ORM\Column]
    private ?float $ecart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createtAt = null;

    #[ORM\Column(length: 255)]
    private ?string $justificatif = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVente(): ?float
    {
        return $this->vente;
    }

    public function setVente(float $vente): static
    {
        $this->vente = $vente;

        return $this;
    }

    public function getCaisse(): ?float
    {
        return $this->caisse;
    }

    public function setCaisse(float $caisse): static
    {
        $this->caisse = $caisse;

        return $this;
    }

    public function getEcart(): ?float
    {
        return $this->ecart;
    }

    public function setEcart(float $ecart): static
    {
        $this->ecart = $ecart;

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

    public function getJustificatif(): ?string
    {
        return $this->justificatif;
    }

    public function setJustificatif(string $justificatif): static
    {
        $this->justificatif = $justificatif;

        return $this;
    }
}
