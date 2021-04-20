<?php

namespace Validator\Validators;

abstract class AValidator implements ValidatorInreface
{
    protected array $rules;

    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
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

    protected function addRule(callable $rule): static
    {
        $ruleSet = $this->getRules();
        $ruleSet[] = $rule;

        return new static($ruleSet);
    }

    public function getRules(): array
    {
        return $this->rules;
    }
}
