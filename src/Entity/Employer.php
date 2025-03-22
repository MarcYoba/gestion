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

    #[ORM\ManyToMany(targetEntity: Agence::class, inversedBy: 'employers')]
    private Collection $idagence;

    #[ORM\OneToOne(inversedBy: 'Employer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 100)]
    private ?string $poste = null;

    #[ORM\ManyToOne(inversedBy: 'employer')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salaire $salaire = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    public function __construct()
    {
        $this->idagence = new ArrayCollection();
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

    

    

    /**
     * @return Collection<int, Agence>
     */
    public function getIdagence(): Collection
    {
        return $this->idagence;
    }

    public function addIdagence(Agence $idagence): static
    {
        if (!$this->idagence->contains($idagence)) {
            $this->idagence->add($idagence);
        }

        return $this;
    }

    public function removeIdagence(Agence $idagence): static
    {
        $this->idagence->removeElement($idagence);

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

    public function getSalaire(): ?Salaire
    {
        return $this->salaire;
    }

    public function setSalaire(?Salaire $salaire): static
    {
        $this->salaire = $salaire;

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
}
