<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class XmlLoginAuthenticator extends AbstractAuthenticator
{
    public function supports(Request $request): ?bool
    {
        return 'xml' === $request->getContentType();
    }

    public function authenticate(Request $request): Passport
    {
        $xml = new \SimpleXmlElement($request->getContent());
        if (!isset($xml->auth)) {
            throw new CustomUserMessageAuthenticationException('No <auth> element found.');
        }

        return new Passport(
            new UserBadge($xml->auth->username),
            new PasswordCredentials($xml->auth->password)
        );
    }

    public function onAuthenticationSuccess(
        Request $request, TokenInterface $token, string $firewallName
    ): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(
        Request $request, AuthenticationException $exception
    ): ?Response
    {
        return new Response('<error>'.$exception->getMessageKey().'</error>', 401, [
            'Content-Type' => 'text/xml',
        ]);
    }
}
