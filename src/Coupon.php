<?php

namespace Recca0120\Cart;

use Recca0120\Cart\Contracts\Coupon as CouponContract;

class Coupon implements CouponContract
{
    public function __construct()
    {
    }

    public function apply(CartContract $cart)
    {
    }
}
