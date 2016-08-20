<?php

use Mockery as m;
use Recca0120\Cart\Serializers\OpisClosure;

class OpisClosureTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_serialize()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $serializer = new OpisClosure();

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

        $this->assertTrue(is_string($serializer->serialize(function () {})));
    }

    public function test_unserialize()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $serializer = new OpisClosure();

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

        $serialized = $serializer->serialize(function () {});
        $this->assertTrue($serializer->unserialize($serialized) instanceof Closure);
    }

    public function test_cannot_unserialize()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $serializer = new OpisClosure();

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

        $this->assertSame('foo', $serializer->unserialize('foo'));
    }
}
