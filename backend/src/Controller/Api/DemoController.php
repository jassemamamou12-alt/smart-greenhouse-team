<?php

// src/Controller/Api/DemoController.php
// Code 12.9 - Chapitre 12, TP 18 - Smart Greenhouse
namespace App\Controller\Api;

use App\Dto\MeasurementBatchDto;
use App\Entity\Device;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class DemoController extends AbstractController
{
    #[Route('/api/demo/readings', name: 'api_demo_readings', methods: ['POST'])]
    public function readings(#[MapRequestPayload] MeasurementBatchDto $batch): JsonResponse
    {
        // arrive ici seulement si le JSON est lisible et valide
        return $this->json([
            'deviceId' => $batch->deviceId,
            'accepted' => count($batch->readings),
        ]);
    }

    #[Route('/api/demo/devices/{id}', name: 'api_demo_device', methods: ['GET'],
        requirements: ['id' => '\d+'])]
    public function device(#[MapEntity] Device $device): JsonResponse
    {
        return $this->json($device, 200, [], ['groups' => ['device:read']]);
    }
}
