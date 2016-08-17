<?php

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Mockery as m;
use Recca0120\Cart\Cart;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Coupon as CouponContract;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Recca0120\Cart\Contracts\Storage as StorageContract;
use Recca0120\Cart\Coupon;
use Recca0120\Cart\Item;
use Recca0120\Cart\ServiceProvider;
use Recca0120\Cart\Storage;

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

        $app
            ->shouldReceive('singleton')->with(StorageContract::class, Storage::class)->once()
            ->shouldReceive('singleton')->with(CartContract::class, Cart::class)->once()
            ->shouldReceive('bind')->with(ItemContract::class, Item::class)->once()
            ->shouldReceive('bind')->with(CouponContract::class, Coupon::class)->once();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $serviceProvider = new ServiceProvider($app);
        $serviceProvider->register();
    }
}
