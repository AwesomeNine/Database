# Database

[![Awesome9](https://img.shields.io/badge/Awesome-9-brightgreen)](https://awesome9.co)
[![Latest Stable Version](https://poser.pugx.org/awesome9/database/v/stable)](https://packagist.org/packages/awesome9/database)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/awesome9/database.svg)](https://packagist.org/packages/awesome9/database)
[![Total Downloads](https://poser.pugx.org/awesome9/database/downloads)](https://packagist.org/packages/awesome9/database)
[![License](https://poser.pugx.org/awesome9/database/license)](https://packagist.org/packages/awesome9/database)

<p align="center">
	<img src="https://img.icons8.com/nolan/256/database.png"/>
</p>

## 📃 About Database

This package is an expressive query builder for WordPress, it ease the SQL generation and takes care of sanitization of data as well.

### Data Sanitisation

The purpose of this library is to provide an **expressive** and **safe*** way
to run queries against your WordPress database (typically involving custom tables).

To this end all **values** provided are escaped, but note that **column and table**
names are not yet escaped. In any case, even if they were you should be whitelisting
any allowed columns/tables: otherwise using user-input, or other untrusted data to
determine the column/table could allow an attacker to retrieve data they shouldn't
or generate a map of your database.

---

## 💾 Installation

``` bash
composer require awesome9/database
```

## 🕹 Usage

For complete usage details goto [documentation](https://github.com/AwesomeNine/Database/wiki)

```php
include('vendor/autoload.php');

$select = new Awesome9\Database\select( 'unique_query_id', 'users' );

$select->where( 'id', 2 )
  ->orderBy( 'id', 'desc' )
  ->limit( 20 )
  ->execute();
```

## 📖 Changelog

[See the changelog file](./CHANGELOG.md)
