<?php

namespace App\Entity;

use App\Repository\PassifARepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PassifARepository::class)]
class PassifA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'passifAs')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'passifAs')]
    private ?Agence $agence = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\Column(length: 255)]
    private ?string $REF = null;

    #[ORM\Column(length: 255)]
    private ?string $cathegorie = null;

    #[ORM\OneToMany(mappedBy: 'passif', targetEntity: DepensePassifA::class)]
    private Collection $depensePassifAs;

    public function __construct()
    {
        $this->depensePassifAs = new ArrayCollection();
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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;

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

    public function getREF(): ?string
    {
        return $this->REF;
    }

    public function setREF(string $REF): static
    {
        $this->REF = $REF;

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
            $depensePassifA->setPassif($this);
        }

        return $this;
    }

    public function removeDepensePassifA(DepensePassifA $depensePassifA): static
    {
        if ($this->depensePassifAs->removeElement($depensePassifA)) {
            // set the owning side to null (unless already changed)
            if ($depensePassifA->getPassif() === $this) {
                $depensePassifA->setPassif(null);
            }
        }

        return $this;
    }
}
