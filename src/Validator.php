<?php

namespace LubaRo\PhpValidator;

use LubaRo\PhpValidator\Validators\StringValidator;
use LubaRo\PhpValidator\Validators\NumberValidator;
use LubaRo\PhpValidator\Validators\ArrayValidator;

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
        if (!array_key_exists($type, static::VALIDATORS)) {
            throw new \Exception('Unknown validator type ' . $type);
        }

        $customRules = $this->customRules;
        $customRules[$type][$name] = $fn;

        $this->customRules = $customRules;
    }
}
