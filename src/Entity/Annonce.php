<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $poste = null;

    #[ORM\Column(length: 100)]
    private ?string $lieu = null;

    #[ORM\Column(length: 255)]
    private ?string $horaire = null;

    #[ORM\Column(length: 100)]
    private ?string $salaire = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?bool $publie = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Recruteur $recruteur = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Consultant $consultant = null;

    #[ORM\OneToMany(targetEntity: CandidatAnnonce::class, mappedBy: 'annonce')]
    private Collection $candidatAnnonces;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateCreation = null;
    
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateModif = null;

    public function __construct()
    {
        $this->candidatAnnonces = new ArrayCollection();
        $this->dateCreation = new \DateTimeImmutable();
    }  

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getHoraire(): ?string
    {
        return $this->horaire;
    }

    public function setHoraire(string $horaire): static
    {
        $this->horaire = $horaire;

        return $this;
    }

    public function getSalaire(): ?string
    {
        return $this->salaire;
    }

    public function setSalaire(string $salaire): static
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPublie(): ?bool
    {
        return $this->publie;
    }

    public function setPublie(?bool $publie): static
    {
        $this->publie = $publie;

        return $this;
    }

    public function getRecruteur(): ?Recruteur
    {
        return $this->recruteur;
    }

    public function setRecruteur(?Recruteur $recruteur): static
    {
        $this->recruteur = $recruteur;

        return $this;
    }

    public function getConsultant(): ?Consultant
    {
        return $this->consultant;
    }

    public function setConsultant(?Consultant $consultant): static
    {
        $this->consultant = $consultant;

        return $this;
    }

    public function __toString()   
    {
        return $this->getLieu() . ' : ' .$this->getDescription();
    }


    /**
     * @return Collection<int, CandidatAnnonce>
     */
    public function getCandidatAnnonces(): Collection
    {
        return $this->candidatAnnonces;
    }

    public function addCandidatAnnonce(CandidatAnnonce $candidatAnnonce): static
    {
        if (!$this->candidatAnnonces->contains($candidatAnnonce)) {
            $this->candidatAnnonces->add($candidatAnnonce);
            $candidatAnnonce->setAnnonce($this);
        }

        return $this;
    }

    public function removeCandidatAnnonce(CandidatAnnonce $candidatAnnonce): static
    {
        if ($this->candidatAnnonces->removeElement($candidatAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($candidatAnnonce->getAnnonce() === $this) {
                $candidatAnnonce->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModif(): ?\DateTimeInterface
    {
        return $this->dateModif;
    }

    public function setDateModif(?\DateTimeInterface $dateModif): static
    {
        $this->dateModif = $dateModif;

        return $this;
    }
        

 


  
}
