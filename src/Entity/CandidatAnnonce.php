<?php

namespace App\Entity;

use App\Repository\CandidatAnnonceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatAnnonceRepository::class)]
class CandidatAnnonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'candidatAnnonces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $annonce = null;

    #[ORM\ManyToOne(inversedBy: 'candidatAnnonces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Candidat $candidat = null;

    #[ORM\Column(nullable: true)]
    private ?bool $approbationConsultant = null;

    #[ORM\Column(nullable: true)]
    private ?bool $envoiMailRecruteur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): static
    {
        $this->annonce = $annonce;

        return $this;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): static
    {
        $this->candidat = $candidat;

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

    public function isEnvoiMailRecruteur(): ?bool
    {
        return $this->envoiMailRecruteur;
    }

    public function setEnvoiMailRecruteur(?bool $envoiMailRecruteur): static
    {
        $this->envoiMailRecruteur = $envoiMailRecruteur;

        return $this;
    }
}
