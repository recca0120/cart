<?php

namespace Recca0120\Cart\Contracts;

interface Coupon
{
    public function getDescription();

    public function discount(Cart $cart);
}
