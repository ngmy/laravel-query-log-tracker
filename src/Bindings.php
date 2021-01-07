<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker;

use Illuminate\Database\Connection;

class Bindings
{
    /** @var array<int, Binding>|array<string, Binding> */
    private $bindings = [];

    /**
     * @param array<int, mixed>|array<string, mixed> $bindings
     * @return self
     */
    public static function fromArray(array $bindings): self
    {
        return new self(array_reduce(array_keys($bindings), function (array $carry, $key) use ($bindings): array {
            $carry[$key] = new Binding($bindings[$key]);
            return $carry;
        }, []));
    }

    /**
     * @param array<int, Binding>|array<string, Binding> $bindings
     * @return void
     */
    public function __construct(array $bindings)
    {
        $this->bindings = $bindings;
    }

    /**
     * @return self
     */
    public function prepare(): self
    {
        return new self(array_reduce(array_keys($this->bindings), function (array $carry, $key): array {
            $carry[$key] = $this->bindings[$key]->prepare();
            return $carry;
        }, []));
    }

    /**
     * @return array<int, mixed>|array<string, mixed>
     */
    public function toArray(): array
    {
        return array_reduce(array_keys($this->bindings), function (array $carry, $key): array {
            $carry[$key] = $this->bindings[$key]->value();
            return $carry;
        }, []);
    }

    /**
     * @param Sql        $sql
     * @param Connection $connection
     * @return Sql
     */
    public function bindTo(Sql $sql, Connection $connection): Sql
    {
        $bindings = $this->prepare()->toArray();

        if (empty($bindings)) {
            return $sql;
        }

        $bindings = $connection->prepareBindings($bindings);
        $bindings = static::fromArray($bindings);
        foreach ($bindings->bindings as $key => $binding) {
            $placeholder = new Placeholder($key);
            $sql = $binding->bindTo($sql, $placeholder, $connection);
        }

        return $sql;
    }
}
