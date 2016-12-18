<?php

namespace Recca0120\Cart;

class Item
{
    public $id;
    public $name;
    public $price;
    public $quantity;
    public $attributes = [];

    public function __construct($id, $name, $price, $attributes = [])
    {
        $this
            ->setId($id)
            ->setName($name)
            ->setPrice($price)
            ->setAttributes($attributes);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function total()
    {
        return $this->getPrice() * $this->getQuantity();
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'attributes' => $this->attributes,
        ];
    }

    public function toJson($option = 0)
    {
        return json_encode($this->toArray(), $option);
    }
}
