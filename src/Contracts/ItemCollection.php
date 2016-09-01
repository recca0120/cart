<?php

namespace Recca0120\Cart\Contracts;

interface ItemCollection
{
    /**
     * add.
     *
     * @method add
     *
     * @param \Recca0120\Cart\Contracts\Fee $Fee
     *
     * @return static
     */
    public function add(Item $item, $quantity = 0);

    /**
     * remove.
     *
     * @method remove
     *
     * @param \Recca0120\Cart\Contracts\Item|string $item
     *
     * @return static
     */
    public function remove($item);

    /**
     * total.
     *
     * @method total
     *
     * @return float
     */
    public function total();
}
