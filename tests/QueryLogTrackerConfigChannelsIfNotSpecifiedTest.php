<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker\Tests;

use DB;

class QueryLogTrackerConfigChannelsIfNotSpecifiedTest extends TestCase
{
    /**
     * @return void
     */
    public function testQueryIsLoggedToSpecifiedChannels(): void
    {
        DB::select('select * from users');

        $logDefaultChannel = $this->getLog();

        assert(method_exists($this, 'assertMatchesRegularExpression'));
        $this->assertMatchesRegularExpression('/.*INFO.*select \* from users/', $logDefaultChannel);
    }
}
