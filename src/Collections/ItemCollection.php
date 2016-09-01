<?php

namespace Recca0120\Cart\Collections;

use Illuminate\Support\Collection;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Recca0120\Cart\Contracts\ItemCollection as ItemCollectionContract;

class ItemCollection extends Collection implements ItemCollectionContract
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
    public function add(ItemContract $item, $quantity = 0)
    {
        $item->setQuantity($quantity);
        $this->put($item->getSku(), $item);

        return $this;
    }

    /**
     * remove.
     *
     * @method remove
     *
     * @param \Recca0120\Cart\Contracts\Item|string $item
     *
     * @return static
     */
    public function remove($item)
    {
        $sku = ($item instanceof ItemContract) ? $item->getSku() : $item;
        $this->forget($sku);

        return $this;
    }

    /**
     * total.
     *
     * @method total
     *
     * @return float
     */
    public function total()
    {
        return (float) $this->sum(function (ItemContract $item) {
            return $item->total();
        });
    }
}
