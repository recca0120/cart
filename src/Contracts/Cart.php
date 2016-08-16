<?php

namespace Recca0120\Cart\Contracts;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use IteratorAggregate;
use JsonSerializable;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface Cart extends ArrayAccess, Arrayable, Countable, IteratorAggregate, Jsonable, JsonSerializable, Storagable
{
    /**
     * getId.
     *
     * @method getId
     *
     * @param string|null $key
     *
     * @return string
     */
    public function getId($key = null);

    /**
     * setId.
     *
     * @method setId
     *
     * @param string $id
     *
     * @return static
     */
    public function setId($id);

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
    public function put(ItemContract $item, $quantity = 1);

    /**
     * remove.
     *
     * @method remove
     *
     * @param \Recca0120\Cart\Contracts\Item|int   $item
     *
     * @return static
     */
    public function remove($item);

    /**
     * items.
     *
     * @method items
     *
     * @return \Illuminate\Support\Collection
     */
    public function items();

    /**
     * total.
     *
     * @method total
     *
     * @return int
     */
    public function total();

    /**
     * instance.
     *
     * @method instance
     *
     * @param string $id
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     *
     * @return static
     */
    public static function instance($id = null, SessionInterface $session = null);
}
