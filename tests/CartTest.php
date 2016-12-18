<?php

use Mockery as m;
use Recca0120\Cart\Cart;

class CartTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_session_storage()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $storage = m::spy('Recca0120\Cart\Storage');
        $item = m::spy('Recca0120\Cart\Item');
        $id = uniqid();

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $storage
            ->shouldReceive('restore')->andReturn([]);

        $item
            ->shouldReceive('getId')->andReturn($id);

        $cart = new Cart('default', $storage);
        $cart->add($item);
        unset($cart);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $storage->shouldHaveReceived('restore')->once();
        $item->shouldHaveReceived('getId')->once();
        $storage->shouldHaveReceived('store')->with([$id => $item])->once();
    }

    public function test_add_item()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $storage = m::spy('Recca0120\Cart\Storage');
        $item = m::spy('Recca0120\Cart\Item');
        $id = uniqid();

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $item
            ->shouldReceive('getId')->andReturn($id);

        $cart = new Cart('default', $storage);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $cart->add($item);
        $this->assertSame(1, $cart->count());
    }

    public function test_remove_item()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $storage = m::spy('Recca0120\Cart\Storage');
        $item = m::spy('Recca0120\Cart\Item');
        $id = uniqid();

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $item
            ->shouldReceive('getId')->andReturn($id);

        $cart = new Cart('default', $storage);
        $cart->add($item);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $this->assertSame(1, $cart->count());
        $cart->remove($id);
        $this->assertSame(0, $cart->count());
    }

    public function test_clear()
    {
        /*
        |------------------------------------------------------------
        | Arrange
        |------------------------------------------------------------
        */

        $storage = m::spy('Recca0120\Cart\Storage');
        $item = m::spy('Recca0120\Cart\Item');
        $id = uniqid();

        /*
        |------------------------------------------------------------
        | Act
        |------------------------------------------------------------
        */

        $item
            ->shouldReceive('getId')->andReturn($id);

        $cart = new Cart('default', $storage);
        $cart->add($item);

        /*
        |------------------------------------------------------------
        | Assert
        |------------------------------------------------------------
        */

        $this->assertSame(1, $cart->count());
        $cart->clear();
        $this->assertSame(0, $cart->count());
    }

}
