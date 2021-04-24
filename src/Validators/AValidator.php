<?php

namespace LubaRo\PhpValidator\Validators;

abstract class AValidator implements IValidator
{
    protected array $rules;
    protected array $customRules;

    public function __construct(array $rules = [], array $customRules = [])
    {
        $this->rules = $rules;
        $this->customRules = $customRules;
    }

    abstract protected function basicCheck(): callable;

    public function isValid(mixed $data): bool
    {
        $result = true;

        $basicChecker = static::basicCheck();
        if (!$basicChecker($data)) {
            return false;
        }

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
        $this->rules = $ruleSet;

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

    protected function getCustomRuleByName(string $name): ?callable
    {
        $customRules = $this->getCustomRules();

        return $customRules[$name] ?? null;
    }

    public function test(string $methodName, mixed ...$params): static
    {
        $method = $this->getCustomRuleByName($methodName);

        if ($method === null) {
            throw new \Exception('Trying to call undefined method ' . $methodName);
        }

        $rule = fn($data) => $method($data, ...$params);

        return static::addRule($rule);
    }
}
