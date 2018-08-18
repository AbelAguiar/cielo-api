<?php

namespace AbelAguiar\Cielo;

use AbelAguiar\Cielo\Contracts\Arrayable;
use InvalidArgumentException;

class Customer implements Arrayable
{
    protected $name;

    public function __construct($name = null)
    {
        if (is_null($name)) {
            throw new InvalidArgumentException('You must provide a Name.');
        }

        $this->name = $name;
    }

    /**
     * Gets the name of the customer.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns a array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'Name' => $this->name,
        ];
    }
}
