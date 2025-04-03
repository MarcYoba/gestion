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

    public function __construct()
    {
        $this->versements = new ArrayCollection();
        $this->vente = new ArrayCollection();
        $this->credit = new ArrayCollection();
        $this->facture = new ArrayCollection();
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

    

    
}
