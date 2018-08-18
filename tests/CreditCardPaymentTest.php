<?php

namespace AbelAguiar\Cielo\Tests;

use AbelAguiar\Cielo\Payments\CreditCardPayment;
use PHPUnit\Framework\TestCase;

class CreditCardPaymentTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidCreditCardPaymentProvider
     */
    public function an_exception_must_be_raised_with_invalid_data($data)
    {
        $this->setExpectedException(\RuntimeException::class);

        $payment = new CreditCardPayment($data);
    }

    /**
     * @test
     * @dataProvider validCreditCardPaymentProvider
     */
    public function it_create_a_credit_card_payment_type($data, $brand)
    {
        $payment = new CreditCardPayment($data);

        $this->assertInstanceOf(CreditCardPayment::class, $payment);
        $this->assertEquals('CreditCard', $payment->getPaymentType());
        $this->assertEquals($brand, $payment->getCardBrand());
    }

    public function validCreditCardPaymentProvider()
    {
        return [
            [
                [
                    'cardNumber'     => '0000000000000001',
                    'holder'         => 'John F. Doe',
                    'expirationDate' => '12/2016',
                    'securityCode'   => '123',
                    'installments'   => 10,
                    'amount'         => 129.90,
                ],
                'Visa',
            ],
            [
                [
                    'cardNumber'     => '4024007140756650',
                    'holder'         => 'John F. Doe',
                    'expirationDate' => '12/2016',
                    'securityCode'   => '123',
                    'installments'   => 10,
                    'amount'         => 129.90,
                ],
                'Visa',
            ],
            [
                [
                    'cardNumber'     => '372814216093900',
                    'holder'         => 'John F. Doe',
                    'expirationDate' => '12/2016',
                    'securityCode'   => '123',
                    'installments'   => 10,
                    'amount'         => 129.90,
                ],
                'AmericanExpress',
            ],
        ];
    }

    public function invalidCreditCardPaymentProvider()
    {
        return [
            [
                [
                    'creditCardNumber' => '0000000000000001',
                    'holderName'       => 'John F. Doe',
                    'expirationDate'   => '12/2016',
                    'security_code'    => '123',
                    'installment'      => 10,
                    'price'            => 129.90,
                ],
            ],
        ];
    }
}
