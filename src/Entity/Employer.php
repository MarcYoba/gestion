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
}
