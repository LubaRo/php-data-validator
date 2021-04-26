<?php

namespace Validator\Tests;

use PHPUnit\Framework\TestCase;
use LubaRo\PhpValidator\Validator;
use LubaRo\PhpValidator\Validators\ArrayValidator;

class ArrayValidatorTest extends TestCase
{
    /**
     * @dataProvider initStateDataProvider
     * @dataProvider requiredRuleDataProvider
     * @dataProvider sizeofRuleDataProvider
     * @dataProvider multipleRulesDataProvider
     */
    public function testNumberValidator(ArrayValidator $schema, mixed $value, bool $expected): void
    {
        $this->assertSame($expected, $schema->isValid($value));
    }

    public function initStateDataProvider(): array
    {
        $schema = (new Validator())->array();

        return [
            'init: indexed array' => [$schema, [223, 'a', []], true],
            'init: associative array' => [$schema, ['x' => 1, 'y' => 3], true],
            'init: empty array' => [$schema, [], true],
            'init: null is valid empty value' => [$schema, null, true],
            'init: number is not an array' => [$schema, 245, false],
        ];
    }

    public function requiredRuleDataProvider(): array
    {
        $schema = (new Validator())->array()->required();

        return [
            'required: empty array is valid' => [$schema, [], true],
            'required: associative array is valid' => [$schema, ['x' => 1, 'y' => 3], true],
            'required: indexed array is valid' => [$schema, [223, 'a', []], true],
            'required: null is not valid' => [$schema, null, false],
        ];
    }

    public function sizeofRuleDataProvider(): array
    {
        $schema = (new Validator())->array()->sizeof(2);

        return [
            'sizeof: size is equal to required' => [$schema, ['x' => 1, 'y' => 3], true],
            'sizeof: number of elems is less' => [$schema, [], false],
            'sizeof: number of elems is greater' => [$schema, [1, 2, 3], false],
        ];
    }

    public function shapeRuleDataProvider(): array
    {
        $v = new Validator();

        $schema = $v->array()->shape([
            'id'   => $v->number()->required()->positive(),
            'name' => $v->string()->required(),
            'age'  => $v->number()->positive()
        ]);

        return [
            'shape: appropriate array shape' => [
                $schema,
                ['id'   => 24, 'name' => 'Peter', 'age'  => 27],
                true
            ],
            'shape: one of required key is missing' => [
                $schema,
                ['id'   => 24, 'firstname' => 'Peter', 'age'  => 27],
                false
            ],
            'shape: one of the elem is invalid' => [
                $schema,
                ['id'   => 24, 'firstname' => 'Peter', 'age'  => -27],
                false
            ],
        ];
    }

    public function multipleRulesDataProvider(): array
    {
        $v = new Validator();

        $schema = $v->array()->required()->sizeof(3)->shape([
            'name' => $v->string()->required(),
            'age'  => $v->number()->positive()
        ]);

        return [
            'multi: suits all rules' => [
                $schema,
                ['name' => 'Jenny', 'hobbies' => ['guitar', 'cooking'], 'age' => 32],
                true
            ],
            'multi: violates nested rule' => [
                $schema,
                ['name' => 'Jenny', 'hobbies' => ['guitar', 'cooking'], 'age' => -32],
                false
            ],
            'multi: violates one of the rules' => [
                $schema,
                ['name' => 'Jenny', 'hobbies' => ['guitar', 'cooking'], 'age' => 32, 'asdf'],
                false
            ],
        ];
    }
}
