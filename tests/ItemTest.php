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

        $id = 1;
        $name = 'foo';
        $price = 100.00;
        $quantity = 1;
        $options = [];

        $attributes = compact('id', 'name', 'price', 'quantity', 'options');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $item = new Item($id, $name, $price);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $this->assertSame($id, $item['id']);
        $this->assertSame($name, $item['name']);
        $this->assertSame($price, $item['price']);
        $this->assertSame($id, $item->id);
        $this->assertSame($name, $item->name);
        $this->assertSame($price, $item->price);
        $this->assertSame($id, $item->getId());
        $this->assertSame($name, $item->getName());
        $this->assertSame($price, $item->getPrice());
        $this->assertSame($attributes, $item->toArray());
        $this->assertSame(json_encode($attributes), $item->toJson());

        $this->assertTrue(isset($item['id']));
        unset($item['id']);
        $this->assertFalse(isset($item['id']));
        $item['id'] = $id;
        $this->assertTrue(isset($item['id']));
    }

    public function test_set_options()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $id = 1;
        $name = 'foo';
        $price = 100.00;
        $quantity = 1;
        $options = [];

        $attributes = compact('id', 'name', 'price', 'quantity', 'options');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $item = new Item($id, $name, $price);

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

        $item->setOption('fuzz', 'buzz');
        $this->assertSame(array_merge($options, ['fuzz' => 'buzz']), $item->getOptions());

        $item->setOptions([]);
        $item->setOption($options);
        $this->assertSame($options, $item->getOptions());
        $this->assertSame('bar', $item->getOption('foo'));
    }

    public function test_array_access()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $id = 1;
        $name = 'foo';
        $price = 100.00;
        $options = [];

        $attributes = compact('id', 'name', 'price', 'options');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $item = new Item($id, $name, $price);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $this->assertTrue(isset($item['id']));
        unset($item['id']);
        $this->assertFalse(isset($item['id']));
        $item['id'] = $id;
        $this->assertTrue(isset($item['id']));
    }
}
