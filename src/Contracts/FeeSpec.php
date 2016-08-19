<?php

namespace Recca0120\Cart\Contracts;

interface FeeSpec extends Handler
{
    public function __construct($code, $description, callable $handler = null);

    public function getCode();

    public function setCode($code);

    public function getDescription();

    public function setDescription($description);

    public function getValue();

    public function setValue($value);

    public function defaultHandler(Cart $cart, FeeSpec $coupon);

    public function apply(Cart $cart);
}
