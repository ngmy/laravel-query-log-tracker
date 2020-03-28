<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker;

class Sql
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     * @return void
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $pattern
     * @return bool
     */
    public function matchesPattern(string $pattern): bool
    {
        return (bool) \preg_match($pattern, $this->value);
    }

    /**
     * @param array<int, string> $patterns
     * @return bool
     */
    public function matchesPatterns(array $patterns): bool
    {
        if (!empty($patterns)) {
            foreach ($patterns as $pattern) {
                if ($this->matchesPattern($pattern)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
