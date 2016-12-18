<?php

namespace Recca0120\Cart;

use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind(Cart::class, function ($app) {
            return new Cart(new Storage('default', $app['session']->driver()));
        });
    }
}
