<?php

namespace Recca0120\Cart\Coupons;

use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Util;

class FreeShipping extends Coupon
{
    protected $shippingFee;

    protected $freeShipping;

    public function __construct($shippingFee = 0, $freeShipping = 0)
    {
        $this->shippingFee = $shippingFee;
        $this->freeShipping = $freeShipping;
        $this->setCode(static::class);
        $this->setName(Util::hash(static::class));
    }

    public function discount(CartContract $cart)
    {
        $grossTotal = $cart->getGrossTotal();

        if ($grossTotal >= $this->freeShipping || $grossTotal == 0) {
            return 0;
        }

        return $this->shippingFee;
    }
}
