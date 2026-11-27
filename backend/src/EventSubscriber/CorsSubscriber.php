<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Autorise le frontend (autre domaine) a appeler l'API.
 *
 * Sans ce subscriber, le navigateur bloque les requetes de l'app Flutter
 * (erreur "CORS policy: No 'Access-Control-Allow-Origin' header").
 *
 * Les origines autorisees sont lues dans CORS_ALLOWED_ORIGINS (variable
 * d'environnement), separees par des virgules. Si la variable est absente,
 * on autorise tout le monde avec '*' — acceptable tant qu'aucun secret
 * sensible n'est expose publiquement.
 */
final class CorsSubscriber implements EventSubscriberInterface
{
    /**
     * Reponse immediate a la requete preflight OPTIONS, envoyee avant
     * le routage (priorite 9999) pour eviter une erreur 404 sur une
     * route qui n'accepte pas OPTIONS.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || !$event->getRequest()->isMethod('OPTIONS')) {
            return;
        }

        $response = new Response('', 204);
        $this->applyHeaders($event->getRequest()->headers->get('Origin'), $response);

        $event->setResponse($response);
    }

    /**
     * Ajoute les headers CORS a toutes les reponses reelles (GET, POST...).
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->applyHeaders(
            $event->getRequest()->headers->get('Origin'),
            $event->getResponse(),
        );
    }

    private function applyHeaders(?string $origin, Response $response): void
    {
        $allowed = $this->allowedOrigins();

        // Avec '*', on ne peut pas combiner avec Allow-Credentials: true.
        if ($allowed === ['*']) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
        } elseif ($origin !== null && in_array($origin, $allowed, true)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Vary', 'Origin');
        } elseif ($origin !== null) {
            // Origine inconnue : on n'ecrit pas de header, le navigateur bloquera.
            return;
        } else {
            return;
        }

        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept');
        $response->headers->set('Access-Control-Expose-Headers', 'Content-Length, Content-Range');
        $response->headers->set('Access-Control-Max-Age', '3600');
    }

    /**
     * @return list<string>
     */
    private function allowedOrigins(): array
    {
        $raw = getenv('CORS_ALLOWED_ORIGINS');
        if ($raw === false || trim($raw) === '') {
            return ['*'];
        }

        $origins = array_filter(array_map('trim', explode(',', $raw)));

        return $origins === [] ? ['*'] : array_values($origins);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // 9999 = avant le routage, pour intercepter OPTIONS.
            KernelEvents::REQUEST  => ['onKernelRequest', 9999],
            KernelEvents::RESPONSE => ['onKernelResponse', 0],
        ];
    }
}
