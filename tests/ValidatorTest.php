<?php

namespace Validator\Tests;

use PHPUnit\Framework\TestCase;
use Hexlet\Validator\Validator;

class ValidatorTest extends TestCase
{
    public function testCustomValidator(): void
    {
        $v = new Validator();

        $fn = fn($value, $start) => str_starts_with($value, $start);

        $v->addValidator('string', 'startWith', $fn);

        $schema = $v->string()->test('startWith', 'H');
        $this->assertFalse($schema->isValid('exlet'));
        $this->assertTrue($schema->isValid('Hexlet'));

        $fn = fn($value, $min) => $value >= $min;
        $v->addValidator('number', 'min', $fn);

        $schema = $v->number()->test('min', 5);
        $this->assertFalse($schema->isValid(4));
        $this->assertTrue($schema->isValid(6));
    }
}
