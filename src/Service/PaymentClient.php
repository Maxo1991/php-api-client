<?php

declare(strict_types=1);

namespace PaymentAPI\Service;

use PaymentAPI\Exception\InvalidApiKeyException;

class PaymentClient
{
    private const BASE_RATE_UPPERCASE = 115;
    private const BASE_RATE_LOWERCASE = 58;

    public function __construct(private readonly string $apiKey)
    {
    }

    /**
     * Returns the converted rate based on currency and value.
     *
     * @param string $currency
     * @param float $value
     * @return float
     *
     * @throws InvalidApiKeyException if API key is invalid
     */
    public function getRate(string $currency, float $value): float
    {
        $baseRate = $this->getBaseRate();
        $rateAdjustment = $this->getRateAdjustment(strtoupper($currency), $value);

        return $value * ($baseRate + $rateAdjustment);
    }

    /**
     * Determines base rate from the first character of the API key.
     *
     * @return float
     *
     * @throws InvalidApiKeyException if API key does not start with a letter
     */
    private function getBaseRate(): float
    {
        $firstChar = $this->apiKey[0] ?? '';

        if (!$firstChar || !preg_match('/[a-zA-Z]/', $firstChar)) {
            throw new InvalidApiKeyException("API key must start with a letter.");
        }

        return preg_match('/[A-Z]/', $firstChar) ? self::BASE_RATE_UPPERCASE
            : self::BASE_RATE_LOWERCASE;
    }

    /**
     * @param string $currency
     * @param float $value
     * @return float
     */
    private function getRateAdjustment(string $currency, float $value): float
    {
        return match ($currency) {
            'USD' => 5,
            'EUR' => 10,
            'JPY' => -3,
            'BTC' => 57,
            'RSD' => 3,
            'CHF' => 18,
            'GBP' => -2,
            default => 0,
        };
    }
}
