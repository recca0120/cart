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
use Recca0120\Cart\Helpers\HandlerSerializer;

class Cart implements CartContract
{
    use HandlerSerializer;

    protected static $instance = [];

    protected $name = 'default';

    protected $storage;

    protected $items;

    protected $coupons;

    protected $fees;

    protected $handler;

    public function __construct($name = 'default', StorageContract $storage = null)
    {
        $this->setName($name);
        $this->setStorage($storage);
        self::$instance[$this->getName()] = $this;
    }

    public function setStorage(StorageContract $storage = null)
    {
        $this->storage = (is_null($storage) === false) ? $storage : new Storage();
        $data = $this->storage->get($this->getName());

        $this->setItems(Arr::get($data, 'items'));
        $this->setCoupons(Arr::get($data, 'coupons'));
        $this->setFees(Arr::get($data, 'fees'));
        $this->setHandler(Arr::get($data, 'handler'));

        return $this;
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

    public function addCoupon(CouponContract $coupon)
    {
        $this->coupons()->add($coupon);
        $this->save();

        return $this;
    }

    public function removeCoupon($coupon)
    {
        $this->coupons()->remove($coupon);
        $this->save();

        return $this;
    }

    public function addFee(FeeContract $fee)
    {
        $this->fees()->add($fee);
        $this->save();

        return $this;
    }

    public function removeFee($fee)
    {
        $this->fees()->remove($fee);
        $this->save();

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
        $coupon = $this->coupons()->apply($this)->reduce(function ($total, $coupon) {
            return $total + $coupon->getValue();
        }, 0);

        $fee = $this->fees()->apply($this)->reduce(function ($total, $fee) {
            return $total + $fee->getValue();
        }, 0);

        $grossTotal = $this->grossTotal();

        $total = max(0, $this->grossTotal() + $fee - $coupon);

        $params = [
            'total'      => $total,
            'options'    => [
                'grossTotal' => $grossTotal,
                'coupon'     => $coupon,
                'fee'        => $fee,
                'cart'       => $this,
            ],
        ];

        return call_user_func_array($this->getHandler(), $params);
    }

    public function defaultHandler($total, $options)
    {
        return $total;
    }

    public function coupons()
    {
        return $this->coupons;
    }

    public function fees()
    {
        return $this->fees;
    }

    public function add(ItemContract $item, $quantity = 0)
    {
        $this->items->add($item, $quantity);
        $this->save();

        return $this;
    }

    public function remove($item)
    {
        $this->items->remove($item);
        $this->save();

        return $this;
    }

    public function clear($clearAll = false)
    {
        $this->setItems(null);
        if ($clearAll === true) {
            $this->setCoupons(null);
            $this->setFees(null);
            $this->setHandler(null);
        }
        $this->save();

        return $this;
    }

    public function save()
    {
        $this->storage->set($this->getName(), [
            'items'   => $this->items(),
            'coupons' => $this->coupons(),
            'fees'    => $this->fees(),
        ]);
    }

    public static function instance($name = 'default', StorageContract $storage = null)
    {
        return (array_key_exists($name, static::$instance) === true) ? self::$instance[$name] : new static($name, $storage);
    }

    public static function driver($name = 'default', StorageContract $storage = null)
    {
        return static::instance($name, $storage);
    }

    protected function setItems(ItemCollection $items = null)
    {
        $this->items = is_null($items) === false ? $items : new ItemCollection();

        return $this;
    }

    protected function setCoupons(CouponCollection $coupons = null)
    {
        $this->coupons = is_null($coupons) === false ? $coupons : new CouponCollection();

        return $this;
    }

    protected function setFees(FeeCollection $fees = null)
    {
        $this->fees = is_null($fees) === false ? $fees : new FeeCollection();

        return $this;
    }
}
