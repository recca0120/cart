<?php

use Faker\Factory as FakerFactory;
use Mockery as m;
use Recca0120\Cart\Cart;
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

        $id = uniqid();
        $faker = FakerFactory::create();
        $session = m::mock(SessionInterface::class);
        $items = [];
        for ($i = 0; $i < 10; $i++) {
            $item = new Item($i++, $faker->name, $faker->numberBetween(100, 1000));
            $items[] = $item;
        }
        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $cart = new Cart($id, $session);

        $total = array_reduce($items, function ($prev, $next) use ($cart, $faker) {
            $quantity = $faker->numberBetween(1, 10);
            $cart->put($next, $quantity);

            return $prev + $next->getQuantity() * $next->getPrice();
        }, 0);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $this->assertSame(hash(Cart::ALGORITHM, $id), $cart->getId());
        $this->assertSame(count($items), $cart->count());
        $this->assertSame($total, $cart->total());
        $this->assertSame($items, $cart->toArray());
        $this->assertSame(json_encode($items), $cart->toJson());
    }
}
