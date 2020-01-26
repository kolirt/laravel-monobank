<?php

namespace Kolirt\Monobank\Traits;

use Exception;
use Illuminate\Support\Collection;
use stdClass;

trait PublicMethods
{

    /**
     * Get exchange rate for currency pair.
     *
     * @param $from
     * @param $to
     * @return float
     */
    public function rate($from, $to): float
    {
        $this->validateCurrencyTypeOrException($from, $to);
        $pair = $this->getPair($from, $to);
        return $pair->baseCurrency == $from ? $pair->baseRate : $pair->relatedRate;
    }

    /**
     * Get currency pair.
     *
     * @param $from
     * @param $to
     * @return stdClass
     */
    public function pair($from, $to): stdClass
    {
        $this->validateCurrencyTypeOrException($from, $to);
        return $this->getPair($from, $to);
    }

    /**
     * Get currency pairs by currency code.
     *
     * @param null $currency
     * @return Collection
     */
    public function pairs($currency = null): Collection
    {
        if (!is_null($currency)) {
            $this->validateCurrencyTypeOrException($currency);
        }

        $pairs = $this->getPairs($currency);

        if (!is_null($currency)) {
            return $pairs->filter(function ($item) use ($currency) {
                $data = [
                    $item->baseCurrency == $currency,
                    $item->baseCurrencyCode,
                    $item->relatedCurrency,
                    $item->relatedCurrencyCode
                ];
                return in_array($currency, $data);
            })->values();
        }

        return $pairs;
    }

    /**
     * Calculate exchange result.
     *
     * @param $from
     * @param $to
     * @param float $amount
     * @return float
     */
    public function exchange($from, $to, float $amount): float
    {
        $this->validateCurrencyTypeOrException($from, $to);
        $rate = $this->rate($from, $to);
        return toFixed($amount * $rate);
    }

    /**
     * Check to pair exist.
     *
     * @param $from
     * @param $to
     * @return bool
     */
    public function pairExist($from, $to): bool
    {
        try {
            $this->getPair($from, $to);
            return true;
        } catch (Exception $exception) {
        }
        return false;
    }

    /**
     * Validate list of currency ISO codes or ISO numbers.
     *
     * @param mixed ...$currencies
     * @return bool
     */
    public function validateCurrencyTypes(...$currencies): bool
    {
        foreach ($currencies as $currency) {
            $stingValidation = is_string($currency) && in_array($currency, array_keys(self::CURRENCIES));
            $integerValidation = is_integer($currency) && in_array($currency, self::CURRENCIES);
            if (!$stingValidation && !$integerValidation) {
                return false;
            }
        }
        return true;
    }

}