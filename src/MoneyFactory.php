<?php

declare(strict_types=1);

namespace DeSmart\Larvel\Money;

use Money\Currency;
use Money\Money;

class MoneyFactory
{
    public static string $defaultCurrency = 'EUR';

    public static function fromFloat(float $amount, ?string $currency = null): Money
    {
        return new Money(round($amount * 100), new Currency($currency ?? static::$defaultCurrency));
    }

    public static function fromInteger(int $amount, ?string $currency = null): Money
    {
        return new Money($amount, new Currency($currency ?? static::$defaultCurrency));
    }

    public static function toFloat(Money $money): float
    {
        return $money->getAmount() / 100.0;
    }
}
