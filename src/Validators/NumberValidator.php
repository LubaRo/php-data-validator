<?php

namespace Hexlet\Validator\Validators;

class NumberValidator extends AValidator
{
    public function basicCheck(): callable
    {
        return fn(mixed $data) => is_numeric($data) || is_null($data);
    }

    public function required(): self
    {
        $rule = fn($num) => !is_null($num);

        return $this->addRule($rule);
    }

    public function positive(): self
    {
        $rule = fn($num) => is_null($num) || $num >= 0;

        return $this->addRule($rule);
    }

    public function range(int|float $min, int|float $max): self
    {
        $rule = fn($num) => !is_null($num) && $num >= $min && $num <= $max;

        return $this->addRule($rule);
    }
}
