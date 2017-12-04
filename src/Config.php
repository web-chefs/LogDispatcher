<?php

return [

    /*
    |---------------------------------------------------------------------------
    | Log level indicates minimum log level Monolog should log.
    |
    | - 100: debug
    | - 200: info
    | - 250: notice
    | - 300: warning
    | - 400: error
    | - 500: critical
    | - 550: alert
    | - 600: emergency
    |---------------------------------------------------------------------------
    */

    /*
    |---------------------------------------------------------------------------
    | List of Routes
    |---------------------------------------------------------------------------
    |
    | Auto Make: App::makeWith():
    | Each route needs to have a name as the key that will be used for the
    | logger channel name. The route config must specific a handler, at least
    | one level and a params. If no params is required an empty array is
    | required.
    |
    | Callback Make:
    | For complex handlers a closure can be set for the handler.
    |
    | RouteBuilder:
    | If handler returns a instance of RouteBuilderInterface then the
    |
    */

    'routes' => [
        'test' => [
            'aSync'   => true,
            'handler' => Monolog\Handler\StreamHandler::class,
            'levels'  => [ 'warning' ],
            'params'  => [
                'stream' => storage_path('log/router/test.log'),
            ],
        ],
    ],

];