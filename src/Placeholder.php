<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker;

class Placeholder
{
    /** @var int|string */
    private $value;

    /**
     * @param int|string $value
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function isQuestionPlaceholder(): bool
    {
        return \is_numeric($this->value);
    }

    /**
     * @return string
     */
    public function getBindingRegularExpression(): string
    {
        // @see \Barryvdh\Debugbar\DataCollector\QueryCollector::addQuery()
        // @see https://github.com/barryvdh/laravel-debugbar/blob/v3.2.8/src/DataCollector/QueryCollector.php#L110
        return $this->isQuestionPlaceholder()
            ? "/\?(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/"
            : "/:{$this->value}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";
    }
}
