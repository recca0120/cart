<?php

namespace Recca0120\Cart;

use Closure;
use Illuminate\Support\Fluent;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Extra as ExtraContract;
use Recca0120\Cart\Helpers\HandlerSerializer;

abstract class Extra extends Fluent implements ExtraContract
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
        $this->value = (float) $value;

        return $this;
    }

    public function apply(CartContract $cart)
    {
        $value = call_user_func_array($this->getHandler(), [$cart, $this]);

        return $this->setValue($value);
    }

    public function defaultHandler(CartContract $cart, ExtraContract $coupon)
    {
        return 0;
    }
}
