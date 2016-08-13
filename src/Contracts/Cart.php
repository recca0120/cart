<?php

namespace Recca0120\Cart\Contracts;

use Countable;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface Cart extends Arrayable, Countable, Jsonable
{
    /**
     * getId.
     *
     * @method getId
     *
     * @return string
     */
    public function getId();

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
     * setSession.
     *
     * @method setSession
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     *
     * @return static
     */
    public function setSession(SessionInterface $session);

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
    public function put(ItemContract $item, $quantity = 1);

    /**
     * count.
     *
     * @method count
     *
     * @return int
     */
    public function count();

    /**
     * total.
     *
     * @method total
     *
     * @return int
     */
    public function total();
}
