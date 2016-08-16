<?php

namespace Recca0120\Cart;

use CachingIterator;
use Illuminate\Support\Collection;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class Cart implements CartContract
{
    const ALGORITHM = 'sha256';

    const ITEM_KEY = 'items';

    /**
     * $instance.
     *
     * @var array
     */
    protected static $instance = [];

    /**
     * $id.
     *
     * @var string
     */
    protected $id;

    /**
     * $items.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $items;

    /**
     * $storage.
     *
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $storage;

    /**
     * __construct.
     *
     * @method __construct
     *
     * @param string $id
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $storage
     */
    public function __construct($id = null, SessionInterface $storage = null)
    {
        $id = is_null($id) === true ? static::class : $id;
        self::$instance[$id] = $this;
        $this->items = new Collection();

        $this->setId($id);
        $this->setStorage($storage);
    }

    /**
     * getStorage.
     *
     * @method getStorage
     *
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * setStorage.
     *
     * @method setStorage
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $storage
     *
     * @return static
     */
    public function setStorage(SessionInterface $storage = null)
    {
        $this->storage = is_null($storage) === false ? $storage : new Session(new PhpBridgeSessionStorage());
        if ($this->storage->isStarted() === false) {
            $this->storage->start();
            $this->registerShutdownStorage();
        }

        $items = $this->storage->get($this->getId(static::ITEM_KEY));
        if (empty($items) === false) {
            $this->items = unserialize($items);
        }

        return $this;
    }

    /**
     * updateStorage.
     *
     * @method updateStorage
     *
     * @param string    $key
     * @param mixed     $value
     *
     * @return static
     */
    public function updateStorage($key, $value)
    {
        $this->storage->set($this->getId(static::ITEM_KEY), serialize($value));

        return $this;
    }

    /**
     * registerShutdownStorage.
     *
     * @method registerShutdownStorage
     *
     * @return static
     */
    public function registerShutdownStorage()
    {
        register_shutdown_function(function () {
            if ($this->storage->isStarted() === true) {
                $this->storage->save();
            }
        });

        return $this;
    }

    /**
     * getId.
     *
     * @method getId
     *
     * @return string
     */
    public function getId($key = null)
    {
        return $this->id.$key;
    }

    /**
     * setId.
     *
     * @method setId
     *
     * @param string $id
     *
     * @return static
     */
    public function setId($id)
    {
        $this->id = hash(static::ALGORITHM, $id);

        return $this;
    }

    /**
     * put.
     *
     * @method put
     *
     * @param \Recca0120\Cart\Contracts\Item   $item
     * @param int                              $quantity
     *
     * @return static
     */
    public function put(ItemContract $item, $quantity = 1)
    {
        $item->setQuantity($quantity);
        $this->items->put($item->getId(), $item);
        $this->updateStorage('items', $this->items);

        return $this;
    }

    /**
     * remove.
     *
     * @method remove
     *
     * @param \Recca0120\Cart\Contracts\Item|int   $item
     *
     * @return static
     */
    public function remove($item)
    {
        $id = ($item instanceof Item) ? $item->getId() : $item;
        $this->items->forget([$id]);
        $this->updateStorage('items', $this->items);

        return $this;
    }

    /**
     * items.
     *
     * @method items
     *
     * @return int
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * total.
     *
     * @method total
     *
     * @return int
     */
    public function total()
    {
        return $this->items->reduce(function ($total, $item) {
            return $total + ($item->getPrice() * $item->getQuantity());
        }, 0);
    }

    /**
     * instance.
     *
     * @method instance
     *
     * @param string $id
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $storage
     *
     * @return static
     */
    public static function instance($id = null, SessionInterface $storage = null)
    {
        $id = is_null($id) === true ? static::class : $id;

        return isset(self::$instance[$id]) === false ? new static($id, $storage) : self::$instance[$id];
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items->toArray();
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->items->jsonSerialize();
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->items->getIterator();
    }

    /**
     * Get a CachingIterator instance.
     *
     * @param  int  $flags
     * @return \CachingIterator
     */
    public function getCachingIterator($flags = CachingIterator::CALL_TOSTRING)
    {
        return $this->items->getCachingIterator($flags);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return $this->items->count();
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
        return $this->items->offsetSet($key, $value);
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
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->items->toJson();
    }
}
