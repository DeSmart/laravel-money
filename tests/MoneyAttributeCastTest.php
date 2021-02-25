<?php

declare(strict_types=1);

namespace DeSmart\Laravel\Money\Tests;


use DeSmart\Larvel\Money\Casts\Money;
use DeSmart\Larvel\Money\MoneyFactory;
use Illuminate\Database\Eloquent\Model;
use Money\Currency;
use Orchestra\Testbench\TestCase;

class MoneyAttributeCastTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        MoneyFactory::$defaultCurrency = 'USD';
    }

    /** @test */
    public function it_should_get_attribute_as_money_object(): void
    {
        $model = new TestModel();
        $model->setRawAttributes(['money' => 1000]);

        $attribute = $model->getAttribute('money');

        $this->assertInstanceOf(\Money\Money::class, $attribute);
        $this->assertEquals('1000', $attribute->getAmount());
        $this->assertEquals('USD', $attribute->getCurrency()->getCode());
    }

    /** @test */
    public function it_should_get_attribute_as_null_value(): void
    {
        $model = new TestModel(['money' => null]);

        $this->assertNull($model->getAttribute('money'));
    }

    /** @test */
    public function it_should_set_int_as_attribute(): void
    {
        $model = new TestModel(['money' => 1000]);
        $model->setAttribute('money', 2000);

        $this->assertEquals('2000', $model->getAttributes()['money']);
    }

    /** @test */
    public function it_should_set_money_object_as_attribute(): void
    {
        $model = new TestModel(['money' => 1000]);
        $model->setAttribute('money', new \Money\Money(2000, new Currency('USD')));

        $this->assertEquals('2000', $model->getAttributes()['money']);
    }

    /** @test */
    public function it_should_serialize_money_attribute(): void
    {
        $model = new TestModel(['money' => 1000]);

        $modelToArray = $model->toArray();

        $this->assertContains('1000', $modelToArray['money']); //amount
        $this->assertContains('USD', $modelToArray['money']); //currency
        $this->assertContains(10.0, $modelToArray['money']); //float
    }

    /** @test */
    public function it_should_get_money_object_including_custom_currency_attribute(): void
    {
        $model = new TestModelWithCustomCurrencyAttribute();
        $model->setRawAttributes(['money' => 1000, 'custom_currency' => 'ZWL']);

        $attribute = $model->getAttribute('money');

        $this->assertInstanceOf(\Money\Money::class, $attribute);
        $this->assertEquals('1000', $attribute->getAmount());
        $this->assertEquals('ZWL', $attribute->getCurrency()->getCode());
    }

    /** @test */
    public function it_should_serialize_money_attribute_including_custom_currency_attribute(): void
    {
        $model = new TestModelWithCustomCurrencyAttribute(['money' => 1000, 'custom_currency' => 'ZWL']);

        $modelToArray = $model->toArray();

        $this->assertContains('1000', $modelToArray['money']); //amount
        $this->assertContains('ZWL', $modelToArray['money']); //currency
        $this->assertContains(10.0, $modelToArray['money']); //float
    }
}

class TestModel extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'money' => Money::class,
    ];
}

class TestModelWithCustomCurrencyAttribute extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'money' => Money::class . ':custom_currency',
    ];
}