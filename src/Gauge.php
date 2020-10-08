<?php

namespace TobiasDierich\Gauge;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Testing\Fakes\EventFake;
use Throwable;
use TobiasDierich\Gauge\Contracts\EntriesRepository;

class Gauge
{
    use AuthorizesRequests,
        ListensForStorageOpportunities,
        RegistersWatchers;

    /**
     * The list of queued entries to be stored.
     *
     * @var array
     */
    public static $entriesQueue = [];

    /**
     * The list of hidden request headers.
     *
     * @var array
     */
    public static $hiddenRequestHeaders = [
        'authorization',
        'php-auth-pw',
    ];

    /**
     * The list of hidden request parameters.
     *
     * @var array
     */
    public static $hiddenRequestParameters = [
        'password',
        'password_confirmation',
    ];

    /**
     * The list of hidden response parameters.
     *
     * @var array
     */
    public static $hiddenResponseParameters = [];

    /**
     * Indicates if Gauge should record entries.
     *
     * @var bool
     */
    public static $shouldRecord = false;

    /**
     * Indicates if Gauge migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * Register the Gauge watchers and start recording if necessary.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    public static function start($app)
    {
        if (!config('gauge.enabled') || $app->runningInConsole()) {
            return;
        }

        static::registerWatchers($app);

        if (static::handlingApprovedRequest($app)) {
            static::startRecording();
        }
    }

    /**
     * Determine if the application is handling an approved request.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return bool
     */
    protected static function handlingApprovedRequest($app)
    {
        return !$app->runningInConsole() && !$app['request']->is(
                array_merge([
                    config('gauge.path') . '*',
                    'gauge-api*',
                    'vendor/gauge*',
                ], config('gauge.ignore_paths', []))
            );
    }

    /**
     * Start recording entries.
     *
     * @return void
     */
    public static function startRecording()
    {
        static::$shouldRecord = !cache('gauge:pause-recording');
    }

    /**
     * Stop recording entries.
     *
     * @return void
     */
    public static function stopRecording()
    {
        static::$shouldRecord = false;
    }

    /**
     * Execute the given callback without recording Gauge entries.
     *
     * @param callable $callback
     *
     * @return void
     */
    public static function withoutRecording($callback)
    {
        $shouldRecord = static::$shouldRecord;

        static::$shouldRecord = false;

        call_user_func($callback);

        static::$shouldRecord = $shouldRecord;
    }

    /**
     * Determine if Gauge is recording.
     *
     * @return bool
     */
    public static function isRecording()
    {
        return static::$shouldRecord && !app('events') instanceof EventFake;
    }

    /**
     * Record the given entry.
     *
     * @param string                             $type
     * @param \TobiasDierich\Gauge\IncomingEntry $entry
     *
     * @return void
     */
    protected static function record(string $type, IncomingEntry $entry)
    {
        if (!static::isRecording()) {
            return;
        }

        $entry->type($type);

        try {
            if (Auth::hasResolvedGuards() && Auth::hasUser()) {
                $entry->user(Auth::user());
            }
        } catch (Throwable $e) {
            // Do nothing.
        }

        static::withoutRecording(function () use ($entry) {
            static::$entriesQueue[] = $entry;
        });
    }

    /**
     * Record the given entry.
     *
     * @param \TobiasDierich\Gauge\IncomingEntry $entry
     *
     * @return void
     */
    public static function recordQuery(IncomingEntry $entry)
    {
        static::record(EntryType::QUERY, $entry);
    }

    /**
     * Record the given entry.
     *
     * @param \TobiasDierich\Gauge\IncomingEntry $entry
     *
     * @return void
     */
    public static function recordRequest(IncomingEntry $entry)
    {
        static::record(EntryType::REQUEST, $entry);
    }

    /**
     * Flush all entries in the queue.
     *
     * @return static
     */
    public static function flushEntries()
    {
        static::$entriesQueue = [];

        return new static;
    }

    /**
     * Store the queued entries and flush the queue.
     *
     * @param \TobiasDierich\Gauge\Contracts\EntriesRepository $storage
     *
     * @return void
     */
    public static function store(EntriesRepository $storage)
    {
        if (empty(static::$entriesQueue)) {
            return;
        }

        static::withoutRecording(function () use ($storage) {
            try {
                $storage->store(static::collectEntries());
            } catch (Exception $e) {
                app(ExceptionHandler::class)->report($e);
            }
        });

        static::$entriesQueue = [];
    }

    /**
     * Collect the entries for storage.
     *
     * @return \Illuminate\Support\Collection
     */
    protected static function collectEntries()
    {
        return collect(static::$entriesQueue);
    }

    /**
     * Hide the given request header.
     *
     * @param array $headers
     *
     * @return static
     */
    public static function hideRequestHeaders(array $headers)
    {
        static::$hiddenRequestHeaders = array_merge(
            static::$hiddenRequestHeaders,
            $headers
        );

        return new static;
    }

    /**
     * Hide the given request parameters.
     *
     * @param array $attributes
     *
     * @return static
     */
    public static function hideRequestParameters(array $attributes)
    {
        static::$hiddenRequestParameters = array_merge(
            static::$hiddenRequestParameters,
            $attributes
        );

        return new static;
    }

    /**
     * Hide the given response parameters.
     *
     * @param array $attributes
     *
     * @return static
     */
    public static function hideResponseParameters(array $attributes)
    {
        static::$hiddenResponseParameters = array_merge(
            static::$hiddenResponseParameters,
            $attributes
        );

        return new static;
    }

    /**
     * Configure Gauge to not register its migrations.
     *
     * @return static
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;

        return new static;
    }

    /**
     * Get the default JavaScript variables for Gauge.
     *
     * @return array
     */
    public static function scriptVariables()
    {
        return [
            'path' => config('gauge.path'),
        ];
    }

    /**
     * Check if the given route is currently active.
     *
     * @param string $route
     *
     * @return bool
     */
    public static function isActiveRouteGroup(string $route)
    {
        $currentRoute = explode('.', optional(request()->route())->getName() ?: '/');

        if (count($currentRoute) < 2) {
            return false;
        }

        return $currentRoute[1] === $route;
    }
}
