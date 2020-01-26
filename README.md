# Laravel Monobank
This package was created for [Monobank API](https://api.monobank.ua/docs).

## Installation
```
$ composer require kolirt/laravel-monobank
```
```
$ php artisan monobank:install
```

## Data structure

### CurrencyInfo:object
```php
<?php

{
    +"baseCurrency": 980
    +"baseCurrencyCode": "UAH"
    +"relatedCurrency": 978
    +"relatedCurrencyCode": "EUR"
    +"date": Carbon\Carbon @1579924208 {
        date: 2020-01-25 03:50:08.0 UTC (+00:00)
    }
    +"rateBuy": 26.7
    +"rateSell": 27.1003
    +"baseRate": 0.036899960517042
    +"relatedRate": 26.7
}
```


## Methods
- [Currency rates](#currency-rates)
    - [Get exchange rate for currency pair.](#ratefrom-to)
    - [Get currency pair.](#pairfrom-to)
    - [Get currency pairs by currency code.](#pairscurrency--null)
    - [Calculate exchange result.](#exchangefrom-to-float-amount)
    - [Check to pair exist.](#pairexistfrom-to)
    - [Validate list of currency ISO codes or ISO numbers.](#validatecurrencytypesstringinteger-currency)
    
### Currency rates

#### rate($from, $to)
Get exchange rate for currency pair. Response: **float**

```php
<?php

$from = \Kolirt\Monobank\Monobank::CURRENCIES['UAH'];
$to = \Kolirt\Monobank\Monobank::CURRENCIES['USD'];

$rate = \Kolirt\Monobank\Facade\Monobank::rate($from, $to);

// OR

$rate = \Kolirt\Monobank\Facade\Monobank::rate('UAH', 'USD');
```

#### pair($from, $to)
Get currency pair. Response: **[CurrencyInfo:object](#currencyinfoobject)**

```php
<?php

$from = \Kolirt\Monobank\Monobank::CURRENCIES['UAH'];
$to = \Kolirt\Monobank\Monobank::CURRENCIES['USD'];

$pair = Monobank::pair($from, $to);

// OR

$pair = Monobank::pair('UAH', 'USD');
```

#### pairs($currency = null)
Get currency pairs by currency code. Response: **Collection of [CurrencyInfo:object](#currencyinfoobject)**

```php
<?php

$currency = \Kolirt\Monobank\Monobank::CURRENCIES['EUR'];

$pairs = Monobank::pairs($currency);

// OR

$pairs = Monobank::pairs('EUR');
```

#### exchange($from, $to, float $amount)
Calculate exchange result. Response: **float**

```php
<?php

$from = \Kolirt\Monobank\Monobank::CURRENCIES['UAH'];
$to = \Kolirt\Monobank\Monobank::CURRENCIES['USD'];

$total = Monobank::exchange($from, $to, 1000);

// OR

$total = Monobank::exchange('UAH', 'USD', 1000);
```

#### pairExist($from, $to)
Check to pair exist. Response: **bool**

```php
<?php

$from = \Kolirt\Monobank\Monobank::CURRENCIES['UAH'];
$to = \Kolirt\Monobank\Monobank::CURRENCIES['USD'];

$total = Monobank::pairExist($from, $to);

// OR

$total = Monobank::pairExist('UAH', 'USD');
```

#### validateCurrencyTypes(string|integer ...$currency)
Validate list of currency ISO codes or ISO numbers. Response: **bool**

```php
<?php

$first = \Kolirt\Monobank\Monobank::CURRENCIES['UAH'];
$second = \Kolirt\Monobank\Monobank::CURRENCIES['USD'];
$third = \Kolirt\Monobank\Monobank::CURRENCIES['EUR'];

$bool = Monobank::validateCurrencyTypes($first, $second, $third);

// OR

$bool = Monobank::validateCurrencyTypes('UAH', 'USD', 'EUR');
```
