<?php

namespace Recca0120\Cart\Coupons;

use Recca0120\Cart\Contracts\Coupon as CouponContract;

abstract class Coupon implements CouponContract
{
    protected $name;

    protected $description;

    protected $code;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
