<?php

namespace Validator\Tests;

use PHPUnit\Framework\TestCase;
use LubaRo\PhpValidator\Validator;

class ArrayValidatorTest extends TestCase
{
    public function testInitState(): void
    {
        $v = new Validator();
        $schema = $v->array();

        $this->assertTrue($schema->isValid(null), 'Null value is valid');
        $this->assertTrue($schema->isValid([]), 'Empty array is valid');
        $this->assertTrue($schema->isValid(['x' => 1, 'y' => 3]), 'Associative array is valid');
        $this->assertTrue($schema->isValid([223, 'a', []]), 'Indexed array is valid');

        $this->assertFalse($schema->isValid(444), 'Numeric value is valid');
    }

    public function testRequired(): void
    {
        $v = new Validator();

        $schema = $v->array()->required();

        $this->assertFalse($schema->isValid(null), 'Null value is not valid');

        $this->assertTrue($schema->isValid([]), 'Empty array is valid');
        $this->assertTrue($schema->isValid(['x' => 1, 'y' => 3]), 'Associative array is valid');
        $this->assertTrue($schema->isValid([223, 'a', []]), 'Indexed array is valid');
    }

    public function testSizeof(): void
    {
        $v = new Validator();

        $schema = $v->array()->sizeof(4);

        $this->assertTrue(
            $schema->isValid(['x' => 1, 'y' => 3, 'a' => 'afd', 'some' => 'qwerty']),
            'Associative array with 4 elems is valid'
        );
        $this->assertTrue(
            $schema->isValid([223, 'a', ['g' => 444], 32.23]),
            'Indexed array with 4 elems is valid'
        );

        $this->assertFalse($schema->isValid(null), 'Null value is not valid');
        $this->assertFalse($schema->isValid([]), 'Empty array is not valid');
        $this->assertFalse($schema->isValid([1, 5, 514, 'fasfd', 544]), 'The number of elements is greater');
        $this->assertFalse($schema->isValid(['a' => 'only one']), 'The number of elements is less');
    }

    public function testMultipleRules(): void
    {
        $v = new Validator();

        $schema = $v->array();

        $ruleSet1 = $schema->required()->sizeof(5);

        $this->assertTrue($ruleSet1->isValid(['a', 1, 378, [], 'qwerty']));
        $this->assertFalse($ruleSet1->isValid(null));
        $this->assertFalse($ruleSet1->isValid([1, 2]));
        $this->assertFalse($ruleSet1->isValid([1, 2, 3, 4, 5, 6]));

        $this->assertTrue($v->number()->positive()->isValid(null));
        $this->assertTrue($v->array()->sizeof(2)->shape([
            'name' => $v->string()->required(),
            'age'  => $v->number()->positive()
        ])->isValid(['name' => 'maya', 'age' => null]));

        $this->assertTrue($schema->sizeof(2)->isValid(['a', 'b']));
        $this->assertTrue($schema->isValid([13]));
    }

    public function testShape(): void
    {
        $v = new Validator();

        $schema = $v
            ->array()
            ->shape([
                'id'   => $v->number()->required()->positive(),
                'name' => $v->string()->required(),
                'age'  => $v->number()->positive()
            ]);

        $this->assertTrue($schema->isValid([
            'id'   => 24,
            'name' => 'Peter',
            'age'  => 27
        ]));

        $this->assertTrue($schema->isValid([
            'id'   => 24,
            'name' => 'Peter',
            'age'  => 32
        ]));

        $this->assertFalse($schema->isValid([
            'id'   => -5,
            'name' => 'Peter',
            'age'  => null
        ]));

        $this->assertFalse($schema->isValid([
            'id'   => 2,
            'name' => null,
            'age'  => 12
        ]));
    }
}
