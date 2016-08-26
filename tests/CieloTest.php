<?php

namespace IanRodriguesBR\Cielo\Tests;

use IanRodriguesBR\Cielo\Exceptions\InvalidMerchantIdException;
use IanRodriguesBR\Cielo\Exceptions\InvalidMerchantKeyException;
use IanRodriguesBR\Cielo\Exceptions\InvalidCustomerException;
use IanRodriguesBR\Cielo\Exceptions\InvalidPaymentMethodException;
use IanRodriguesBR\Cielo\Cielo;
use IanRodriguesBR\Cielo\Customer;
use IanRodriguesBR\Cielo\Payment;
use IanRodriguesBR\Cielo\Payments\CreditCardPayment;
use PHPUnit\Framework\TestCase;

class CieloTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidMerchantProvider
     */
    public function it_must_validate_the_merchant_id_and_merchant_key(
        $expectedException,
        $merchantId,
        $merchantKey
    )
    {
        $this->setExpectedException($expectedException);

        $cielo = new Cielo($merchantId, $merchantKey);
    }

    /**
     * @test
     * @dataProvider validMerchantProvider
     */
    public function it_must_instantiate_a_valid_object($merchantId, $merchantKey)
    {
        $cielo = new Cielo($merchantId, $merchantKey);

        $this->assertInstanceOf(Cielo::class, $cielo);
        $this->assertEquals($merchantId, $cielo->getMerchantId());
        $this->assertEquals($merchantKey, $cielo->getMerchantKey());
        $this->assertTrue($cielo->isInProduction());
    }

    /**
     * @test
     * @dataProvider validMerchantProvider
     */
    public function it_must_instantiate_a_valid_object_in_sandbox_mode($merchantId, $merchantKey)
    {
        $cielo = new Cielo($merchantId, $merchantKey, false);

        $this->assertFalse($cielo->isInProduction());
    }

    /**
     * @test
     * @dataProvider validMerchantProvider
     */
    public function it_must_create_an_instance_of_customer($merchantId, $merchantKey)
    {
        $cielo = new Cielo($merchantId, $merchantKey);
        $cielo->setCustomer('John F. Doe');

        $this->assertInstanceOf(Customer::class, $cielo->getCustomer());
        $this->assertEquals('John F. Doe', $cielo->getCustomer()->getName());
    }

    /**
     * @test
     * @dataProvider validMerchantProvider
     */
    public function it_must_create_an_instance_of_payment($merchantId, $merchantKey)
    {
        $cielo = new Cielo($merchantId, $merchantKey);
        $creditCardPaymentMethod = new CreditCardPayment([
            'cardNumber' => '0000000000000001',
            'holder' => 'John F Doe',
            'expirationDate' => '12/2016',
            'securityCode' => '123',
            'installments' => 10,
            'amount' => 129.90
        ]);

        $cielo->setPaymentMethod($creditCardPaymentMethod);

        $this->assertInstanceOf(Payment::class, $cielo->getPaymentMethod());
        $this->assertEquals('CreditCard', $cielo->getPaymentMethod()->getPaymentType());
    }

    /**
     * @test
     * @dataProvider validMerchantProvider
     */
    public function a_customer_be_seted_to_perform_a_transaction($merchantId, $merchantKey)
    {
        $this->setExpectedException(InvalidCustomerException::class);

        $cielo = new Cielo($merchantId, $merchantKey);
        $cielo->performTransaction();
    }

    /**
     * @test
     * @dataProvider validMerchantProvider
     */
    public function a_payment_method_be_seted_to_perform_a_transaction($merchantId, $merchantKey)
    {
        $this->setExpectedException(InvalidPaymentMethodException::class);

        $cielo = new Cielo($merchantId, $merchantKey);
        $cielo->setCustomer('John F Doe');
        $cielo->performTransaction();
    }

    public function it_should_perform_a_transaction()
    {
        $cielo = new Cielo(
            '7cd24544-9887-4cfa-98ec-a824aa61bfc9',
            'NJGQLYAJEBFQJDQIJEQBAWYOOICZGXQYLEQPXXRD',
            false
        );

        $paymentMethod = new CreditCardPayment([
            'cardNumber' => '0000000000000001',
            'holder' => 'John F Doe',
            'expirationDate' => '12/2016',
            'securityCode' => '123',
            'installments' => 10,
            'amount' => 129.90
        ]);

        $cielo->setCustomer('John F Doe')
              ->setPaymentMethod($paymentMethod);

        $res = $cielo->performTransaction();

        $this->assertEquals('1', $res->Payment->Status);
    }

    public function invalidMerchantProvider()
    {
        return [
            // valid merchant id, invalid merchant key
            [
                InvalidMerchantKeyException::class,
                'a1s2d3f4-a1s2-a1s2-a1s2-a1s2d3f4g5h6',
                'invalid merchant key'
            ],
            // invalid merchant id, valid merchant key
            [
                InvalidMerchantIdException::class,
                'invalid merchant id',
                'Q1W2E3R4T5Y6U7I8O9P0Z0X9C8V7B6N5M4L3J2H1'
            ]
        ];
    }

    public function validMerchantProvider()
    {
        return [
            ['xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', '0123456789012345678901234567890123456789'],
            ['a1s2d3f4-a1s2-a1s2-a1s2-a1s2d3f4g5h6', 'Q1W2E3R4T5Y6U7I8O9P0Z0X9C8V7B6N5M4L3J2H1']
        ];
    }
}
