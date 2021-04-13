<?php

namespace Validator\Tests;

use PHPUnit\Framework\TestCase;
use Validator\Validator;

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

        $schema = $v->array();
        $schema->required();

        $this->assertFalse($schema->isValid(null), 'Null value is not valid');

        $this->assertTrue($schema->isValid([]), 'Empty array is valid');
        $this->assertTrue($schema->isValid(['x' => 1, 'y' => 3]), 'Associative array is valid');
        $this->assertTrue($schema->isValid([223, 'a', []]), 'Indexed array is valid');
    }

    public function testSizeof(): void
    {
        $v = new Validator();

        $schema = $v->array();
        $schema->sizeof(4);

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
}
