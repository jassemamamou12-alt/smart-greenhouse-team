<?php

// src/Dto/MeasurementBatchDto.php
// Code 12.8 - Chapitre 12, TP 18 - Smart Greenhouse
namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class MeasurementBatchDto
{
    /**
     * @param ReadingDto[] $readings
     */
    public function __construct(
        #[Assert\Positive]
        public int $deviceId = 0,
        #[Assert\Count(min: 1, max: 10)]
        #[Assert\Valid]
        public array $readings = [],
    ) {
    }
}
