<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker\Tests;

use DB;

class QueryLogTrackerConfigChannelTest extends TestCase
{
    /**
     * @return void
     */
    public function testQueryIsLoggedToSpecifiedChannel(): void
    {
        DB::select('select * from users');

        $logDefaultChannel = $this->getLog();
        $logSpecifiedChannel = $this->getLog('laravel-ngmy-query-log-tracker.log');

        $this->assertDoesNotMatchRegularExpression('/.*INFO.*select \* from users/', $logDefaultChannel);
        $this->assertMatchesRegularExpression('/.*INFO.*select \* from users/', $logSpecifiedChannel);
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

        $app->make('config')->set('ngmy-query-log-tracker.channel', 'ngmy-query-log-tracker');
    }
}
