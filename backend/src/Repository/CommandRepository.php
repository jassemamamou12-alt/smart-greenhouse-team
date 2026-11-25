<?php

// src/Repository/CommandRepository.php
// Exercice 4 - Chapitre 12 - Smart Greenhouse
namespace App\Repository;

use App\Entity\Command;
use App\Entity\Device;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Command>
 */
class CommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Command::class);
    }

    /**
     * @return Command[]
     */
    public function findPendingForDevice(Device $device): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.device = :device')
            ->andWhere('c.status = :status')
            ->setParameter('device', $device)
            ->setParameter('status', 'pending')
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
