<?php

declare(strict_types=1);

use Klsoft\Yii3Auth\AuthenticationMethodInterface;
use Klsoft\Yii3User\Method\WebAuth;

/** @var array $params */

return [
    WebAuth::class => [
        'withAuthRouteName()' => [$params['yiisoft/user']['authRouteName']],
        'withRedirectQueryParameterName()' => [$params['yiisoft/user']['redirectQueryParameterName']],
    ],

    AuthenticationMethodInterface::class => WebAuth::class,
];
