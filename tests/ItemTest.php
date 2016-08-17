<?php

use Mockery as m;
use Recca0120\Cart\Item;

class ItemTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_item()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $sku = 1;
        $name = 'foo';
        $price = 100.00;
        $quantity = 0;
        $options = [];

        $attributes = compact('sku', 'name', 'price', 'quantity', 'options');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $item = new Item($sku, $name, $price);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $this->assertSame($sku, $item['sku']);
        $this->assertSame($name, $item['name']);
        $this->assertSame($price, $item['price']);
        $this->assertSame($sku, $item->sku);
        $this->assertSame($name, $item->name);
        $this->assertSame($price, $item->price);
        $this->assertSame($sku, $item->getSku());
        $this->assertSame($name, $item->getName());
        $this->assertSame($price, $item->getPrice());
        $this->assertSame($attributes, $item->toArray());
        $this->assertSame(json_encode($attributes), $item->toJson());
        $this->assertSame($price * $quantity, $item->total());

        $this->assertTrue(isset($item['sku']));
        unset($item['sku']);
        $this->assertFalse(isset($item['sku']));
        $item['sku'] = $sku;
        $this->assertTrue(isset($item['sku']));
    }

    public function test_set_options()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $sku = 1;
        $name = 'foo';
        $price = 100.00;
        $quantity = 0;
        $options = [];

        $attributes = compact('sku', 'name', 'price', 'quantity', 'options');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $item = new Item($sku, $name, $price);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $options = [
            'foo' => 'bar',
        ];
        $item->setOptions($options);
        $this->assertSame($options, $item->getOptions());
        $this->assertSame('bar', $item->getOption('foo'));

        $item->setOptions([]);
        $item->setOption($options);
    }
}
