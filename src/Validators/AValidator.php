<?php

namespace Validator\Validators;

abstract class AValidator implements ValidatorInreface
{
    protected array $rules;
    protected array $customRules;

    public function __construct(array $rules = [], array $customRules = [])
    {
        $this->rules = $rules;
        $this->customRules = $customRules;
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

    public function addRule(callable $rule): static
    {
        $ruleSet = $this->getRules();
        $ruleSet[] = $rule;

        return new static($ruleSet, $this->getCustomRules());
    }

    protected function getRules(): array
    {
        return $this->rules;
    }

    protected function getCustomRules(): array
    {
        return $this->customRules;
    }

    protected function getCustomRule(string $name): ?callable
    {
        $customRules = $this->getCustomRules();

        return $customRules[$name] ?? null;
    }

    public function test(string $methodName, mixed ...$params): static
    {
        $method = $this->getCustomRule($methodName);

        if ($method === null) {
            throw new \Exception('Trying to call undefined method ' . $methodName);
        }

        $rule = fn($data) => $method($data, ...$params);

        return static::addRule($rule);
    }
}
