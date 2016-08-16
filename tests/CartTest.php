<?php

use Faker\Factory as FakerFactory;
use Mockery as m;
use Recca0120\Cart\Cart;
use Recca0120\Cart\Coupons\FreeShippingCoupon;
use Recca0120\Cart\Item;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_cart()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $faker = FakerFactory::create();
        $session = m::mock(SessionInterface::class);
        $id = uniqid();
        $hash = Cart::getHash($id);
        $items = collect();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $itemLength = 10;
        for ($i = 1; $i <= $itemLength; $i++) {
            $items->put($i, new Item($i, $faker->name, $faker->numberBetween(100, 1000)));
        }

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

        $cart = new Cart($id, $session);
        $itemTotal = $items->reduce(function ($prev, $item) use ($cart, $faker) {
            $quantity = $faker->numberBetween(1, 10);
            $cart->add($item, $quantity);

            return $prev + $item->getTotal();
        }, 0);

        $this->assertSame($hash, $cart->getId());
        $this->assertSame($items->toArray(), $cart->items()->toArray());
        $this->assertSame($itemLength, $cart->count());
        $this->assertSame($itemTotal, $cart->total());

        $coupon = new FreeShippingCoupon(200, 5000000000);
        $cart->addCoupon($coupon);
        $this->assertSame($itemTotal + 200, $cart->total());

        $cart->remove(1);
        $this->assertSame($itemLength - 1, $cart->items()->count());

        $cart->clear();
        $this->assertSame(0, $cart->items()->count());
    }
}
