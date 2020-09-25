<?php

namespace TobiasDierich\Gauge;

trait AuthorizesRequests
{
    /**
     * The callback that should be used to authenticate Gauge users.
     *
     * @var \Closure
     */
    public static $authUsing;

    /**
     * Register the Gauge authentication callback.
     *
     * @param \Closure $callback
     *
     * @return static
     */
    public static function auth($callback)
    {
        static::$authUsing = $callback;

        return new static;
    }

    /**
     * Determine if the given request can access the Gauge dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public static function check($request)
    {
        return (static::$authUsing ?: function () {
            return app()->environment('local');
        })($request);
    }
}
