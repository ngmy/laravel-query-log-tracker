<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Application;
use Illuminate\Log\LogManager;
use Psr\Log\LoggerInterface;

class QueryLogManager
{
    /** @var Application */
    private $app;
    /** @var LoggerFactory */
    private $loggerFactory;
    /** @var QueryFactory */
    private $queryFactory;
    /** @var LoggerInterface */
    private $logger;
    /** @var string */
    private $level;
    /** @var array<int, string> */
    private $excludePatterns;
    /** @var bool */
    private $enable = true;

    /**
     * @param Application    $app
     * @param LoggerFactory  $loggerFactory
     * @param QueryFactory   $queryFactory
     * @return void
     */
    public function __construct(
        Application $app,
        LoggerFactory $loggerFactory,
        QueryFactory $queryFactory
    ) {
        $this->app = $app;
        $this->loggerFactory = $loggerFactory;
        $this->queryFactory = $queryFactory;

        $config = $this->configuration();
        $this->level = $config['level'] ?? 'info';
        $this->excludePatterns = $config['exclude_patterns'] ?? [];
    }

    /**
     * @return LoggerInterface
     */
    public function logger(): LoggerInterface
    {
        if (!isset($this->logger)) {
            $this->logger = $this->configureLogger($this->makeLogger());
        }
        return $this->logger;
    }

    /**
     * @param QueryExecuted $event
     * @return void
     */
    public function writeLog(QueryExecuted $event): void
    {
        if (!$this->isEnable()) {
            return;
        }

        $query = $this->queryFactory->makeFromEvent($event);

        if ($query->getSql()->matchesPatterns($this->excludePatterns)) {
            return;
        }

        $this->logger()->{$this->level}(...$query->getSqlWithContext());
    }

    /**
     * @return void
     */
    public function beginDisable(): void
    {
        $this->enable = false;
    }

    /**
     * @return void
     */
    public function endDisable(): void
    {
        $this->enable = true;
    }

    /**
     * @param callable $callback
     * @return void
     */
    public function disable(callable $callback): void
    {
        $this->beginDisable();

        try {
            $callback();
        } finally {
            $this->endDisable();
        }
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @return LoggerInterface
     */
    protected function makeLogger(): LoggerInterface
    {
        return $this->loggerFactory->make();
    }

    /**
     * @param LoggerInterface $logger
     * @return LoggerInterface
     */
    protected function configureLogger(LoggerInterface $logger): LoggerInterface
    {
        $config = $this->configuration();

        $channels = $config['channels'] ?? [];
        if (!empty($channels) && $logger instanceof LogManager) {
            $logger = $logger->stack($channels);
        }

        return $logger;
    }

    /**
     * @return array<string, mixed>
     */
    protected function configuration(): array
    {
        return $this->app->make('config')->get('ngmy-query-log-tracker');
    }
}
