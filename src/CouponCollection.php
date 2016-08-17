<?php

namespace Recca0120\Cart;

use Illuminate\Support\Collection;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Coupon as CouponContract;

class CouponCollection extends Collection
{
    public function add(CouponContract $coupon, $quantity = 0)
    {
        $coupon->setQuantity($quantity);
        $this->put($coupon->getCode(), $coupon);

        return $this;
    }

    public function remove($coupon)
    {
        $couponId = ($coupon instanceof CouponContract) ? $coupon->getCode() : $coupon;
        $this->forget($couponId);

        return $this;
    }

    public function discount(CartContract $cart)
    {
        return $this->map(function ($coupon) use ($cart) {
            $discount = $coupon->apply($cart);
            $coupon->setDiscount($discount);

            return $discount;
        });
    }
}
