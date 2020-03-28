<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker\Tests;

use Carbon\Carbon;
use DB;
use Ngmy\LaravelQueryLogTracker\{
    Binding,
    Bindings,
    Sql,
};

class BindingsTest extends TestCase
{
    /**
     * @return array<string, array{
     *             sql: string,
     *             bindings: array<int, mixed>|array<string, mixed>,
     *             expectedResult: string
     *         }>
     */
    public function provideFormat(): array
    {
        return [
            'Bindings are empty' => [
                'sql' => 'select * from users',
                'bindings' => [],
                'expectedResult' => 'select * from users',
            ],
            'Bindings are strings' => [
                'sql' => 'select * from users where name = ?',
                'bindings' => ['1'],
                'expectedResult' => 'select * from users where name = \'1\'',
            ],
            'Bindings are integers' => [
                'sql' => 'select * from users where id = ?',
                'bindings' => [1],
                'expectedResult' => 'select * from users where id = 1',
            ],
            'Bindings are floating points' => [
                'sql' => 'select * from users where score >= ?',
                'bindings' => [2.5],
                'expectedResult' => 'select * from users where score >= 2.5',
            ],
            'Bindings are Carbon objects' => [
                'sql' => 'select * from users where created_at = ?',
                'bindings' => [new Carbon('2020-01-01 00:00:00')],
                'expectedResult' => 'select * from users where created_at = \'2020-01-01 00:00:00\''
            ],
            'Bindings are position parameters' => [
                'sql' => 'select * from users id code = ? and type = ?',
                'bindings' => [1, 2],
                'expectedResult' => 'select * from users id code = 1 and type = 2',
            ],
            'Bindings are named parameters' => [
                'sql' => 'select * from users id code = :code and type = :type',
                'bindings' => ['code' => 1, 'type' => 2],
                'expectedResult' => 'select * from users id code = 1 and type = 2',
            ],
        ];
    }

    /**
     * @param string                                 $sql
     * @param array<int, mixed>|array<string, mixed> $bindings
     * @param string                                 $expectedResult
     * @return void
     * @dataProvider provideFormat
     */
    public function testFormat(string $sql, array $bindings, string $expectedResult): void
    {
        $sut = $this->makeSut($bindings);

        $sql = new Sql($sql);
        $connection = DB::connection();

        $actualResult = $sut->bindTo($sql, $connection);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @param array<int, mixed>|array<string, mixed> $bindings
     * @return Bindings
     */
    public function makeSut(array $bindings): Bindings
    {
        return Bindings::fromArray($bindings);
    }
}
