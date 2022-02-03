<?php

declare(strict_types=1);

use DI\Container;
use Monolog\Logger;

return function (Container $container) {
    $container->set('settings', function () {
        return [
            'name' => 'Example Slim Application',
            'displayErrorDetails' => true,
            'logErrorDetails' => true,
            'logErrors' => true,
            'logger' => [
                'name' => 'slim-app',
                'path' => __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
        ];
    });
};
