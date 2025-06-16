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
        if (!$user instanceof \App\Entity\User) {
        return;
        }
        $loginTime = $user->getLastLogin(); // Supposons que cette méthode existe

        if ($loginTime && $loginTime < (new \DateTime())->modify('-2 hours')) {
            // Mettre à jour la session (lastLogin) si l'utilisateur tente de se reconnecter
            // throw new AccountExpiredException('Votre session a expiré. Veuillez vous reconnecter.');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        // Rien à faire ici
    }
}