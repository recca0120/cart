<?php

namespace Recca0120\Cart;

use Illuminate\Support\Collection;
use Recca0120\Cart\Contracts\Coupon as CouponContract;

class CouponCollection extends Collection
{
    public function add(CouponContract $coupon, $quantity = 0)
    {
        $coupon->setQuantity($quantity);
        $this->put($coupon->getId(), $coupon);

        return $this;
    }

    public function remove($coupon)
    {
        $couponId = ($coupon instanceof CouponContract) ? $coupon->getId() : $coupon;
        $this->forget($couponId);

        return $this;
    }

    public function total()
    {
        return $this->sum(function (CouponContract $coupon) {
            return $coupon->total();
        });
    }
}
