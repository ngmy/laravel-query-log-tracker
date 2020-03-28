<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker\Tests;

use Carbon\Carbon;
use DB;
use QueryLogTracker;

class QueryLogTrackerTest extends TestCase
{
    /**
     * @return void
     */
    public function testQueryIsLogged(): void
    {
        DB::select('select * from users');

        $log = $this->getLog();

        $this->assertRegExp('/INFO.*select \* from users/', $log);
    }

    /**
     * @return void
     */
    public function testQueryWithPositionParametersIsLogged(): void
    {
        DB::select('select * from users where id = ? or email = ? or created_at = ?', [
            1,
            'hoge@example.com',
            new Carbon('2020-01-01 00:00:00'),
        ]);

        $log = $this->getLog();

        $this->assertRegExp('/INFO.*select \* from users' .
            '.*\[1,"hoge@example\.com","2020\-01\-01 00:00:00"\]/', $log);
    }

    /**
     * @return void
     */
    public function testQueryWithNamedParametersIsLogged(): void
    {
        DB::select('select * from users where id = :id or email = :email or created_at = :created_at', [
            'id' => 1,
            'email' => 'hoge@example.com',
            'created_at' => new Carbon('2020-01-01 00:00:00'),
        ]);

        $log = $this->getLog();

        $this->assertRegExp('/INFO.*select \* from users' .
            '.*\{"id":1,"email":"hoge@example\.com","created_at":"2020\-01\-01 00:00:00"\}/', $log);
    }

    /**
     * @return void
     */
    public function testQueryIsNotLoggedBetweenBeginDisableAndEndDisable(): void
    {
        QueryLogTracker::beginDisable();
        DB::select('select * from users');
        QueryLogTracker::endDisable();

        $log = $this->getLog();

        $this->assertNotRegExp('/INFO.*select \* from users/', $log);
    }

    /**
     * @return void
     */
    public function testQueryIsNotLoggedInDisable(): void
    {
        QueryLogTracker::disable(function (): void {
            DB::select('select * from users');
        });

        $log = $this->getLog();

        $this->assertNotRegExp('/INFO.*select \* from users/', $log);
    }
}
