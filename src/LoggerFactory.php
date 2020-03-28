<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker;

use Illuminate\Foundation\Application;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    /** @var Application $app */
    private $app;

    /**
     * @param Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return LoggerInterface
     */
    public function make(): LoggerInterface
    {
        return $this->app->make(LoggerInterface::class);
    }
}
