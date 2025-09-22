<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
class Clients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToOne(inversedBy: 'clients', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'clients', targetEntity: Versement::class)]
    private Collection $versements;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Vente::class, orphanRemoval: true)]
    private Collection $vente;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Credit::class, orphanRemoval: true)]
    private Collection $credit;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Facture::class, orphanRemoval: true)]
    private Collection $facture;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: VersementA::class)]
    private Collection $versementAs;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: VenteA::class)]
    private Collection $venteAs;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: FactureA::class)]
    private Collection $factureAs;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Poussin::class)]
    private Collection $poussins;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Vaccin::class)]
    private Collection $vaccins;

    public function __construct()
    {
        $this->versements = new ArrayCollection();
        $this->vente = new ArrayCollection();
        $this->credit = new ArrayCollection();
        $this->facture = new ArrayCollection();
        $this->versementAs = new ArrayCollection();
        $this->venteAs = new ArrayCollection();
        $this->factureAs = new ArrayCollection();
        $this->poussins = new ArrayCollection();
        $this->vaccins = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Versement>
     */
    public function getVersements(): Collection
    {
        return $this->versements;
    }

    public function addVersement(Versement $versement): static
    {
        if (!$this->versements->contains($versement)) {
            $this->versements->add($versement);
            $versement->setClients($this);
        }

        return $this;
    }

    public function removeVersement(Versement $versement): static
    {
        if ($this->versements->removeElement($versement)) {
            // set the owning side to null (unless already changed)
            if ($versement->getClients() === $this) {
                $versement->setClients(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vente>
     */
    public function getVente(): Collection
    {
        return $this->vente;
    }

    public function addVente(Vente $vente): static
    {
        if (!$this->vente->contains($vente)) {
            $this->vente->add($vente);
            $vente->setClient($this);
        }

        return $this;
    }

    public function removeVente(Vente $vente): static
    {
        if ($this->vente->removeElement($vente)) {
            // set the owning side to null (unless already changed)
            if ($vente->getClient() === $this) {
                $vente->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Credit>
     */
    public function getCredit(): Collection
    {
        return $this->credit;
    }

    public function addCredit(Credit $credit): static
    {
        if (!$this->credit->contains($credit)) {
            $this->credit->add($credit);
            $credit->setClient($this);
        }

        return $this;
    }

    public function removeCredit(Credit $credit): static
    {
        if ($this->credit->removeElement($credit)) {
            // set the owning side to null (unless already changed)
            if ($credit->getClient() === $this) {
                $credit->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFacture(): Collection
    {
        return $this->facture;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->facture->contains($facture)) {
            $this->facture->add($facture);
            $facture->setClient($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->facture->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getClient() === $this) {
                $facture->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, VersementA>
     */
    public function getVersementAs(): Collection
    {
        return $this->versementAs;
    }

    public function addVersementA(VersementA $versementA): static
    {
        if (!$this->versementAs->contains($versementA)) {
            $this->versementAs->add($versementA);
            $versementA->setClient($this);
        }

        return $this;
    }

    public function removeVersementA(VersementA $versementA): static
    {
        if ($this->versementAs->removeElement($versementA)) {
            // set the owning side to null (unless already changed)
            if ($versementA->getClient() === $this) {
                $versementA->setClient(null);
            }
        }

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
            $venteA->setClient($this);
        }

        return $this;
    }

    public function removeVenteA(VenteA $venteA): static
    {
        if ($this->venteAs->removeElement($venteA)) {
            // set the owning side to null (unless already changed)
            if ($venteA->getClient() === $this) {
                $venteA->setClient(null);
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
            $factureA->setClient($this);
        }

        return $this;
    }

    public function removeFactureA(FactureA $factureA): static
    {
        if ($this->factureAs->removeElement($factureA)) {
            // set the owning side to null (unless already changed)
            if ($factureA->getClient() === $this) {
                $factureA->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Poussin>
     */
    public function getPoussins(): Collection
    {
        return $this->poussins;
    }

    public function addPoussin(Poussin $poussin): static
    {
        if (!$this->poussins->contains($poussin)) {
            $this->poussins->add($poussin);
            $poussin->setClient($this);
        }

        return $this;
    }

    public function removePoussin(Poussin $poussin): static
    {
        if ($this->poussins->removeElement($poussin)) {
            // set the owning side to null (unless already changed)
            if ($poussin->getClient() === $this) {
                $poussin->setClient(null);
            }
        }

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

    /**
     * @return Collection<int, Vaccin>
     */
    public function getVaccins(): Collection
    {
        return $this->vaccins;
    }

    public function addVaccin(Vaccin $vaccin): static
    {
        if (!$this->vaccins->contains($vaccin)) {
            $this->vaccins->add($vaccin);
            $vaccin->setClient($this);
        }

        return $this;
    }

    public function removeVaccin(Vaccin $vaccin): static
    {
        if ($this->vaccins->removeElement($vaccin)) {
            // set the owning side to null (unless already changed)
            if ($vaccin->getClient() === $this) {
                $vaccin->setClient(null);
            }
        }

        return $this;
    }

    

    
}
