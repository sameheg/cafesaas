<?php

namespace App\Support;

class CurrencyConverter
{
    /**
     * Convert minor unit amount between currencies.
     */
    public function convert(int $amount, string $from, string $to): int
    {
        if ($from === $to) {
            return $amount;
        }

        // Demo rates; in real implementation integrate with FX service.
        $rates = [
            'USD' => ['EUR' => 0.9],
            'EUR' => ['USD' => 1.11],
        ];

        $rate = $rates[$from][$to] ?? 1;

        return (int) round($amount * $rate);
    }
}
