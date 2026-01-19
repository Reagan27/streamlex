<?php

use Monolog\Handler\NullHandler;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    */
    'default' => 'null',
    'enabled' => false,

    /*
    |--------------------------------------------------------------------------
    | Log Settings
    |--------------------------------------------------------------------------
    */
    'path' => '/dev/null',
    'level' => 'emergency',
    
    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    */
    'deprecations' => [
        'channel' => 'null',
        'trace' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    */
    'channels' => [
        'stack' => [
            'driver' => 'null',
            'channels' => ['null'],
            'ignore_exceptions' => true,
        ],
        
        'single' => [
            'driver' => 'null',
            'handler' => NullHandler::class,
        ],

        'daily' => [
            'driver' => 'null',
            'handler' => NullHandler::class,
        ],

        'null' => [
            'driver' => 'null',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'driver' => 'null',
            'handler' => NullHandler::class,
            'path' => '/dev/null',
        ],
    ],
];
