<?php

use Mockery as m;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Coupons\FreeShippingCoupon;

class FreeShippingCouponTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_pay_shipping_fee()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $shippingFee = 100;
        $freeShipping = 500;
        $grossTotal = 100;
        $coupon = new FreeShippingCoupon($shippingFee, $freeShipping);
        $cart = m::mock(CartContract::class);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $cart
            ->shouldReceive('addCoupon')->with($coupon)->once()
            ->shouldReceive('getGrossTotal')->andReturn($grossTotal)
            ->shouldReceive('total')->once()->andReturnUsing(function () use ($coupon) {
                return $coupon->discount(m::self());
            });

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $cart->addCoupon($coupon);
        $this->assertSame($shippingFee, $cart->total());
    }

    public function test_free_shipping()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $shippingFee = 100;
        $freeShipping = 500;
        $grossTotal = 1000;
        $coupon = new FreeShippingCoupon($shippingFee, $freeShipping);
        $cart = m::mock(CartContract::class);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $cart
            ->shouldReceive('addCoupon')->with($coupon)->once()
            ->shouldReceive('getGrossTotal')->andReturn($grossTotal)
            ->shouldReceive('total')->once()->andReturnUsing(function () use ($coupon) {
                return $coupon->discount(m::self());
            });

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $cart->addCoupon($coupon);
        $this->assertSame(0, $cart->total());
    }
}
