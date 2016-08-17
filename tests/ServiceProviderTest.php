<?php

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Mockery as m;
use Recca0120\Cart\Cart;
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

        $app->shouldReceive('singleton')->with(Cart::class, m::type(Closure::class))->once()->andReturnUsing(function ($className, $closure) use ($app) {
            $this->assertInstanceOf(Cart::class, $closure($app));
        });

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $serviceProvider = new ServiceProvider($app);
        $serviceProvider->register();
    }
}
