<?php

namespace Validator\Validators;

use function Symfony\Component\String\u;

class StringValidator extends AValidator
{
    public function __construct(array $rules = [], array $customRules = [])
    {
        parent::__construct($rules, $customRules);

        $this->rules[] = fn(mixed $data) => is_string($data) || is_null($data);
    }

    public function required(): self
    {
        $rule = fn(string $str) => !u($str)->isEmpty();

        return $this->addRule($rule);
    }

    public function minLength(int $minLength): self
    {
        $rule = fn(string $str) => strlen($str) >= $minLength;

        return $this->addRule($rule);
    }

    public function contains(string $substr): self
    {
        $rule = fn(string $str) => u($str)->containsAny($substr);

        return $this->addRule($rule);
    }
}
