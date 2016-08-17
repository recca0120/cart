<?php

namespace Recca0120\Cart\Contracts;

interface CouponCollection
{
    public function add(Coupon $coupon);

    public function remove($coupon);

    public function apply(Cart $cart);
}
