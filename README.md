# Laravel Monobank

This package was created for [Monobank API](https://api.monobank.ua/docs).

## Installation
```
$ composer require kolirt/laravel-monobank
```
```
$ php artisan monobank:install
```

## Methods

- [Currency rates](#currency-rates)
    - [Get exchange rate for currency pair.](#rate)
    - [Get currency pair.](#pair)
    - [Get currency pairs by currency code.](#pairs)
    - [Calculate exchange result.](#exchange)
    - [Check to pair exist.](#pairExist)
    - [Validate list of currency ISO codes or ISO numbers.](#validateCurrencyTypes)
    
### Currency rates

#### rate
Get exchange rate for currency pair.

```php
<?php

$from = \Kolirt\Monobank\Monobank::CURRENCIES['UAH'];
$to = \Kolirt\Monobank\Monobank::CURRENCIES['USD'];

$rate = \Kolirt\Monobank\Facade\Monobank::rate($from, $to); // float rate($from, $to)

// OR

$rate = \Kolirt\Monobank\Facade\Monobank::rate('UAH', 'USD');
```

#### pair
Get currency pair.

```php
<?php

$from = \Kolirt\Monobank\Monobank::CURRENCIES['UAH'];
$to = \Kolirt\Monobank\Monobank::CURRENCIES['USD'];

$pair = Monobank::pair($from, $to); // stdClass pair($from, $to)

// OR

$pair = Monobank::pair('UAH', 'USD');
```

#### pairs
Get currency pairs by currency code.


```php
<?php

$currency = \Kolirt\Monobank\Monobank::CURRENCIES['EUR'];

$pairs = Monobank::pairs($currency); // Collection pairs($currency = null)

// OR

$pairs = Monobank::pairs('EUR');
```

#### exchange
Calculate exchange result.

```php
<?php

$from = \Kolirt\Monobank\Monobank::CURRENCIES['UAH'];
$to = \Kolirt\Monobank\Monobank::CURRENCIES['USD'];

$total = Monobank::exchange($from, $to, 1000); // float exchange($from, $to, float $amount)

// OR

$total = Monobank::exchange('UAH', 'USD', 1000);
```

#### pairExist
Check to pair exist.

```php
<?php

$from = \Kolirt\Monobank\Monobank::CURRENCIES['UAH'];
$to = \Kolirt\Monobank\Monobank::CURRENCIES['USD'];

$total = Monobank::pairExist($from, $to); // bool pairExist($from, $to)

// OR

$total = Monobank::pairExist('UAH', 'USD');
```

#### validateCurrencyTypes
Validate list of currency ISO codes or ISO numbers.

```php
<?php

$first = \Kolirt\Monobank\Monobank::CURRENCIES['UAH'];
$second = \Kolirt\Monobank\Monobank::CURRENCIES['USD'];
$third = \Kolirt\Monobank\Monobank::CURRENCIES['EUR'];

$bool = Monobank::validateCurrencyTypes($first, $second, $third); // bool validateCurrencyTypes(string|integer ...$currency)

// OR

$bool = Monobank::validateCurrencyTypes('UAH', 'USD', 'EUR');
```
