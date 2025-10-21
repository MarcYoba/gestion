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

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Vente::class)]
    private Collection $ventes;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Facture::class)]
    private Collection $factures;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: VenteA::class)]
    private Collection $venteAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: FactureA::class)]
    private Collection $factureAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: TempAgence::class)]
    private Collection $tempAgences;

    #[ORM\OneToMany(mappedBy: 'Agence', targetEntity: Caisse::class)]
    private Collection $caisses;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: CaisseA::class)]
    private Collection $caisseAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Poussin::class)]
    private Collection $poussins;

    #[ORM\OneToMany(mappedBy: 'agance', targetEntity: Historique::class)]
    private Collection $historiques;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: HistoriqueA::class)]
    private Collection $historiqueAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Actif::class)]
    private Collection $actifs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: ActifA::class)]
    private Collection $actifAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Passif::class)]
    private Collection $passifs;

    #[ORM\OneToMany(mappedBy: 'Agence', targetEntity: PassifA::class)]
    private Collection $passifAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Vaccin::class)]
    private Collection $vaccins;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Consultation::class)]
    private Collection $consultations;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Suivi::class)]
    private Collection $suivis;

    #[ORM\OneToMany(mappedBy: 'agance', targetEntity: Lots::class)]
    private Collection $lots;

    #[ORM\OneToMany(mappedBy: 'agecne', targetEntity: DepenseActif::class)]
    private Collection $depenseActifs;

    #[ORM\OneToMany(mappedBy: 'Agence', targetEntity: DepenseActifA::class)]
    private Collection $depenseActifAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: DepensePassif::class)]
    private Collection $depensePassifs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: DepensePassifA::class)]
    private Collection $depensePassifAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Prospection::class)]
    private Collection $prospections;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: ProspectionA::class)]
    private Collection $prospectionAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Autopsie::class)]
    private Collection $autopsies;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Tresorerie::class)]
    private Collection $tresoreries;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: TresorerieA::class)]
    private Collection $tresorerieAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Balance::class)]
    private Collection $balances;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: BalanceA::class)]
    private Collection $balanceAs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Immobilisation::class)]
    private Collection $immobilisations;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: ImmobilisationA::class)]
    private Collection $immobilisationAs;

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
        $this->ventes = new ArrayCollection();
        $this->factures = new ArrayCollection();
        $this->venteAs = new ArrayCollection();
        $this->factureAs = new ArrayCollection();
        $this->tempAgences = new ArrayCollection();
        $this->caisses = new ArrayCollection();
        $this->caisseAs = new ArrayCollection();
        $this->poussins = new ArrayCollection();
        $this->historiques = new ArrayCollection();
        $this->historiqueAs = new ArrayCollection();
        $this->actifs = new ArrayCollection();
        $this->actifAs = new ArrayCollection();
        $this->passifs = new ArrayCollection();
        $this->passifAs = new ArrayCollection();
        $this->vaccins = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->suivis = new ArrayCollection();
        $this->lots = new ArrayCollection();
        $this->depenseActifs = new ArrayCollection();
        $this->depenseActifAs = new ArrayCollection();
        $this->depensePassifs = new ArrayCollection();
        $this->depensePassifAs = new ArrayCollection();
        $this->prospections = new ArrayCollection();
        $this->prospectionAs = new ArrayCollection();
        $this->autopsies = new ArrayCollection();
        $this->tresoreries = new ArrayCollection();
        $this->tresorerieAs = new ArrayCollection();
        $this->balances = new ArrayCollection();
        $this->balanceAs = new ArrayCollection();
        $this->immobilisations = new ArrayCollection();
        $this->immobilisationAs = new ArrayCollection();
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

    /**
     * @return Collection<int, Vente>
     */
    public function getVentes(): Collection
    {
        return $this->ventes;
    }

    public function addVente(Vente $vente): static
    {
        if (!$this->ventes->contains($vente)) {
            $this->ventes->add($vente);
            $vente->setAgence($this);
        }

        return $this;
    }

    public function removeVente(Vente $vente): static
    {
        if ($this->ventes->removeElement($vente)) {
            // set the owning side to null (unless already changed)
            if ($vente->getAgence() === $this) {
                $vente->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setAgence($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getAgence() === $this) {
                $facture->setAgence(null);
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
            $venteA->setAgence($this);
        }

        return $this;
    }

    public function removeVenteA(VenteA $venteA): static
    {
        if ($this->venteAs->removeElement($venteA)) {
            // set the owning side to null (unless already changed)
            if ($venteA->getAgence() === $this) {
                $venteA->setAgence(null);
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
            $factureA->setAgence($this);
        }

        return $this;
    }

    public function removeFactureA(FactureA $factureA): static
    {
        if ($this->factureAs->removeElement($factureA)) {
            // set the owning side to null (unless already changed)
            if ($factureA->getAgence() === $this) {
                $factureA->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TempAgence>
     */
    public function getTempAgences(): Collection
    {
        return $this->tempAgences;
    }

    public function addTempAgence(TempAgence $tempAgence): static
    {
        if (!$this->tempAgences->contains($tempAgence)) {
            $this->tempAgences->add($tempAgence);
            $tempAgence->setAgence($this);
        }

        return $this;
    }

    public function removeTempAgence(TempAgence $tempAgence): static
    {
        if ($this->tempAgences->removeElement($tempAgence)) {
            // set the owning side to null (unless already changed)
            if ($tempAgence->getAgence() === $this) {
                $tempAgence->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Caisse>
     */
    public function getCaisses(): Collection
    {
        return $this->caisses;
    }

    public function addCaiss(Caisse $caiss): static
    {
        if (!$this->caisses->contains($caiss)) {
            $this->caisses->add($caiss);
            $caiss->setAgence($this);
        }

        return $this;
    }

    public function removeCaiss(Caisse $caiss): static
    {
        if ($this->caisses->removeElement($caiss)) {
            // set the owning side to null (unless already changed)
            if ($caiss->getAgence() === $this) {
                $caiss->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CaisseA>
     */
    public function getCaisseAs(): Collection
    {
        return $this->caisseAs;
    }

    public function addCaisseA(CaisseA $caisseA): static
    {
        if (!$this->caisseAs->contains($caisseA)) {
            $this->caisseAs->add($caisseA);
            $caisseA->setAgence($this);
        }

        return $this;
    }

    public function removeCaisseA(CaisseA $caisseA): static
    {
        if ($this->caisseAs->removeElement($caisseA)) {
            // set the owning side to null (unless already changed)
            if ($caisseA->getAgence() === $this) {
                $caisseA->setAgence(null);
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
            $poussin->setAgence($this);
        }

        return $this;
    }

    public function removePoussin(Poussin $poussin): static
    {
        if ($this->poussins->removeElement($poussin)) {
            // set the owning side to null (unless already changed)
            if ($poussin->getAgence() === $this) {
                $poussin->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Historique>
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(Historique $historique): static
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques->add($historique);
            $historique->setAgance($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): static
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getAgance() === $this) {
                $historique->setAgance(null);
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
            $historiqueA->setAgence($this);
        }

        return $this;
    }

    public function removeHistoriqueA(HistoriqueA $historiqueA): static
    {
        if ($this->historiqueAs->removeElement($historiqueA)) {
            // set the owning side to null (unless already changed)
            if ($historiqueA->getAgence() === $this) {
                $historiqueA->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Actif>
     */
    public function getActifs(): Collection
    {
        return $this->actifs;
    }

    public function addActif(Actif $actif): static
    {
        if (!$this->actifs->contains($actif)) {
            $this->actifs->add($actif);
            $actif->setAgence($this);
        }

        return $this;
    }

    public function removeActif(Actif $actif): static
    {
        if ($this->actifs->removeElement($actif)) {
            // set the owning side to null (unless already changed)
            if ($actif->getAgence() === $this) {
                $actif->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ActifA>
     */
    public function getActifAs(): Collection
    {
        return $this->actifAs;
    }

    public function addActifA(ActifA $actifA): static
    {
        if (!$this->actifAs->contains($actifA)) {
            $this->actifAs->add($actifA);
            $actifA->setAgence($this);
        }

        return $this;
    }

    public function removeActifA(ActifA $actifA): static
    {
        if ($this->actifAs->removeElement($actifA)) {
            // set the owning side to null (unless already changed)
            if ($actifA->getAgence() === $this) {
                $actifA->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Passif>
     */
    public function getPassifs(): Collection
    {
        return $this->passifs;
    }

    public function addPassif(Passif $passif): static
    {
        if (!$this->passifs->contains($passif)) {
            $this->passifs->add($passif);
            $passif->setAgence($this);
        }

        return $this;
    }

    public function removePassif(Passif $passif): static
    {
        if ($this->passifs->removeElement($passif)) {
            // set the owning side to null (unless already changed)
            if ($passif->getAgence() === $this) {
                $passif->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PassifA>
     */
    public function getPassifAs(): Collection
    {
        return $this->passifAs;
    }

    public function addPassifA(PassifA $passifA): static
    {
        if (!$this->passifAs->contains($passifA)) {
            $this->passifAs->add($passifA);
            $passifA->setAgence($this);
        }

        return $this;
    }

    public function removePassifA(PassifA $passifA): static
    {
        if ($this->passifAs->removeElement($passifA)) {
            // set the owning side to null (unless already changed)
            if ($passifA->getAgence() === $this) {
                $passifA->setAgence(null);
            }
        }

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
            $vaccin->setAgence($this);
        }

        return $this;
    }

    public function removeVaccin(Vaccin $vaccin): static
    {
        if ($this->vaccins->removeElement($vaccin)) {
            // set the owning side to null (unless already changed)
            if ($vaccin->getAgence() === $this) {
                $vaccin->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Consultation>
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): static
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations->add($consultation);
            $consultation->setAgence($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getAgence() === $this) {
                $consultation->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Suivi>
     */
    public function getSuivis(): Collection
    {
        return $this->suivis;
    }

    public function addSuivi(Suivi $suivi): static
    {
        if (!$this->suivis->contains($suivi)) {
            $this->suivis->add($suivi);
            $suivi->setAgence($this);
        }

        return $this;
    }

    public function removeSuivi(Suivi $suivi): static
    {
        if ($this->suivis->removeElement($suivi)) {
            // set the owning side to null (unless already changed)
            if ($suivi->getAgence() === $this) {
                $suivi->setAgence(null);
            }
        }

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
            $lot->setAgance($this);
        }

        return $this;
    }

    public function removeLot(Lots $lot): static
    {
        if ($this->lots->removeElement($lot)) {
            // set the owning side to null (unless already changed)
            if ($lot->getAgance() === $this) {
                $lot->setAgance(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DepenseActif>
     */
    public function getDepenseActifs(): Collection
    {
        return $this->depenseActifs;
    }

    public function addDepenseActif(DepenseActif $depenseActif): static
    {
        if (!$this->depenseActifs->contains($depenseActif)) {
            $this->depenseActifs->add($depenseActif);
            $depenseActif->setAgecne($this);
        }

        return $this;
    }

    public function removeDepenseActif(DepenseActif $depenseActif): static
    {
        if ($this->depenseActifs->removeElement($depenseActif)) {
            // set the owning side to null (unless already changed)
            if ($depenseActif->getAgecne() === $this) {
                $depenseActif->setAgecne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DepenseActifA>
     */
    public function getDepenseActifAs(): Collection
    {
        return $this->depenseActifAs;
    }

    public function addDepenseActifA(DepenseActifA $depenseActifA): static
    {
        if (!$this->depenseActifAs->contains($depenseActifA)) {
            $this->depenseActifAs->add($depenseActifA);
            $depenseActifA->setAgence($this);
        }

        return $this;
    }

    public function removeDepenseActifA(DepenseActifA $depenseActifA): static
    {
        if ($this->depenseActifAs->removeElement($depenseActifA)) {
            // set the owning side to null (unless already changed)
            if ($depenseActifA->getAgence() === $this) {
                $depenseActifA->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DepensePassif>
     */
    public function getDepensePassifs(): Collection
    {
        return $this->depensePassifs;
    }

    public function addDepensePassif(DepensePassif $depensePassif): static
    {
        if (!$this->depensePassifs->contains($depensePassif)) {
            $this->depensePassifs->add($depensePassif);
            $depensePassif->setAgence($this);
        }

        return $this;
    }

    public function removeDepensePassif(DepensePassif $depensePassif): static
    {
        if ($this->depensePassifs->removeElement($depensePassif)) {
            // set the owning side to null (unless already changed)
            if ($depensePassif->getAgence() === $this) {
                $depensePassif->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DepensePassifA>
     */
    public function getDepensePassifAs(): Collection
    {
        return $this->depensePassifAs;
    }

    public function addDepensePassifA(DepensePassifA $depensePassifA): static
    {
        if (!$this->depensePassifAs->contains($depensePassifA)) {
            $this->depensePassifAs->add($depensePassifA);
            $depensePassifA->setAgence($this);
        }

        return $this;
    }

    public function removeDepensePassifA(DepensePassifA $depensePassifA): static
    {
        if ($this->depensePassifAs->removeElement($depensePassifA)) {
            // set the owning side to null (unless already changed)
            if ($depensePassifA->getAgence() === $this) {
                $depensePassifA->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Prospection>
     */
    public function getProspections(): Collection
    {
        return $this->prospections;
    }

    public function addProspection(Prospection $prospection): static
    {
        if (!$this->prospections->contains($prospection)) {
            $this->prospections->add($prospection);
            $prospection->setAgence($this);
        }

        return $this;
    }

    public function removeProspection(Prospection $prospection): static
    {
        if ($this->prospections->removeElement($prospection)) {
            // set the owning side to null (unless already changed)
            if ($prospection->getAgence() === $this) {
                $prospection->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProspectionA>
     */
    public function getProspectionAs(): Collection
    {
        return $this->prospectionAs;
    }

    public function addProspectionA(ProspectionA $prospectionA): static
    {
        if (!$this->prospectionAs->contains($prospectionA)) {
            $this->prospectionAs->add($prospectionA);
            $prospectionA->setAgence($this);
        }

        return $this;
    }

    public function removeProspectionA(ProspectionA $prospectionA): static
    {
        if ($this->prospectionAs->removeElement($prospectionA)) {
            // set the owning side to null (unless already changed)
            if ($prospectionA->getAgence() === $this) {
                $prospectionA->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Autopsie>
     */
    public function getAutopsies(): Collection
    {
        return $this->autopsies;
    }

    public function addAutopsy(Autopsie $autopsy): static
    {
        if (!$this->autopsies->contains($autopsy)) {
            $this->autopsies->add($autopsy);
            $autopsy->setAgence($this);
        }

        return $this;
    }

    public function removeAutopsy(Autopsie $autopsy): static
    {
        if ($this->autopsies->removeElement($autopsy)) {
            // set the owning side to null (unless already changed)
            if ($autopsy->getAgence() === $this) {
                $autopsy->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tresorerie>
     */
    public function getTresoreries(): Collection
    {
        return $this->tresoreries;
    }

    public function addTresorery(Tresorerie $tresorery): static
    {
        if (!$this->tresoreries->contains($tresorery)) {
            $this->tresoreries->add($tresorery);
            $tresorery->setAgence($this);
        }

        return $this;
    }

    public function removeTresorery(Tresorerie $tresorery): static
    {
        if ($this->tresoreries->removeElement($tresorery)) {
            // set the owning side to null (unless already changed)
            if ($tresorery->getAgence() === $this) {
                $tresorery->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TresorerieA>
     */
    public function getTresorerieAs(): Collection
    {
        return $this->tresorerieAs;
    }

    public function addTresorerieA(TresorerieA $tresorerieA): static
    {
        if (!$this->tresorerieAs->contains($tresorerieA)) {
            $this->tresorerieAs->add($tresorerieA);
            $tresorerieA->setAgence($this);
        }

        return $this;
    }

    public function removeTresorerieA(TresorerieA $tresorerieA): static
    {
        if ($this->tresorerieAs->removeElement($tresorerieA)) {
            // set the owning side to null (unless already changed)
            if ($tresorerieA->getAgence() === $this) {
                $tresorerieA->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Balance>
     */
    public function getBalances(): Collection
    {
        return $this->balances;
    }

    public function addBalance(Balance $balance): static
    {
        if (!$this->balances->contains($balance)) {
            $this->balances->add($balance);
            $balance->setAgence($this);
        }

        return $this;
    }

    public function removeBalance(Balance $balance): static
    {
        if ($this->balances->removeElement($balance)) {
            // set the owning side to null (unless already changed)
            if ($balance->getAgence() === $this) {
                $balance->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BalanceA>
     */
    public function getBalanceAs(): Collection
    {
        return $this->balanceAs;
    }

    public function addBalanceA(BalanceA $balanceA): static
    {
        if (!$this->balanceAs->contains($balanceA)) {
            $this->balanceAs->add($balanceA);
            $balanceA->setAgence($this);
        }

        return $this;
    }

    public function removeBalanceA(BalanceA $balanceA): static
    {
        if ($this->balanceAs->removeElement($balanceA)) {
            // set the owning side to null (unless already changed)
            if ($balanceA->getAgence() === $this) {
                $balanceA->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Immobilisation>
     */
    public function getImmobilisations(): Collection
    {
        return $this->immobilisations;
    }

    public function addImmobilisation(Immobilisation $immobilisation): static
    {
        if (!$this->immobilisations->contains($immobilisation)) {
            $this->immobilisations->add($immobilisation);
            $immobilisation->setAgence($this);
        }

        return $this;
    }

    public function removeImmobilisation(Immobilisation $immobilisation): static
    {
        if ($this->immobilisations->removeElement($immobilisation)) {
            // set the owning side to null (unless already changed)
            if ($immobilisation->getAgence() === $this) {
                $immobilisation->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ImmobilisationA>
     */
    public function getImmobilisationAs(): Collection
    {
        return $this->immobilisationAs;
    }

    public function addImmobilisationA(ImmobilisationA $immobilisationA): static
    {
        if (!$this->immobilisationAs->contains($immobilisationA)) {
            $this->immobilisationAs->add($immobilisationA);
            $immobilisationA->setAgence($this);
        }

        return $this;
    }

    public function removeImmobilisationA(ImmobilisationA $immobilisationA): static
    {
        if ($this->immobilisationAs->removeElement($immobilisationA)) {
            // set the owning side to null (unless already changed)
            if ($immobilisationA->getAgence() === $this) {
                $immobilisationA->setAgence(null);
            }
        }

        return $this;
    }
}
