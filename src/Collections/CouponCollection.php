<?php

namespace Recca0120\Cart\Collections;

use Illuminate\Support\Collection;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Coupon as CouponContract;
use Recca0120\Cart\Contracts\CouponCollection as CouponCollectionContract;

class CouponCollection extends Collection implements CouponCollectionContract
{
    public function add(CouponContract $coupon)
    {
        $this->put($coupon->getCode(), $coupon);

        return $this;
    }

    public function remove($coupon)
    {
        $couponId = ($coupon instanceof CouponContract) ? $coupon->getCode() : $coupon;
        $this->forget($couponId);

        return $this;
    }

    public function apply(CartContract $cart)
    {
        return $this->map(function ($coupon) use ($cart) {
            return $coupon->apply($cart);
        });
    }
}
