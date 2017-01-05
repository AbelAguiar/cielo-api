# PHP-SDK for Cielo API 3.0

I'm working on it, don't take my changes seriously by now.
___Documentation of Cielo___: https://developercielo.github.io/Webservice-3.0/

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
composer require ianrodriguesbr/cielo-api
```
or you can add this in your `composer.json`
```json
"require": {
    "ianrodriguesbr/cielo-api": "^1.1"
}
```

Performing a Simple Transaction
----
```php
<?php

require_once('vendor/autoload.php');

use IanRodriguesBR\Cielo\Cielo;
use IanRodriguesBR\Cielo\Payments\CreditCardPayment;

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
    'amount' => 259.90
]);

// Set the Customer and the Payment Method...
$cielo->setCustomer('John F. Doe')
      ->setPaymentMethod($debitCard);

// Performs a transaction...
$response = $cielo->performTransaction();
```
Consulting a Transaction
----
```php
<?php

require_once('vendor/autoload.php');

use IanRodriguesBR\Cielo\Cielo;

// Create a new instance of Cielo...
$cielo = new Cielo(
    'Your Merchant ID goes here',
    'Your Merchant Key goes here'
);

// Consult a transaction by id...
$response = $cielo->consultTransaction('Transaction ID goes here');
```