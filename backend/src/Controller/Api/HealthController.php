<?php
// src/Controller/Api/HealthController.php
namespace App\Controller\Api;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class HealthController extends AbstractController
{
    #[Route('/api/health', name: 'api_health', methods: ['GET'])]
    #[Route('/health', name: 'health_root', methods: ['GET'])]
    public function __invoke(Connection $connection): JsonResponse
    {
        try {
            $connection->executeQuery('SELECT 1'); // la base repond-elle ?
            $database = 'up';
            $code = 200;
        } catch (\Throwable) {
            $database = 'down';
            $code = 503;
        }

        return $this->json([
            'status'   => $code === 200 ? 'ok' : 'degraded',
            'database' => $database,
            'time'     => (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
                            ->format('Y-m-d\TH:i:s\Z'),
        ], $code);
    }
}
