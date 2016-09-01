<?php

namespace Recca0120\Cart\Contracts;

interface FeeCollection
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
    public function add(Fee $fee);

    /**
     * remove.
     *
     * @method remove
     *
     * @param \Recca0120\Cart\Contracts\Fee|string $Fee
     *
     * @return static
     */
    public function remove($fee);

    /**
     * apply.
     *
     * @method apply
     *
     * @param CartContract $cart
     *
     * @return static
     */
    public function apply(Cart $cart);
}
