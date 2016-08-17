<?php

namespace Recca0120\Cart;

use Illuminate\Support\Arr;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart implements CartContract
{
    protected static $instance = [];

    protected $name = 'default';

    protected $storage;

    protected $items;

    protected $coupons;

    public function __construct($name = 'default', SessionInterface $session = null)
    {
        $this->setName($name);
        self::$instance[$this->getName()] = $this;
        $this->storage = new Storage($session);
        $data = $this->storage->get($this);
        $this->items = Arr::get($data, 'items', new ItemCollection());
        $this->coupons = Arr::get($data, 'coupons', new CouponCollection());
    }

    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function add(ItemContract $item, $quantity = 0)
    {
        $this->items->add($item, $quantity);
        $this->storage->set($this);

        return $this;
    }

    public function remove($item)
    {
        $this->items->remove($item);
        $this->storage->set($this);

        return $this;
    }

    public function clear()
    {
        $this->items = new ItemCollection();
        $this->coupons = new CouponCollection();
        $this->storage->set($this);

        return $this;
    }

    public function items()
    {
        return $this->items;
    }

    public function count()
    {
        return $this->items()->count();
    }

    public function total()
    {
        return $this->items()->total();
    }

    public function coupons()
    {
        return $this->coupons;
    }

    public static function instance($name = 'default', SessionInterface $session = null)
    {
        return (array_key_exists($name, static::$instance) === true) ? self::$instance[$name] : new static($name, $session);
    }

    public static function driver($name = 'default', SessionInterface $session = null)
    {
        return static::instance($name, $session);
    }
}
