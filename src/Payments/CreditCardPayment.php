<?php

namespace IanRodriguesBR\Cielo\Payments;

use IanRodriguesBR\Cielo\Payment;
use IanRodriguesBR\Cielo\Contracts\Arrayable;
use RunTimeException;

class CreditCardPayment extends Payment implements Arrayable
{
    protected $paymentType = 'CreditCard';
    
    protected $installments;
    protected $cardNumber;
    protected $holder;
    protected $expirationDate;
    protected $securityCode;
    protected $brand;

    protected $brandPatterns = [
        'Visa'            => '/^4[0-9]{12}(?:[0-9]{3})?$/',
        'MasterCard'      => '/^5[1-5][0-9]{14}$/',
        'JCB'             => '/^(?:2131|1800|35\d{3})\d{11}$/',
        'Discover'        => '/^6(?:011|5[0-9][0-9])[0-9]{12}$/',
        'DinersClub'      => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',
        'AmericanExpress' => '/^3[47][0-9]{13}$/'
    ];

    public function __construct(array $attributes)
    {
        $this->setAttributes($attributes);
    }

    /**
     * Sets the attributes for the credit card payment.
     *
     * @param array $attributes
     * @throws RunTimeException
     */
    protected function setAttributes($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (!property_exists(self::class, $key)) {
                throw new RunTimeException(sprintf('The property "%s", that you are trying to access don\'t exists.', $key));
            }

            if ($key == 'cardNumber') {
                $this->setCardBrand($value);
            }

            $this->$key = $value;
        }
    }

    /**
     * Gets the card brand.
     *
     * @return string
     */
    public function getCardBrand()
    {
        return ($this->brand)?: 'Visa';
    }

    /**
     * Sets the card brand.
     *
     * @param string $cardNumber
     */
    protected function setCardBrand($cardNumber)
    {
        foreach ($this->brandPatterns as $brand => $pattern) {
            if (preg_match($pattern, $cardNumber)) {
                $this->brand = $brand;
                return;
            }

            $this->brand = 'Visa';
        }
    }

    /**
     * Returns a array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'Type' => $this->paymentType,
            'Amount' => $this->amount,
            'Installments' => $this->installments,
            'CreditCard' => [
                'CardNumber' => $this->cardNumber,
                'Holder' => $this->holder,
                'ExpirationDate' => $this->expirationDate,
                'SecurityCode' => $this->securityCode,
                'Brand' => $this->brand
            ]
        ];
    }
}