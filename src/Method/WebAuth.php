<?php

declare(strict_types=1);

namespace Klsoft\Yii3User\Method;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Klsoft\Yii3Auth\AuthenticationMethodInterface;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\Route;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\User\CurrentUser;

/**
 * Implementation of the `AuthenticationMethodInterface` for authenticating users in the web applications.
 */
final class WebAuth implements AuthenticationMethodInterface
{
    private string $authRouteName = 'login';
    private string $redirectQueryParameterName = 'redirect_uri';

    public function __construct(
        private readonly CurrentUser              $currentUser,
        private readonly UrlGeneratorInterface    $urlGenerator,
        private readonly ResponseFactoryInterface $responseFactory)
    {
    }

    public function authenticate(ServerRequestInterface $request): ?IdentityInterface
    {
        if ($this->currentUser->isGuest()) {
            return null;
        }

        return $this->currentUser->getIdentity();
    }

    /**
     * {@inheritDoc}
     *
     * Creates a new instance of the response and adds a `Location` header with a temporary redirect.
     */
    public function challenge(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $queryParameters = [];
        if ($this->redirectQueryParameterName !== '') {
            $queryParameters[$this->redirectQueryParameterName] = $request->getUri()->__toString();
        }

        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader('Location', $this->urlGenerator->generate($this->authRouteName, [], $queryParameters));
    }

    /**
     * Returns a new instance with the specified route name.
     *
     * @param string $routeName The authentication route name.
     *
     * @return self
     */
    public function withAuthRouteName(string $routeName): self
    {
        $new = clone $this;
        $new->authRouteName = $routeName;
        return $new;
    }

    /**
     * Returns a new instance with the specified redirect query parameter name.
     *
     * @param string $redirectQueryParameterName The redirect query parameter name.
     *
     * @return self
     */
    public function withRedirectQueryParameterName(string $redirectQueryParameterName): self
    {
        $new = clone $this;
        $new->redirectQueryParameterName = $redirectQueryParameterName;
        return $new;
    }
}
