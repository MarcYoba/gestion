<?php

namespace App\Entity;

use App\Repository\VenteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VenteRepository::class)]
class Vente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'vente')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clients $client = null;

    #[ORM\ManyToOne(inversedBy: 'vente')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $esperce = null;

   



    #[ORM\Column(length: 50)]
    private ?string $aliment = null;

    #[ORM\Column(length: 20)]
    private ?string $heure = null;

    #[ORM\Column(length: 30)]
    private ?string $statusvente = null;

    #[ORM\OneToOne(mappedBy: 'vente', cascade: ['persist', 'remove'])]
    private ?Credit $credit = null;

    #[ORM\Column]
    private ?float $montantcredit = null;

    #[ORM\Column]
    private ?float $montantcash = null;

    #[ORM\Column]
    private ?float $montantbanque = null;

    #[ORM\Column]
    private ?float $montantmomo = null;

    #[ORM\OneToMany(mappedBy: 'vente', targetEntity: Facture::class, orphanRemoval: true)]
    private Collection $facture;

    #[ORM\Column]
    private ?float $reduction = null;

    #[ORM\OneToMany(mappedBy: 'vente', targetEntity: Quantiteproduit::class, orphanRemoval: true)]
    private Collection $quantiteproduits;

    #[ORM\ManyToOne(inversedBy: 'ventes')]
    private ?Agence $agence = null;

    public function __construct()
    {
        $this->facture = new ArrayCollection();
        $this->quantiteproduits = new ArrayCollection();
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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

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

    public function getEsperce(): ?string
    {
        return $this->esperce;
    }

    public function setEsperce(string $esperce): static
    {
        $this->esperce = $esperce;

        return $this;
    }

    


    public function getAliment(): ?string
    {
        return $this->aliment;
    }

    public function setAliment(string $aliment): static
    {
        $this->aliment = $aliment;

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

    public function getStatusvente(): ?string
    {
        return $this->statusvente;
    }

    public function setStatusvente(string $statusvente): static
    {
        $this->statusvente = $statusvente;

        return $this;
    }

    public function getCredit(): ?Credit
    {
        return $this->credit;
    }

    public function setCredit(Credit $credit): static
    {
        // set the owning side of the relation if necessary
        if ($credit->getVente() !== $this) {
            $credit->setVente($this);
        }

        $this->credit = $credit;

        return $this;
    }

    public function getMontantcredit(): ?float
    {
        return $this->montantcredit;
    }

    public function setMontantcredit(float $montantcredit): static
    {
        $this->montantcredit = $montantcredit;

        return $this;
    }

    public function getMontantcash(): ?float
    {
        return $this->montantcash;
    }

    public function setMontantcash(float $montantcash): static
    {
        $this->montantcash = $montantcash;

        return $this;
    }

    public function getMontantbanque(): ?float
    {
        return $this->montantbanque;
    }

    public function setMontantbanque(float $montantbanque): static
    {
        $this->montantbanque = $montantbanque;

        return $this;
    }

    public function getMontantmomo(): ?float
    {
        return $this->montantmomo;
    }

    public function setMontantmomo(float $montantmomo): static
    {
        $this->montantmomo = $montantmomo;

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
            $facture->setVente($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->facture->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getVente() === $this) {
                $facture->setVente(null);
            }
        }

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

    /**
     * @return Collection<int, Quantiteproduit>
     */
    public function getQuantiteproduits(): Collection
    {
        return $this->quantiteproduits;
    }

    public function addQuantiteproduit(Quantiteproduit $quantiteproduit): static
    {
        if (!$this->quantiteproduits->contains($quantiteproduit)) {
            $this->quantiteproduits->add($quantiteproduit);
            $quantiteproduit->setVente($this);
        }

        return $this;
    }

    public function removeQuantiteproduit(Quantiteproduit $quantiteproduit): static
    {
        if ($this->quantiteproduits->removeElement($quantiteproduit)) {
            // set the owning side to null (unless already changed)
            if ($quantiteproduit->getVente() === $this) {
                $quantiteproduit->setVente(null);
            }
        }

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
