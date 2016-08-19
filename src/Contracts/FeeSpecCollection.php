<?php

namespace Recca0120\Cart\Contracts;

interface FeeSpecCollection
{
    public function add(FeeSpec $fee);

    public function remove($fee);

    public function apply(Cart $cart);
}
