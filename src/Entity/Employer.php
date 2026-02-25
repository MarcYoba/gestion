<?php

namespace App\Entity;

use App\Repository\EmployerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployerRepository::class)]
class Employer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;


    #[ORM\OneToOne(inversedBy: 'Employer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 100)]
    private ?string $poste = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'employer')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agence $agence = null;

    #[ORM\OneToMany(mappedBy: 'employer', targetEntity: Salaire::class)]
    private Collection $salaires;

    #[ORM\OneToMany(mappedBy: 'employer', targetEntity: SalaireA::class)]
    private Collection $salaireAs;

    #[ORM\OneToMany(mappedBy: 'Employer', targetEntity: TransfertA::class)]
    private Collection $transfertAs;

    public function __construct()
    {
        $this->salaires = new ArrayCollection();
        $this->salaireAs = new ArrayCollection();
        $this->transfertAs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): static
    {
        $this->poste = $poste;

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
            $salaire->setEmployer($this);
        }

        return $this;
    }

    public function removeSalaire(Salaire $salaire): static
    {
        if ($this->salaires->removeElement($salaire)) {
            // set the owning side to null (unless already changed)
            if ($salaire->getEmployer() === $this) {
                $salaire->setEmployer(null);
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
            $salaireA->setEmployer($this);
        }

        return $this;
    }

    public function removeSalaireA(SalaireA $salaireA): static
    {
        if ($this->salaireAs->removeElement($salaireA)) {
            // set the owning side to null (unless already changed)
            if ($salaireA->getEmployer() === $this) {
                $salaireA->setEmployer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TransfertA>
     */
    public function getTransfertAs(): Collection
    {
        return $this->transfertAs;
    }

    public function addTransfertA(TransfertA $transfertA): static
    {
        if (!$this->transfertAs->contains($transfertA)) {
            $this->transfertAs->add($transfertA);
            $transfertA->setEmployer($this);
        }

        return $this;
    }

    public function removeTransfertA(TransfertA $transfertA): static
    {
        if ($this->transfertAs->removeElement($transfertA)) {
            // set the owning side to null (unless already changed)
            if ($transfertA->getEmployer() === $this) {
                $transfertA->setEmployer(null);
            }
        }

        return $this;
    }
}
