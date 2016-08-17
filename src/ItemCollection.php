<?php

namespace Recca0120\Cart;

use Illuminate\Support\Collection;
use Recca0120\Cart\Contracts\Item as ItemContract;

class ItemCollection extends Collection
{
    public function add(ItemContract $item, $quantity = 0)
    {
        $item->setQuantity($quantity);
        $this->put($item->getId(), $item);

        return $this;
    }

    public function remove($item)
    {
        $itemId = ($item instanceof ItemContract) ? $item->getId() : $item;
        $this->forget($itemId);

        return $this;
    }

    public function total()
    {
        return $this->sum(function (ItemContract $item) {
            return $item->total();
        });
    }
}
