<?php

namespace Validator\Validators;

class NumberValidator
{
    private array $rules = [];

    public function __construct()
    {
        $this->rules[] = fn(mixed $data) => is_numeric($data) || is_null($data);
    }

    public function isValid(mixed $data): bool
    {
        $result = true;

        foreach ($this->rules as $rule) {
            if ($rule($data) === false) {
                $result = false;
                break;
            }
        }
        return $result;
    }

    public function required(): void
    {
        $this->rules[] = fn($num) => !is_null($num);
    }

    public function positive(): void
    {
        $this->rules[] = fn($num) => !is_null($num) && $num >= 0;
    }

    public function range(int|float $min, int|float $max): void
    {
        $this->rules[] = fn($num) => !is_null($num) && $num >= $min && $num <= $max;
    }
}
