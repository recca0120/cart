<?php

namespace Recca0120\Cart;

use Illuminate\Support\Collection;

class Cart
{
    public $items;

    protected $storage;

    public function __construct(Storage $storage = null)
    {
        $this->storage = is_null($storage) === true ? new Storage() : $storage;
        $this->items = $this->storage->restore();
    }

    public function add(Item $item, $quantity = 1)
    {
        $this->items->put($item->getId(), $item->setQuantity($quantity));

        return $this;
    }

    public function remove($id)
    {
        return $this->items->forget($id);
    }

    public function clear()
    {
        $this->items = $this->items->filter(function() {
            return false;
        });

        return $this;
    }

    public function items()
    {
        return $this->items;
    }

    public function count()
    {
        return $this->items->count();
    }

    public function total()
    {
        return $this->items->reduce(function ($sum, $item) {
            return $sum += $item->getPrice() * $item->getQuantity();
        }, 0);
    }

    public function toArray()
    {
        return $this->items->map(function($item) {
            return $item->toArray();
        })->toArray();
    }

    public function toJson($option = 0)
    {
        return json_encode($this->toArray(), $option);
    }

    public function __destruct()
    {
        $this->storage->store($this->items);
    }
}
