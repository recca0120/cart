<?php

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Mockery as m;
use Recca0120\Cart\Cart;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\ServiceProvider;

class ServiceProviderTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_register()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $app = m::mock(ApplicationContract::class);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $app->shouldReceive('singleton')->with(CartContract::class, Cart::class);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $serviceProvider = new ServiceProvider($app);
        $serviceProvider->register();
    }
}
