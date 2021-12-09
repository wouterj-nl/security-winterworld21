<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginAttemptListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[AsEventListener(event: LoginSuccessEvent::class, dispatcher: 'security.event_dispatcher.main')]
    #[AsEventListener(event: LoginFailureEvent::class, dispatcher: 'security.event_dispatcher.main')]
    public function onLoginAttempt(LoginSuccessEvent|LoginFailureEvent $event)
    {
        $userIdentifier = $event->getPassport()->getBadge(UserBadge::class)->getUserIdentifier();
        $clientIp = $event->getRequest()->getClientIp();
        $success = $event instanceof LoginSuccessEvent;

        $this->logger->info('Login attempt for "{user}"', [
            'user' => $userIdentifier,
            'ip' => $clientIp,
            'success' => $success,
            'error' => !$success ? $event->getException()->getMessage() : null,
        ]);
    }
}
