<?php

namespace Recca0120\Cart\Tests;

use Mockery as m;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Recca0120\Cart\Cart;
use Recca0120\Cart\Item;

class CartTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testPut()
    {
        $storage = m::mock('Recca0120\Cart\Storage');

        $storage->shouldReceive('restore')->once()->andReturn(
            $collection = new Collection
        );
        $cart = new Cart($storage);
        $item = new Item('foo', 'bar', 100, 10, ['foo' => 'bar']);
        $item2 = new Item('foo2', 'bar2', 200, 3, ['foo2' => 'bar2']);

        $cart->put($item);
        $cart->put($item2);
        $this->assertSame(2, $cart->count());
        $this->assertSame($item->total() + $item2->total(), $cart->total());

        $cart->put($item);
        $this->assertSame(2, $cart->count());
        $this->assertSame($item->total() + $item2->total(), $cart->total());

        $cart->remove($item);
        $this->assertSame(1, $cart->count());
        $this->assertSame($item2->total(), $cart->total());

        $cart->put($item);
        $this->assertSame(2, $cart->count());
        $this->assertSame($item->total() + $item2->total(), $cart->total());

        $cart->remove($item->getId());
        $this->assertSame(1, $cart->count());
        $this->assertSame($item2->total(), $cart->total());

        $cart->put($item);
        $this->assertSame(2, $cart->count());

        // $cart->clear();
        // $this->assertSame(0, $cart->count());

        $this->assertSame($collection, $cart->items());
        $storage->shouldReceive('store')->once()->with($collection);
    }

    // public function test_session_storage()
    // {
    //     /*
    //     |------------------------------------------------------------
    //     | Arrange
    //     |------------------------------------------------------------
    //     */
    //
    //     $storage = m::spy('Recca0120\Cart\Storage');
    //     $item = m::spy('Recca0120\Cart\Item');
    //     $id = uniqid();
    //
    //     /*
    //     |------------------------------------------------------------
    //     | Act
    //     |------------------------------------------------------------
    //     */
    //
    //     $storage
    //         ->shouldReceive('restore')->andReturn([]);
    //
    //     $item
    //         ->shouldReceive('getId')->andReturn($id);
    //
    //     $cart = new Cart($storage);
    //     $cart->add($item);
    //     unset($cart);
    //
    //     /*
    //     |------------------------------------------------------------
    //     | Assert
    //     |------------------------------------------------------------
    //     */
    //
    //     $storage->shouldHaveReceived('restore')->once();
    //     $item->shouldHaveReceived('getId')->once();
    //     $storage->shouldHaveReceived('store')->with([$id => $item])->once();
    // }
    //
    // public function test_add_item()
    // {
    //     /*
    //     |------------------------------------------------------------
    //     | Arrange
    //     |------------------------------------------------------------
    //     */
    //
    //     $storage = m::spy('Recca0120\Cart\Storage');
    //     $item = m::spy('Recca0120\Cart\Item');
    //     $id = uniqid();
    //
    //     /*
    //     |------------------------------------------------------------
    //     | Act
    //     |------------------------------------------------------------
    //     */
    //
    //     $item
    //         ->shouldReceive('getId')->andReturn($id);
    //
    //     $cart = new Cart($storage);
    //
    //     /*
    //     |------------------------------------------------------------
    //     | Assert
    //     |------------------------------------------------------------
    //     */
    //
    //     $cart->add($item);
    //     $this->assertSame(1, $cart->count());
    // }
    //
    // public function test_remove_item()
    // {
    //     /*
    //     |------------------------------------------------------------
    //     | Arrange
    //     |------------------------------------------------------------
    //     */
    //
    //     $storage = m::spy('Recca0120\Cart\Storage');
    //     $item = m::spy('Recca0120\Cart\Item');
    //     $id = uniqid();
    //
    //     /*
    //     |------------------------------------------------------------
    //     | Act
    //     |------------------------------------------------------------
    //     */
    //
    //     $item
    //         ->shouldReceive('getId')->andReturn($id);
    //
    //     $cart = new Cart($storage);
    //     $cart->add($item);
    //
    //     /*
    //     |------------------------------------------------------------
    //     | Assert
    //     |------------------------------------------------------------
    //     */
    //
    //     $this->assertSame(1, $cart->count());
    //     $cart->remove($id);
    //     $this->assertSame(0, $cart->count());
    // }
    //
    // public function test_clear()
    // {
    //     /*
    //     |------------------------------------------------------------
    //     | Arrange
    //     |------------------------------------------------------------
    //     */
    //
    //     $storage = m::spy('Recca0120\Cart\Storage');
    //     $item = m::spy('Recca0120\Cart\Item');
    //     $id = uniqid();
    //
    //     /*
    //     |------------------------------------------------------------
    //     | Act
    //     |------------------------------------------------------------
    //     */
    //
    //     $item
    //         ->shouldReceive('getId')->andReturn($id);
    //
    //     $cart = new Cart($storage);
    //     $cart->add($item);
    //
    //     /*
    //     |------------------------------------------------------------
    //     | Assert
    //     |------------------------------------------------------------
    //     */
    //
    //     $this->assertSame(1, $cart->count());
    //     $cart->clear();
    //     $this->assertSame(0, $cart->count());
    // }
}
