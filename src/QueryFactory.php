<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker;

use Illuminate\Database\Events\QueryExecuted;

class QueryFactory
{
    /**
     * @param QueryExecuted $event
     * @return Query
     */
    public function makeFromEvent(QueryExecuted $event): Query
    {
        return new Query(
            new Sql($event->sql),
            Bindings::fromArray($event->bindings),
            $event->time,
            $event->connectionName,
            $event->connection
        );
    }
}
