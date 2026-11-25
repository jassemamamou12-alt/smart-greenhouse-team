<?php

// src/Entity/Device.php
// Code 12.3 - Chapitre 12, TP 17 - Smart Greenhouse
namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
#[ORM\Table(name: 'devices')]
#[ORM\Index(name: 'idx_devices_user', columns: ['user_id'])]
class Device
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['device:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'devices')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column(length: 100)]
    #[Groups(['device:read'])]
    private string $name = '';

    #[ORM\Column(length: 150, nullable: true)]
    #[Groups(['device:read'])]
    private ?string $location = null;

    #[ORM\Column(length: 64, unique: true)]
    private string $apiTokenHash = ''; // jamais serialise : aucun groupe

    #[ORM\Column]
    #[Groups(['device:read'])]
    private bool $isActive = true;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    #[Groups(['device:read'])]
    #[Context([
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d\TH:i:s\Z',
        DateTimeNormalizer::TIMEZONE_KEY => 'UTC',
    ])]
    private ?\DateTimeImmutable $lastSeenAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    /** @var Collection<int, Sensor> */
    #[ORM\OneToMany(mappedBy: 'device', targetEntity: Sensor::class,
        cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['device:read'])]
    private Collection $sensors;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->sensors = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(User $user): static { $this->user = $user; return $this; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getLocation(): ?string { return $this->location; }
    public function setLocation(?string $loc): static { $this->location = $loc; return $this; }
    public function getApiTokenHash(): string { return $this->apiTokenHash; }
    public function setApiTokenHash(string $h): static { $this->apiTokenHash = $h; return $this; }
    public function isActive(): bool { return $this->isActive; }
    public function setActive(bool $active): static { $this->isActive = $active; return $this; }
    public function getLastSeenAt(): ?\DateTimeImmutable { return $this->lastSeenAt; }
    public function touch(): static { $this->lastSeenAt = new \DateTimeImmutable(); return $this; }

    /** @return Collection<int, Sensor> */
    public function getSensors(): Collection { return $this->sensors; }

    public function addSensor(Sensor $sensor): static
    {
        if (!$this->sensors->contains($sensor)) {
            $this->sensors->add($sensor);
            $sensor->setDevice($this); // maintient les deux cotes de la relation
        }
        return $this;
    }
}
