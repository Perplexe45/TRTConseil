<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    private ?bool $enService = null;

    #[ORM\Column(nullable: true)]
    private ?bool $connecRecruteur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $connecCandidat = null;

    #[ORM\Column(nullable: true)]
    private ?bool $connecConsultant = null;

    #[ORM\Column(nullable: true)]
    private ?bool $connecAdministrateur = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Consultant $consultant = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Candidat $candidat = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Recruteur $recruteur = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Administrateur $administrateur = null;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';



        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isEnService(): ?bool
    {
        return $this->enService;
    }

    public function setEnService(?bool $enService): static
    {
        $this->enService = $enService;

        return $this;
    }

    public function isConnecRecruteur(): ?bool
    {
        return $this->connecRecruteur;
    }

    public function setConnecRecruteur(?bool $connecRecruteur): static
    {
        $this->connecRecruteur = $connecRecruteur;

        return $this;
    }

    public function isConnecCandidat(): ?bool
    {
        return $this->connecCandidat;
    }

    public function setConnecCandidat(?bool $connecCandidat): static
    {
        $this->connecCandidat = $connecCandidat;

        return $this;
    }

    public function isConnecConsultant(): ?bool
    {
        return $this->connecConsultant;
    }

    public function setConnecConsultant(?bool $connecConsultant): static
    {
        $this->connecConsultant = $connecConsultant;

        return $this;
    }

    public function isConnecAdministrateur(): ?bool
    {
        return $this->connecAdministrateur;
    }

    public function setConnecAdministrateur(?bool $connecAdministrateur): static
    {
        $this->connecAdministrateur = $connecAdministrateur;

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

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): static
    {
        $this->candidat = $candidat;

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

    public function getAdministrateur(): ?Administrateur
    {
        return $this->administrateur;
    }

    public function setAdministrateur(?Administrateur $administrateur): static
    {
        $this->administrateur = $administrateur;

        return $this;
    }
}
