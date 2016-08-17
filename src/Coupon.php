<?php

namespace Recca0120\Cart;

use Closure;
use Illuminate\Support\Fluent;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Coupon as CouponContract;

class Coupon extends Fluent  implements CouponContract
{
    public function __construct($code, $description, Closure $handler = null)
    {
        $this->code = $code;
        $this->description = $description;
        $this->discount = 0;
        $this->handler = is_null($handler) === false ? $handler : [$this, 'defaultHandler'];
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setHandler(Closure $handler)
    {
        $this->handler = $handler;
    }

    public function defaultHandler(CartContract $cart)
    {
        return $cart;
    }

    public function apply(CartContract $cart)
    {
        return call_user_func($this->handler, $cart);
    }
}
