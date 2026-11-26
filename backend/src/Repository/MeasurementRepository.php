<?php

// src/Repository/MeasurementRepository.php
namespace App\Repository;

use App\Entity\Device;
use App\Entity\Measurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Measurement>
 */
class MeasurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

    /**
     * @return Measurement[]
     */
    public function findRecentForDevice(Device $device, int $limit = 20): array
    {
        return $this->createQueryBuilder('m')
            ->join('m.sensor', 's')
            ->andWhere('s.device = :device')
            ->setParameter('device', $device) // parametre lie : pas d'injection SQL
            ->orderBy('m.measuredAt', 'DESC')
            ->setMaxResults($limit) // LIMIT
            ->getQuery()
            ->getResult();
    }
}
