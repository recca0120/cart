<?php

namespace Recca0120\Cart;

use Closure;
use Illuminate\Support\Fluent;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\CouponOrFee as CouponOrFeeContract;
use Recca0120\Cart\Helpers\HandlerSerializer;

abstract class CouponOrFee extends Fluent implements CouponOrFeeContract
{
    use HandlerSerializer;

    public function __construct($code, $description, Closure $handler = null)
    {
        $this
            ->setCode($code)
            ->setDescription($description)
            ->setHandler($handler)
            ->setValue(null);
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

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function apply(CartContract $cart)
    {
        $value = call_user_func_array($this->getHandler(), [$cart, $this]);

        return $this->setValue($value);
    }

    public function defaultHandler(CartContract $cart, CouponOrFeeContract $coupon)
    {
        return 0;
    }
}
