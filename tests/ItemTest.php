<?php

use Mockery as m;
use Recca0120\Cart\Item;

class ItemTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_new_item()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $id = uniqid();
        $name = 'foo';
        $price = 100;
        $quantity = 10;
        $attributes = ['foo'];
        $array = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'attributes' => $attributes,
        ];

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $item = new Item($id, $name, $price, $quantity, $attributes);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $this->assertSame($id, $item->getId());
        $this->assertSame($name, $item->getName());
        $this->assertSame($price, $item->getPrice());
        $this->assertSame($quantity, $item->getQuantity());
        $this->assertSame($attributes, $item->getAttributes());
        $this->assertSame($array, $item->toArray());
        $this->assertSame(json_encode($array), $item->toJson());
    }
}
