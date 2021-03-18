<?php

declare(strict_types=1);

namespace DeSmart\Larvel\Money;

use Money\Currencies;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;

class MoneyFactory
{
    public static string $defaultCurrency = 'EUR';
    private static ?Currencies $currencies = null;

    public static function fromFloat(float $amount, ?string $currency = null): Money
    {
        $currency = new Currency($currency ?? static::$defaultCurrency);

        return new Money(round($amount * pow(10, self::getCurrencySubunit($currency))), $currency);
    }

    public static function fromInteger(int $amount, ?string $currency = null): Money
    {
        return new Money($amount, new Currency($currency ?? static::$defaultCurrency));
    }

    public static function toFloat(Money $money): float
    {
        return $money->getAmount() / pow(10, self::getCurrencySubunit($money->getCurrency())) * 1.0;
    }

    public static function getCurrencies(): Currencies
    {
        if (is_null(self::$currencies)) {
            self::$currencies = new ISOCurrencies();
        }

        return self::$currencies;
    }

    private static function getCurrencySubunit(Currency $currency): int
    {
        return self::getCurrencies()->subunitFor($currency);
    }
}
