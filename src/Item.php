<?php

namespace Recca0120\Cart;

use Recca0120\Cart\Contracts\Item as ItemContract;

class Item implements ItemContract
{
    public $attribute = [
        'id'       => null,
        'name'     => null,
        'price'    => 0.00,
        'quantity' => 1,
        'options'  => [],
    ];

    /**
     * __construct.
     *
     * @method __construct
     *
     * @param  int|string   $id
     * @param  string       $name
     * @param  int          $quantity
     * @param  float        $price
     */
    public function __construct($id = null, $name = null, $price = 0.00, $options = [], $quantity = 1)
    {
        $this
            ->setId($id)
            ->setName($name)
            ->setPrice($price)
            ->setQuantity($quantity)
            ->setOptions($options);
    }

    /**
     * getId.
     *
     * @method getId
     *
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setId.
     *
     * @method setId
     *
     * @param int|string $id
     *
     * @return static
     */
    public function setId($id)
    {
        $this->id = $id;

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
    public function getOption($key)
    {
        return $this->options[$key];
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

        $this->attribute['options'][$key] = $value;

        return $this;
    }

    /**
     * Convert the Fluent instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attribute;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the Fluent instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Determine if the given offset exists.
     *
     * @param  string  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * Get the value for a given offset.
     *
     * @param  string  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    /**
     * Set the value at the given offset.
     *
     * @param  string  $offset
     * @param  mixed   $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * Unset the value at the given offset.
     *
     * @param  string  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }

    /**
     * Dynamically retrieve the value of an attribute.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attribute[$key];
    }

    /**
     * Dynamically set the value of an attribute.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->attribute[$key] = $value;
    }

    /**
     * Dynamically check if an attribute is set.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->attribute[$key]);
    }

    /**
     * Dynamically unset an attribute.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attribute[$key]);
    }
}
