<?php

use Faker\Factory as FakerFactory;
use Illuminate\Support\Collection;
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
        $hash = hash(Cart::ALGORITHM, $id);
        $faker = FakerFactory::create();
        $session = m::mock(SessionInterface::class);
        $items = new Collection();
        $j = 0;
        for ($i = 0; $i < 10; $i++) {
            $item = new Item($j++, $faker->name, $faker->numberBetween(100, 1000));
            $items->put($item->getId(), $item);
        }

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $session
            ->shouldReceive('isStarted')->once()->andReturn(false)
            ->shouldReceive('start')->once()
            ->shouldReceive('get')->with($hash.Cart::ITEM_KEY)->andReturn(serialize(collect()))
            ->shouldReceive('set')
            ->shouldReceive('save');

        $cart = new Cart($id, $session);

        $total = $items->reduce(function ($prev, $next) use ($cart, $faker) {
            $quantity = $faker->numberBetween(1, 10);
            $cart->put($next, $quantity);

            return $prev + $next->getQuantity() * $next->getPrice();
        }, 0);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $this->assertSame($hash, $cart->getId());
        $this->assertSame($session, $cart->getStorage());
        $this->assertSame($items->count(), $cart->count());
        $this->assertSame($total, $cart->total());
        $this->assertSame($items->toArray(), $cart->toArray());
        $this->assertSame($items->toArray(), $cart->items()->toArray());
        $this->assertSame($items->toJson(), $cart->toJson());
        $this->assertSame($items->toJson(), (string) $cart);
        $this->assertSame($items->getIterator()->getArrayCopy(), $cart->getIterator()->getArrayCopy());
        $this->assertSame($items->getCachingIterator()->getArrayCopy(), $cart->getCachingIterator()->getArrayCopy());
        $this->assertSame($cart, Cart::instance($id));
        $cart->offsetExists(0);
        $cart->offsetGet(0);
        $cart->offsetSet(0, []);
        $cart->offsetUnset(0);

        $count = $cart->count();
        $cart->remove(2);
        $this->assertSame($count - 1, $cart->count());
    }
}
