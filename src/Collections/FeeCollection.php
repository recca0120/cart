<?php

namespace Recca0120\Cart\Collections;

use Illuminate\Support\Collection;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Fee as FeeContract;
use Recca0120\Cart\Contracts\FeeCollection as FeeCollectionContract;

class FeeCollection extends Collection implements FeeCollectionContract
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
    public function add(FeeContract $Fee)
    {
        $this->put($Fee->getCode(), $Fee);

        return $this;
    }

    /**
     * remove.
     *
     * @method remove
     *
     * @param \Recca0120\Cart\Contracts\Fee|string $Fee
     *
     * @return static
     */
    public function remove($Fee)
    {
        $code = ($Fee instanceof FeeContract) ? $Fee->getCode() : $Fee;
        $this->forget($code);

        return $this;
    }

    /**
     * apply.
     *
     * @method apply
     *
     * @param CartContract $cart
     *
     * @return static
     */
    public function apply(CartContract $cart)
    {
        return $this->map(function ($Fee) use ($cart) {
            return $Fee->apply($cart);
        });
    }
}
