<?php

use Mockery as m;
use Recca0120\Cart\Contracts\Cart as CartContract;
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

        $this->assertTrue(is_string($serialized));
        $this->assertTrue(is_callable(unserialize($serialized)->getHandler()));
    }

    public function test_serialize_custom_handler()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $fee = new Fee('test', 'test', function (CartContract $cart) {
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

        $this->assertTrue(is_string($serialized));
        $this->assertInstanceOf(Closure::class, unserialize($serialized)->getHandler());
    }
}
