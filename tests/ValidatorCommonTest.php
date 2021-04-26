<?php

namespace Validator\Tests;

use PHPUnit\Framework\TestCase;
use LubaRo\PhpValidator\Validator;

class ValidatorCommonTest extends TestCase
{
    protected Validator $validator;

    protected function setUp(): void
    {
        $this->validator = new Validator();
    }

    public function testCustomValidator(): void
    {
        $v = $this->validator;

        $startsWith  = fn($value, $start) => str_starts_with($value, $start);
        $greaterThan = fn($value, $min) => $value > $min;
        $maxSize     = fn($value, $maxSize) => sizeof($value) <= $maxSize;

        $v->addValidator('string', 'startsWith', $startsWith);
        $v->addValidator('number', 'greaterThan', $greaterThan);
        $v->addValidator('array', 'maxSize', $maxSize);

        $stringSchema = $v->string()->test('startsWith', 'H');

        $this->assertTrue($stringSchema->isValid('Hexlet'));
        $this->assertFalse($stringSchema->isValid('exlet'));

        $numberSchema = $v->number()->test('greaterThan', 5);

        $this->assertTrue($numberSchema->isValid(6));
        $this->assertFalse($numberSchema->isValid(4));

        $arraySchema = $v->array()->test('maxSize', 3);

        $this->assertTrue($arraySchema->isValid(['a']));
        $this->assertFalse($arraySchema->isValid([1, 2, [], 4]));
    }

    public function testUnknownValidatorTypeForCustomRule(): void
    {
        $wrongValidatorName = 'float';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unknown validator type ' . $wrongValidatorName);

        $this->validator->addValidator($wrongValidatorName, 'funcName', fn($a) => true);
    }

    public function testStatelessOfValidator(): void
    {
        $stringValidator    = $this->validator->string();
        $requiredStringValidator = $stringValidator->required();

        $this->assertTrue($stringValidator->isValid(''));
        $this->assertFalse($requiredStringValidator->isValid(''));
    }
}
