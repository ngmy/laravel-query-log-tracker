<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker;

use Event;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class QueryLogTrackerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $configPath = __DIR__ . '/../config/ngmy-query-log-tracker.php';
        $this->mergeConfigFrom($configPath, 'ngmy-query-log-tracker');
        $this->publishes([$configPath => \config_path('ngmy-query-log-tracker.php')], 'ngmy-query-log-tracker');

        Event::listen(QueryExecuted::class, QueryExecutedListener::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(QueryLogManager::class, function (Application $app): QueryLogManager {
            return new QueryLogManager(
                $app,
                $app->make(LoggerFactory::class),
                $app->make(QueryFactory::class)
            );
        });
    }
}
