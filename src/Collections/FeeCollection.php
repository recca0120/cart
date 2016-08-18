<?php

namespace Recca0120\Cart\Collections;

use Illuminate\Support\Collection;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Fee as FeeContract;
use Recca0120\Cart\Contracts\FeeCollection as FeeCollectionContract;

class FeeCollection extends Collection implements FeeCollectionContract
{
    public function add(FeeContract $fee)
    {
        $this->put($fee->getCode(), $fee);

        return $this;
    }

    public function remove($fee)
    {
        $feeId = ($fee instanceof FeeContract) ? $fee->getCode() : $fee;
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
