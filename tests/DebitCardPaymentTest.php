<?php

namespace AbelAguiar\Cielo\Tests;

use AbelAguiar\Cielo\Payments\DebitCardPayment;
use PHPUnit\Framework\TestCase;

class DebitCardPaymentTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidDebitCardPaymentProvider
     */
    public function an_exception_must_be_raised_with_invalid_data($data)
    {
        $this->setExpectedException(\RuntimeException::class);

        $payment = new DebitCardPayment($data);
    }

    /**
     * @test
     * @dataProvider validDebitCardPaymentProvider
     */
    public function it_create_a_debit_card_payment_type($data, $brand)
    {
        $payment = new DebitCardPayment($data);

        $this->assertInstanceOf(DebitCardPayment::class, $payment);
        $this->assertEquals('DebitCard', $payment->getPaymentType());
        $this->assertEquals($brand, $payment->getCardBrand());
    }

    public function validDebitCardPaymentProvider()
    {
        return [
            [
                [
                    'cardNumber'     => '0000000000000001',
                    'holder'         => 'John F. Doe',
                    'expirationDate' => '12/2019',
                    'securityCode'   => '123',
                    'amount'         => 129.90,
                ],
                'Visa',
            ],
            [
                [
                    'cardNumber'     => '4024007140756650',
                    'holder'         => 'John F. Doe',
                    'expirationDate' => '12/2019',
                    'securityCode'   => '123',
                    'amount'         => 129.90,
                ],
                'Visa',
            ],
            [
                [
                    'cardNumber'     => '5415747340190572',
                    'holder'         => 'John F. Doe',
                    'expirationDate' => '12/2019',
                    'securityCode'   => '430',
                    'amount'         => 129.90,
                ],
                'Master',
            ],
        ];
    }

    public function invalidDebitCardPaymentProvider()
    {
        return [
            [
                [
                    'debitCardNumber' => '0000000000000001',
                    'holderName'      => 'John F. Doe',
                    'expirationDate'  => '12/2016',
                    'security_code'   => '123',
                    'price'           => 129.90,
                ],
            ],
        ];
    }
}
