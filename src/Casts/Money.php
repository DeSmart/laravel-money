<?php

declare(strict_types=1);

namespace DeSmart\Larvel\Money\Casts;

use DeSmart\Larvel\Money\MoneyFactory;
use DeSmart\Larvel\Money\MoneyFormatter;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Money implements CastsAttributes
{
    /**
     * @var string
     */
    private string $currencyAttribute = 'currency';

    /**
     * @param string ...$args
     */
    public function __construct(string ...$args)
    {
        if (count($args) === 1) {
            $this->currencyAttribute = $args[0];
        }
    }

    /**
     * @param Model $model
     * @param string $key
     * @param int|null $value
     * @param array $attributes
     * @return \Money\Money|null
     */
    public function get($model, string $key, $value, array $attributes): ?\Money\Money
    {
        if (is_null($value)) {
            return null;
        }

        return MoneyFactory::fromInteger($value, $attributes[$this->currencyAttribute] ?? MoneyFactory::$defaultCurrency);
    }

    /**
     * @param Model $model
     * @param string $key
     * @param \Money\Money|int|string|null $value
     * @param array $attributes
     * @return int|null
     */
    public function set($model, string $key, $value, array $attributes): ?int
    {
        if (is_null($value)) {
            return $value;
        }

        if ($value instanceof \Money\Money) {
            $value = $value->getAmount();
        }

        if (is_int($value) || is_string($value)) {
            return (int) $value;
        }

        // throw exception
        return $value;
    }

    /**
     * @param Model $model
     * @param string $key
     * @param \Money\Money $value
     * @param array $attributes
     * @return mixed
     */
    public function serialize($model, string $key, $value, array $attributes)
    {
        return MoneyFormatter::jsonSerialize($value);
    }
}
