<?php

use Faker\Factory as FakerFactory;
use Mockery as m;
use Recca0120\Cart\Cart;
use Recca0120\Cart\Coupon;
use Recca0120\Cart\Fee;
use Recca0120\Cart\Item;
use Recca0120\Cart\Storage;

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

        $session = m::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');

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

    public function test_add_items()
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

        $this->assertSame((float) $total, $cart->total());
        $this->assertSame((float) $total, $cart->items()->total());
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

    public function test_clear_items()
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

        $cart->addCoupon(new Coupon('test', 'test'));
        $cart->addFee(new Fee('test', 'test'));

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
        $this->assertSame(1, $cart->coupons()->count());
        $this->assertSame(1, $cart->fees()->count());
    }

    public function test_clear_all()
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

        $cart->addCoupon(new Coupon('test', 'test'));
        $cart->addFee(new Fee('test', 'test'));

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

        $cart->clear(true);

        $this->assertSame(0, $cart->count());
        $this->assertSame(0, $cart->items()->count());
        $this->assertSame(0, $cart->coupons()->count());
        $this->assertSame(0, $cart->fees()->count());
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
            return $cart->subtotal() * $rate;
        });

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $subtotal = $items->sum(function ($item) {
            return $item->total();
        });

        $discount = $cart->subtotal() * $rate;
        $total = $subtotal - $discount;

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $cart->addCoupon($coupon);
        $this->assertSame((float) $subtotal, $cart->subtotal());
        $this->assertSame((float) $total, $cart->total());

        $this->assertSame($code, $coupon->getCode());
        $this->assertSame($description, $coupon->getDescription());
        $this->assertSame($discount, $coupon->getValue());
        $this->assertSame(0, $coupon->defaultHandler($cart, $coupon));

        $cart->removeCoupon($coupon->getCode());
        $this->assertSame((float) $subtotal, $cart->subtotal());
        $this->assertSame((float) $subtotal, $cart->total());
    }

    public function test_add_coupon_20_percent_off_fixed()
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
            return $cart->subtotal() * $rate;
        });

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $subtotal = $items->sum(function ($item) {
            return $item->total();
        });

        $discount = $cart->subtotal() * $rate;
        $total = $subtotal - $discount;

        $cart->setHandler(function ($total, $options) {
            return round($total);
        });

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $cart->addCoupon($coupon);
        $this->assertSame((float) $subtotal, $cart->subtotal());
        $this->assertSame(round($total), $cart->total());

        $this->assertSame($code, $coupon->getCode());
        $this->assertSame($description, $coupon->getDescription());
        $this->assertSame($discount, $coupon->getValue());
        $this->assertSame(0, $coupon->defaultHandler($cart, $coupon));

        $cart->removeCoupon($coupon->getCode());
        $this->assertSame((float) $subtotal, $cart->subtotal());
        $this->assertSame((float) $subtotal, $cart->total());
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

        $subtotal = $items->sum(function ($item) {
            return $item->total();
        });

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $cart->addFee($fee);
        $this->assertSame((float) $subtotal, $cart->subtotal());
        $this->assertSame((float) $subtotal + 120, $cart->total());

        $this->assertSame($code, $fee->getCode());
        $this->assertSame($description, $fee->getDescription());
        $this->assertSame(0, $fee->defaultHandler($cart, $fee));

        $cart->removeFee($fee->getCode());
        $this->assertSame((float) $subtotal, $cart->subtotal());
        $this->assertSame((float) $subtotal, $cart->total());
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
        $shippingFee = 120;
        $code = 'shipping-fee';
        $description = '運費120';
        $fee = new Fee($code, $description, function (Cart $cart, Fee $fee) use ($shippingFee) {
            return $shippingFee;
        });

        $code = 'shipping-fee';
        $description = '滿10免運費';
        $coupon = new Coupon($code, $description, function (Cart $cart, Coupon $coupon) use ($shippingFee) {
            return $cart->subtotal() >= 10 ? $shippingFee : 0;
            // if ($cart->subtotal() >= 10) {
            //     $cart->removeFee('shipping-fee');
            // }
            //
            // return 0;
        });

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $subtotal = $items->sum(function ($item) {
            return $item->total();
        });

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $cart->addFee($fee);
        $this->assertSame((float) $subtotal, $cart->subtotal());
        $this->assertSame((float) $subtotal + 120, $cart->total());

        $total = $subtotal >= 10 ? $subtotal : $subtotal + 120;

        $cart->addCoupon($coupon);
        $this->assertSame((float) $subtotal, $cart->subtotal());
        $this->assertSame((float) $total, $cart->total());
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
