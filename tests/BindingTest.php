<?php

declare(strict_types=1);

namespace Ngmy\LaravelQueryLogTracker\Tests;

use Ngmy\LaravelQueryLogTracker\Binding;

class BindingTest extends TestCase
{
    /**
     * @return array<string, array{binding: mixed, expectedResult: mixed}>
     */
    public function providePrepare(): array
    {
        return [
            'Binding is an integer' => [
                'binding' => 1,
                'expectedResult' => 1,
            ],
            'Binding is a legal string' => [
                'binding' => 'ほげ',
                'expectedResult' => 'ほげ',
            ],
            'Binding is an illegal string' => [
                'binding' => \mb_convert_encoding('ほげ', 'EUC-JP'),
                'expectedResult' => '[BINARY DATA]',
            ],
        ];
    }

    /**
     * @param mixed $binding
     * @param mixed $expectedResult
     * @return void
     * @dataProvider providePrepare
     */
    public function testPrepare($binding, $expectedResult): void
    {
        $sut = $this->makeSut($binding);

        $actualResult = $sut->prepare();

        $expectedResult = $this->makeSut($expectedResult);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @param mixed $binding
     * @return Binding
     */
    public function makeSut($binding): Binding
    {
        return new Binding($binding);
    }
}
