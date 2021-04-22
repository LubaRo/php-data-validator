<?php

namespace Hexlet\Validator\Validators;

use function Symfony\Component\String\u;

class StringValidator extends AValidator
{
    public function basicCheck(): callable
    {
        return fn(mixed $data) => is_string($data);
    }

    public function required(): self
    {
        $rule = fn(string $str) => !u($str)->isEmpty();

        return $this->addRule($rule);
    }

    public function minLength(int $minLength): self
    {
        $rule = fn(string $str) => strlen($str) >= $minLength;

        return $this->addRule($rule);
    }

    public function contains(string $substr): self
    {
        $rule = fn(string $str) => u($str)->containsAny($substr);

        return $this->addRule($rule);
    }

    public function addRule(callable $rule): static
    {
        $ruleSet = $this->getRules();
        $ruleSet[] = $rule;
        $this->rules = $ruleSet;

        return new static($ruleSet, $this->getCustomRules());
    }
}
