<?php

namespace App\Entity;

use App\Repository\ActifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActifRepository::class)]
class Actif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?float $brut = null;

    #[ORM\Column]
    private ?float $amortissement = null;

    #[ORM\Column]
    private ?float $net = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(length: 255)]
    private ?string $cathegorie = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\ManyToOne(inversedBy: 'actifs')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'actifs')]
    private ?Agence $agence = null;

    #[ORM\Column(length: 255)]
    private ?string $REF = null;

    #[ORM\OneToMany(mappedBy: 'actif', targetEntity: DepenseActif::class)]
    private Collection $depenseActifs;

    public function __construct()
    {
        $this->depenseActifs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getBrut(): ?float
    {
        return $this->brut;
    }

    public function setBrut(float $brut): static
    {
        $this->brut = $brut;

        return $this;
    }

    public function getAmortissement(): ?float
    {
        return $this->amortissement;
    }

    public function setAmortissement(float $amortissement): static
    {
        $this->amortissement = $amortissement;

        return $this;
    }

    public function getNet(): ?float
    {
        return $this->net;
    }

    public function setNet(float $net): static
    {
        $this->net = $net;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

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

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): static
    {
        $this->ordre = $ordre;

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

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;

        return $this;
    }

    public function getREF(): ?string
    {
        return $this->REF;
    }

    public function setREF(string $REF): static
    {
        $this->REF = $REF;

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
            $depenseActif->setActif($this);
        }

        return $this;
    }

    public function removeDepenseActif(DepenseActif $depenseActif): static
    {
        if ($this->depenseActifs->removeElement($depenseActif)) {
            // set the owning side to null (unless already changed)
            if ($depenseActif->getActif() === $this) {
                $depenseActif->setActif(null);
            }
        }

        return $this;
    }
}
