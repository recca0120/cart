<?php

namespace Recca0120\Cart;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Coupon as CouponContract;
use Recca0120\Cart\Contracts\Fee as FeeContract;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Recca0120\Cart\Contracts\Storage as StorageContract;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton(StorageContract::class, function ($app) {
            return new Storage($app['session']->driver());
        });
        $this->app->singleton(CartContract::class, Cart::class);
        $this->app->bind(ItemContract::class, Item::class);
        $this->app->bind(CouponContract::class, Coupon::class);
        $this->app->bind(FeeContract::class, Fee::class);
    }
}
