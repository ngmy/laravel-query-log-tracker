<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker\Tests;

use File;
use Ngmy\LaravelQueryLogTracker\{
    QueryLogTrackerFacade,
    QueryLogTrackerServiceProvider,
};
use Orchestra\Testbench\Database\MigrateProcessor;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->clearAllLogs();

        parent::tearDown();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array<int, string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            QueryLogTrackerServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array<string, string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'QueryLogTracker' => QueryLogTrackerFacade::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app->make('config')->set('database.default', 'mysql');
    }

    /**
     * Migrate Laravel's default migrations.
     *
     * @param array<int, string>|string $database
     * @return void
     * @see \Orchestra\Testbench\Concerns\WithLaravelMigrations::loadLaravelMigrations()
     */
    protected function loadLaravelMigrations($database = []): void
    {
        $options = \is_array($database) ? $database : ['--database' => $database];

        $options['--path'] = 'migrations';

        $migrator = new MigrateProcessor($this, $options);
        QueryLogTrackerFacade::disable(function () use ($migrator): void {
            $migrator->up();
        });

        $this->resetApplicationArtisanCommands($this->app);

        $this->beforeApplicationDestroyed(static function () use ($migrator): void {
            QueryLogTrackerFacade::disable(function () use ($migrator): void {
                $migrator->rollback();
            });
        });
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getLog(string $name = 'laravel.log'): string
    {
        $path = \storage_path('logs/' . $name);
        return File::exists($path) ? File::get($path) : '';
    }

    /**
     * @return void
     */
    protected function clearAllLogs(): void
    {
        $path = File::glob(\storage_path('logs/*.log'));
        File::delete($path);
    }
}
