<?php

namespace Validator\Validators;

class ArrayValidator
{
    private array $rules = [];

    public function __construct()
    {
        $this->rules[] = fn(mixed $data) => is_array($data) || is_null($data);
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
        $this->rules[] = fn($arr) => !is_null($arr);
    }

    public function sizeof(int $size): void
    {
        $this->rules[] = fn($arr) => !is_null($arr) && sizeof($arr) === $size;
    }
}
