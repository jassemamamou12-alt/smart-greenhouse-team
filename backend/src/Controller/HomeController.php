<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Racine de l'API : sert de health check par défaut pour Render
 * (le service est jugé sain si "/" renvoie 2xx/3xx).
 */
#[Route('/')]
class HomeController
{
    #[Route(name: 'home', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'service' => 'smart-greenhouse-api',
            'status' => 'ok',
            'endpoints' => ['/api/health'],
        ]);
    }
}
