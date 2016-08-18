<?php

namespace Recca0120\Cart\Collections;

use Illuminate\Support\Collection;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Recca0120\Cart\Contracts\ItemCollection as ItemCollectionContract;

class ItemCollection extends Collection implements ItemCollectionContract
{
    public function add(ItemContract $item, $quantity = 0)
    {
        $item->setQuantity($quantity);
        $this->put($item->getSku(), $item);

        return $this;
    }

    public function remove($item)
    {
        $sku = ($item instanceof ItemContract) ? $item->getSku() : $item;
        $this->forget($sku);

        return $this;
    }

    public function total()
    {
        return $this->sum(function (ItemContract $item) {
            return $item->total();
        });
    }
}
