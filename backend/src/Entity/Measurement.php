<?php

// src/Entity/Measurement.php
// Code 12.4 - Chapitre 12, TP 17 - Smart Greenhouse
namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
#[ORM\Table(name: 'measurements')]
#[ORM\UniqueConstraint(name: 'uq_measurements_sensor_time',
    columns: ['sensor_id', 'measured_at'])]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id = null; // bigint : chaine en PHP

    #[ORM\ManyToOne] // unidirectionnelle : pas de collection cote Sensor
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Sensor $sensor = null;

    #[ORM\Column(type: Types::FLOAT)]
    private float $value = 0.0;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $measuredAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    public function __construct(Sensor $sensor, float $value, \DateTimeImmutable $measuredAt)
    {
        $this->sensor = $sensor;
        $this->value = $value;
        $this->measuredAt = $measuredAt;
        $this->createdAt = new \DateTimeImmutable();
    }


    public function getId(): ?string { return $this->id; }
    public function getSensor(): ?Sensor { return $this->sensor; }
    public function getValue(): float { return $this->value; }
    public function getMeasuredAt(): \DateTimeImmutable { return $this->measuredAt; }
}
