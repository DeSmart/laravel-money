<?php

declare(strict_types=1);

namespace DeSmart\Laravel\Money\Tests;

use DeSmart\Larvel\Money\MoneyFactory;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class MoneyFactoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        MoneyFactory::$defaultCurrency = 'USD';
    }
    /** @test */
    public function it_should_create_money_object_from_integer_with_default_currency(): void
    {
        $money = MoneyFactory::fromInteger(10000);

        $this->assertEquals('10000', $money->getAmount());
        $this->assertEquals('USD', $money->getCurrency()->getCode());
    }

    /** @test */
    public function it_should_create_money_object_from_integer_with_non_default_currency(): void
    {
        $money = MoneyFactory::fromInteger(10000, 'GBP');

        $this->assertEquals('10000', $money->getAmount());
        $this->assertEquals('GBP', $money->getCurrency()->getCode());
    }

    /** @test */
    public function it_should_create_money_object_from_float_with_default_currency(): void
    {
        $money = MoneyFactory::fromFloat(100.1);

        $this->assertEquals('10010', $money->getAmount());
        $this->assertEquals('USD', $money->getCurrency()->getCode());
    }

    /** @test */
    public function it_should_create_money_object_from_float_with_non_default_currency(): void
    {
        $money = MoneyFactory::fromFloat(100.1, 'GBP');

        $this->assertEquals('10010', $money->getAmount());
        $this->assertEquals('GBP', $money->getCurrency()->getCode());
    }

    /** @test */
    public function it_should_convert_money_amount_to_float(): void
    {
        $money = new Money(10000, new Currency('USD'));

        $float = MoneyFactory::toFloat($money);

        $this->assertEquals(100.0, $float);
        $this->assertIsFloat($float);
    }
}