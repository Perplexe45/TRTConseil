<?php

namespace App\Entity;

use App\Repository\CandidatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatRepository::class)]
class Candidat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?bool $approbationConsultant = null;

    #[ORM\ManyToOne(inversedBy: 'candidats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Metier $metier = null;

    #[ORM\OneToMany(targetEntity: CandidatAnnonce::class, mappedBy: 'candidat')]
    private Collection $candidatAnnonces;

    public function __construct()
    {
        $this->candidatAnnonces = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
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

    public function isApprobationConsultant(): ?bool
    {
        return $this->approbationConsultant;
    }

    public function setApprobationConsultant(?bool $approbationConsultant): static
    {
        $this->approbationConsultant = $approbationConsultant;

        return $this;
    }

    public function getMetier(): ?Metier
    {
        return $this->metier;
    }

    public function setMetier(?Metier $metier): static
    {
        $this->metier = $metier;

        return $this;
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
            $candidatAnnonce->setCandidat($this);
        }

        return $this;
    }

    public function removeCandidatAnnonce(CandidatAnnonce $candidatAnnonce): static
    {
        if ($this->candidatAnnonces->removeElement($candidatAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($candidatAnnonce->getCandidat() === $this) {
                $candidatAnnonce->setCandidat(null);
            }
        }

        return $this;
    }
}
