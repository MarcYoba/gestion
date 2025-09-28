<?php

namespace App\Entity;

use App\Repository\ProduitARepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitARepository::class)]
class ProduitA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'produitAs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $prixvente = null;

    #[ORM\Column]
    private ?float $prixachat = null;

    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\Column]
    private ?float $gain = null;

    #[ORM\Column]
    private ?float $stockdebut = null;

    #[ORM\Column(length: 100)]
    private ?string $cathegorie = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: AchatA::class, orphanRemoval: true)]
    private Collection $achatAs;

    #[ORM\ManyToOne(inversedBy: 'produitAs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agence $agence = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: VenteA::class)]
    private Collection $venteAs;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: FactureA::class)]
    private Collection $factureAs;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: QuantiteproduitA::class)]
    private Collection $quantiteproduitAs;

    #[ORM\OneToMany(mappedBy: 'produitA', targetEntity: HistoriqueA::class)]
    private Collection $historiqueAs;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $expiration = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Lots::class)]
    private Collection $lots;

    public function __construct()
    {
        $this->achatAs = new ArrayCollection();
        $this->venteAs = new ArrayCollection();
        $this->factureAs = new ArrayCollection();
        $this->quantiteproduitAs = new ArrayCollection();
        $this->historiqueAs = new ArrayCollection();
        $this->lots = new ArrayCollection();
    }

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrixvente(): ?float
    {
        return $this->prixvente;
    }

    public function setPrixvente(float $prixvente): static
    {
        $this->prixvente = $prixvente;

        return $this;
    }

    public function getPrixachat(): ?float
    {
        return $this->prixachat;
    }

    public function setPrixachat(float $prixachat): static
    {
        $this->prixachat = $prixachat;

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

    public function getGain(): ?float
    {
        return $this->gain;
    }

    public function setGain(float $gain): static
    {
        $this->gain = $gain;

        return $this;
    }

    public function getStockdebut(): ?float
    {
        return $this->stockdebut;
    }

    public function setStockdebut(float $stockdebut): static
    {
        $this->stockdebut = $stockdebut;

        return $this;
    }

    public function getCathegorie(): ?string
    {
        return $this->cathegorie;
    }

    public function setCathegorie(string $cathegorie): static
    {
        $this->cathegorie = $cathegorie;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
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

    /**
     * @return Collection<int, AchatA>
     */
    public function getAchatAs(): Collection
    {
        return $this->achatAs;
    }

    public function addAchatA(AchatA $achatA): static
    {
        if (!$this->achatAs->contains($achatA)) {
            $this->achatAs->add($achatA);
            $achatA->setProduit($this);
        }

        return $this;
    }

    public function removeAchatA(AchatA $achatA): static
    {
        if ($this->achatAs->removeElement($achatA)) {
            // set the owning side to null (unless already changed)
            if ($achatA->getProduit() === $this) {
                $achatA->setProduit(null);
            }
        }

        return $this;
    }

    public function getAgence(): ?agence
    {
        return $this->agence;
    }

    public function setAgence(?agence $agence): static
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * @return Collection<int, VenteA>
     */
    public function getVenteAs(): Collection
    {
        return $this->venteAs;
    }

    public function addVenteA(VenteA $venteA): static
    {
        if (!$this->venteAs->contains($venteA)) {
            $this->venteAs->add($venteA);
            $venteA->setProduit($this);
        }

        return $this;
    }

    public function removeVenteA(VenteA $venteA): static
    {
        if ($this->venteAs->removeElement($venteA)) {
            // set the owning side to null (unless already changed)
            if ($venteA->getProduit() === $this) {
                $venteA->setProduit(null);
            }
        }

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
            $factureA->setProduit($this);
        }

        return $this;
    }

    public function removeFactureA(FactureA $factureA): static
    {
        if ($this->factureAs->removeElement($factureA)) {
            // set the owning side to null (unless already changed)
            if ($factureA->getProduit() === $this) {
                $factureA->setProduit(null);
            }
        }

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
            $quantiteproduitA->setProduit($this);
        }

        return $this;
    }

    public function removeQuantiteproduitA(QuantiteproduitA $quantiteproduitA): static
    {
        if ($this->quantiteproduitAs->removeElement($quantiteproduitA)) {
            // set the owning side to null (unless already changed)
            if ($quantiteproduitA->getProduit() === $this) {
                $quantiteproduitA->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HistoriqueA>
     */
    public function getHistoriqueAs(): Collection
    {
        return $this->historiqueAs;
    }

    public function addHistoriqueA(HistoriqueA $historiqueA): static
    {
        if (!$this->historiqueAs->contains($historiqueA)) {
            $this->historiqueAs->add($historiqueA);
            $historiqueA->setProduitA($this);
        }

        return $this;
    }

    public function removeHistoriqueA(HistoriqueA $historiqueA): static
    {
        if ($this->historiqueAs->removeElement($historiqueA)) {
            // set the owning side to null (unless already changed)
            if ($historiqueA->getProduitA() === $this) {
                $historiqueA->setProduitA(null);
            }
        }

        return $this;
    }

    public function getExpiration(): ?string
    {
        return $this->expiration;
    }

    public function setExpiration(?string $expiration): static
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * @return Collection<int, Lots>
     */
    public function getLots(): Collection
    {
        return $this->lots;
    }

    public function addLot(Lots $lot): static
    {
        if (!$this->lots->contains($lot)) {
            $this->lots->add($lot);
            $lot->setProduit($this);
        }

        return $this;
    }

    public function removeLot(Lots $lot): static
    {
        if ($this->lots->removeElement($lot)) {
            // set the owning side to null (unless already changed)
            if ($lot->getProduit() === $this) {
                $lot->setProduit(null);
            }
        }

        return $this;
    }
}
