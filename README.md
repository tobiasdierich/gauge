## Gauge - Laravel Application Performance Monitoring

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tobiasdierich/gauge.svg?style=flat-square)](https://packagist.org/packages/tobiasdierich/gauge)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/tobiasdierich/gauge.svg?style=flat-square)](https://packagist.org/packages/tobiasdierich/gauge)

Gauge is an easy to use package to monitor the performance of your Laravel applications. Gauge in based on [Laravel Telescope](https://github.com/laravel/telescope).

## Requirements

Gauge works with the latest Laravel version starting at v6. The package has been tested with the latest versions of MySQL (v8) and Postgres (v13). Other version might work as well but are not supported officially.

## Installation

Install the package via composer:
``` bash
composer require tobiasdierich/gauge
```

After installing Gauge, publish the assets and run the migrations be executing the following commands:
```bash
php artisan gauge:install
php artisan migrate
```

Once done, open the Gauge dashboard at `/gauge`.

### Data Pruning

Since gauge collects a bunch of data when enabled, you have to make sure to regularly prune old data from the database.
Gauge comes with a prune command which by default removes all database entries older than a week. Setup your scheduler
to prune old entries daily like this:

```php
$schedule->command('gauge:prune')->daily();
```

### Dashboard Authorization

By default, the dashboard exposed at `/gauge` is only accessible in local environments. If you want to access the
dashboard in production environments, you can modify the gate function inside your `app/providers/GaugeServiceProvider.php`:

```php
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
                'foo@bar.com'
            ]);
        });
    }
```

### Configuration

The main configuration file is located at `config/gauge.php`. Use this file to disable Gauge, configure the watchers, etc.

## Credits

- [Tobias Dierich](https://twitter.com/tobiasdierich)
- [Laravel Telescope Contributors](https://github.com/laravel/telescope/contributors)

## License

The MIT License (MIT). Please check the [License File](LICENSE.md) for more information.
