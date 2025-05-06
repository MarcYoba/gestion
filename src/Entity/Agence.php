<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgenceRepository::class)]
class Agence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datecreation = null;


    #[ORM\ManyToOne(inversedBy: 'agences')]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $createdBY = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Employer::class)]
    private Collection $employer;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Depenses::class)]
    private Collection $depenses;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Achat::class)]
    private Collection $achats;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Produit::class)]
    private Collection $produits;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: ProduitA::class)]
    private Collection $produitAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Fournisseur::class)]
    private Collection $fournisseurs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: FournisseurA::class)]
    private Collection $fournisseurAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: AchatA::class)]
    private Collection $achatAs;

    public function __construct()
    {
        $this->employer = new ArrayCollection();
        $this->depenses = new ArrayCollection();
        $this->achats = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->produitAs = new ArrayCollection();
        $this->fournisseurs = new ArrayCollection();
        $this->fournisseurAs = new ArrayCollection();
        $this->achatAs = new ArrayCollection();
    }

   

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

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): static
    {
        $this->datecreation = $datecreation;

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

    public function getCreatedBY(): ?int
    {
        return $this->createdBY;
    }

    public function setCreatedBY(int $createdBY): static
    {
        $this->createdBY = $createdBY;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * @return Collection<int, Employer>
     */
    public function getEmployer(): Collection
    {
        return $this->employer;
    }

    public function addEmployer(Employer $employer): static
    {
        if (!$this->employer->contains($employer)) {
            $this->employer->add($employer);
            $employer->setAgence($this);
        }

        return $this;
    }

    public function removeEmployer(Employer $employer): static
    {
        if ($this->employer->removeElement($employer)) {
            // set the owning side to null (unless already changed)
            if ($employer->getAgence() === $this) {
                $employer->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Depenses>
     */
    public function getDepenses(): Collection
    {
        return $this->depenses;
    }

    public function addDepense(Depenses $depense): static
    {
        if (!$this->depenses->contains($depense)) {
            $this->depenses->add($depense);
            $depense->setAgence($this);
        }

        return $this;
    }

    public function removeDepense(Depenses $depense): static
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getAgence() === $this) {
                $depense->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Achat>
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): static
    {
        if (!$this->achats->contains($achat)) {
            $this->achats->add($achat);
            $achat->setAgence($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): static
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getAgence() === $this) {
                $achat->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setAgence($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getAgence() === $this) {
                $produit->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProduitA>
     */
    public function getProduitAs(): Collection
    {
        return $this->produitAs;
    }

    public function addProduitA(ProduitA $produitA): static
    {
        if (!$this->produitAs->contains($produitA)) {
            $this->produitAs->add($produitA);
            $produitA->setAgence($this);
        }

        return $this;
    }

    public function removeProduitA(ProduitA $produitA): static
    {
        if ($this->produitAs->removeElement($produitA)) {
            // set the owning side to null (unless already changed)
            if ($produitA->getAgence() === $this) {
                $produitA->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fournisseur>
     */
    public function getFournisseurs(): Collection
    {
        return $this->fournisseurs;
    }

    public function addFournisseur(Fournisseur $fournisseur): static
    {
        if (!$this->fournisseurs->contains($fournisseur)) {
            $this->fournisseurs->add($fournisseur);
            $fournisseur->setAgence($this);
        }

        return $this;
    }

    public function removeFournisseur(Fournisseur $fournisseur): static
    {
        if ($this->fournisseurs->removeElement($fournisseur)) {
            // set the owning side to null (unless already changed)
            if ($fournisseur->getAgence() === $this) {
                $fournisseur->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FournisseurA>
     */
    public function getFournisseurAs(): Collection
    {
        return $this->fournisseurAs;
    }

    public function addFournisseurA(FournisseurA $fournisseurA): static
    {
        if (!$this->fournisseurAs->contains($fournisseurA)) {
            $this->fournisseurAs->add($fournisseurA);
            $fournisseurA->setAgence($this);
        }

        return $this;
    }

    public function removeFournisseurA(FournisseurA $fournisseurA): static
    {
        if ($this->fournisseurAs->removeElement($fournisseurA)) {
            // set the owning side to null (unless already changed)
            if ($fournisseurA->getAgence() === $this) {
                $fournisseurA->setAgence(null);
            }
        }

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
            $achatA->setAgence($this);
        }

        return $this;
    }

    public function removeAchatA(AchatA $achatA): static
    {
        if ($this->achatAs->removeElement($achatA)) {
            // set the owning side to null (unless already changed)
            if ($achatA->getAgence() === $this) {
                $achatA->setAgence(null);
            }
        }

        return $this;
    }
}
