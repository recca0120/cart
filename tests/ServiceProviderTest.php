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

        $app = m::mock('Illuminate\Contracts\Foundation\Application');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $app
            ->shouldReceive('singleton')->with('Recca0120\Cart\Contracts\Storage', 'Recca0120\Cart\Storage')->once()
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
