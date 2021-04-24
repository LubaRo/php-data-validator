# PHP Validator

[![GitHub CI](https://github.com/LubaRo/php-oop-project-lvl1/actions/workflows/ci-check.yml/badge.svg)](https://github.com/LubaRo/php-oop-project-lvl1/actions/workflows/ci-check.yml)

Validate strings, numbers and arrays according to predefined or custom defined rules.

### Installation

`composer require lubaro/php-validator`

### Validating strings
___

- `required()` - checks that string is exists and not empty
- `minLength(int $minLength)` - checks that the checked value length is equal or greater than passed min length value
- `contains(string $substr)` - checks that the checked value contains one or more times the passed substring

```php
$v = new LubaRo\PhpValidator\Validator();

$stringValidator = $v->string(); // say to validator that we want to validate strings

$stringValidator->isValid(''); // => true
$stringValidator->required()->isValid(''); // => false

$ruleSet1 = $stringValidator->required()->contains('aka');

$ruleSet1->isValid('Checked value aka string value'); // => true
$ruleSet1->isValid('Simple string'); // => false
```

### Validating numbers
___

- `required()` - checks that number is exists
- `positive()` - checks that number is greater than zero
- `range(int|float $min, int|float $max)` - checks that number value is between or equal to the provided values

```php
$v = new LubaRo\PhpValidator\Validator();

$v->number()->isValid(45.15); // => true
$v->number()->isValid('45.15'); // => false
$v->number()->range(-4, 15)->isValid(0); // => true
```

### Validating arrays
___
- `required()` - checks that array is exists
- `sizeof(int $size)` - checks that array size is equal to given value
- `shape(array $shape)` - checks that array contains specified elements

```php
$v = new LubaRo\PhpValidator\Validator();

$v->array()->isValid([1, 2, 3]); // => true
$v->array()->sizeof(2)->isValid(['name' => 'Pablo', 'age' => 27]); // => true

$arrayShape = $v->array()->shape([
    'name' => $v->string()->required(),
    'age'  => $v->number()->positive()
]);

$arrayShape->isValid(['name' => 'Pablo', 'age' => 27, 'hobbies' => []]); // => true
$arrayShape->isValid(['name' => 'Jake', 'hobbies' => []]); // => false
$arrayShape->isValid(['name' => 'Gary', 'age' => -1800]); // => false

```

### Custom defined rules
___
You can add own rules to any type of validators such as string, number or array

```php
$v = new LubaRo\PhpValidator\Validator();

// in custom function first parameter is a validating value
// other parameters come after
// for custom functions number of parameters is not restricted
$fn = fn($checkedNumber, $param1) => $checkedNumber < $param1;

// addValidator(validatorName, customFunctionName, function);
$v->addValidator('number', 'isLessThan', $fn);

// apply custom function using test(customFuncName, ...params)
$lessThan15 = $v->number()->required()->test('isLessThan', 15);

$lessThan15->isValid(5);  // => true 
$lessThan15->isValid(25); // => false 


```