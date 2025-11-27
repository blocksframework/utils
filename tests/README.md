# Utils Tests

PHPUnit test suite for the Blocks Utils module.

## Running Tests

### Run all tests
```bash
./vendor/bin/phpunit
```

### Run all tests with detailed output
```bash
./vendor/bin/phpunit --testdox
```

### Run specific test file
```bash
./vendor/bin/phpunit tests/RandomnessTest.php
./vendor/bin/phpunit tests/TokenTest.php
```

### Run with coverage (requires Xdebug or PCOV)
```bash
./vendor/bin/phpunit --coverage-html coverage/
```

## Test Coverage

### RandomnessTest
Tests for the `Randomness` class:
- ✅ String generation with default and custom character sets
- ✅ Predefined character set constants (NUMERIC, ALPHANUMERIC, etc.)
- ✅ Single character sets
- ✅ Input validation (zero/negative length, empty charset)
- ✅ Multibyte character rejection (UTF-8, emoji)
- ✅ Randomness and distribution verification
- ✅ Large string generation

### TokenTest
Tests for the `Token` class:
- ✅ Hexadecimal token generation
- ✅ Lowercase verification (a-f, 0-9)
- ✅ Even and odd length handling
- ✅ Single character tokens
- ✅ Input validation (zero/negative length)
- ✅ Randomness and distribution verification
- ✅ Large token generation
- ✅ Performance benchmarking
- ✅ Performance comparison vs Randomness (validates efficiency claim)

## Test Statistics
- **Total Tests:** 27
- **Total Assertions:** 106
- **Code Coverage:** Both classes fully covered

## Adding New Tests

1. Create a new test file in `tests/` directory
2. Extend `PHPUnit\Framework\TestCase`
3. Use namespace `Blocks\Utils\Tests`
4. Name test methods with `test` prefix
5. Run tests to verify

Example:
```php
<?php

namespace Blocks\Utils\Tests;

use PHPUnit\Framework\TestCase;

class MyClassTest extends TestCase {
    public function testSomething(): void {
        $this->assertTrue(true);
    }
}
```
