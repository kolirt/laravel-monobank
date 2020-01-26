<?php

namespace Kolirt\Monobank;

use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Kolirt\Monobank\Traits\PublicMethods;
use Kolirt\Monobank\Traits\Transformers;
use stdClass;

class Monobank
{

    use PublicMethods, Transformers;

    private $client;
    private $endpoint = 'https://api.monobank.ua';

    private $rates = null;

    const CURRENCIES = [
        'USD' => 840,
        'EUR' => 978,
        'RUB' => 643,
        'PLN' => 985,
        'GBP' => 826,
        'JPY' => 392,
        'CHF' => 756,
        'CNY' => 156,
        'AED' => 784,
        'AFN' => 971,
        'ALL' => 8,
        'AMD' => 51,
        'AOA' => 973,
        'ARS' => 32,
        'AUD' => 36,
        'AZN' => 944,
        'BDT' => 50,
        'BGN' => 975,
        'BHD' => 48,
        'BIF' => 108,
        'BND' => 96,
        'BOB' => 68,
        'BRL' => 986,
        'BWP' => 72,
        'BYN' => 933,
        'CAD' => 124,
        'CDF' => 976,
        'CLP' => 152,
        'COP' => 170,
        'CRC' => 188,
        'CUP' => 192,
        'CZK' => 203,
        'DJF' => 262,
        'DKK' => 208,
        'DZD' => 12,
        'EGP' => 818,
        'ETB' => 230,
        'GEL' => 981,
        'GHS' => 936,
        'GMD' => 270,
        'GNF' => 324,
        'HKD' => 344,
        'HRK' => 191,
        'HUF' => 348,
        'IDR' => 360,
        'ILS' => 376,
        'INR' => 356,
        'IQD' => 368,
        'IRR' => 364,
        'ISK' => 352,
        'JOD' => 400,
        'KES' => 404,
        'KGS' => 417,
        'KHR' => 116,
        'KPW' => 408,
        'KRW' => 410,
        'KWD' => 414,
        'KZT' => 398,
        'LAK' => 418,
        'LBP' => 422,
        'LKR' => 144,
        'LYD' => 434,
        'MAD' => 504,
        'MDL' => 498,
        'MGA' => 969,
        'MKD' => 807,
        'MNT' => 496,
        'MUR' => 480,
        'MWK' => 454,
        'MXN' => 484,
        'MYR' => 458,
        'MZN' => 943,
        'NAD' => 516,
        'NGN' => 566,
        'NIO' => 558,
        'NOK' => 578,
        'NPR' => 524,
        'NZD' => 554,
        'OMR' => 512,
        'PEN' => 604,
        'PHP' => 608,
        'PKR' => 586,
        'PYG' => 600,
        'QAR' => 634,
        'RON' => 946,
        'RSD' => 941,
        'SAR' => 682,
        'SCR' => 690,
        'SDG' => 938,
        'SEK' => 752,
        'SGD' => 702,
        'SLL' => 694,
        'SOS' => 706,
        'SRD' => 968,
        'SYP' => 760,
        'SZL' => 748,
        'THB' => 764,
        'TJS' => 972,
        'TND' => 788,
        'TRY' => 949,
        'TWD' => 901,
        'TZS' => 834,
        'UGX' => 800,
        'UYU' => 858,
        'UZS' => 860,
        'VND' => 704,
        'XAF' => 950,
        'XOF' => 952,
        'YER' => 886,
        'ZAR' => 710,
        'UAH' => 980,
    ];

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->endpoint,
            'timeout'  => config('monobank.timeout', 3),
            'headers'  => [
                'Accept' => 'application/json'
            ]
        ]);
    }

    /**
     * Get all currency rates from Monobank API.
     *
     * @param null $baseCurrency
     * @return Collection
     * @throws Exception
     */
    private function getPairs($baseCurrency = null): Collection
    {
        if (is_null($this->rates)) {
            try {
                $request = $this->client->get('/bank/currency');
            } catch (Exception $exception) {
                $request = $exception->getResponse();
                Log::error($exception);
            }

            if ($request->getStatusCode() === 200) {
                $response = $request->getBody()->getContents();
                if (is_json($response)) {
                    $this->rates = collect($this->transformCurrencyModel(json_decode($response), $baseCurrency));
                    return $this->rates;
                }
            }

            $this->rates = collect([]);
            return $this->rates;
        }

        return $this->rates;
    }

    /**
     * Get currency pair.
     *
     * @param $from
     * @param $to
     * @return stdClass
     * @throws Exception
     */
    private function getPair($from, $to): stdClass
    {
        $this->validateCurrencyTypeOrException($from, $to);

        $currencies = $this->getPairs($from);

        $currency = $currencies->first(function ($item) use ($from, $to) {
            $rule1 = $item->baseCurrency == $from && $item->relatedCurrency == $to;
            $rule2 = $item->baseCurrency == $to && $item->relatedCurrency == $from;
            return $rule1 || $rule2;
        });

        if ($currency) {
            return $currency;
        }

        throw new Exception('Currencies pair ' . $from . '-' . $to . ' is not found.');
    }

    /**
     * Throw exception if list of currency ISO codes or ISO numbers are not valid.
     *
     * @param string|integer ...$currencies
     * @return bool
     * @throws Exception
     */
    private function validateCurrencyTypeOrException(...$currencies): bool
    {
        foreach ($currencies as $currency) {
            if (is_string($currency) && !in_array($currency, array_keys(self::CURRENCIES))) {
                throw new Exception('Not available currency ISO code: ' . $currency . '.');
            } else if (is_integer($currency) && !in_array($currency, self::CURRENCIES)) {
                throw new Exception('Not available currency ISO number code: ' . $currency . '.');
            } else if (!is_integer($currency) && !is_string($currency)) {
                throw new Exception('Not available currency format.');
            }
        }
        return true;
    }


}
