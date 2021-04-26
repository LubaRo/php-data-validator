<?php

namespace Validator\Tests;

use PHPUnit\Framework\TestCase;
use LubaRo\PhpValidator\Validator;
use LubaRo\PhpValidator\Validators\NumberValidator;

class NumberValidatorTest extends TestCase
{
    /**
     * @dataProvider initStateDataProvider
     * @dataProvider requiredRuleDataProvider
     * @dataProvider positiveRuleDataProvider
     * @dataProvider rangeRuleDataProvider
     * @dataProvider multipleRulesDataProvider
     */
    public function testNumberValidator(NumberValidator $schema, mixed $value, bool $expected): void
    {
        $this->assertSame($expected, $schema->isValid($value));
    }

    public function initStateDataProvider(): array
    {
        $schema = (new Validator())->number();

        return [
            'init: zero is valid number' => [$schema, 0, true],
            'init: negative num is valid' => [$schema, -42, true],
            'init: positive num is valid' => [$schema, 34.12, true],
            'init: float is valid' => [$schema, 25, true],
            'init: null is valid empty value' => [$schema, null, true],
            'init: string only with number' => [$schema, '436', true],
            'init: string only with float' => [$schema, '43.6', true],
            'init: string with num and chars' => [$schema, '43a', false],
            'init: empty string' => [$schema, '', false],
        ];
    }

    public function requiredRuleDataProvider(): array
    {
        $schema = (new Validator())->number()->required();

        return [
            'required: positive number' => [$schema, 234, true],
            'required: negative number' => [$schema, -43.2, true],
            'required: zero value' => [$schema, 0, true],
            'require: null is not valid' => [$schema, null, false],
        ];
    }

    public function positiveRuleDataProvider(): array
    {
        $schema = (new Validator())->number()->positive();

        return [
            'positive: int value' => [$schema, 905, true],
            'positive: float value' => [$schema, 23.342, true],
            'positive: zero is not valid' => [$schema, 0, false],
            'positive: negative is not valid' => [$schema, -32, false],
        ];
    }

    public function rangeRuleDataProvider(): array
    {
        $schema = (new Validator())->number()->range(-5, 4);

        return [
            'range: int value inside range' => [$schema, 2, true],
            'range: float value inside range' => [$schema, 0.75, true],
            'range: bottom bound' => [$schema, -5, true],
            'range: upper bound' => [$schema, 4, true],
            'range: less than the bottom bound' => [$schema, -6, false],
            'range: greater than the upper bound' => [$schema, 5, false],
        ];
    }

    public function multipleRulesDataProvider(): array
    {
        $schema = (new Validator())->number()->required()->range(-35, 40)->positive();

        return [
            'multi: all rules passes' => [$schema, 40, true],
            'multi: positive rule violated' => [$schema, -5, false],
            'multi: out of the range' => [$schema, 105, false],
            'multi: required rule violated' => [$schema, null, false],
        ];
    }
}
