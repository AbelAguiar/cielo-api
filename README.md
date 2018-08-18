# PHP-SDK for Cielo API

[![PHP from Packagist](https://img.shields.io/packagist/php-v/abelaguiar/cielo-api.svg)](https://packagist.org/packages/abelaguiar/cielo-api) [![Packagist](https://img.shields.io/packagist/l/abelaguiar/cielo-api.svg)](https://packagist.org/packages/abelaguiar/cielo-api) [![Packagist](https://img.shields.io/packagist/vpre/abelaguiar/cielo-api.svg)](https://packagist.org/packages/abelaguiar/cielo-api) [![Build Status](https://travis-ci.org/abelaguiar/cielo-api.svg?branch=master)](https://travis-ci.org/abelaguiar/cielo-api)

I'm working on it, don't take my changes seriously by now.
___Documentation of Cielo___: https://developercielo.github.io/manual/cielo-ecommerce#sandbox-e-ferramentas

License
----
MIT

Dependencies
----
* PHP >= 5.6.4

Installing via Composer
----
You can install via terminal, executing the following command:
```shell
composer require abelaguiar/cielo-api
```
or you can add this in your `composer.json`
```json
"require": {
    "abelaguiar/cielo-api": "^1.1.1"
}
```

Performing a Simple Transaction
----
```php
<?php

require_once('vendor/autoload.php');

use AbelAguiar\Cielo\Cielo;
use AbelAguiar\Cielo\Payments\CreditCardPayment;

// Create a new instance of Cielo...
$cielo = new Cielo(
    'Your Merchant ID goes here',
    'Your Merchant Key goes here'
);

// Create a new instance of Payment Method Credit...
$creditCard = new CreditCardPayment([
    'cardNumber' => '0000000000000001',
    'holder' => 'John F Doe',
    'expirationDate' => '12/2020',
    'securityCode' => '123',
    'installments' => 5,
    'amount' => 259.90
]);

// Set the Customer and the Payment Method...
$cielo->setCustomer('John F. Doe')
      ->setPaymentMethod($creditCard);

// Performs a transaction...
$response = $cielo->performTransaction();

// or ------------------------------------

// Create a new instance of Payment Method Debit...
$debitCard = new DebitCardPayment([
    'cardNumber' => '0000000000000001',
    'holder' => 'John F Doe',
    'expirationDate' => '12/2020',
    'securityCode' => '123',
    'amount' => 259.90,
    'returnUrl' => 'https://www.cielo.com.br'
]);

// Set the Customer and the Payment Method...
$cielo->setCustomer('John F. Doe')
      ->setPaymentMethod($debitCard);

// Performs a transaction...
$response = $cielo->performTransaction();
```
Consulting or Capture a Transaction
----
```php
<?php

require_once('vendor/autoload.php');

use AbelAguiar\Cielo\Cielo;

// Create a new instance of Cielo...
$cielo = new Cielo(
    'Your Merchant ID goes here',
    'Your Merchant Key goes here'
);

// Consult a transaction by id...
$response = $cielo->consultTransaction('Transaction ID goes here');

// Consult a transaction by id...
$response = $cielo->captureTransaction('Transaction ID goes here');
```