<?php

namespace Validator\Tests;

use PHPUnit\Framework\TestCase;
use Validator\Validator;

class NumberValidatorTest extends TestCase
{
    public function testInitState(): void
    {
        $v = new Validator();
        $schema = $v->number();

        $this->assertFalse($schema->isValid(''), 'String is not a number');
        $this->assertFalse($schema->isValid('4kl'), 'String is not a number');

        $this->assertTrue($schema->isValid('543'), 'String with number is valid');
        $this->assertTrue($schema->isValid('54.3'), 'String with float is valid');
        $this->assertTrue($schema->isValid(null), 'String with number is valid');

        $this->assertTrue($schema->isValid(543), 'Integer is valid');
        $this->assertTrue($schema->isValid(34.12), 'Float is valid');
    }

    public function testRequired(): void
    {
        $v = new Validator();

        $schema = $v->number();
        $schema->required();

        $this->assertTrue($schema->isValid(432), 'Int value is valid');
        $this->assertTrue($schema->isValid(43.232), 'Float value is valid');
        $this->assertTrue($schema->isValid(0), 'Zero value is valid');

        $this->assertFalse($schema->isValid(null), 'NULL is not valid');
    }

    public function testPositive(): void
    {
        $v = new Validator();

        $schema = $v->number();
        $schema->positive();

        $this->assertTrue($schema->isValid(432), 'Int positive value is valid');
        $this->assertTrue($schema->isValid(43.232), 'Float positive value is valid');
        $this->assertTrue($schema->isValid(0), 'Zero value is valid');

        $this->assertFalse($schema->isValid(null), 'NULL is not valid');
        $this->assertFalse($schema->isValid(-32), 'Negative int is not valid');
        $this->assertFalse($schema->isValid(-423.12), 'Negative float is not valid');
    }

    public function testRange(): void
    {
        $v = new Validator();

        $schema = $v->number();
        $schema->range(-5, 4);

        $this->assertTrue($schema->isValid(2), 'Range value value is valid');
        $this->assertTrue($schema->isValid(0.75), 'Float range value value is valid');
        $this->assertTrue($schema->isValid(-5), 'Bottom bound is valid');
        $this->assertTrue($schema->isValid(4), 'Upper bound is valid');

        $this->assertFalse($schema->isValid(null), 'NULL is not valid');
        $this->assertFalse($schema->isValid(-6.3), 'Less than the lower bound');
        $this->assertFalse($schema->isValid(43), 'Greater than the upper bound');
    }

    public function testMultipleRules(): void
    {
        $v = new Validator();

        $schema = $v->number();

        $schema->required();
        $schema->range(-35, 40);
        $schema->positive();

        $this->assertFalse($schema->isValid(null), 'Number must exists');
        $this->assertFalse($schema->isValid(-20), 'Number must be positive');
        $this->assertFalse($schema->isValid(46), 'Number out of the range');

        $this->assertTrue($schema->isValid(0), 'Valid number');
        $this->assertTrue($schema->isValid(40), 'Valid number');
    }
}