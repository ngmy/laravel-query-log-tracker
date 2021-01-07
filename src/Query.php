<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker;

use Illuminate\Database\Connection;

class Query
{
    /** @var Sql */
    private $sql;
    /** @var Bindings */
    private $bindings;
    /** @var float */
    private $time;
    /** @var string */
    private $connectionName;
    /** @var Connection */
    private $connection;

    /**
     * @param Sql        $sql
     * @param Bindings   $bindings
     * @param float      $time
     * @param string     $connectionName
     * @param Connection $connection
     * @return void
     */
    public function __construct(
        Sql $sql,
        Bindings $bindings,
        float $time,
        string $connectionName,
        Connection $connection
    ) {
        $this->sql = $sql;
        $this->bindings = $bindings;
        $this->time = $time;
        $this->connectionName = $connectionName;
        $this->connection = $connection;
    }

    /**
     * @return Sql
     */
    public function getSql(): Sql
    {
        return $this->bindings->bindTo($this->sql, $this->connection);
    }

    /**
     * @return array{bindings: array<int, mixed>|array<string, mixed>, time: float, connectionName: string}
     */
    public function getContext(): array
    {
        return [
            'bindings' => $this->bindings->prepare()->toArray(),
            'time' => $this->time,
            'connectionName' => $this->connectionName,
        ];
    }

    /**
     * @return array{0: Sql, 1: array<string, mixed>}
     */
    public function getSqlWithContext(): array
    {
        return [
            $this->getSql(),
            $this->getContext()
        ];
    }
}
