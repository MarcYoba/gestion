<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column(length: 255)]
    private ?string $sexe = null;

    #[ORM\Column]
    private ?int $poid = null;

    #[ORM\Column(length: 255)]
    private ?string $esperce = null;

    #[ORM\Column(length: 255)]
    private ?string $robe = null;

    #[ORM\Column(length: 255)]
    private ?string $race = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?Clients $client = null;

    #[ORM\Column(length: 255)]
    private ?string $vaccin = null;

    #[ORM\Column(length: 255)]
    private ?string $vermufuge = null;

    #[ORM\Column(length: 255)]
    private ?string $regime = null;

    #[ORM\Column(length: 255)]
    private ?string $motifConsultation = null;

    #[ORM\Column]
    private ?int $temperature = null;

    #[ORM\Column(length: 255)]
    private ?string $symtome = null;

    #[ORM\Column(length: 255)]
    private ?string $dianostique = null;

    #[ORM\Column(length: 255)]
    private ?string $traitement = null;

    #[ORM\Column(length: 255)]
    private ?string $pronostique = null;

    #[ORM\Column(length: 255)]
    private ?string $prophylaxe = null;

    #[ORM\Column(length: 255)]
    private ?string $indication = null;

    #[ORM\Column]
    private ?int $nomtant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createtAd = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?Agence $agence = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateVermufige = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateRappel = null;

    #[ORM\Column(length: 255)]
    private ?string $examain = null;

    #[ORM\Column(length: 255)]
    private ?string $docteur = null;

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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getPoid(): ?int
    {
        return $this->poid;
    }

    public function setPoid(int $poid): static
    {
        $this->poid = $poid;

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

    public function getRobe(): ?string
    {
        return $this->robe;
    }

    public function setRobe(string $robe): static
    {
        $this->robe = $robe;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(string $race): static
    {
        $this->race = $race;

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

    public function getVaccin(): ?string
    {
        return $this->vaccin;
    }

    public function setVaccin(string $vaccin): static
    {
        $this->vaccin = $vaccin;

        return $this;
    }

    public function getVermufuge(): ?string
    {
        return $this->vermufuge;
    }

    public function setVermufuge(string $vermufuge): static
    {
        $this->vermufuge = $vermufuge;

        return $this;
    }

    public function getRegime(): ?string
    {
        return $this->regime;
    }

    public function setRegime(string $regime): static
    {
        $this->regime = $regime;

        return $this;
    }

    public function getMotifConsultation(): ?string
    {
        return $this->motifConsultation;
    }

    public function setMotifConsultation(string $motifConsultation): static
    {
        $this->motifConsultation = $motifConsultation;

        return $this;
    }

    public function getTemperature(): ?int
    {
        return $this->temperature;
    }

    public function setTemperature(int $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getSymtome(): ?string
    {
        return $this->symtome;
    }

    public function setSymtome(string $symtome): static
    {
        $this->symtome = $symtome;

        return $this;
    }

    public function getDianostique(): ?string
    {
        return $this->dianostique;
    }

    public function setDianostique(string $dianostique): static
    {
        $this->dianostique = $dianostique;

        return $this;
    }

    public function getTraitement(): ?string
    {
        return $this->traitement;
    }

    public function setTraitement(string $traitement): static
    {
        $this->traitement = $traitement;

        return $this;
    }

    public function getPronostique(): ?string
    {
        return $this->pronostique;
    }

    public function setPronostique(string $pronostique): static
    {
        $this->pronostique = $pronostique;

        return $this;
    }

    public function getProphylaxe(): ?string
    {
        return $this->prophylaxe;
    }

    public function setProphylaxe(string $prophylaxe): static
    {
        $this->prophylaxe = $prophylaxe;

        return $this;
    }

    public function getIndication(): ?string
    {
        return $this->indication;
    }

    public function setIndication(string $indication): static
    {
        $this->indication = $indication;

        return $this;
    }

    public function getNomtant(): ?int
    {
        return $this->nomtant;
    }

    public function setNomtant(int $nomtant): static
    {
        $this->nomtant = $nomtant;

        return $this;
    }

    public function getCreatetAd(): ?\DateTimeInterface
    {
        return $this->createtAd;
    }

    public function setCreatetAd(\DateTimeInterface $createtAd): static
    {
        $this->createtAd = $createtAd;

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

    public function getDateVermufige(): ?\DateTimeInterface
    {
        return $this->dateVermufige;
    }

    public function setDateVermufige(\DateTimeInterface $dateVermufige): static
    {
        $this->dateVermufige = $dateVermufige;

        return $this;
    }

    public function getDateRappel(): ?\DateTimeInterface
    {
        return $this->dateRappel;
    }

    public function setDateRappel(\DateTimeInterface $dateRappel): static
    {
        $this->dateRappel = $dateRappel;

        return $this;
    }

    public function getExamain(): ?string
    {
        return $this->examain;
    }

    public function setExamain(string $examain): static
    {
        $this->examain = $examain;

        return $this;
    }

    public function getDocteur(): ?string
    {
        return $this->docteur;
    }

    public function setDocteur(string $docteur): static
    {
        $this->docteur = $docteur;

        return $this;
    }
}
