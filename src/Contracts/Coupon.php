<?php

namespace Recca0120\Cart\Contracts;

use Closure;

interface Coupon
{
    public function __construct($code, $description, Closure $handler = null);

    public function getCode();

    public function setCode($code);

    public function getDescription();

    public function setDescription($description);

    public function getDiscount();

    public function setDiscount($discount);

    public function defaultHandler(Cart $cart, Coupon $coupon);

    public function getHandler();

    public function setHandler(Closure $handler = null);

    public function apply(Cart $cart);
}
