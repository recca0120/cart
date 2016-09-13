<?php

use Mockery as m;
use Recca0120\Cart\Contracts\Cart;
use Recca0120\Cart\Fee;

class FeeTest extends PHPUnit_Framework_TestCase
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

        $fee = new Fee('test', 'test');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $serialized = serialize($fee);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $unserialized = unserialize($serialized);
        $this->assertTrue(is_string($serialized));
        $this->assertTrue(is_array($unserialized->getHandler()));
        $this->assertTrue(is_callable($unserialized->getHandler()));
    }

    public function test_serialize_custom_handler()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $fee = new Fee('test', 'test', function (Cart $cart) {
            return 0;
        });

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $serialized = serialize($fee);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $unserialized = unserialize($serialized);
        $this->assertTrue(is_string($serialized));
        $this->assertInstanceOf(Closure::class, $unserialized->getHandler());
    }
}
