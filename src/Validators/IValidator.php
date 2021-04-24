<?php

namespace LubaRo\PhpValidator\Validators;

interface IValidator
{
    public function __construct(array $rules = [], array $customRules = []);

    public function isValid(mixed $data): bool;

    public function test(string $methodName, mixed ...$params): static;
}
