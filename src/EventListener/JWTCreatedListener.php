<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    /**
     * @param JWTCreatedEvent $event
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();

        $payload = [
            'userId' => $user->getId(),
            'email' => $user->getEmail(),
            'fullname' => sprintf('%s %s', $user->getFirstName(), $user->getLastName()),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
        ];

        $event->setData($payload);
    }
}
