<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker;

use Illuminate\Database\Events\QueryExecuted;

class QueryExecutedListener
{
    /** @var QueryLogManager */
    private $log;

    /**
     * Create the event listener.
     *
     * @param QueryLogManager $log
     * @return void
     */
    public function __construct(QueryLogManager $log)
    {
        $this->log = $log;
    }

    /**
     * Handle the event.
     *
     * @param QueryExecuted $event
     * @return void
     */
    public function handle(QueryExecuted $event): void
    {
        $this->log->writeLog($event);
    }
}
