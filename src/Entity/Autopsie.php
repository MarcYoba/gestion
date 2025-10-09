<?php

namespace App\Entity;

use App\Repository\AutopsieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AutopsieRepository::class)]
class Autopsie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'autopsies')]
    private ?Clients $client = null;

    #[ORM\Column(length: 255)]
    private ?string $famille = null;

    #[ORM\Column(length: 255)]
    private ?string $espece = null;

    #[ORM\Column(length: 255)]
    private ?string $race = null;

    #[ORM\Column(length: 255)]
    private ?string $age = null;

    #[ORM\Column(length: 255)]
    private ?string $origine = null;

    #[ORM\Column(length: 255)]
    private ?string $effectif = null;

    #[ORM\Column(length: 255)]
    private ?string $morbidite = null;

    #[ORM\Column(length: 255)]
    private ?string $mortalite = null;

    #[ORM\Column(length: 255)]
    private ?string $clinique = null;

    #[ORM\Column(length: 255)]
    private ?string $traitement = null;

    #[ORM\Column(length: 255)]
    private ?string $pathologiques = null;

    #[ORM\Column(length: 255)]
    private ?string $antecedent = null;

    #[ORM\Column(length: 255)]
    private ?string $vaccinations = null;

    #[ORM\Column(length: 255)]
    private ?string $embonpoint = null;

    #[ORM\Column(length: 255)]
    private ?string $mort = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datemort = null;

    #[ORM\Column(length: 255)]
    private ?string $Lieu = null;

    #[ORM\Column(length: 255)]
    private ?string $conservation = null;

    #[ORM\Column(length: 255)]
    private ?string $durreconservation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateautopsie = null;

    #[ORM\Column(length: 255)]
    private ?string $medecin = null;

    #[ORM\Column(length: 255)]
    private ?string $appendices = null;

    #[ORM\Column(length: 255)]
    private ?string $muqueuses = null;

    #[ORM\Column(length: 255)]
    private ?string $peau = null;

    #[ORM\Column(length: 255)]
    private ?string $membre = null;

    #[ORM\Column(length: 255)]
    private ?string $anomalies = null;

    #[ORM\Column(length: 255)]
    private ?string $tissu = null;

    #[ORM\Column(length: 255)]
    private ?string $tube = null;

    #[ORM\Column(length: 255)]
    private ?string $respiratoire = null;

    #[ORM\Column(length: 255)]
    private ?string $circulatoire = null;

    #[ORM\Column(length: 255)]
    private ?string $genital = null;

    #[ORM\Column(length: 255)]
    private ?string $urinaire = null;

    #[ORM\Column(length: 255)]
    private ?string $locomoteur = null;

    #[ORM\Column(length: 255)]
    private ?string $nerveux = null;

    #[ORM\Column(length: 255)]
    private ?string $endocrines = null;

    #[ORM\Column(length: 255)]
    private ?string $glandes = null;

    #[ORM\Column(length: 255)]
    private ?string $hemato = null;

    #[ORM\Column(length: 255)]
    private ?string $diagnostic = null;

    #[ORM\Column(length: 255)]
    private ?string $certitude = null;

    #[ORM\ManyToOne(inversedBy: 'autopsies')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'autopsies')]
    private ?Agence $agence = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFamille(): ?string
    {
        return $this->famille;
    }

    public function setFamille(string $famille): static
    {
        $this->famille = $famille;

        return $this;
    }

    public function getEspece(): ?string
    {
        return $this->espece;
    }

    public function setEspece(string $espece): static
    {
        $this->espece = $espece;

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

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getOrigine(): ?string
    {
        return $this->origine;
    }

    public function setOrigine(string $origine): static
    {
        $this->origine = $origine;

        return $this;
    }

    public function getEffectif(): ?string
    {
        return $this->effectif;
    }

    public function setEffectif(string $effectif): static
    {
        $this->effectif = $effectif;

        return $this;
    }

    public function getMorbidite(): ?string
    {
        return $this->morbidite;
    }

    public function setMorbidite(string $morbidite): static
    {
        $this->morbidite = $morbidite;

        return $this;
    }

    public function getMortalite(): ?string
    {
        return $this->mortalite;
    }

    public function setMortalite(string $mortalite): static
    {
        $this->mortalite = $mortalite;

        return $this;
    }

    public function getClinique(): ?string
    {
        return $this->clinique;
    }

    public function setClinique(string $clinique): static
    {
        $this->clinique = $clinique;

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

    public function getPathologiques(): ?string
    {
        return $this->pathologiques;
    }

    public function setPathologiques(string $pathologiques): static
    {
        $this->pathologiques = $pathologiques;

        return $this;
    }

    public function getAntecedent(): ?string
    {
        return $this->antecedent;
    }

    public function setAntecedent(string $antecedent): static
    {
        $this->antecedent = $antecedent;

        return $this;
    }

    public function getVaccinations(): ?string
    {
        return $this->vaccinations;
    }

    public function setVaccinations(string $vaccinations): static
    {
        $this->vaccinations = $vaccinations;

        return $this;
    }

    public function getEmbonpoint(): ?string
    {
        return $this->embonpoint;
    }

    public function setEmbonpoint(string $embonpoint): static
    {
        $this->embonpoint = $embonpoint;

        return $this;
    }

    public function getMort(): ?string
    {
        return $this->mort;
    }

    public function setMort(string $mort): static
    {
        $this->mort = $mort;

        return $this;
    }

    public function getDatemort(): ?\DateTimeInterface
    {
        return $this->datemort;
    }

    public function setDatemort(\DateTimeInterface $datemort): static
    {
        $this->datemort = $datemort;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->Lieu;
    }

    public function setLieu(string $Lieu): static
    {
        $this->Lieu = $Lieu;

        return $this;
    }

    public function getConservation(): ?string
    {
        return $this->conservation;
    }

    public function setConservation(string $conservation): static
    {
        $this->conservation = $conservation;

        return $this;
    }

    public function getDurreconservation(): ?string
    {
        return $this->durreconservation;
    }

    public function setDurreconservation(string $durreconservation): static
    {
        $this->durreconservation = $durreconservation;

        return $this;
    }

    public function getDateautopsie(): ?\DateTimeInterface
    {
        return $this->dateautopsie;
    }

    public function setDateautopsie(\DateTimeInterface $dateautopsie): static
    {
        $this->dateautopsie = $dateautopsie;

        return $this;
    }

    public function getMedecin(): ?string
    {
        return $this->medecin;
    }

    public function setMedecin(string $medecin): static
    {
        $this->medecin = $medecin;

        return $this;
    }

    public function getAppendices(): ?string
    {
        return $this->appendices;
    }

    public function setAppendices(string $appendices): static
    {
        $this->appendices = $appendices;

        return $this;
    }

    public function getMuqueuses(): ?string
    {
        return $this->muqueuses;
    }

    public function setMuqueuses(string $muqueuses): static
    {
        $this->muqueuses = $muqueuses;

        return $this;
    }

    public function getPeau(): ?string
    {
        return $this->peau;
    }

    public function setPeau(string $peau): static
    {
        $this->peau = $peau;

        return $this;
    }

    public function getMembre(): ?string
    {
        return $this->membre;
    }

    public function setMembre(string $membre): static
    {
        $this->membre = $membre;

        return $this;
    }

    public function getAnomalies(): ?string
    {
        return $this->anomalies;
    }

    public function setAnomalies(string $anomalies): static
    {
        $this->anomalies = $anomalies;

        return $this;
    }

    public function getTissu(): ?string
    {
        return $this->tissu;
    }

    public function setTissu(string $tissu): static
    {
        $this->tissu = $tissu;

        return $this;
    }

    public function getTube(): ?string
    {
        return $this->tube;
    }

    public function setTube(string $tube): static
    {
        $this->tube = $tube;

        return $this;
    }

    public function getRespiratoire(): ?string
    {
        return $this->respiratoire;
    }

    public function setRespiratoire(string $respiratoire): static
    {
        $this->respiratoire = $respiratoire;

        return $this;
    }

    public function getCirculatoire(): ?string
    {
        return $this->circulatoire;
    }

    public function setCirculatoire(string $circulatoire): static
    {
        $this->circulatoire = $circulatoire;

        return $this;
    }

    public function getGenital(): ?string
    {
        return $this->genital;
    }

    public function setGenital(string $genital): static
    {
        $this->genital = $genital;

        return $this;
    }

    public function getUrinaire(): ?string
    {
        return $this->urinaire;
    }

    public function setUrinaire(string $urinaire): static
    {
        $this->urinaire = $urinaire;

        return $this;
    }

    public function getLocomoteur(): ?string
    {
        return $this->locomoteur;
    }

    public function setLocomoteur(string $locomoteur): static
    {
        $this->locomoteur = $locomoteur;

        return $this;
    }

    public function getNerveux(): ?string
    {
        return $this->nerveux;
    }

    public function setNerveux(string $nerveux): static
    {
        $this->nerveux = $nerveux;

        return $this;
    }

    public function getEndocrines(): ?string
    {
        return $this->endocrines;
    }

    public function setEndocrines(string $endocrines): static
    {
        $this->endocrines = $endocrines;

        return $this;
    }

    public function getGlandes(): ?string
    {
        return $this->glandes;
    }

    public function setGlandes(string $glandes): static
    {
        $this->glandes = $glandes;

        return $this;
    }

    public function getHemato(): ?string
    {
        return $this->hemato;
    }

    public function setHemato(string $hemato): static
    {
        $this->hemato = $hemato;

        return $this;
    }

    public function getDiagnostic(): ?string
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(string $diagnostic): static
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    public function getCertitude(): ?string
    {
        return $this->certitude;
    }

    public function setCertitude(string $certitude): static
    {
        $this->certitude = $certitude;

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
}
