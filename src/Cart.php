<?php

namespace Recca0120\Cart;

use Illuminate\Support\Arr;
use Recca0120\Cart\Collections\FeeCollection;
use Recca0120\Cart\Collections\ItemCollection;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Fee as FeeContract;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Recca0120\Cart\Contracts\Storage as StorageContract;

class Cart implements CartContract
{
    use HandlerSerializer;

    protected static $instance = [];

    protected $name = 'default';

    protected $storage;

    protected $items;

    protected $fees = [
        'coupons'   => null,
        'fees'      => null,
    ];

    protected $handler;

    public static function instance($name = 'default', StorageContract $storage = null)
    {
        return (array_key_exists($name, static::$instance) === true) ? self::$instance[$name] : new static($name, $storage);
    }

    public static function driver($name = 'default', StorageContract $storage = null)
    {
        return static::instance($name, $storage);
    }

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

        $this->setItemCollection(Arr::get($data, 'items'));
        $this->setHandler(Arr::get($data, 'handler'));
        foreach ($this->fees as $key => $value) {
            $this->setFeeCollection($key, Arr::get($data, 'fees.'.$key));
        }

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
        return (float) $this->items()->total();
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

        $total = $this->grossTotal() + $fee - $coupon;

        $params = [
            'total'      => $total,
            'options'    => [
                'grossTotal' => $grossTotal,
                'coupon'     => $coupon,
                'fee'        => $fee,
                'cart'       => $this,
            ],
        ];

        return (float) max(0, call_user_func_array($this->getHandler(), $params));
    }

    public function defaultHandler($total, $options)
    {
        return $total;
    }

    public function add(ItemContract $item, $quantity = 0)
    {
        $this->items()->add($item, $quantity);
        $this->save();

        return $this;
    }

    public function remove($item)
    {
        $this->items()->remove($item);
        $this->save();

        return $this;
    }

    public function clear($clearAll = false)
    {
        $this->setItemCollection(null);
        if ($clearAll === true) {
            $this->setHandler(null);
            foreach ($this->fees as $key => $value) {
                $this->setFeeCollection($key, null);
            }
        }
        $this->save();

        return $this;
    }

    public function save()
    {
        $this->storage->set($this->getName(), [
            'items'   => $this->items,
            'fees'    => $this->fees,
            'handler' => $this->getHandler(),
        ]);

        return $this;
    }

    public function coupons()
    {
        return $this->getFeeCollection('coupons');
    }

    public function addCoupon(FeeContract $coupon)
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

    public function fees()
    {
        return $this->getFeeCollection('fees');
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

    protected function setItemCollection(ItemCollection $itemCollection = null)
    {
        $this->items = is_null($itemCollection) === false ? $itemCollection : new ItemCollection();

        return $this;
    }

    protected function setFeeCollection($key, FeeCollection $feeCollection = null)
    {
        $this->fees[$key] = is_null($feeCollection) === false ? $feeCollection : new FeeCollection();

        return $this;
    }

    protected function getFeeCollection($key)
    {
        return Arr::get($this->fees, $key);
    }
}
