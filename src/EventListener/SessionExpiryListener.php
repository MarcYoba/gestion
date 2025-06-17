<?php 
namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SessionExpiryListener
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $session = $request->getSession();
        
        // Vérifier si la route actuelle est déjà app_login
        if ($request->attributes->get('_route') === 'app_login') {
            return;
        }

        // Vérifier l'expiration du remember me
        if ($this->tokenStorage->getToken() === null && $session->has('_security.last_used')) {
            $lastUsed = $session->get('_security.last_used');
            
            if (time() - $lastUsed > 7200) {
                $session->invalidate();
                $response = new RedirectResponse($this->urlGenerator->generate('app_login'));
                $event->setResponse($response);
            }
        }
    }
}