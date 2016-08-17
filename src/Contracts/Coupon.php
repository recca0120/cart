<?php

namespace Recca0120\Cart\Contracts;

interface Coupon
{
    public function getName();

    public function setName($name);

    public function setCode($code);

    public function getCode();

    public function setDescription($description);

    public function getDescription();

    public function discount(Cart $cart);
}
