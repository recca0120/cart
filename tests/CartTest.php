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

        $instance = Cart::instance();
        $instance2 = Cart::driver('foo');

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

        $this->assertSame($instance, Cart::instance());
        $this->assertSame($instance2, Cart::instance('foo'));
    }

    public function test_cart()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $name = uniqid();
        $session = m::mock(SessionInterface::class);
        $itemLength = 10;
        $faker = FakerFactory::create();
        $items = collect();

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

        $storage = new Storage($session);
        $cart = new Cart($name, $storage);

        $cart->clear();
        $this->assertSame([], $cart->items()->toArray());
        $this->assertSame([], $cart->coupons()->toArray());

        for ($i = 1; $i <= $itemLength; $i++) {
            $items->put($i, new Item($i, $faker->name, $faker->numberBetween(100, 1000)));
        }

        $total = $items->reduce(function ($prev, $item) use ($faker, $cart) {
            $quantity = $faker->numberBetween(1, 10);
            $cart->add($item, $quantity);

            return $prev + $item->total();
        }, 0);

        $this->assertSame($name, $cart->getName());
        $this->assertSame($total, $cart->items()->total());

        $this->assertSame($cart->items()->count(), $cart->count());
        $this->assertSame($cart->items()->total(), $cart->total());

        $cart->remove(1);
        $this->assertSame($cart->items()->total(), $cart->total());
        $this->assertSame($itemLength - 1, $cart->count());

        $coupon = new Coupon('freeShipping', '運2000免運費', function ($cart) {
            return ($cart->items()->total() > 2000) ? 120 : 0;
        });

        $total = $cart->total();
        $cart->coupons()->add($coupon);
        $this->assertSame($total + 120, $cart->total());
        $this->assertSame(120, $coupon->getDiscount());
        $this->assertSame('freeShipping', $coupon->getCode());
        $this->assertSame('運2000免運費', $coupon->getDescription());

        $cart->coupons()->add(new Coupon('n', 'n'));
        $cart->total();
    }
}
