<?php

namespace Recca0120\Cart;

use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Fee as FeeContract;

class Fee extends CouponOrFee implements FeeContract
{
    public function defaultHandler(CartContract $cart, FeeContract $fee)
    {
        return 0;
    }
}
