<?php

namespace Recca0120\Cart\Contracts;

interface FeeCollection
{
    public function add(Fee $fee);

    public function remove($fee);

    public function apply(Cart $cart);
}
