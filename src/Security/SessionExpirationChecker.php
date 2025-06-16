<?php
// src/Security/SessionExpirationChecker.php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;

class SessionExpirationChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        $loginTime = $user->getLastLogin(); // Supposons que cette méthode existe
        
        if ($loginTime && $loginTime->diff(new \DateTime())->h >= 6) {
            throw new AccountExpiredException('Session expirée');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        // Rien à faire ici
    }
}