<?php

namespace Validator\Validators;

interface ValidatorInreface
{
    public function __construct(array $rules = [], array $customRules = []);

    public function isValid(mixed $data): bool;

    public function addRule(callable $rule): static;

    public function test(string $methodName, mixed ...$params): static;
}
