<?php
// src/EventListener/SessionTimeoutListener.php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class SessionTimeoutListener
{
    use TargetPathTrait;

    private $router;
    private $timeout;

    public function __construct(RouterInterface $router, int $timeout = 28800)
    {
        $this->router = $router;
        $this->timeout = $timeout;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $session = $request->getSession();
        
        // Vérifier si l'utilisateur est connecté
        if ($session->has('_security_main') && $session->has('last_activity')) {
            $lastActivity = $session->get('last_activity');
            
            // Si le timeout est dépassé, déconnecter
            if (time() - $lastActivity > $this->timeout) {
                $session->invalidate();
                $event->setResponse(
                    new RedirectResponse($this->router->generate('app_logout'))
                );
                return;
            }
        }
        
        // Mettre à jour le timestamp de dernière activité
        if ($session->has('_security_main')) {
            $session->set('last_activity', time());
        }
    }
}