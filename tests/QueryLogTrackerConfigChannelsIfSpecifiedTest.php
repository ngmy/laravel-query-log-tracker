<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker\Tests;

use DB;

class QueryLogTrackerConfigChannelsIfSpecifiedTest extends TestCase
{
    /**
     * @return void
     */
    public function testQueryIsLoggedToSpecifiedChannels(): void
    {
        DB::select('select * from users');

        $logDefaultChannel = $this->getLog();
        $logSpecifiedChannel1 = $this->getLog('laravel-ngmy-query-log-tracker1.log');
        $logSpecifiedChannel2 = $this->getLog('laravel-ngmy-query-log-tracker2.log');

        assert(method_exists($this, 'assertMatchesRegularExpression'));
        assert(method_exists($this, 'assertDoesNotMatchRegularExpression'));
        $this->assertDoesNotMatchRegularExpression('/.*INFO.*select \* from users/', $logDefaultChannel);
        $this->assertMatchesRegularExpression('/.*INFO.*select \* from users/', $logSpecifiedChannel1);
        $this->assertMatchesRegularExpression('/.*INFO.*select \* from users/', $logSpecifiedChannel2);
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

        $app->make('config')->set('logging.channels.ngmy-query-log-tracker1', [
            'driver' => 'single',
            'path' => \storage_path('logs/laravel-ngmy-query-log-tracker1.log'),
        ]);
        $app->make('config')->set('logging.channels.ngmy-query-log-tracker2', [
            'driver' => 'single',
            'path' => \storage_path('logs/laravel-ngmy-query-log-tracker2.log'),
        ]);

        $app->make('config')->set('ngmy-query-log-tracker.channels', [
            'ngmy-query-log-tracker1',
            'ngmy-query-log-tracker2',
        ]);
    }
}
