<?php

// src/Service/DeviceTokenHasher.php
// Code 12.2 - Chapitre 12 - Smart Greenhouse
namespace App\Service;

final class DeviceTokenHasher
{
    /** Empreinte SHA-256 en hexadecimal (64 caracteres), identique a celle de la base. */
    public function hash(string $token): string
    {
        return hash('sha256', $token);
    }

    /** Jeton aleatoire de 64 caracteres hexa (256 bits), remis une seule fois a l'appareil. */
    public function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
