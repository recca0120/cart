<?php

namespace Recca0120\Cart;

use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart implements CartContract
{
    const ALGORITHM = 'sha256';

    /**
     * $id.
     *
     * @var string
     */
    protected $id;

    /**
     * $items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * $session.
     *
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * __construct.
     *
     * @method __construct
     */
    public function __construct($id = null, SessionInterface $session = null)
    {
        $id = is_null($id) === true ? static::class : $id;

        $this->setId($id);

        if (is_null($session) === false) {
            $this->setSession($session);
        }
    }

    /**
     * getId.
     *
     * @method getId
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
     * setSession.
     *
     * @method setSession
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     *
     * @return static
     */
    public function setSession(SessionInterface $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * put.
     *
     * @method put
     *
     * @param  \Recca0120\Cart\Contracts\Item   $item
     * @param  int                              $quantity
     *
     * @return static
     */
    public function put(ItemContract $item, $quantity = 1)
    {
        $item->setQuantity($quantity);
        array_push($this->items, $item);

        return $this;
    }

    /**
     * count.
     *
     * @method count
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
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
        return array_reduce($this->items, function ($total, $item) {
            return $total + ($item->getPrice() * $item->getQuantity());
        }, 0);
    }

    /**
     * Convert the Fluent instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the Fluent instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
