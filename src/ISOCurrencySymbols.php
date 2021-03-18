<?php

declare(strict_types=1);

namespace DeSmart\Larvel\Money;

class ISOCurrencySymbols
{
    private static array $symbols = [];

    public function getSymbol(string $currency): string
    {
        return self::getSymbols()[$currency] ?? $currency;
    }

    private function getSymbols(): array
    {
        if (empty(self::$symbols)) {
            self::$symbols = $this->loadSymbols();
        }

        return self::$symbols;
    }

    private function loadSymbols(): array
    {
        $file = __DIR__ . '/../resources/currency_symbols.php';

        if (file_exists($file)) {
            return require $file;
        }

        throw new \RuntimeException('Failed to load currency symbols.');
    }
}