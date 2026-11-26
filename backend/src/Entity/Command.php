<?php

// src/Entity/Command.php
// Exercice 3 - Chapitre 12 - Smart Greenhouse
namespace App\Entity;

use App\Repository\CommandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
#[ORM\Table(name: 'commands')]
// WHERE declare sous la forme normalisee par PostgreSQL : Doctrine compare alors
// le mapping a l'index introspecte et doctrine:schema:validate reste synchrone
#[ORM\Index(name: 'idx_commands_pending', columns: ['device_id'],
    options: ['where' => "((status)::text = 'pending'::text)"])]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Device $device = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'created_by', nullable: true, onDelete: 'SET NULL')]
    private ?User $createdBy = null;

    #[ORM\Column(length: 30)]
    private string $actuator = '';

    #[ORM\Column(length: 10)]
    private string $action = 'off';

    #[ORM\Column(nullable: true)]
    private ?int $durationSeconds = null;

    #[ORM\Column(length: 15)]
    private string $status = 'pending';

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $executedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    // getters et setters : comme pour les autres entites
    public function getId(): ?int { return $this->id; }
    public function getDevice(): ?Device { return $this->device; }
    public function setDevice(?Device $device): static { $this->device = $device; return $this; }
    public function getCreatedBy(): ?User { return $this->createdBy; }
    public function setCreatedBy(?User $user): static { $this->createdBy = $user; return $this; }
    public function getActuator(): string { return $this->actuator; }
    public function setActuator(string $actuator): static { $this->actuator = $actuator; return $this; }
    public function getAction(): string { return $this->action; }
    public function setAction(string $action): static { $this->action = $action; return $this; }
    public function getDurationSeconds(): ?int { return $this->durationSeconds; }
    public function setDurationSeconds(?int $duration): static { $this->durationSeconds = $duration; return $this; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getExecutedAt(): ?\DateTimeImmutable { return $this->executedAt; }
    public function setExecutedAt(?\DateTimeImmutable $at): static { $this->executedAt = $at; return $this; }
}
