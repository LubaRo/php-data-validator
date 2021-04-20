<?php

namespace Validator\Validators;

class ArrayValidator extends AValidator
{
    public function __construct(array $rules = [], array $customRules = [])
    {
        parent::__construct($rules, $customRules);

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

    public function shape(array $shape): self
    {
        $rule = $this->getShapeChecker($shape);

        return $this->addRule($rule);
    }

    protected function getShapeChecker(array $shape): callable
    {
        return function ($data) use ($shape) {
            $result = true;

            foreach ($shape as $key => $schema) {
                if (!isset($data[$key])) {
                    $result = false;
                    break;
                }

                $value = $data[$key];

                if (!$schema->isValid($value)) {
                    $result = false;
                }
            }

            return $result;
        };
    }
}
