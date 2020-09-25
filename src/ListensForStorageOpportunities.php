<?php

namespace TobiasDierich\Gauge;

use TobiasDierich\Gauge\Contracts\EntriesRepository;

trait ListensForStorageOpportunities
{
    /**
     * Register listeners that store the recorded Gauge entries.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    public static function listenForStorageOpportunities($app)
    {
        static::storeEntriesBeforeTermination($app);
    }

    /**
     * Store the entries in queue before the application termination.
     *
     * This handles storing entries for HTTP requests and Artisan commands.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected static function storeEntriesBeforeTermination($app)
    {
        $app->terminating(function () use ($app) {
            static::store($app[EntriesRepository::class]);
        });
    }
}
