<?php

namespace Recca0120\Cart\Contracts;

use Closure;

interface Fee
{
    public function __construct($code, $description, Closure $handler = null);

    public function getCode();

    public function setCode($code);

    public function getDescription();

    public function setDescription($description);

    public function defaultHandler(Cart $cart);

    public function setHandler(Closure $handler = null);

    public function apply(Cart $cart);
}
