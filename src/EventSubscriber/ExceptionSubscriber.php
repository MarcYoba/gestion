<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Psr\Log\LoggerInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private RouterInterface $router;
    private LoggerInterface $logger;
    private RequestStack $requestStack;

    public function __construct(
        RouterInterface $router, 
        LoggerInterface $logger,
        RequestStack $requestStack
    ) {
        $this->router = $router;
        $this->logger = $logger;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['logException', 10],
                ['processException', 5],
            ],
        ];
    }

    public function logException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        
        $this->logger->error('Exception interceptée: ' . $exception->getMessage(), [
            'fichier' => $exception->getFile(),
            'ligne' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);
    }

    public function processException(ExceptionEvent $event): void
    {
        // Ne traiter qu'en production
        if ($_ENV['APP_ENV'] !== 'prod') {
            return;
        }

        $exception = $event->getThrowable();
        $request = $event->getRequest();

        // Éviter les boucles si la page d'erreur génère elle-même une exception
        if ($request->get('_route') === 'app_error') {
            return;
        }

        // Message flash pour l'utilisateur via RequestStack

        // Redirection vers la page d'erreur
        $response = new RedirectResponse($this->router->generate('app_error'));
        $event->setResponse($response);
    }
}