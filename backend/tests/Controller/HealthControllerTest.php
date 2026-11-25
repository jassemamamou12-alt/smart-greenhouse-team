<?php

// tests/Controller/HealthControllerTest.php
// Code 12.10 - Chapitre 12 - Smart Greenhouse
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class HealthControllerTest extends WebTestCase
{
    public function testHealthReturnsOk(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/health');

        self::assertResponseIsSuccessful(); // 2xx
        self::assertResponseHeaderSame('Content-Type', 'application/json');

        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertSame('ok', $data['status']);
        self::assertSame('up', $data['database']);
    }
}
