<?php

namespace Validator\Validators;

use function Symfony\Component\String\u;

class StringValidator
{
    private array $rules = [];

    public function __construct()
    {
        $this->rules[] = fn(mixed $data) => is_string($data);
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
        $this->rules[] = fn(string $str) => !u($str)->isEmpty();
    }

    public function minLength(int $minLength): void
    {
        $this->rules[] = fn(string $str) => strlen($str) >= $minLength;
    }

    public function contains(string $substr): void
    {
        $this->rules[] = fn(string $str) => u($str)->containsAny($substr);
    }
}
