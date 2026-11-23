<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Endpoint de santé : sert de health check pour Render et de preuve
 * que l'API répond. À étendre par l'équipe backend.
 */
#[Route('/api/health')]
class HealthController
{
    #[Route('', name: 'api_health', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'status' => 'ok',
            'service' => 'smart-greenhouse-api',
            'environment' => $_SERVER['APP_ENV'] ?? 'unknown',
            'timestamp' => (new \DateTimeImmutable())->format(\DateTimeInterface::ATOM),
        ]);
    }
}
