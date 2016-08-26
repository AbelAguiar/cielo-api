<?php

namespace IanRodriguesBR\Cielo;

use IanRodriguesBR\Cielo\Exceptions\InvalidMerchantIdException;
use IanRodriguesBR\Cielo\Exceptions\InvalidMerchantKeyException;
use IanRodriguesBR\Cielo\Exceptions\InvalidCustomerException;
use IanRodriguesBR\Cielo\Exceptions\InvalidPaymentMethodException;
use IanRodriguesBR\Cielo\Payment;

class Cielo
{

    protected $merchantId;
    protected $merchantKey;
    protected $production;
    protected $customer;
    protected $paymentMethod;

    public function __construct($merchantId, $merchantKey, $production = true)
    {
        if (!$this->isValidId($merchantId)) {
            throw new InvalidMerchantIdException('Invalid Merchant ID.');
        }

        if (!$this->isValidKey($merchantKey)) {
            throw new InvalidMerchantKeyException('Invalid Merchant Key.');
        }

        $this->client = new CieloClient($production);
        $this->merchantId = $merchantId;
        $this->merchantKey = $merchantKey;
        $this->production = $production;
    }

    /**
     * Sets the customer.
     *
     * @param string $name
     */
    public function setCustomer($name)
    {
        $this->customer = new Customer($name);
        
        return $this;
    }

    /**
     * Gets the customer.
     *
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Sets the payment method.
     *
     * @param \IanRodriguesBR\Cielo\Payment $payment
     */
    public function setPaymentMethod(Payment $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        
        return $this;
    }

    /**
     * Gets the payment method.
     *
     * @return \IanRodriguesBR\Cielo\Payment
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Performs a new transaction.
     *
     * @return stdClass
     */
    public function performTransaction()
    {
        $this->validateCustomerAndPaymentMethod();

        return $this->client->performTransaction($this);
    }

    public function consultTransaction($paymentId)
    {
        if (!$this->isValidPaymentId($paymentId)) {
            throw new InvalidPaymentIdException('Invalid Payment ID.');
        }

        return $this->client->consultTransaction($this, $paymentId);
    }

    /**
     * Gets the merchant identifier.
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * Gets the merchant key.
     *
     * @return string
     */
    public function getMerchantKey()
    {
        return $this->merchantKey;
    }

    /**
     * Determines if is in production mode.
     *
     * @return boolean
     */
    public function isInProduction()
    {
        return $this->production;
    }

    /**
     * Determines if is a valid identifier.
     *
     * @param  string $merchantId
     * @return boolean
     */
    protected function isValidId($merchantId)
    {
        return (bool) preg_match('/^[a-z0-9]{8}\-([a-z0-9]{4}\-){3}[a-z0-9]{12}$/', $merchantId);
    }

    /**
     * Determines if is a valid payment identifier.
     *
     * @param  string $paymentId
     * @return boolean
     */
    protected function isValidPaymentId($paymentId)
    {
        return (bool) preg_match('/^[a-z0-9]{8}\-([a-z0-9]{4}\-){3}[a-z0-9]{12}$/', $paymentId);
    }

    /**
     * Determines if is a valid key.
     *
     * @param  string $merchantKey
     * @return boolean
     */
    protected function isValidKey($merchantKey)
    {
        return (bool) preg_match('/^[a-zA-Z0-9]{40}$/', $merchantKey);
    }

    protected function validateCustomerAndPaymentMethod()
    {
        if (!$this->customer instanceof Customer) {
            throw new InvalidCustomerException(
                sprintf(
                    'The "%s" property must be an instace of %s. %s given.',
                    '$customer',
                    Customer::class,
                    get_class($this->customer)
                )
            );
        }

        if (!$this->paymentMethod instanceof Payment) {
            throw new InvalidPaymentMethodException(
                sprintf(
                    'The "%s" property must be an instace of %s. %s given.',
                    '$paymentMethod',
                    Payment::class,
                    get_class($this->paymentMethod)
                )
            );
        }
    }
}

