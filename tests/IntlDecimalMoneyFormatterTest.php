<?php

declare(strict_types=1);

namespace DeSmart\Laravel\Money\Tests;

use DeSmart\Larvel\Money\Formatters\IntlDecimalMoneyFormatter;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class IntlDecimalMoneyFormatterTest extends TestCase
{
    /**
     * @test
     * @dataProvider provider
     *
     * @param int|float $amount
     * @param string $currency
     * @param string $format
     * @param string $decimalSeparator
     * @param string $thousandsSeparator
     * @param string $formatted
     */
    public function it_should_format_money(
        $amount,
        string $currency,
        string $format,
        string $decimalSeparator,
        string $thousandsSeparator,
        string $formatted)
    : void {
        $formatter = new IntlDecimalMoneyFormatter($format, $decimalSeparator, $thousandsSeparator);

        $this->assertEquals($formatted, $formatter->format(new Money($amount, new Currency($currency))));
    }

    public function provider(): array
    {
        return [
            [10010, 'GBP', IntlDecimalMoneyFormatter::AMOUNT_FORMAT, ',', ' ', '100,10'],
            [10010, 'GBP', IntlDecimalMoneyFormatter::AMOUNT_CURRENCY_FORMAT, ',', ' ', '100,10GBP'],
            [10010, 'GBP', IntlDecimalMoneyFormatter::AMOUNT_SPACE_CURRENCY_FORMAT, ',', ' ', '100,10 GBP'],
            [10010, 'GBP', IntlDecimalMoneyFormatter::CURRENCY_AMOUNT_FORMAT, ',', ' ', 'GBP100,10'],
            [10010, 'GBP', IntlDecimalMoneyFormatter::CURRENCY_SPACE_AMOUNT_FORMAT, ',', ' ', 'GBP 100,10'],
            [10010, 'TND', IntlDecimalMoneyFormatter::CURRENCY_SPACE_AMOUNT_FORMAT, ',', ' ', 'TND 10,010'], // 3 digits subunit
            [10010, 'CLP', IntlDecimalMoneyFormatter::CURRENCY_SPACE_AMOUNT_FORMAT, ',', ' ', 'CLP 10 010'], // 0 digits subunit
            [10010, 'GBP', IntlDecimalMoneyFormatter::AMOUNT_CURRENCY_SYMBOL_FORMAT, ',', ' ', '100,10£'],
            [10010, 'GBP', IntlDecimalMoneyFormatter::AMOUNT_SPACE_CURRENCY_SYMBOL_FORMAT, ',', ' ', '100,10 £'],
            [10010, 'GBP', IntlDecimalMoneyFormatter::CURRENCY_SYMBOL_AMOUNT_FORMAT, ',', ' ', '£100,10'],
            [10010, 'GBP', IntlDecimalMoneyFormatter::CURRENCY_SYMBOL_SPACE_AMOUNT_FORMAT, ',', ' ', '£ 100,10'],
        ];
    }
}
