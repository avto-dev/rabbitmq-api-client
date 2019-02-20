<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default RabbitMQ API Client Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the RabbitMQ API connections below you wish
    | to use as your default connection.
    |
    */

    'default' => env('RABBITMQ_API_CONNECTION', 'default-connection'),

    /*
    |--------------------------------------------------------------------------
    | RabbitMQ API Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the RabbitMQ API connections setup for your application.
    |
    */

    'connections' => [

        'default-connection' => [
            'entrypoint' => env('RABBITMQ_API_ENTRYPOINT'),
            'login'      => env('RABBITMQ_API_LOGIN', 'guest'),
            'password'   => env('RABBITMQ_API_PASSWORD', 'guest'),
            'timeout'    => (int) env('RABBITMQ_API_TIMEOUT', 5),
            'user_agent' => env('RABBITMQ_API_USER_AGENT'),
            //'guzzle_config' => [], // Documentation: <http://docs.guzzlephp.org/en/latest/quickstart.html>
        ],

    ],

];
