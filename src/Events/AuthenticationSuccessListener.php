<?php

namespace App\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AuthenticationSuccessListener
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $email = $event->getUser()->getUserIdentifier();
        $user = $this->entityManager->getRepository(User::class)->findOneBy(array('email' => $email));

        $event->setData(array_merge(
            $event->getData() ?? [],
            [
                'userId' => $user->getId(),
                'userName' => $user->getUsername(),
                'userEmail' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ]
        ));
    }
}