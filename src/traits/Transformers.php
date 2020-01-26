<?php

namespace Kolirt\Monobank\Traits;

use Carbon\Carbon;
use Exception;
use stdClass;

trait Transformers
{

    /**
     * Transform currency info.
     *
     * @param $currencies
     * @param null $baseCurrency
     * @return array|stdClass
     * @throws Exception
     */
    private function transformCurrencyModel($currencies, $baseCurrency = null)
    {
        if (is_array($currencies)) {
            return array_map(function ($currency) use ($baseCurrency) {
                return $this->transformCurrencyModel($currency, $baseCurrency);
            }, $currencies);
        } else if (is_object($currencies)) {
            $currency = new stdClass;

            $currency->baseCurrency = $currencies->currencyCodeB;
            $currency->baseCurrencyCode = array_search($currency->baseCurrency, self::CURRENCIES);

            $currency->relatedCurrency = $currencies->currencyCodeA;
            $currency->relatedCurrencyCode = array_search($currency->relatedCurrency, self::CURRENCIES);

            $currency->date = Carbon::createFromTimestamp($currencies->date);

            $currency->rateBuy = $currencies->rateBuy ?? $currencies->rateCross;
            $currency->rateSell = $currencies->rateSell ?? $currencies->rateCross;
            $currency->baseRate = 1 / $currency->rateSell;
            $currency->relatedRate = $currency->rateBuy;

            return $currency;
        } else {
            throw new Exception('Transaction list type not available.');
        }
    }

}