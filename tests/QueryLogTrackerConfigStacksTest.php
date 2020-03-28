<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker\Tests;

use DB;

class QueryLogTrackerConfigStacksTest extends TestCase
{
    /**
     * @return void
     */
    public function testQueryIsLoggedToSpecifiedStacks(): void
    {
        DB::select('select * from users');

        $logStack1 = $this->getLog();
        $logStack2 = $this->getLog('laravel-ngmy-query-log-tracker.log');

        $this->assertRegExp('/INFO.*select \* from users/', $logStack1);
        $this->assertRegExp('/INFO.*select \* from users/', $logStack2);
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        $app->make('config')->set('logging.channels.ngmy-query-log-tracker', [
            'driver' => 'single',
            'path' => \storage_path('logs/laravel-ngmy-query-log-tracker.log'),
        ]);

        $app->make('config')->set('ngmy-query-log-tracker.stacks', ['stack', 'ngmy-query-log-tracker']);
    }
}
