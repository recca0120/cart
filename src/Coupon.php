<?php

namespace Recca0120\Cart;

use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Coupon as CouponContract;

class Coupon extends CouponOrFee implements CouponContract
{
    public function defaultHandler(CartContract $cart, CouponContract $coupon)
    {
        return 0;
    }
}
