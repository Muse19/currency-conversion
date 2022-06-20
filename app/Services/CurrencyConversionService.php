<?php

namespace App\Services;

use App\Traits\ConsumesExternalServices;

class CurrencyConversionService
{
    use ConsumesExternalServices;

    protected $baseUri;

    protected $apiKey;

    public function __construct()
    {
        $this->baseUri = config('services.currency_conversion.base_uri');
        $this->apiKey = config('services.currency_conversion.api_key');
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $queryParams['apiKey'] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {
        return $this->apiKey;
    }

    public function convertCurrency($from, $to, $amount)
    {
        $conversionRate = $this->getConversionRate($from, $to);

        $data = [
            'converted_amount' => number_format($amount * $conversionRate, 6),
            'conversion_rate_from' => number_format($conversionRate, 6),
            'conversion_rate_to' => number_format(1 / $conversionRate, 6),
            'conversion_rate_date' => date('Y-m-d H:i:s'),
        ];

        return $data;
    }

    public function getCurrencies()
    {
        try {
            $response = $this->makeRequest(
                'GET',
                '/api/v7/currencies',
            );
        } catch (\Exception $e) {
            throw new \Exception('Unable to retrieve currencies.', 500);
        }

        $response = (array) $response->results;

        return array_values($response);
    }

    private function getConversionRate($from, $to)
    {
        try {
            $response = $this->makeRequest(
                'GET',
                '/api/v7/convert',
                [
                    'q' => "{$from}_{$to}",
                    'compact' => 'ultra',
                ],
            );

            return $response->{strtoupper("{$from}_{$to}")};
        } catch (\Exception $e) {
            throw new \Exception('Unable to get conversion rate', 500);
        }
    }
}
