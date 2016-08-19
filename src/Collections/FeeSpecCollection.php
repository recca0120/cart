<?php

namespace Recca0120\Cart\Collections;

use Illuminate\Support\Collection;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\FeeSpec as FeeSpecContract;
use Recca0120\Cart\Contracts\FeeSpecCollection as FeeSpecCollectionContract;

class FeeSpecCollection extends Collection implements FeeSpecCollectionContract
{
    public function add(FeeSpecContract $fee)
    {
        $this->put($fee->getCode(), $fee);

        return $this;
    }

    public function remove($fee)
    {
        $feeId = ($fee instanceof FeeSpecContract) ? $fee->getCode() : $fee;
        $this->forget($feeId);

        return $this;
    }

    public function apply(CartContract $cart)
    {
        return $this->map(function ($fee) use ($cart) {
            return $fee->apply($cart);
        });
    }
}
