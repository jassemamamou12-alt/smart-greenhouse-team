<?php

// src/Entity/Sensor.php
// Code 12.4 - Chapitre 12, TP 17 - Smart Greenhouse
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'sensors')]
#[ORM\UniqueConstraint(name: 'uq_sensors_device_type', columns: ['device_id', 'type'])]
class Sensor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['device:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sensors')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Device $device = null;

    #[ORM\Column(length: 30)]
    #[Groups(['device:read'])]
    private string $type = '';

    #[ORM\Column(length: 10)]
    #[Groups(['device:read'])]
    private string $unit = '';

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $label = null;

    public function getId(): ?int { return $this->id; }
    public function getDevice(): ?Device { return $this->device; }
    public function setDevice(?Device $device): static { $this->device = $device; return $this; }
    public function getType(): string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }
    public function getUnit(): string { return $this->unit; }
    public function setUnit(string $unit): static { $this->unit = $unit; return $this; }
}
