<?php

namespace TobiasDierich\Gauge;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GaugeApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->authorization();
    }

    /**
     * Configure the Gauge authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        Gauge::auth(function ($request) {
            return app()->environment('local') ||
                Gate::check('viewGauge', [$request->user()]);
        });
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

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
