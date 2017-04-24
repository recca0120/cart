<?php

namespace Recca0120\Cart;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

class Cart implements ArrayAccess, IteratorAggregate
{
    /**
     * $items.
     *
     * @var \Illuminate\Support\Collection
     */
    public $items;

    /**
     *
     * @param \Recca0120\Cart\Storage $storage
     */
    protected $storage;

    /**
     * __construct.
     *
     * @param \Recca0120\Cart\Storage $storage
     */
    public function __construct(Storage $storage = null)
    {
        $this->storage = is_null($storage) === true ? new Storage() : $storage;
        $this->restore();
    }

    /**
     * put.
     *
     * @param \Recca0120\Cart\Item $item
     * @param int $quantity
     * @return $this
     */
    public function put(Item $item)
    {
        $this->items->put($item->getId(), $item);

        return $this;
    }

    /**
     * get.
     *
     * @param \Recca0120\Cart\Item $item
     * @param int $quantity
     * @return $this
     */
    public function get($itemId)
    {
        return $this->items->get($itemId);
    }

    /**
     * remove.
     *
     * @param  string $id
     * @return bool
     */
    public function remove($item)
    {
        return $this->items->forget($item instanceof Item ? $item->getId() : $item);
    }

    /**
     * clear.
     *
     * @return $this
     */
    public function clear()
    {
        $this->items = $this->items->filter(function () {
            return false;
        });

        return $this;
    }

    /**
     * items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * count.
     *
     * @return int
     */
    public function count()
    {
        return $this->items->count();
    }

    /**
     * total.
     *
     * @return float
     */
    public function total()
    {
        return $this->items->sum(function ($item) {
            return $item->getPrice() * $item->getQuantity();
        });
    }

    /**
     * restore.
     *
     * @return $this
     */
    public function restore() {
        $this->items = $this->storage->restore();

        return $this;
    }

    /**
     * store.
     *
     * @return $this
     */
    public function store()
    {
        $this->storage->store($this->items);

        return $this;
    }

    /**
     * toArray.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items->map(function ($item) {
            return $item->toArray();
        })->toArray();
    }

    /**
     * toJson.
     *
     * @param int $option
     * @return string
     */
    public function toJson($option = 0)
    {
        return json_encode($this->toArray(), $option);
    }

    /**
     * __destruct.
     */
    public function __destruct()
    {
        $this->store();
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->items->offsetExists($key);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->items->offsetGet($key);
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->items->offsetSet($key, $value);
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->items->offsetUnset($key);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items->all());
    }
}
