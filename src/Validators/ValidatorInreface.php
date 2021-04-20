<?php

namespace Validator\Validators;

interface ValidatorInreface
{
    public function __construct(array $rules = []);

    public function isValid(mixed $data): bool;
}
