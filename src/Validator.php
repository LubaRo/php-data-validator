<?php

namespace Hexlet\Validator;

use Hexlet\Validator\Validators\AValidator;
use Hexlet\Validator\Validators\StringValidator;
use Hexlet\Validator\Validators\NumberValidator;
use Hexlet\Validator\Validators\ArrayValidator;

class Validator
{
    protected const VALIDATORS = [
        'string' => StringValidator::class,
        'number' => NumberValidator::class,
        'array'  => ArrayValidator::class
    ];
    protected array $customRules = [];

    protected function getValidator(string $type): mixed
    {
        $className = static::VALIDATORS[$type];
        $customRules = $this->getCustomRulesByType($type);

        return new $className([], $customRules);
    }

    public function string(): StringValidator
    {
        return $this->getValidator('string');
    }

    public function number(): NumberValidator
    {
        return $this->getValidator('number');
    }

    public function array(): ArrayValidator
    {
        return $this->getValidator('array');
    }

    protected function getCustomRulesByType(string $type): array
    {
        $rules = $this->customRules;

        return $rules[$type] ?? [];
    }

    public function addValidator(string $type, string $name, callable $fn): void
    {
        $customRules = $this->customRules;
        $customRules[$type][$name] = $fn;

        $this->customRules = $customRules;
    }
}
