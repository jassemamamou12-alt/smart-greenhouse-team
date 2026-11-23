<?php

/**
 * Routeur pour le serveur PHP intégré (php -S) utilisé en local et sur Render.
 *
 * - les fichiers statiques réellement présents sont servis tels quels
 *   (retourner false demande au serveur de servir le fichier)
 * - tout le reste est délégué au front controller Symfony (index.php)
 */
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if ($path !== '/' && is_file(__DIR__.$path)) {
    return false;
}

require __DIR__.'/index.php';
