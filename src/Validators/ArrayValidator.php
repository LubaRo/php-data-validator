<?php

namespace Validator\Validators;

class ArrayValidator extends AValidator
{
    public function __construct(array $rules = [])
    {
        parent::__construct($rules);

        $this->rules[] = fn(mixed $data) => is_array($data) || is_null($data);
    }

    public function required(): self
    {
        $rule = fn($arr) => !is_null($arr);

        return $this->addRule($rule);
    }

    public function sizeof(int $size): self
    {
        $rule = fn($arr) => !is_null($arr) && sizeof($arr) === $size;

        return $this->addRule($rule);
    }
}
