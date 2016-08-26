<?php

namespace IanRodriguesBR\Cielo\Tests;

use IanRodriguesBR\Cielo\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    /** 
     * @test
     * @dataProvider validCustomerNameProvider
     */
    public function it_can_create_a_customer_only_with_the_name($name)
    {
        $customer = new Customer($name);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals($name, $customer->getName());
    }

    public function validCustomerNameProvider()
    {
        return [
            ['John F. Doe'],
            ['Harold C. Cherry'],
            ['Claudia J. Chan']
        ];
    }
}