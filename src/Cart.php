<?php

namespace Recca0120\Cart;

use Illuminate\Support\Arr;
use Recca0120\Cart\Collections\CouponCollection;
use Recca0120\Cart\Collections\FeeCollection;
use Recca0120\Cart\Collections\ItemCollection;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Coupon as CouponContract;
use Recca0120\Cart\Contracts\Fee as FeeContract;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Recca0120\Cart\Contracts\Storage as StorageContract;

class Cart implements CartContract
{
    protected static $instance = [];

    protected $name = 'default';

    protected $storage;

    protected $items;

    protected $coupons;

    public function __construct($name = 'default', StorageContract $storage = null)
    {
        $this->setName($name);
        self::$instance[$this->getName()] = $this;
        $this->setStorage($storage);
    }

    public function setStorage(StorageContract $storage = null)
    {
        $this->storage = (is_null($storage) === false) ? $storage : new Storage();
        $data = $this->storage->get($this);
        $this->items = Arr::get($data, 'items', new ItemCollection());
        $this->coupons = Arr::get($data, 'coupons', new CouponCollection());
        $this->fees = Arr::get($data, 'fees', new FeeCollection());
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
        $this->fees = new FeeCollection();
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

    public function grossTotal()
    {
        return $this->items()->total();
    }

    public function total()
    {
        $coupons = $this->coupons()->apply($this);
        $fees = $this->fees()->apply($this);

        $couponTotal = $coupons->reduce(function ($total, $coupon) {
            return $total + $coupon->getValue();
        }, 0);

        $feeTotal = $fees->reduce(function ($total, $fee) {
            return $total + $fee->getValue();
        }, 0);

        return max(0, $this->grossTotal() + $feeTotal - $couponTotal);
    }

    public function coupons()
    {
        return $this->coupons;
    }

    public function addCoupon(CouponContract $coupon)
    {
        $this->coupons()->add($coupon);
        $this->storage->set($this);

        return $this;
    }

    public function removeCoupon($coupon)
    {
        $this->coupons()->remove($coupon);
        $this->storage->set($this);

        return $this;
    }

    public function fees()
    {
        return $this->fees;
    }

    public function addFee(FeeContract $fee)
    {
        $this->fees()->add($fee);
        $this->storage->set($this);

        return $this;
    }

    public function removeFee($fee)
    {
        $this->fees()->remove($fee);
        $this->storage->set($this);

        return $this;
    }

    public static function instance($name = 'default', StorageContract $storage = null)
    {
        return (array_key_exists($name, static::$instance) === true) ? self::$instance[$name] : new static($name, $storage);
    }

    public static function driver($name = 'default', StorageContract $storage = null)
    {
        return static::instance($name, $storage);
    }
}
