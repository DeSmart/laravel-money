<?php

declare(strict_types=1);

namespace DeSmart\Larvel\Money;

use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

class MoneyFormatter
{
    private static ?\Money\MoneyFormatter $formatter = null;

    public static function formatUsing(\Money\MoneyFormatter $formatter)
    {
        self::$formatter = $formatter;
    }

    public static function getFormatter(): \Money\MoneyFormatter
    {
        return self::$formatter ?? new DecimalMoneyFormatter(MoneyFactory::getCurrencies());
    }

    public static function prettyPrint(Money $money, bool $htmlEntities = false): string
    {
        $formatted = self::getFormatter()->format($money);

        if ($htmlEntities) {
            $formatted = str_replace(' ', '&nbsp', $formatted);
        }

        return $formatted;
    }

    public static function jsonSerialize(Money $money): array
    {
        $serialized = $money->jsonSerialize();
        $serialized['formatted'] = static::prettyPrint($money);
        $serialized['float'] = MoneyFactory::toFloat($money);

        return $serialized;
    }
}
