<?php

namespace Recca0120\Cart\Coupons;

use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Coupon as CouponContract;

class FreeShippingCoupon implements CouponContract
{
    protected $shippingFee;

    protected $freeShipping;

    public function __construct($shippingFee = 0, $freeShipping = 0)
    {
        $this->shippingFee = $shippingFee;
        $this->freeShipping = $freeShipping;
    }

    public function getDescription()
    {
        return sprintf('滿 %s 免運', $this->freeShipping);
    }

    public function discount(CartContract $cart)
    {
        $grossTotal = $cart->getGrossTotal();

        if ($grossTotal >= $this->freeShipping) {
            return 0;
        }

        return $this->shippingFee;
    }
}
