<?php

use Faker\Factory as FakerFactory;
use Mockery as m;
use Recca0120\Cart\Cart;
use Recca0120\Cart\Coupon;
use Recca0120\Cart\Fee;
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

    public function test_add_coupon_20_percent_off()
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
        $code = '20-percent-off';
        $description = '打8折';
        $rate = .8;
        $coupon = new Coupon($code, $description, function ($cart) use ($rate) {
            return $cart->grossTotal() * $rate;
        });

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $grossTotal = $items->sum(function ($item) {
            return $item->total();
        });

        $discount = $cart->grossTotal() * $rate;
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

    public function test_add_fee()
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
        $code = 'shipping-fee';
        $description = '運費120';
        $fee = new Fee($code, $description, function ($cart) {
            return 120;
        });

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $grossTotal = $items->sum(function ($item) {
            return $item->total();
        });

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $cart->addFee($fee);
        $this->assertSame($grossTotal, $cart->grossTotal());
        $this->assertSame($grossTotal + 120, $cart->total());

        $this->assertSame($code, $fee->getCode());
        $this->assertSame($description, $fee->getDescription());
        $this->assertSame(0, $fee->defaultHandler($cart));

        $cart->removeFee($fee->getCode());
        $this->assertSame($grossTotal, $cart->grossTotal());
        $this->assertSame($grossTotal, $cart->total());
    }

    public function test_free_shipping()
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
        $code = 'shipping-fee';
        $description = '運費120';
        $fee = new Fee($code, $description, function ($cart) {
            return 120;
        });

        $code = 'shipping-fee';
        $description = '滿10免運費';
        $coupon = new Coupon($code, $description, function ($cart) {
            if ($cart->grossTotal() >= 10) {
                $cart->removeFee('shipping-fee');
            }

            return 0;
        });

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $grossTotal = $items->sum(function ($item) {
            return $item->total();
        });

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $cart->addFee($fee);
        $this->assertSame($grossTotal, $cart->grossTotal());
        $this->assertSame($grossTotal + 120, $cart->total());

        $total = $grossTotal >= 10 ? $grossTotal : $grossTotal + 120;

        $cart->addCoupon($coupon);
        $this->assertSame($grossTotal, $cart->grossTotal());
        $this->assertSame($total, $cart->total());
    }

    public function test_total_must_bigger_then_zero()
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

        $code = 'error-coupon';
        $description = 'error-coupon';
        $coupon = new Coupon($code, $description, function ($cart) {
            return 1000000000;
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

        $cart->addCoupon($coupon);
        $this->assertGreaterThan(-1, $cart->total());
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
