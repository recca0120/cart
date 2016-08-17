<?php

namespace Recca0120\Cart\Contracts;

interface ItemCollection
{
    public function add(Item $item, $quantity = 0);

    public function remove($item);

    public function total();
}
