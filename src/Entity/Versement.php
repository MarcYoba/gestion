<?php

namespace App\Entity;

use App\Entity\Clients;
use App\Repository\VersementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VersementRepository::class)]
class Versement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column]
    private ?float $Om = null;

    #[ORM\Column]
    private ?float $banque = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAd = null;

    

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'versements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clients $clients = null;

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

    public function getOm(): ?float
    {
        return $this->Om;
    }

    public function setOm(float $Om): static
    {
        $this->Om = $Om;

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

    public function getCreatedAd(): ?\DateTimeInterface
    {
        return $this->createdAd;
    }

    public function setCreatedAd(\DateTimeInterface $createdAd): static
    {
        $this->createdAd = $createdAd;

        return $this;
    }

   

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getClients(): ?Clients
    {
        return $this->clients;
    }

    public function setClients(?Clients $clients): static
    {
        $this->clients = $clients;

        return $this;
    }
}
