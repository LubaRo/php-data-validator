<?php

namespace Hexlet\Validator\Validators;

class ArrayValidator extends AValidator
{
    public function basicCheck(): callable
    {
        return fn(mixed $data) => is_array($data) || is_null($data);
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
        return function ($data) use ($shape): bool {
            $result = true;

            foreach ($shape as $key => $schema) {
                $value = $data[$key] ?? null;

                if (!$schema->isValid($value)) {
                    $result = false;
                }
            }

            return $result;
        };
    }
}
