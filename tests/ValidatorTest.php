<?php

namespace Validator\Tests;

use PHPUnit\Framework\TestCase;
use LubaRo\PhpValidator\Validator;

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

    public function testUnknownValidatorTypeForCustomRule(): void
    {
        $type = 'float';
        $fn = fn($value) => is_float($value);

        $v = new Validator();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unknown validator type ' . $type);

        $v->addValidator($type, 'wrong', $fn);
    }
}
