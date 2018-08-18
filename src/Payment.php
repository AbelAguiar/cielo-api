<?php

namespace AbelAguiar\Cielo;

use InvalidArgumentException;

abstract class Payment
{
    protected $paymentType;
    protected $amount;

    public function __construct($paymentType, $amount = 0)
    {
        if (is_null($paymentType)) {
            throw new InvalidArgumentException('You must provide a payment type.');
        }

        $this->paymentType = $paymentType;
        $this->amount = $amount;
    }

    /**
     * Gets the payment type.
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Gets the amount.
     *
     * @return float
     */
    public function getAmount()
    {
        return (float) $this->amount;
    }

    /**
     * Gets the integer amount.
     *
     * @return int
     */
    public function getIntegerAmount()
    {
        return $this->amount * 100;
    }
}
