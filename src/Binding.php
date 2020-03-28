<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker;

use Illuminate\Database\ConnectionInterface;

class Binding
{
    /** @var mixed */
    private $value;

    /**
     * @param mixed $value
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return self
     */
    public function prepare(): self
    {
        $value = $this->value;

        // Check bindings for illegal (non UTF-8) strings, like Binary data.
        //
        // @see \Barryvdh\Debugbar\DataFormatter\QueryFormatter::checkBindings()
        // @see https://github.com/barryvdh/laravel-debugbar/blob/v3.2.8/src/DataFormatter/QueryFormatter.php#L30
        if (\is_string($this->value) && !\mb_check_encoding($this->value, 'UTF-8')) {
            $value = '[BINARY DATA]';
        }

        return new self($value);
    }

    /**
     * @param Sql                 $sql
     * @param Placeholder         $placeholder
     * @param ConnectionInterface $connection
     * @return Sql
     */
    public function bindTo(Sql $sql, $placeholder, ConnectionInterface $connection): Sql
    {
        $sql = (string) $sql;
        $binding = $this->value;
        $pdo = $connection->getPdo();

        // @see \Barryvdh\Debugbar\DataCollector\QueryCollector::addQuery()
        // @see https://github.com/barryvdh/laravel-debugbar/blob/v3.2.8/src/DataCollector/QueryCollector.php#L107

        // This regex matches placeholders only, not the question marks,
        // nested in quotes, while we iterate through the bindings
        // and substitute placeholders by suitable values.
        $regex = $placeholder->getBindingRegularExpression();

        // Mimic bindValue and only quote non-integer and non-float data types
        if (!\is_int($binding) && !\is_float($binding)) {
            $binding = $pdo->quote($binding);
        }

        // Convert to string representation for preg_replace()
        if (\is_int($binding) || \is_float($binding)) {
            $binding = \var_export($binding, true);
        }

        $sql = \preg_replace($regex, $binding, $sql, 1) ?? $sql;

        return new Sql($sql);
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }
}
