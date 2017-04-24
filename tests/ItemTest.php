<?php

namespace Recca0120\Cart\Tests;

use Mockery as m;
use Recca0120\Cart\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testItem()
    {
        $item = new Item(
            $id = 'foo',
            $name = 'bar',
            $price = 5.0,
            $quantity = 10,
            $attributes = ['foo' => 'bar']
        );

        $this->assertSame($data = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'attributes' => $attributes,
            'total' => $price * $quantity,
        ], $item->toArray());

        $this->assertSame(json_encode($data), $item->toJson());
    }
}
