<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker\Tests;

use DB;

class QueryLogTrackerConfigExcludePatternsTest extends TestCase
{
    /**
     * @return void
     */
    public function testQueryThatMatchTheExcludePatternIsNotLogged(): void
    {
        DB::select('select * from users');

        $log = $this->getLog();

        assert(method_exists($this, 'assertDoesNotMatchRegularExpression'));
        $this->assertDoesNotMatchRegularExpression('/select \* from users/', $log);
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

        $app->make('config')->set('ngmy-query-log-tracker.exclude_patterns', ['/select \* from users/']);
    }
}
