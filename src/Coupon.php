<?php

namespace Recca0120\Cart;

use Closure;
use Illuminate\Support\Fluent;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Coupon as CouponContract;

class Coupon extends Fluent implements CouponContract
{
    public function __construct($code, $description, Closure $handler = null)
    {
        $this
            ->setCode($code)
            ->setDescription($description)
            ->setHandler($handler)
            ->setDiscount(0);
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    public function defaultHandler(CartContract $cart)
    {
        return 0;
    }

    public function setHandler(Closure $handler = null)
    {
        $this->handler = is_null($handler) === false ? $handler : [$this, 'defaultHandler'];

        return $this;
    }

    public function apply(CartContract $cart)
    {
        return call_user_func($this->handler, $cart);
    }
}
