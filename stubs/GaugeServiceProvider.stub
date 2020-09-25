<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use TobiasDierich\Gauge\IncomingEntry;
use TobiasDierich\Gauge\Gauge;
use TobiasDierich\Gauge\GaugeApplicationServiceProvider;

class GaugeServiceProvider extends GaugeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->hideSensitiveRequestDetails();
    }

    /**
     * Prevent sensitive request details from being logged by Gauge.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Gauge::hideRequestParameters(['_token']);

        Gauge::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Gauge gate.
     *
     * This gate determines who can access Gauge in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewGauge', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }
}
