<?php

namespace App\Entity;

use App\Repository\VenteARepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VenteARepository::class)]
class VenteA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'venteAs')]
    private ?Clients $client = null;

    #[ORM\ManyToOne(inversedBy: 'venteAs')]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\ManyToOne(inversedBy: 'venteAs')]
    private ?ProduitA $produit = null;

    #[ORM\Column]
    private ?float $cash = null;

    #[ORM\Column]
    private ?float $reduction = null;

    #[ORM\Column]
    private ?float $banque = null;

    #[ORM\Column(length: 100)]
    private ?string $statut = null;

    #[ORM\Column]
    private ?float $credit = null;

    #[ORM\ManyToOne(inversedBy: 'venteAs')]
    private ?Agence $agence = null;

    #[ORM\OneToMany(mappedBy: 'vente', targetEntity: FactureA::class)]
    private Collection $factureAs;

    #[ORM\Column(length: 100)]
    private ?string $heure = null;

    #[ORM\Column]
    private ?float $momo = null;

    #[ORM\OneToMany(mappedBy: 'vente', targetEntity: QuantiteproduitA::class)]
    private Collection $quantiteproduitAs;

    #[ORM\Column(length: 255)]
    private ?string $esperce = null;

    #[ORM\Column]
    private ?float $om = null;

    public function __construct()
    {
        $this->factureAs = new ArrayCollection();
        $this->quantiteproduitAs = new ArrayCollection();
    }

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

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getProduit(): ?ProduitA
    {
        return $this->produit;
    }

    public function setProduit(?ProduitA $produit): static
    {
        $this->produit = $produit;

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

    public function getReduction(): ?float
    {
        return $this->reduction;
    }

    public function setReduction(float $reduction): static
    {
        $this->reduction = $reduction;

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

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

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * @return Collection<int, FactureA>
     */
    public function getFactureAs(): Collection
    {
        return $this->factureAs;
    }

    public function addFactureA(FactureA $factureA): static
    {
        if (!$this->factureAs->contains($factureA)) {
            $this->factureAs->add($factureA);
            $factureA->setVente($this);
        }

        return $this;
    }

    public function removeFactureA(FactureA $factureA): static
    {
        if ($this->factureAs->removeElement($factureA)) {
            // set the owning side to null (unless already changed)
            if ($factureA->getVente() === $this) {
                $factureA->setVente(null);
            }
        }

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getMomo(): ?float
    {
        return $this->momo;
    }

    public function setMomo(float $momo): static
    {
        $this->momo = $momo;

        return $this;
    }

    /**
     * @return Collection<int, QuantiteproduitA>
     */
    public function getQuantiteproduitAs(): Collection
    {
        return $this->quantiteproduitAs;
    }

    public function addQuantiteproduitA(QuantiteproduitA $quantiteproduitA): static
    {
        if (!$this->quantiteproduitAs->contains($quantiteproduitA)) {
            $this->quantiteproduitAs->add($quantiteproduitA);
            $quantiteproduitA->setVente($this);
        }

        return $this;
    }

    public function removeQuantiteproduitA(QuantiteproduitA $quantiteproduitA): static
    {
        if ($this->quantiteproduitAs->removeElement($quantiteproduitA)) {
            // set the owning side to null (unless already changed)
            if ($quantiteproduitA->getVente() === $this) {
                $quantiteproduitA->setVente(null);
            }
        }

        return $this;
    }

    public function getEsperce(): ?string
    {
        return $this->esperce;
    }

    public function setEsperce(string $esperce): static
    {
        $this->esperce = $esperce;

        return $this;
    }

    public function getOm(): ?float
    {
        return $this->om;
    }

    public function setOm(float $om): static
    {
        $this->om = $om;

        return $this;
    }
}
