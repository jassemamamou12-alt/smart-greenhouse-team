<?php

// src/Dto/ReadingDto.php
// Code 12.8 - Chapitre 12, TP 18 - Smart Greenhouse
namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class ReadingDto
{
    private const RANGES = [
        'temperature' => [-10, 60],
        'air_humidity' => [0, 100],
        'light' => [0, 100],
        'soil_moisture' => [0, 100],
    ];

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(choices: ['temperature', 'air_humidity', 'light', 'soil_moisture'])]
        public string $sensor = '',
        #[Assert\NotNull]
        public ?float $value = null,
    ) {
    }

    #[Assert\Callback]
    public function validateRange(ExecutionContextInterface $context): void
    {
        if ($this->value === null || !isset(self::RANGES[$this->sensor])) {
            return; // deja signale par les autres contraintes
        }
        [$min, $max] = self::RANGES[$this->sensor];
        if ($this->value < $min || $this->value > $max) {
            $context->buildViolation('La valeur doit etre comprise entre {{ min }} et {{ max }}.')
                ->setParameter('{{ min }}', (string) $min)
                ->setParameter('{{ max }}', (string) $max)
                ->atPath('value')
                ->addViolation();
        }
    }
}
