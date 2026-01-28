<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $username = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $speculation = null;

    #[ORM\Column(length: 255)]
    private ?string $localisation = null;

    #[ORM\OneToOne(targetEntity:Clients::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Clients $clients = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Agence::class)]
    private Collection $agences;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Employer $Employer = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Produit::class)]
    private Collection $produits;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Fournisseur::class)]
    private Collection $fournisseurs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Achat::class)]
    private Collection $achat;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Vente::class, orphanRemoval: true)]
    private Collection $vente;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Facture::class, orphanRemoval: true)]
    private Collection $facture;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Quantiteproduit::class, orphanRemoval: true)]
    private Collection $quantiteproduits;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DepenseA::class)]
    private Collection $depenseAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: VersementA::class, orphanRemoval: true)]
    private Collection $versementAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Versement::class, orphanRemoval: true)]
    private Collection $versements;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ProduitA::class, orphanRemoval: true)]
    private Collection $produitAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AchatA::class, orphanRemoval: true)]
    private Collection $achatAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Depenses::class)]
    private Collection $depenses;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FournisseurA::class)]
    private Collection $fournisseurAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: VenteA::class)]
    private Collection $venteAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FactureA::class)]
    private Collection $factureAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: QuantiteproduitA::class)]
    private Collection $quantiteproduitAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TempAgence::class)]
    private Collection $tempAgences;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastLogin = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Caisse::class)]
    private Collection $caisses;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CaisseA::class)]
    private Collection $caisseAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Actif::class)]
    private Collection $actifs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ActifA::class)]
    private Collection $actifAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Passif::class)]
    private Collection $passifs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: PassifA::class)]
    private Collection $passifAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Vaccin::class)]
    private Collection $vaccins;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Consultation::class)]
    private Collection $consultations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Suivi::class)]
    private Collection $suivis;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DepenseActif::class)]
    private Collection $depenseActifs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DepenseActifA::class)]
    private Collection $depenseActifAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DepensePassif::class)]
    private Collection $depensePassifs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DepensePassifA::class)]
    private Collection $depensePassifAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Prospection::class)]
    private Collection $prospections;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ProspectionA::class)]
    private Collection $prospectionAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Autopsie::class)]
    private Collection $autopsies;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Tresorerie::class)]
    private Collection $tresoreries;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TresorerieA::class)]
    private Collection $tresorerieAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Balance::class)]
    private Collection $balances;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: BalanceA::class)]
    private Collection $balanceAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Immobilisation::class)]
    private Collection $immobilisations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ImmobilisationA::class)]
    private Collection $immobilisationAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Salaire::class)]
    private Collection $salaires;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SalaireA::class)]
    private Collection $salaireAs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Sociales::class)]
    private Collection $sociales;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Emprunt::class)]
    private Collection $emprunts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Remboursement::class)]
    private Collection $remboursements;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Retrait::class)]
    private Collection $retraits;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Brouillon::class)]
    private Collection $brouillons;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Magasin::class)]
    private Collection $magasins;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Transfert::class)]
    private Collection $transferts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: InventaireA::class)]
    private Collection $inventaireAs;

    

    public function __construct() {
        $this->clients = new Clients(); // Crée un Client automatiquement
        $this->clients->setUser($this); 
        $this->clients->setCreatedAt(new \DateTimeImmutable());// Lie le Client au User
        $this->agences = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->fournisseurs = new ArrayCollection();
        $this->achat = new ArrayCollection();
        $this->vente = new ArrayCollection();
        $this->facture = new ArrayCollection();
        $this->quantiteproduits = new ArrayCollection();
        $this->depenseAs = new ArrayCollection();
        $this->versementAs = new ArrayCollection();
        $this->versements = new ArrayCollection();
        $this->produitAs = new ArrayCollection();
        $this->achatAs = new ArrayCollection();
        $this->depenses = new ArrayCollection();
        $this->fournisseurAs = new ArrayCollection();
        $this->venteAs = new ArrayCollection();
        $this->factureAs = new ArrayCollection();
        $this->quantiteproduitAs = new ArrayCollection();
        $this->tempAgences = new ArrayCollection();
        $this->caisses = new ArrayCollection();
        $this->caisseAs = new ArrayCollection();
        $this->actifs = new ArrayCollection();
        $this->actifAs = new ArrayCollection();
        $this->passifs = new ArrayCollection();
        $this->passifAs = new ArrayCollection();
        $this->vaccins = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->suivis = new ArrayCollection();
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
        $this->salaires = new ArrayCollection();
        $this->salaireAs = new ArrayCollection();
        $this->sociales = new ArrayCollection();
        $this->emprunts = new ArrayCollection();
        $this->remboursements = new ArrayCollection();
        $this->retraits = new ArrayCollection();
        $this->brouillons = new ArrayCollection();
        $this->magasins = new ArrayCollection();
        $this->transferts = new ArrayCollection();
        $this->inventaireAs = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getSpeculation(): ?string
    {
        return $this->speculation;
    }

    public function setSpeculation(?string $speculation): static
    {
        $this->speculation = $speculation;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getClients(): ?Clients
    {
        return $this->clients;
    }

    public function setClients(Clients $clients): static
    {
        // set the owning side of the relation if necessary
        if ($clients->getUser() !== $this) {
            $clients->setUser($this);
        }

        $this->clients = $clients;

        return $this;
    }

    /**
     * @return Collection<int, Agence>
     */
    public function getAgences(): Collection
    {
        return $this->agences;
    }

    public function addAgence(Agence $agence): static
    {
        if (!$this->agences->contains($agence)) {
            $this->agences->add($agence);
            $agence->setUser($this);
        }

        return $this;
    }

    public function removeAgence(Agence $agence): static
    {
        if ($this->agences->removeElement($agence)) {
            // set the owning side to null (unless already changed)
            if ($agence->getUser() === $this) {
                $agence->setUser(null);
            }
        }

        return $this;
    }

    public function getEmployer(): ?Employer
    {
        return $this->Employer;
    }

    public function setEmployer(Employer $Employer): static
    {
        // set the owning side of the relation if necessary
        if ($Employer->getUser() !== $this) {
            $Employer->setUser($this);
        }

        $this->Employer = $Employer;

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
            $produit->setUser($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUser() === $this) {
                $produit->setUser(null);
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
            $fournisseur->setUser($this);
        }

        return $this;
    }

    public function removeFournisseur(Fournisseur $fournisseur): static
    {
        if ($this->fournisseurs->removeElement($fournisseur)) {
            // set the owning side to null (unless already changed)
            if ($fournisseur->getUser() === $this) {
                $fournisseur->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Achat>
     */
    public function getAchat(): Collection
    {
        return $this->achat;
    }

    public function addAchat(Achat $achat): static
    {
        if (!$this->achat->contains($achat)) {
            $this->achat->add($achat);
            $achat->setUser($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): static
    {
        if ($this->achat->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getUser() === $this) {
                $achat->setUser(null);
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
            $vente->setUser($this);
        }

        return $this;
    }

    public function removeVente(Vente $vente): static
    {
        if ($this->vente->removeElement($vente)) {
            // set the owning side to null (unless already changed)
            if ($vente->getUser() === $this) {
                $vente->setUser(null);
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
            $facture->setUser($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->facture->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getUser() === $this) {
                $facture->setUser(null);
            }
        }

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
            $quantiteproduit->setUser($this);
        }

        return $this;
    }

    public function removeQuantiteproduit(Quantiteproduit $quantiteproduit): static
    {
        if ($this->quantiteproduits->removeElement($quantiteproduit)) {
            // set the owning side to null (unless already changed)
            if ($quantiteproduit->getUser() === $this) {
                $quantiteproduit->setUser(null);
            }
        }

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

    /**
     * @return Collection<int, DepenseA>
     */
    public function getDepenseAs(): Collection
    {
        return $this->depenseAs;
    }

    public function addDepenseA(DepenseA $depenseA): static
    {
        if (!$this->depenseAs->contains($depenseA)) {
            $this->depenseAs->add($depenseA);
            $depenseA->setUser($this);
        }

        return $this;
    }

    public function removeDepenseA(DepenseA $depenseA): static
    {
        if ($this->depenseAs->removeElement($depenseA)) {
            // set the owning side to null (unless already changed)
            if ($depenseA->getUser() === $this) {
                $depenseA->setUser(null);
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
            $versementA->setUser($this);
        }

        return $this;
    }

    public function removeVersementA(VersementA $versementA): static
    {
        if ($this->versementAs->removeElement($versementA)) {
            // set the owning side to null (unless already changed)
            if ($versementA->getUser() === $this) {
                $versementA->setUser(null);
            }
        }

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
            $versement->setUser($this);
        }

        return $this;
    }

    public function removeVersement(Versement $versement): static
    {
        if ($this->versements->removeElement($versement)) {
            // set the owning side to null (unless already changed)
            if ($versement->getUser() === $this) {
                $versement->setUser(null);
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
            $produitA->setUser($this);
        }

        return $this;
    }

    public function removeProduitA(ProduitA $produitA): static
    {
        if ($this->produitAs->removeElement($produitA)) {
            // set the owning side to null (unless already changed)
            if ($produitA->getUser() === $this) {
                $produitA->setUser(null);
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
            $achatA->setUser($this);
        }

        return $this;
    }

    public function removeAchatA(AchatA $achatA): static
    {
        if ($this->achatAs->removeElement($achatA)) {
            // set the owning side to null (unless already changed)
            if ($achatA->getUser() === $this) {
                $achatA->setUser(null);
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
            $depense->setUser($this);
        }

        return $this;
    }

    public function removeDepense(Depenses $depense): static
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getUser() === $this) {
                $depense->setUser(null);
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
            $fournisseurA->setUser($this);
        }

        return $this;
    }

    public function removeFournisseurA(FournisseurA $fournisseurA): static
    {
        if ($this->fournisseurAs->removeElement($fournisseurA)) {
            // set the owning side to null (unless already changed)
            if ($fournisseurA->getUser() === $this) {
                $fournisseurA->setUser(null);
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
            $venteA->setUser($this);
        }

        return $this;
    }

    public function removeVenteA(VenteA $venteA): static
    {
        if ($this->venteAs->removeElement($venteA)) {
            // set the owning side to null (unless already changed)
            if ($venteA->getUser() === $this) {
                $venteA->setUser(null);
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
            $factureA->setUser($this);
        }

        return $this;
    }

    public function removeFactureA(FactureA $factureA): static
    {
        if ($this->factureAs->removeElement($factureA)) {
            // set the owning side to null (unless already changed)
            if ($factureA->getUser() === $this) {
                $factureA->setUser(null);
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
            $quantiteproduitA->setUser($this);
        }

        return $this;
    }

    public function removeQuantiteproduitA(QuantiteproduitA $quantiteproduitA): static
    {
        if ($this->quantiteproduitAs->removeElement($quantiteproduitA)) {
            // set the owning side to null (unless already changed)
            if ($quantiteproduitA->getUser() === $this) {
                $quantiteproduitA->setUser(null);
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
            $tempAgence->setUser($this);
        }

        return $this;
    }

    public function removeTempAgence(TempAgence $tempAgence): static
    {
        if ($this->tempAgences->removeElement($tempAgence)) {
            // set the owning side to null (unless already changed)
            if ($tempAgence->getUser() === $this) {
                $tempAgence->setUser(null);
            }
        }

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

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
            $caiss->setUser($this);
        }

        return $this;
    }

    public function removeCaiss(Caisse $caiss): static
    {
        if ($this->caisses->removeElement($caiss)) {
            // set the owning side to null (unless already changed)
            if ($caiss->getUser() === $this) {
                $caiss->setUser(null);
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
            $caisseA->setUser($this);
        }

        return $this;
    }

    public function removeCaisseA(CaisseA $caisseA): static
    {
        if ($this->caisseAs->removeElement($caisseA)) {
            // set the owning side to null (unless already changed)
            if ($caisseA->getUser() === $this) {
                $caisseA->setUser(null);
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
            $actif->setUser($this);
        }

        return $this;
    }

    public function removeActif(Actif $actif): static
    {
        if ($this->actifs->removeElement($actif)) {
            // set the owning side to null (unless already changed)
            if ($actif->getUser() === $this) {
                $actif->setUser(null);
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
            $actifA->setUser($this);
        }

        return $this;
    }

    public function removeActifA(ActifA $actifA): static
    {
        if ($this->actifAs->removeElement($actifA)) {
            // set the owning side to null (unless already changed)
            if ($actifA->getUser() === $this) {
                $actifA->setUser(null);
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
            $passif->setUser($this);
        }

        return $this;
    }

    public function removePassif(Passif $passif): static
    {
        if ($this->passifs->removeElement($passif)) {
            // set the owning side to null (unless already changed)
            if ($passif->getUser() === $this) {
                $passif->setUser(null);
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
            $passifA->setUser($this);
        }

        return $this;
    }

    public function removePassifA(PassifA $passifA): static
    {
        if ($this->passifAs->removeElement($passifA)) {
            // set the owning side to null (unless already changed)
            if ($passifA->getUser() === $this) {
                $passifA->setUser(null);
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
            $vaccin->setUser($this);
        }

        return $this;
    }

    public function removeVaccin(Vaccin $vaccin): static
    {
        if ($this->vaccins->removeElement($vaccin)) {
            // set the owning side to null (unless already changed)
            if ($vaccin->getUser() === $this) {
                $vaccin->setUser(null);
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
            $consultation->setUser($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getUser() === $this) {
                $consultation->setUser(null);
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
            $suivi->setUser($this);
        }

        return $this;
    }

    public function removeSuivi(Suivi $suivi): static
    {
        if ($this->suivis->removeElement($suivi)) {
            // set the owning side to null (unless already changed)
            if ($suivi->getUser() === $this) {
                $suivi->setUser(null);
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
            $depenseActif->setUser($this);
        }

        return $this;
    }

    public function removeDepenseActif(DepenseActif $depenseActif): static
    {
        if ($this->depenseActifs->removeElement($depenseActif)) {
            // set the owning side to null (unless already changed)
            if ($depenseActif->getUser() === $this) {
                $depenseActif->setUser(null);
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
            $depenseActifA->setUser($this);
        }

        return $this;
    }

    public function removeDepenseActifA(DepenseActifA $depenseActifA): static
    {
        if ($this->depenseActifAs->removeElement($depenseActifA)) {
            // set the owning side to null (unless already changed)
            if ($depenseActifA->getUser() === $this) {
                $depenseActifA->setUser(null);
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
            $depensePassif->setUser($this);
        }

        return $this;
    }

    public function removeDepensePassif(DepensePassif $depensePassif): static
    {
        if ($this->depensePassifs->removeElement($depensePassif)) {
            // set the owning side to null (unless already changed)
            if ($depensePassif->getUser() === $this) {
                $depensePassif->setUser(null);
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
            $depensePassifA->setUser($this);
        }

        return $this;
    }

    public function removeDepensePassifA(DepensePassifA $depensePassifA): static
    {
        if ($this->depensePassifAs->removeElement($depensePassifA)) {
            // set the owning side to null (unless already changed)
            if ($depensePassifA->getUser() === $this) {
                $depensePassifA->setUser(null);
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
            $prospection->setUser($this);
        }

        return $this;
    }

    public function removeProspection(Prospection $prospection): static
    {
        if ($this->prospections->removeElement($prospection)) {
            // set the owning side to null (unless already changed)
            if ($prospection->getUser() === $this) {
                $prospection->setUser(null);
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
            $prospectionA->setUser($this);
        }

        return $this;
    }

    public function removeProspectionA(ProspectionA $prospectionA): static
    {
        if ($this->prospectionAs->removeElement($prospectionA)) {
            // set the owning side to null (unless already changed)
            if ($prospectionA->getUser() === $this) {
                $prospectionA->setUser(null);
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
            $autopsy->setUser($this);
        }

        return $this;
    }

    public function removeAutopsy(Autopsie $autopsy): static
    {
        if ($this->autopsies->removeElement($autopsy)) {
            // set the owning side to null (unless already changed)
            if ($autopsy->getUser() === $this) {
                $autopsy->setUser(null);
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
            $tresorery->setUser($this);
        }

        return $this;
    }

    public function removeTresorery(Tresorerie $tresorery): static
    {
        if ($this->tresoreries->removeElement($tresorery)) {
            // set the owning side to null (unless already changed)
            if ($tresorery->getUser() === $this) {
                $tresorery->setUser(null);
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
            $tresorerieA->setUser($this);
        }

        return $this;
    }

    public function removeTresorerieA(TresorerieA $tresorerieA): static
    {
        if ($this->tresorerieAs->removeElement($tresorerieA)) {
            // set the owning side to null (unless already changed)
            if ($tresorerieA->getUser() === $this) {
                $tresorerieA->setUser(null);
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
            $balance->setUser($this);
        }

        return $this;
    }

    public function removeBalance(Balance $balance): static
    {
        if ($this->balances->removeElement($balance)) {
            // set the owning side to null (unless already changed)
            if ($balance->getUser() === $this) {
                $balance->setUser(null);
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
            $balanceA->setUser($this);
        }

        return $this;
    }

    public function removeBalanceA(BalanceA $balanceA): static
    {
        if ($this->balanceAs->removeElement($balanceA)) {
            // set the owning side to null (unless already changed)
            if ($balanceA->getUser() === $this) {
                $balanceA->setUser(null);
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
            $immobilisation->setUser($this);
        }

        return $this;
    }

    public function removeImmobilisation(Immobilisation $immobilisation): static
    {
        if ($this->immobilisations->removeElement($immobilisation)) {
            // set the owning side to null (unless already changed)
            if ($immobilisation->getUser() === $this) {
                $immobilisation->setUser(null);
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
            $immobilisationA->setUser($this);
        }

        return $this;
    }

    public function removeImmobilisationA(ImmobilisationA $immobilisationA): static
    {
        if ($this->immobilisationAs->removeElement($immobilisationA)) {
            // set the owning side to null (unless already changed)
            if ($immobilisationA->getUser() === $this) {
                $immobilisationA->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Salaire>
     */
    public function getSalaires(): Collection
    {
        return $this->salaires;
    }

    public function addSalaire(Salaire $salaire): static
    {
        if (!$this->salaires->contains($salaire)) {
            $this->salaires->add($salaire);
            $salaire->setUser($this);
        }

        return $this;
    }

    public function removeSalaire(Salaire $salaire): static
    {
        if ($this->salaires->removeElement($salaire)) {
            // set the owning side to null (unless already changed)
            if ($salaire->getUser() === $this) {
                $salaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SalaireA>
     */
    public function getSalaireAs(): Collection
    {
        return $this->salaireAs;
    }

    public function addSalaireA(SalaireA $salaireA): static
    {
        if (!$this->salaireAs->contains($salaireA)) {
            $this->salaireAs->add($salaireA);
            $salaireA->setUser($this);
        }

        return $this;
    }

    public function removeSalaireA(SalaireA $salaireA): static
    {
        if ($this->salaireAs->removeElement($salaireA)) {
            // set the owning side to null (unless already changed)
            if ($salaireA->getUser() === $this) {
                $salaireA->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sociales>
     */
    public function getSociales(): Collection
    {
        return $this->sociales;
    }

    public function addSociale(Sociales $sociale): static
    {
        if (!$this->sociales->contains($sociale)) {
            $this->sociales->add($sociale);
            $sociale->setUser($this);
        }

        return $this;
    }

    public function removeSociale(Sociales $sociale): static
    {
        if ($this->sociales->removeElement($sociale)) {
            // set the owning side to null (unless already changed)
            if ($sociale->getUser() === $this) {
                $sociale->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): static
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts->add($emprunt);
            $emprunt->setUser($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): static
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getUser() === $this) {
                $emprunt->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Remboursement>
     */
    public function getRemboursements(): Collection
    {
        return $this->remboursements;
    }

    public function addRemboursement(Remboursement $remboursement): static
    {
        if (!$this->remboursements->contains($remboursement)) {
            $this->remboursements->add($remboursement);
            $remboursement->setUser($this);
        }

        return $this;
    }

    public function removeRemboursement(Remboursement $remboursement): static
    {
        if ($this->remboursements->removeElement($remboursement)) {
            // set the owning side to null (unless already changed)
            if ($remboursement->getUser() === $this) {
                $remboursement->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Retrait>
     */
    public function getRetraits(): Collection
    {
        return $this->retraits;
    }

    public function addRetrait(Retrait $retrait): static
    {
        if (!$this->retraits->contains($retrait)) {
            $this->retraits->add($retrait);
            $retrait->setUser($this);
        }

        return $this;
    }

    public function removeRetrait(Retrait $retrait): static
    {
        if ($this->retraits->removeElement($retrait)) {
            // set the owning side to null (unless already changed)
            if ($retrait->getUser() === $this) {
                $retrait->setUser(null);
            }
        }

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection<int, Brouillon>
     */
    public function getBrouillons(): Collection
    {
        return $this->brouillons;
    }

    public function addBrouillon(Brouillon $brouillon): static
    {
        if (!$this->brouillons->contains($brouillon)) {
            $this->brouillons->add($brouillon);
            $brouillon->setUser($this);
        }

        return $this;
    }

    public function removeBrouillon(Brouillon $brouillon): static
    {
        if ($this->brouillons->removeElement($brouillon)) {
            // set the owning side to null (unless already changed)
            if ($brouillon->getUser() === $this) {
                $brouillon->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Magasin>
     */
    public function getMagasins(): Collection
    {
        return $this->magasins;
    }

    public function addMagasin(Magasin $magasin): static
    {
        if (!$this->magasins->contains($magasin)) {
            $this->magasins->add($magasin);
            $magasin->setUser($this);
        }

        return $this;
    }

    public function removeMagasin(Magasin $magasin): static
    {
        if ($this->magasins->removeElement($magasin)) {
            // set the owning side to null (unless already changed)
            if ($magasin->getUser() === $this) {
                $magasin->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transfert>
     */
    public function getTransferts(): Collection
    {
        return $this->transferts;
    }

    public function addTransfert(Transfert $transfert): static
    {
        if (!$this->transferts->contains($transfert)) {
            $this->transferts->add($transfert);
            $transfert->setUser($this);
        }

        return $this;
    }

    public function removeTransfert(Transfert $transfert): static
    {
        if ($this->transferts->removeElement($transfert)) {
            // set the owning side to null (unless already changed)
            if ($transfert->getUser() === $this) {
                $transfert->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InventaireA>
     */
    public function getInventaireAs(): Collection
    {
        return $this->inventaireAs;
    }

    public function addInventaireA(InventaireA $inventaireA): static
    {
        if (!$this->inventaireAs->contains($inventaireA)) {
            $this->inventaireAs->add($inventaireA);
            $inventaireA->setUser($this);
        }

        return $this;
    }

    public function removeInventaireA(InventaireA $inventaireA): static
    {
        if ($this->inventaireAs->removeElement($inventaireA)) {
            // set the owning side to null (unless already changed)
            if ($inventaireA->getUser() === $this) {
                $inventaireA->setUser(null);
            }
        }

        return $this;
    }
    
}
