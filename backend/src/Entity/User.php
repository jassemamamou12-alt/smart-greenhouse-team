<?php

// src/Entity/User.php
// TP17 - Etape 1 - Dictionnaire de donnees (tableau 10.7)
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;   // ← AJOUTÉ
use Doctrine\Common\Collections\Collection;        // ← AJOUTÉ
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')] // "user" est un mot reserve de PostgreSQL
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private string $email = '';

    #[ORM\Column(length: 255)]
    private string $passwordHash = ''; // empreinte, jamais le mot de passe en clair

    #[ORM\Column(type: Types::JSON)]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(length: 100)]
    private string $fullName = '';

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    /** @var Collection<int, Device> */                              // ← AJOUTÉ
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Device::class)] // ← AJOUTÉ
    private Collection $devices;                                     // ← AJOUTÉ

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->devices = new ArrayCollection();                      // ← AJOUTÉ
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): static
    {
        $this->passwordHash = $passwordHash;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /** @return Collection<int, Device> */                           // ← AJOUTÉ
    public function getDevices(): Collection                         // ← AJOUTÉ
    {                                                                // ← AJOUTÉ
        return $this->devices;                                       // ← AJOUTÉ
    }                                                                // ← AJOUTÉ
}
