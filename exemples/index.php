<?php

require_once('../vendor/autoload.php');

use IanRodriguesBR\Cielo\Cielo;
use IanRodriguesBR\Cielo\Payments\CreditCardPayment;

$creditCard = new CreditCardPayment([
    'cardNumber' => '4456000000000001',
    'holder' => 'John F Doe',
    'expirationDate' => '12/2016',
    'securityCode' => '123',
    'installments' => 10,
    'amount' => 129.90
]);

var_dump($creditCard);