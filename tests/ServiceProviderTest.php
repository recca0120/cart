<?php

use Mockery as m;
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

        $app = m::mock('Illuminate\Contracts\Foundation\Application, ArrayAccess');
        $sessionManager = m::mock('Illuminate\Session\SessionManager');
        $session = m::mock('Illuminate\Session\SessionInterface');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $sessionManager->shouldReceive('driver')->once()->andReturn($session);

        $session
            ->shouldReceive('isStarted')->once()->andReturn(false)
            ->shouldReceive('start')->once()->andReturn(false);

        $app
            ->shouldreceive('offsetGet')->with('session')->once()->andReturn($sessionManager)
            ->shouldReceive('singleton')->with('Recca0120\Cart\Contracts\Storage', m::type('Closure'))->once()->andReturnUsing(function ($className, $closure) use ($app) {
                $closure($app);
            })
            ->shouldReceive('singleton')->with('Recca0120\Cart\Contracts\Cart', 'Recca0120\Cart\Cart')->once()
            ->shouldReceive('bind')->with('Recca0120\Cart\Contracts\Item', 'Recca0120\Cart\Item')->once()
            ->shouldReceive('bind')->with('Recca0120\Cart\Contracts\Coupon', 'Recca0120\Cart\Coupon')->once()
            ->shouldReceive('bind')->with('Recca0120\Cart\Contracts\Fee', 'Recca0120\Cart\Fee')->once();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $serviceProvider = new ServiceProvider($app);
        $serviceProvider->register();
    }
}
