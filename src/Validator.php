<?php

namespace Validator;

use Validator\Validators\StringValidator;

class Validator
{
    public function string(): StringValidator
    {
        return new StringValidator();
    }
}
