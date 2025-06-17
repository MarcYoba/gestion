<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ActivitySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest'],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        // Ne pas tracker sur la page de login
        if ($request->attributes->get('_route') === 'app_login') {
            return;
        }

        if ($request->hasSession() && $this->isUserActivity($request)) {
            $request->getSession()->set('_security.last_used', time());
        }
    }

    private function isUserActivity($request): bool
    {
        // Exclure les requêtes AJAX et les assets
        return !$request->isXmlHttpRequest() 
            && !str_starts_with($request->getPathInfo(), '/_profiler')
            && !str_starts_with($request->getPathInfo(), '/_wdt');
    }
}