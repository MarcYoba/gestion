<?php
namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;

class SessionExpirationChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        $loginTime = $user->getLastLogin(); // Supposons que cette méthode existe

        if ($loginTime && $loginTime < new \DateTime('-2 hours')) {
            throw new AccountExpiredException('Session expirée');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        // Rien à faire ici
    }
}