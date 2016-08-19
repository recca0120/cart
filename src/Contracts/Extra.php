<?php

namespace Recca0120\Cart\Contracts;

use Closure;

interface Extra extends Handler
{
    public function __construct($code, $description, Closure $handler = null);

    public function getCode();

    public function setCode($code);

    public function getDescription();

    public function setDescription($description);

    public function getValue();

    public function setValue($value);

    public function defaultHandler(Cart $cart, Extra $coupon);

    public function apply(Cart $cart);
}
