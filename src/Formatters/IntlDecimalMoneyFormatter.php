<?php

declare(strict_types=1);

namespace DeSmart\Larvel\Money\Formatters;

use DeSmart\Larvel\Money\ISOCurrencySymbols;
use DeSmart\Larvel\Money\MoneyFactory;
use Money\Currencies;
use Money\Money;
use Money\MoneyFormatter;

class IntlDecimalMoneyFormatter implements MoneyFormatter
{
    public const AMOUNT_FORMAT = '{AMOUNT}';
    public const CURRENCY_AMOUNT_FORMAT = '{CURRENCY}{AMOUNT}';
    public const CURRENCY_SPACE_AMOUNT_FORMAT = '{CURRENCY}{SPACE}{AMOUNT}';
    public const AMOUNT_CURRENCY_FORMAT = '{AMOUNT}{CURRENCY}';
    public const AMOUNT_SPACE_CURRENCY_FORMAT = '{AMOUNT}{SPACE}{CURRENCY}';
    public const CURRENCY_SYMBOL_AMOUNT_FORMAT = '{CURRENCY_SYMBOL}{AMOUNT}';
    public const CURRENCY_SYMBOL_SPACE_AMOUNT_FORMAT = '{CURRENCY_SYMBOL}{SPACE}{AMOUNT}';
    public const AMOUNT_CURRENCY_SYMBOL_FORMAT = '{AMOUNT}{CURRENCY_SYMBOL}';
    public const AMOUNT_SPACE_CURRENCY_SYMBOL_FORMAT = '{AMOUNT}{SPACE}{CURRENCY_SYMBOL}';

    private string $format;
    private string $decimalSeparator;
    private string $thousandsSeparator;
    private Currencies $currencies;
    private ISOCurrencySymbols $currencySymbols;

    public function __construct(string $format, string $decimalSeparator = '.', string $thousandsSeparator = ',')
    {
        $this->format = $format;
        $this->decimalSeparator = $decimalSeparator;
        $this->thousandsSeparator = $thousandsSeparator;
        $this->currencies = MoneyFactory::getCurrencies();
        $this->currencySymbols = new ISOCurrencySymbols();
    }

    public function format(Money $money): string
    {
        $currencySubunit = $this->currencies->subunitFor($money->getCurrency());

        $float = $money->absolute()->getAmount() / pow(10, $currencySubunit) * 1.0;
        $currency = $money->getCurrency()->getCode();
        $currencySymbol = '';

        if (str_contains($this->format, '{CURRENCY_SYMBOL}')) {
            $currencySymbol = $this->currencySymbols->getSymbol($currency);
        }

        $amount = number_format($float, $currencySubunit, $this->decimalSeparator, $this->thousandsSeparator);

        $formatted = str_replace(
            ['{CURRENCY}', '{CURRENCY_SYMBOL}', '{AMOUNT}', '{SPACE}'],
            [$currency, $currencySymbol, $amount, ' '],
            $this->format
        );

        return $money->isNegative() ? '-' . $formatted : $formatted;
    }
}
