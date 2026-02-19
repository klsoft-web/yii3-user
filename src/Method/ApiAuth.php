<?php

declare(strict_types=1);

namespace Klsoft\Yii3User\Method;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Klsoft\Yii3Auth\AuthenticationMethodInterface;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\User\CurrentUser;

/**
 * Implementation of the `AuthenticationMethodInterface` for authenticating users in the API clients.
 */
final class ApiAuth implements AuthenticationMethodInterface
{
    public function __construct(private readonly CurrentUser $currentUser)
    {
    }

    public function authenticate(ServerRequestInterface $request): ?IdentityInterface
    {
        if ($this->currentUser->isGuest()) {
            return null;
        }

        return $this->currentUser->getIdentity();
    }

    public function challenge(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $response;
    }
}
