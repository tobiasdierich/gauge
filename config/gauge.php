<?php

use TobiasDierich\Gauge\Http\Middleware\Authorize;
use TobiasDierich\Gauge\Watchers;

return [

    /*
    |--------------------------------------------------------------------------
    | Gauge Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where Gauge will be accessible from. If the
    | setting is null, Gauge will reside under the same domain as the
    | application. Otherwise, this value will be used as the subdomain.
    |
    */

    'domain' => env('GAUGE_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Gauge Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Gauge will be accessible from. Feel free
    | to change this path to anything you like. Note that the URI will not
    | affect the paths of its internal API that aren't exposed to users.
    |
    */

    'path' => env('GAUGE_PATH', 'gauge'),

    /*
    |--------------------------------------------------------------------------
    | Gauge Storage Driver
    |--------------------------------------------------------------------------
    |
    | This configuration options determines the storage driver that will
    | be used to store Gauge's data. In addition, you may set any
    | custom options as needed by the particular driver you choose.
    |
    */

    'driver' => env('GAUGE_DRIVER', 'database'),

    'storage' => [
        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'chunk'      => 1000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Gauge Master Switch
    |--------------------------------------------------------------------------
    |
    | This option may be used to disable all Gauge watchers regardless
    | of their individual configuration, which simply provides a single
    | and convenient way to enable or disable Gauge data storage.
    |
    */

    'enabled' => env('GAUGE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Gauge Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Gauge route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */

    'middleware' => [
        'web',
        Authorize::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Ignored Paths & Commands
    |--------------------------------------------------------------------------
    |
    | The following array lists the URI paths and Artisan commands that will
    | not be watched by Gauge. In addition to this list, some Laravel
    | commands, like migrations and queue commands, are always ignored.
    |
    */

    'ignore_paths' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Gauge Watchers
    |--------------------------------------------------------------------------
    |
    | The following array lists the "watchers" that will be registered with
    | Gauge. The watchers gather the application's profile data when
    | a request or task is executed. Feel free to customize this list.
    |
    */

    'watchers' => [
        Watchers\QueryWatcher::class => [
            'enabled'         => env('GAUGE_QUERY_WATCHER', true),
            'ignore_packages' => true,
            'slow'            => 100,
        ],

        Watchers\RequestWatcher::class => [
            'enabled'    => env('GAUGE_REQUEST_WATCHER', true),
            'size_limit' => env('GAUGE_RESPONSE_SIZE_LIMIT', 64),
        ],
    ],
];
