<?php

namespace Recca0120\Cart\Facades;

use Illuminate\Support\Facades\Facade;
use Recca0120\Cart\Contracts\Item as ItemContract;

class Item extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ItemContract::class;
    }
}
