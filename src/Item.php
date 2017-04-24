<?php

namespace Recca0120\Cart;

class Item
{
    /**
     * $id.
     *
     * @var string
     */
    public $id;

    /**
     * $name.
     *
     * @var string
     */
    public $name;

    /**
     * $price.
     *
     * @var float
     */
    public $price;

    /**
     * $quantity.
     *
     * @var int
     */
    public $quantity;

    /**
     * $attributes.
     *
     * @var array
     */
    public $attributes = [];

    /**
     * __construct.
     *
     * @param string $id
     * @param string $name
     * @param float $price
     * @param array $attributes
     */
    public function __construct($id, $name, $price, $quantity = 1, $attributes = [])
    {
        $this->setId($id)
            ->setName($name)
            ->setPrice($price)
            ->setQuantity($quantity)
            ->setAttributes($attributes);
    }

    /**
     * getId.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setId.
     *
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * getId.
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
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getPrice.
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
     * @param float $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * getQuantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * setQuantity.
     *
     * @param int $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * getAttributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * setAttributes.
     *
     * @param array $attributes
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * getTotal.
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total();
    }

    /**
     * total.
     *
     * @return float
     */
    public function total()
    {
        return $this->getPrice() * $this->getQuantity();
    }

    /**
     * toArray.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'quantity' => $this->getQuantity(),
            'attributes' => $this->getAttributes(),
            'total' => $this->getTotal(),
        ];
    }

    /**
     * toJson.
     *
     * @return string
     */
    public function toJson($option = 0)
    {
        return json_encode($this->toArray(), $option);
    }
}
