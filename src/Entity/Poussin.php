<?php

namespace App\Entity;

use App\Repository\PoussinRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PoussinRepository::class)]
class Poussin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'poussins')]
    private ?Clients $client = null;

    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(length: 255)]
    private ?string $souche = null;

    #[ORM\Column]
    private ?float $mobilepay = null;

    #[ORM\Column]
    private ?float $credit = null;

    #[ORM\Column]
    private ?float $cash = null;

    #[ORM\Column]
    private ?float $reste = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datecommande = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datelivaison = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $daterapelle = null;

    #[ORM\ManyToOne(inversedBy: 'poussins')]
    private ?Agence $agence = null;

    #[ORM\Column]
    private ?float $banque = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

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

    public function getSouche(): ?string
    {
        return $this->souche;
    }

    public function setSouche(string $souche): static
    {
        $this->souche = $souche;

        return $this;
    }

    public function getMobilepay(): ?float
    {
        return $this->mobilepay;
    }

    public function setMobilepay(float $mobilepay): static
    {
        $this->mobilepay = $mobilepay;

        return $this;
    }

    public function getCredit(): ?float
    {
        return $this->credit;
    }

    public function setCredit(float $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

    public function getCash(): ?float
    {
        return $this->cash;
    }

    public function setCash(float $cash): static
    {
        $this->cash = $cash;

        return $this;
    }

    public function getReste(): ?float
    {
        return $this->reste;
    }

    public function setReste(float $reste): static
    {
        $this->reste = $reste;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDatecommande(): ?\DateTimeInterface
    {
        return $this->datecommande;
    }

    public function setDatecommande(\DateTimeInterface $datecommande): static
    {
        $this->datecommande = $datecommande;

        return $this;
    }

    public function getDatelivaison(): ?\DateTimeInterface
    {
        return $this->datelivaison;
    }

    public function setDatelivaison(\DateTimeInterface $datelivaison): static
    {
        $this->datelivaison = $datelivaison;

        return $this;
    }

    public function getDaterapelle(): ?\DateTimeInterface
    {
        return $this->daterapelle;
    }

    public function setDaterapelle(\DateTimeInterface $daterapelle): static
    {
        $this->daterapelle = $daterapelle;

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

    public function getBanque(): ?float
    {
        return $this->banque;
    }

    public function setBanque(float $banque): static
    {
        $this->banque = $banque;

        return $this;
    }
}
