<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker\Tests;

use DB;

class QueryLogTrackerConfigLevelTest extends TestCase
{
    /**
     * @return void
     */
    public function testQueryIsLoggedAtTheSpecifiedLevel(): void
    {
        DB::select('select * from users');

        $log = $this->getLog();

        assert(method_exists($this, 'assertMatchesRegularExpression'));
        $this->assertMatchesRegularExpression('/DEBUG.*select \* from users/', $log);
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

        $app->make('config')->set('ngmy-query-log-tracker.level', 'debug');
    }
}
