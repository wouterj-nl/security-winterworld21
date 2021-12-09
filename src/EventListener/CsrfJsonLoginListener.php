<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class CsrfJsonLoginListener
{
    #[AsEventListener(
        event: CheckPassportEvent::class,
        priority: 2048,
        dispatcher: 'security.event_dispatcher.api'
    )]
    public function onCheckPassportEvent(CheckPassportEvent $event)
    {
        if (!$event->getAuthenticator() instanceof JsonLoginAuthenticator) {
            return;
        }

        $passport = $event->getPassport();
        $passport->addBadge(
            new CsrfTokenBadge(
                'authenticate',
                $this->requestStack->getMainRequest()->get('csrf_token'),
            )
        );
    }
}
