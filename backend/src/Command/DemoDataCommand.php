<?php

// src/Command/DemoDataCommand.php
// Code 12.7 - Chapitre 12, TP 17 - Smart Greenhouse
namespace App\Command;

use App\Entity\Device;
use App\Entity\Sensor;
use App\Entity\User;
use App\Service\DeviceTokenHasher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:demo-data', description: 'Cree un utilisateur et un appareil de demo')]
final class DemoDataCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly DeviceTokenHasher $hasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $user = (new User())
            ->setEmail('amel@example.com')
            ->setFullName('Amel K.')
            ->setPasswordHash('hash-de-test'); // le vrai hachage viendra au chapitre 14

        $token = $this->hasher->generateToken();
        $device = (new Device())
            ->setUser($user)
            ->setName('Serre A')
            ->setLocation('Tunnel 1')
            ->setApiTokenHash($this->hasher->hash($token));

        $types = [['temperature', '°C'], ['air_humidity', '%'],
            ['light', '%'], ['soil_moisture', '%']];
        foreach ($types as [$type, $unit]) {
            $device->addSensor((new Sensor())->setType($type)->setUnit($unit));
        }

        $this->em->persist($user);
        $this->em->persist($device); // les capteurs suivent (cascade persist)
        $this->em->flush(); // un seul appel : une transaction

        $io->success(sprintf('Appareil %d cree avec %d capteurs.',
            $device->getId(), $device->getSensors()->count()));
        $io->warning('Jeton de l\'appareil (affiche une seule fois, a noter) : ' . $token);

        return Command::SUCCESS;
    }
}
