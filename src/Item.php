<?php

namespace Recca0120\Cart;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;
use Recca0120\Cart\Contracts\Item as ItemContract;

class Item extends Fluent implements ItemContract
{
    /**
     * __construct.
     *
     * @method __construct
     *
     * @param int|string $sku
     * @param string     $name
     * @param int        $quantity
     * @param float      $price
     */
    public function __construct($sku = null, $name = null, $price = 0.00, $options = [], $quantity = 0)
    {
        $this
            ->setSku($sku)
            ->setName($name)
            ->setPrice($price)
            ->setQuantity($quantity)
            ->setOptions($options);
    }

    /**
     * getSku.
     *
     * @method getSku
     *
     * @return int|string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * setSku.
     *
     * @method setSku
     *
     * @param int|string $sku
     *
     * @return static
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * getName.
     *
     * @method getName
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * setName.
     *
     * @method setName
     *
     * @param string $name
     *
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getPrice.
     *
     * @method getPrice
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * setPrice.
     *
     * @method setPrice
     *
     * @param string $price
     *
     * @return static
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * getQuantity.
     *
     * @method getQuantity
     *
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * setQuantity.
     *
     * @method setQuantity
     *
     * @param string $quantity
     *
     * @return static
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * getOptions.
     *
     * @method getOptions
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * setOptions.
     *
     * @method setOptions
     *
     * @param string $options
     *
     * @return static
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * getOption.
     *
     * @method getOption
     *
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        return Arr::get($this->options, $key, $default);
    }

    /**
     * setOption.
     *
     * @method setOption
     *
     * @param string $key
     * @param string $value
     *
     * @return static
     */
    public function setOption($key, $value = null)
    {
        if (is_array($key) === true) {
            array_walk($key, function ($value, $key) {
                $this->setOption($key, $value);
            });

            return $this;
        }

        $this->attributes['options'][$key] = $value;

        return $this;
    }

    /**
     * total.
     *
     * @method total
     *
     * @return float
     */
    public function total()
    {
        return $this->getQuantity() * $this->getPrice();
    }
}
