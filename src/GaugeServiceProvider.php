<?php

namespace TobiasDierich\Gauge;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use TobiasDierich\Gauge\Contracts\ClearableRepository;
use TobiasDierich\Gauge\Contracts\EntriesRepository;
use TobiasDierich\Gauge\Contracts\PrunableRepository;
use TobiasDierich\Gauge\Storage\DatabaseEntriesRepository;
use TobiasDierich\Gauge\Storage\MetricsRepository;

class GaugeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if (!config('gauge.enabled')) {
            return;
        }

        Route::middlewareGroup('gauge', config('gauge.middleware', []));

        $this->registerRoutes();
        $this->registerMigrations();
        $this->registerPublishing();

        Gauge::start($this->app);
        Gauge::listenForStorageOpportunities($this->app);

        $this->loadViewsFrom(
            __DIR__.'/../resources/views', 'gauge'
        );
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        });
    }

    /**
     * Get the Gauge route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration()
    {
        return [
            'domain'     => config('gauge.domain', null),
            'namespace'  => 'TobiasDierich\Gauge\Http\Controllers',
            'prefix'     => config('gauge.path'),
            'middleware' => 'gauge',
        ];
    }

    /**
     * Register the package's migrations.
     *
     * @return void
     */
    private function registerMigrations()
    {
        if ($this->app->runningInConsole() && $this->shouldMigrate()) {
            $this->loadMigrationsFrom(__DIR__ . '/Storage/migrations');
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Storage/migrations' => database_path('migrations'),
            ], 'gauge-migrations');

            $this->publishes([
                __DIR__ . '/../config/gauge.php' => config_path('gauge.php'),
            ], 'gauge-config');

            $this->publishes([
                __DIR__ . '/../stubs/GaugeServiceProvider.stub' => app_path('Providers/GaugeServiceProvider.php'),
            ], 'gauge-provider');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/gauge.php', 'gauge'
        );

        $this->registerStorageDriver();

        $this->commands([
            Console\ClearCommand::class,
            Console\InstallCommand::class,
            Console\PruneCommand::class,
            Console\PublishCommand::class,
        ]);
    }

    /**
     * Register the package storage driver.
     *
     * @return void
     */
    protected function registerStorageDriver()
    {
        $driver = config('gauge.driver');

        if (method_exists($this, $method = 'register' . ucfirst($driver) . 'Driver')) {
            $this->$method();
        }
    }

    /**
     * Register the package database storage driver.
     *
     * @return void
     */
    protected function registerDatabaseDriver()
    {
        $this->app->singleton(
            EntriesRepository::class, DatabaseEntriesRepository::class
        );

        $this->app->singleton(
            ClearableRepository::class, DatabaseEntriesRepository::class
        );

        $this->app->singleton(
            PrunableRepository::class, DatabaseEntriesRepository::class
        );

        $this->app->when(DatabaseEntriesRepository::class)
            ->needs('$connection')
            ->give(config('gauge.storage.database.connection'));

        $this->app->when(DatabaseEntriesRepository::class)
            ->needs('$chunkSize')
            ->give(config('gauge.storage.database.chunk'));

        $this->app->singleton(MetricsRepository::class);

        $this->app->when(MetricsRepository::class)
            ->needs('$connection')
            ->give(config('gauge.storage.database.connection'));
    }

    /**
     * Determine if we should register the migrations.
     *
     * @return bool
     */
    protected function shouldMigrate()
    {
        return Gauge::$runsMigrations && config('gauge.driver') === 'database';
    }
}
