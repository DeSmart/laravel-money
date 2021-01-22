<?php

declare(strict_types=1);

namespace DeSmart\Laravel\Money\Tests;

use DeSmart\Larvel\Money\MoneyFormatter;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class MoneyFormatterTest extends TestCase
{
    /** @test */
    public function it_should_pretty_print_money_using_formatter(): void
    {
        $formatter = new class implements \Money\MoneyFormatter {
            public function format(Money $money): string
            {
                return 'money_' . $money->getAmount() . '_' . $money->getCurrency()->getCode();
            }
        };

        MoneyFormatter::formatUsing($formatter);

        $this->assertEquals('money_1000_USD', MoneyFormatter::prettyPrint(new Money(1000, new Currency('USD'))));
    }
}