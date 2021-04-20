<?php

namespace Validator\Tests;

use PHPUnit\Framework\TestCase;
use Hexlet\Validator\Validator;

class StringValidatorTest extends TestCase
{
    public function testInitState(): void
    {
        $v = new Validator();
        $schema = $v->string();

        $this->assertFalse($schema->isValid(null), 'NULL value is not valid');
        $this->assertTrue($schema->isValid(''), 'Empty string check');
        $this->assertTrue($schema->isValid('qwerty'), 'Ordinary string check');

        $this->assertFalse($schema->isValid(455), 'Number type value check');

        $this->assertTrue($schema->isValid('445'), 'String with number check');
        $this->assertTrue($schema->isValid('null'), 'Null word check');
    }

    public function testRequired(): void
    {
        $v = new Validator();

        $schema = $v->string()->required();

        $this->assertFalse($schema->isValid(''), 'Empty string check');
        $this->assertTrue($schema->isValid('h'), 'Single char check');
        $this->assertTrue($schema->isValid('simple words'), 'Ordinary string check');
    }

    public function testMinLength(): void
    {
        $v = new Validator();

        $schema = $v->string()->minLength(5);

        $this->assertFalse($schema->isValid(''), 'Empty string check');
        $this->assertFalse($schema->isValid('abcd'), 'Length is shorter than needed');
        $this->assertTrue($schema->isValid('abcde'), 'Equal to minLength string check');
        $this->assertTrue(
            $schema->isValid('The quick brown fox jumps over the lazy dog'),
            'Greater than minLength string check'
        );
    }

    public function testContains(): void
    {
        $v = new Validator();

        $schema = $v->string()->contains('some');

        $this->assertTrue($schema->isValid('The some string'), 'Contains check');
        $this->assertFalse($schema->isValid(''), 'Empty string check');
        $this->assertFalse($schema->isValid('abcde'), 'String does not contains the substr');
    }

    public function testMultipleRules(): void
    {
        $v = new Validator();

        $schema = $v->string()
            ->minLength(3)
            ->required()
            ->contains('ze');

        $this->assertFalse($schema->isValid('ze'), 'Not enough length');
        $this->assertFalse($schema->isValid('a'), 'Not enough length');
        $this->assertFalse($schema->isValid(''), 'Empty string check');
        $this->assertFalse($schema->isValid('fke'), 'Length is equal to min without needed substr');

        $this->assertTrue($schema->isValid('aze'), 'Length is equal to min with required substr');
        $this->assertTrue($schema->isValid('aze fl;as'), 'Length is greater than min with required substr');
    }
}
