<?php

namespace Recca0120\Cart;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Recca0120\Cart\Contracts\Cart as CartContract;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CartContract::class, Cart::class);
    }
}
