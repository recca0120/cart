<?php

use Faker\Factory as FakerFactory;
use Mockery as m;
use Recca0120\Cart\Cart;
use Recca0120\Cart\Coupon;
use Recca0120\Cart\Item;
use Recca0120\Cart\Storage;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_instance()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $session = m::mock(SessionInterface::class);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $session
            ->shouldReceive('isStarted')->once()->andReturn(false)
            ->shouldReceive('start')->once()
            ->shouldReceive('get')->once()->andReturn([])
            ->shouldReceive('set');

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $instance = Cart::instance();
        $this->assertSame($instance, Cart::instance());

        $instance = Cart::driver('foo', new Storage($session));
        $this->assertSame($instance, Cart::instance('foo'));
    }

    public function test_add_item()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $name = uniqid();
        $cart = new Cart($name);
        $items = $this->generateItems();
        $items->each(function ($item) use ($cart) {
            $cart->add($item, $item->getQuantity());
        });

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $total = $items->sum(function ($item) {
            return $item->total();
        });

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $this->assertSame($name, $cart->getName());

        $this->assertSame($items->count(), $cart->count());
        $this->assertSame($items->count(), $cart->items()->count());

        $this->assertSame($total, $cart->total());
        $this->assertSame($total, $cart->items()->total());
    }

    public function test_remove_item()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $cart = new Cart();
        $items = $this->generateItems();
        $items->each(function ($item) use ($cart) {
            $cart->add($item, $item->getQuantity());
        });

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $count = $items->count();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $cart->remove(1);
        $this->assertSame($count -= 1, $cart->count());
        $this->assertSame($count, $cart->items()->count());

        $cart->remove($items->last());
        $this->assertSame($count -= 1, $cart->count());
        $this->assertSame($count, $cart->items()->count());
    }

    public function test_clear_item()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $cart = new Cart();
        $items = $this->generateItems();
        $items->each(function ($item) use ($cart) {
            $cart->add($item, $item->getQuantity());
        });

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $cart->clear();

        $this->assertSame(0, $cart->count());
        $this->assertSame(0, $cart->items()->count());
        $this->assertSame(0, $cart->coupons()->count());
    }

    public function test_add_coupon()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $cart = new Cart();
        $items = $this->generateItems();
        $items->each(function ($item) use ($cart) {
            $cart->add($item, $item->getQuantity());
        });
        $code = 'freeshippin';
        $description = '滿1000免運費';
        $fee = -120;
        $coupon = new Coupon($code, $description, function ($cart) use ($fee) {
            return ($cart->grossTotal() < 1000) ? $fee : 0;
        });

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $grossTotal = $items->sum(function ($item) {
            return $item->total();
        });

        $discount = ($cart->grossTotal() < 1000) ? $fee : 0;
        $total = $grossTotal - $discount;
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $cart->addCoupon($coupon);
        $this->assertSame($grossTotal, $cart->grossTotal());
        $this->assertSame($total, $cart->total());

        $this->assertSame($code, $coupon->getCode());
        $this->assertSame($description, $coupon->getDescription());
        $this->assertSame($discount, $coupon->getDiscount());
        $this->assertSame(0, $coupon->defaultHandler($cart));

        $cart->removeCoupon($coupon->getCode());
        $this->assertSame($grossTotal, $cart->grossTotal());
        $this->assertSame($grossTotal, $cart->total());
    }

    protected function generateItems()
    {
        $faker = FakerFactory::create();
        $length = 10;
        $items = collect();
        for ($i = 1; $i <= $length; $i++) {
            $items->put($i, new Item($i, $faker->name, $faker->numberBetween(1, 10), [], $faker->numberBetween(1, 10)));
        }

        return $items;
    }
}
