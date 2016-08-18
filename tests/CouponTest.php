<?php

use Mockery as m;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Coupon;

class CouponTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_serialize_default_handler()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $coupon = new Coupon('test', 'test');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $serialized = serialize($coupon);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $unserialized = unserialize($serialized);
        $this->assertTrue(is_string($serialized));
        $this->assertTrue(is_array($unserialized->getHandler()));
        $unserialized->getHandler();
    }

    public function test_serialize_custom_handler()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $coupon = new Coupon('test', 'test', function (CartContract $cart) {
            return 0;
        });

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $serialized = serialize($coupon);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $unserialized = unserialize($serialized);
        $this->assertTrue(is_string($serialized));
        $this->assertInstanceOf(Closure::class, $unserialized->getHandler());
        $unserialized->getHandler();
    }
}
