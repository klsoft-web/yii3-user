# YII3-USER

The package provides an implementation of the AuthenticationMethodInterface that allows redirection to a protected route after successful authentication. It also releases the CookieLogin class with the extends method withCookieSecure(), which allows you to configure CookieLogin for an HTTP connection.

## Requirement

 - PHP 8.1 or higher.

## Installation

```bash
composer require klsoft/yii3-user
```

## How to use

First, protect the route:

```php
use Klsoft\Yii3Auth\Middleware\Authentication;
 
Route::post('/create')
        ->middleware(Authentication::class)
        ->action([SiteController::class, 'create'])
        ->name('site/create')
```

Then, within the login action:
```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\User\CurrentUser;

public function login(ServerRequestInterface $request): ResponseInterface
{
    // ...
    //After a successful authentication $this->currentUser->login($identity)
    $queryParams = $request->getQueryParams();
    if(isset($queryParams['redirect_uri'])) {
        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader('Location', $queryParams['redirect_uri']);
    }
}
```

Example configuration for CookieLogin for an HTTP connection:

```php
use DateInterval;
use Klsoft\Yii3User\Login\Cookie\CookieLogin;

return [
    // ...
    CookieLogin::class => [
        '__construct()' => [
            'duration' => $params['yiisoft/user']['cookieLogin']['duration'] !== null ?
                new DateInterval($params['yiisoft/user']['cookieLogin']['duration']) :
                null,
        ],
        'withCookieSecure()' => [false]
    ],
];
```
