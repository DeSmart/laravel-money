# Changelog

All notable changes to `laravel-money` will be documented in this file:

## 1.2.0 - 2021-03-16

Changed:
* update `MoneyFactory` and `IntlDecimalMoneyFormatter` to use currency's real subunit when creating and 
  formatting `Money` objects (previously `100` as a fixed number was used)

## 1.1.0 - 2021-02-25

Added:
* added a possibility to customize currency-related attribute when using money-object casting

## 1.0.0 - 2021-01-23

Added:
* initial release
