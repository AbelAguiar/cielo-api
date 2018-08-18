<?php

namespace AbelAguiar\Cielo\Payments;

use AbelAguiar\Cielo\Contracts\Arrayable;
use AbelAguiar\Cielo\Payment;
use RunTimeException;

class DebitCardPayment extends Payment implements Arrayable
{
    protected $paymentType = 'DebitCard';

    protected $cardNumber;
    protected $returnUrl;
    protected $holder;
    protected $expirationDate;
    protected $securityCode;
    protected $brand;

    protected $brandPatterns = [
        'Visa'   => '/^4[0-9]{12}(?:[0-9]{3})?$/',
        'Master' => '/^5[1-5][0-9]{14}$/',
    ];

    public function __construct(array $attributes)
    {
        $this->setAttributes($attributes);
    }

    /**
     * Sets the attributes for the credit card payment.
     *
     * @param array $attributes
     *
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
        return ($this->brand) ?: 'Visa';
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
            'Type'      => $this->paymentType,
            'Amount'    => $this->getIntegerAmount(),
            'ReturnUrl' => $this->returnUrl,
            'DebitCard' => [
                'CardNumber'     => $this->cardNumber,
                'Holder'         => $this->holder,
                'ExpirationDate' => $this->expirationDate,
                'SecurityCode'   => $this->securityCode,
                'Brand'          => $this->brand,
            ],
        ];
    }
}
