<?php

namespace Kolirt\Monobank\Facade;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use stdClass;

/**
 * @method static float rate($from, $to) Get exchange rate for currency pair.
 * @method static stdClass pair($from, $to) Get currency pair.
 * @method static Collection pairs($currency = null) Get currency pairs by currency code.
 * @method static float exchange($from, $to, float $amount) Calculate exchange result.
 * @method static bool pairExist($from, $to) Check to pair exist.
 * @method static bool validateCurrencyTypes(string|integer ...$currency) Validate list of currency ISO codes or ISO numbers.
 */
class Monobank extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'monobank';
    }

}