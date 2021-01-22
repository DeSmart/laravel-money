<?php

declare(strict_types=1);

namespace DeSmart\Larvel\Money\Formatters;

use Money\Currencies\ISOCurrencies;
use Money\Money;
use Money\MoneyFormatter;

class IntlDecimalMoneyFormatter implements MoneyFormatter
{
    public const AMOUNT_FORMAT = '{AMOUNT}';
    public const CURRENCY_AMOUNT_FORMAT = '{CURRENCY}{AMOUNT}';
    public const CURRENCY_SPACE_AMOUNT_FORMAT = '{CURRENCY}{SPACE}{AMOUNT}';
    public const AMOUNT_CURRENCY_FORMAT = '{AMOUNT}{CURRENCY}';
    public const AMOUNT_SPACE_CURRENCY_FORMAT = '{AMOUNT}{SPACE}{CURRENCY}';

    private string $format;
    private string $decimalSeparator;
    private string $thousandsSeparator;
    private ISOCurrencies $currencies;

    public function __construct(string $format, string $decimalSeparator = '.', string $thousandsSeparator = ',')
    {
        $this->format = $format;
        $this->decimalSeparator = $decimalSeparator;
        $this->thousandsSeparator = $thousandsSeparator;
        $this->currencies = new ISOCurrencies();
    }

    public function format(Money $money): string
    {
        $float = $money->absolute()->getAmount() / 100.0;
        $decimals = $this->currencies->subunitFor($money->getCurrency());
        $currency = $money->getCurrency()->getCode();

        $amount = number_format($float, $decimals, $this->decimalSeparator, $this->thousandsSeparator);

        $formatted = str_replace(
            ['{CURRENCY}', '{AMOUNT}', '{SPACE}'],
            [$currency, $amount, ' '],
            $this->format
        );

        return $money->isNegative() ? '-' . $formatted : $formatted;
    }
}