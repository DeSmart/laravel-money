# Laravel Money

[![Latest version](https://img.shields.io/packagist/v/desmart/laravel-money.svg?style=flat)](https://github.com/DeSmart/laravel-money)
![Tests](https://github.com/desmart/laravel-money/workflows/Run%20Tests/badge.svg)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/DeSmart/laravel-money/blob/master/LICENSE)

Package provides a simple wrapper around `\Money\Money` objects creation and formatting.

## Installation
To install the package via Composer, simply run the following command:
```
composer require desmart/laravel-money
```

## Usage
Package provides three main elements:
- money objects factory,
- money objects formatter,
- money objects casting class.

### Factory
Factory streamlines `\Money\Money` objects instantiation. `\Money\Money` objects can be created using either integer or 
float values.

```php
\DeSmart\Larvel\Money\MoneyFactory::fromInteger(1000); // New object with the lowest subunit of the (default) currency
\DeSmart\Larvel\Money\MoneyFactory::fromInteger(1000, 'PLN'); // New object with specified currency
```

```php 
\DeSmart\Larvel\Money\MoneyFactory::fromFloat(10); // New object with 'regular' unit of the (default) currency, i.e. 10.50 USD means 10 dollars and 50 cents
\DeSmart\Larvel\Money\MoneyFactory::fromFloat(10, 'PLN') // New object with specified currency
```

#### Default currency
In case application does not handle multiple currencies, it is recommended to set up default currency to the required
one. It speeds up coding and make code less error prone. Default currency can be set up in a very easy way:
```php
// For example, in AppServiceProvider.php

\DeSmart\Larvel\Money\MoneyFactory::$defaultCurrency = 'USD'; // Package's default is set to 'EUR'
```

### Formatter
To pretty print money value, simply call `\DeSmart\Larvel\Money\MoneyFormatter::prettyPrint($money)`. `prettyPrint` 
method accepts also a second parameter that indicates if the formatted value should contain html entities - 
effectively, every space in the formatted value will be replaced with `&nbsp`.

`\DeSmart\Larvel\Money\MoneyFormatter` behind the scenes uses registered formatter class. If no custom class is
registered, default `\Money\Formatter\DecimalMoneyFormatter` formatter will be used. 

This package ships with an additional formatter, `\DeSmart\Larvel\Money\Formatters\IntlDecimalMoneyFormatter`, which 
allows defining a particular format in which money value should be presented. There are few defaults (as consts):
- display only amount (`{AMOUN}`),
- display currency and amount (`{CURRENCY}{AMOUNT}`),
- display currency and amount, separated with space (`{CURRENCY}{SPACE}{AMOUNT}`),
- display amount and currency (`{AMOUNT}{CURRENCY}`),
- display amount and currency, separated with space (`{AMOUNT}{SPACE}{CURRENCY}`).
Any other format can be used, as long as it utilizes three keywords: `{AMOUNT}`, `{CURRENCY}`, `{SPACE}`.
Along with the format, decimal and thousands separators can be defined.
  
Registering formatter is fairly easy:
```php
// For example, in AppServiceProvider.php

\DeSmart\Larvel\Money\MoneyFormatter::formatUsing(
    new \DeSmart\Larvel\Money\Formatters\IntlDecimalMoneyFormatter('{AMOUNT}{CURRENCy}', ',', ' ')
);
```

### Laravel attribute casting
Package provides also a custom casting class that allows to use `\Money\Money` objects with Laravel models.

```php
protected $casts = [
    'money' => \DeSmart\Larvel\Money\Casts\Money::class,
];
```

Casting class gets/sets money-like value from/to the model and also has a method that serializes `\Money\Money` objects
to an array (when `toArray` or `toJson` methods are used on the model):
```php
[
    // ... 
    'money' => [
        'amount' => '10000',
        'currency' => 'PLN',
        'formatted' = '100,00 PLN',
        'float' = 100.0
    ],
]
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.