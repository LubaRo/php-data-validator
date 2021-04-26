<?php

namespace Validator\Tests;

use PHPUnit\Framework\TestCase;
use LubaRo\PhpValidator\Validator;
use LubaRo\PhpValidator\Validators\StringValidator;

class StringValidatorTest extends TestCase
{
    /**
     * @dataProvider initStateDataProvider
     * @dataProvider requiredRuleDataProvider
     * @dataProvider minLengthRuleDataProvider
     * @dataProvider containsRuleDataProvider
     * @dataProvider multipleRulesDataProvider
     */
    public function testStringValidator(StringValidator $schema, mixed $value, bool $expected): void
    {
        $this->assertSame($expected, $schema->isValid($value));
    }

    public function initStateDataProvider(): array
    {
        $schema = (new Validator())->string();

        return [
            'init: empty string'    => [$schema, '', true],
            'init: ordinary string' => [$schema, 'qwerty', true],
            'init: null is not a string' => [$schema, null, false],
            'init: number is not a string' => [$schema, 455, false],
        ];
    }

    public function requiredRuleDataProvider(): array
    {
        $schema = (new Validator())->string()->required();

        return [
            'required: single char'     => [$schema, 'y', true],
            'required: ordinary string' => [$schema, 'simple words', true],
            'required: empty string'    => [$schema, '', false],
        ];
    }

    public function minLengthRuleDataProvider(): array
    {
        $stringValidator = (new Validator())->string();
        $getScheme = fn($minLength) => $stringValidator->minLength($minLength);

        return [
            'minLength: length is equal to min'        => [$getScheme(8), 'lazy fox', true],
            'minLength: length is greater than min'    => [$getScheme(5), 'lazy fox', true],
            'minLength: zero length'                   => [$getScheme(0), 'asdf', true],
            'minLength: zero length with empty string' => [$getScheme(0), '', true],
            'minLength: length is not enough'          => [$getScheme(20), 'lazy fox', false],
        ];
    }

    public function containsRuleDataProvider(): array
    {
        $stringValidator = (new Validator())->string();
        $getScheme = fn($substr) => $stringValidator->contains($substr);

        return [
            'contains: string contains the substr' => [
                $getScheme('some'), 'The some string', true
            ],
            'contains: string does not contains the substr' => [
                $getScheme('cat'), 'brown fox jumps over the lazy dog', false
            ],
        ];
    }

    public function multipleRulesDataProvider(): array
    {
        $schema = (new Validator())->string()
            ->minLength(3)
            ->required()
            ->contains('ze');

        return [
            'multi: all rules passes1' => [$schema, 'aze', true],
            'multi: all rules passes2' => [$schema, 'lkds aze sw', true],
            'multi: length does not match' => [$schema, 'ze', false],
            'multi: does not contains the substr' => [$schema, 'qwerty asdfg', false],
            'multi: string required' => [$schema, '', false],
        ];
    }
}
