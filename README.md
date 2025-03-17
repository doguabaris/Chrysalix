# Chrysalix

Chrysalix is an open-source, lightweight PHP library that performs metamorphic code transformations. It creates functionally equivalent variants of your PHP code by applying a series of customizable transformation rules. This makes it an excellent tool for testing, code obfuscation, and exploring alternative coding styles.

[![Latest Stable Version](https://poser.pugx.org/chrysalix/chrysalix/v?style=for-the-badge)](https://packagist.org/packages/chrysalix/chrysalix)
[![Latest Unstable Version](https://poser.pugx.org/chrysalix/chrysalix/v/unstable?style=for-the-badge)](https://packagist.org/packages/chrysalix/chrysalix)
[![License](https://poser.pugx.org/chrysalix/chrysalix/license?style=for-the-badge)](https://packagist.org/packages/chrysalix/chrysalix)
[![PHP Version Require](https://poser.pugx.org/chrysalix/chrysalix/require/php?style=for-the-badge)](https://packagist.org/packages/chrysalix/chrysalix)

## Features

- **Experiment with different code structures** without changing the underlying behavior.
- **Improve your testing processes** by generating multiple representations of the same logic.
- **Obfuscate your code** to protect your intellectual property and deter reverse engineering.

Chrysalix is designed to be **modular** and **extensible**, so you can easily combine the built-in transformations or create your own custom rules.

## Installation

Install the library using Composer:

```bash
composer require chrysalix/chrysalix
```

## Usage

Below is an example of how to use Chrysalix in your project:

```php
<?php
require_once 'vendor/autoload.php';

use Chrysalix\Core\Chrysalix;
use Chrysalix\Transformation\ForToWhileTransformer;
use Chrysalix\Transformation\OperatorReorderer;
use Chrysalix\Transformation\VariableRenamer;

$originalCode = <<<'CODE'
<?php
$a = 5;
$b = 10;
$c = $a + $b;
for ($i = 0; $i < 10; $i++) {
    echo $i;
}
CODE;

// Initialize the Chrysalix engine with your desired transformations
$engine = new Chrysalix([
    new VariableRenamer(['prefix' => 'var']),
    new OperatorReorderer(),
    new ForToWhileTransformer(),
]);

$transformedCode = $engine->process($originalCode);

echo $transformedCode;
```

### Expected Output (Example)
When all transformations are applied, you might get an output like:

```php
<?php
$var1 = 5;
$var2 = 10;
$var3 = $var2 + $var1; // Operators swapped
$i = 0;
while ($i < 10) { // For loop converted to while
    echo $i;
    $i++;
}
```

## Built-in Transformations

Chrysalix comes with several built-in transformation classes that you can use individually or in combination:

### VariableRenamer
Renames variables consistently throughout your code.  
**Example:**
```php
$a = 5; 
$b = 10;
```
Becomes:
```php
$var1 = 5;
$var2 = 10;
```

### OperatorReorderer
Swaps operands in commutative expressions, such as addition (`+`) and multiplication (`*`).  
**Example:**
```php
$c = $a + $b;
```
Becomes:
```php
$c = $b + $a;
```

### ForToWhileTransformer
Converts simple `for` loops into equivalent `while` loops.  
**Example:**
```php
for ($i = 0; $i < 10; $i++) {
    echo $i;
}
```
Becomes:
```php
$i = 0;
while ($i < 10) {
    echo $i;
    $i++;
}
```

## Extending Chrysalix

You can extend Chrysalix by creating your own transformation rules. Simply implement the `ITransformation`:

```php
use Chrysalix\Contract\ITransformation;

class CustomTransformer implements ITransformation
{
    public function transform(array $tokens): array
    {
        // Modify tokens as needed
        return $tokens;
    }
}
```

Add your custom transformer to the engine:

```php
$engine = new Chrysalix([
    new CustomTransformer(),
]);
```

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for contribution guidelines.

## License

This library is licensed under the [MIT License](LICENSE).
