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

    /**
     * $instance.
     *
     * @var array
     */
    protected static $instance = [];

    /**
     * $name.
     *
     * @var string
     */
    protected $name = 'default';

    /**
     * $storage.
     *
     * @var \Recca0120\Cart\Contracts\Storage
     */
    protected $storage;

    /**
     * $items.
     *
     * @var \Recca0120\Cart\Collections\ItemCollection
     */
    protected $items;

    /**
     * $fees.
     *
     * @var array
     */
    protected $fees = [
        'coupons' => null,
        'fees' => null,
    ];

    /**
     * $handler.
     *
     * @var callable
     */
    protected $handler;

    /**
     * instance.
     *
     * @method instance
     *
     * @param string                            $name
     * @param \Recca0120\Cart\Contracts\Storage $storage
     *
     * @return static
     */
    public static function instance($name = 'default', StorageContract $storage = null)
    {
        return (array_key_exists($name, static::$instance) === true) ? self::$instance[$name] : new static($name, $storage);
    }

    /**
     * driver.
     *
     * @method driver
     *
     * @param string                            $name
     * @param \Recca0120\Cart\Contracts\Storage $storage
     *
     * @return static
     */
    public static function driver($name = 'default', StorageContract $storage = null)
    {
        return static::instance($name, $storage);
    }

    /**
     * __construct.
     *
     * @method __construct
     *
     * @param string                           $name
     * @param \Recca0120\Cart\Contracts\Storag $storage
     */
    public function __construct($name = 'default', StorageContract $storage = null)
    {
        $this->setName($name);
        $this->setStorage($storage);
        self::$instance[$this->getName()] = $this;
    }

    /**
     * setStorage.
     *
     * @method setStorage
     *
     * @param \Recca0120\Cart\Contracts\Storag $storage
     */
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

    /**
     * setName.
     *
     * @method setName
     *
     * @param string $name
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getName.
     *
     * @method getName
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * items.
     *
     * @method items
     *
     * @return \Recca0120\Cart\Collections\ItemCollection
     */
    public function items()
    {
        return $this->items;
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
        return $this->items()->count();
    }

    /**
     * subtotal.
     *
     * @method subtotal
     *
     * @return float
     */
    public function subtotal()
    {
        return (float) $this->items()->total();
    }

    /**
     * total.
     *
     * @method total
     *
     * @return float
     */
    public function total()
    {
        $coupon = $this->coupons()->apply($this)->reduce(function ($total, $coupon) {
            return $total + $coupon->getValue();
        }, 0);

        $fee = $this->fees()->apply($this)->reduce(function ($total, $fee) {
            return $total + $fee->getValue();
        }, 0);

        $subtotal = $this->subtotal();

        $total = $this->subtotal() + $fee - $coupon;

        $params = [
            'total' => $total,
            'options' => [
                'subtotal' => $subtotal,
                'coupon' => $coupon,
                'fee' => $fee,
                'cart' => $this,
            ],
        ];

        return (float) max(0, call_user_func_array($this->getHandler(), $params));
    }

    /**
     * defaultHandler.
     *
     * @method defaultHandler
     *
     * @param float $total
     * @param array $options
     *
     * @return float
     */
    public function defaultHandler($total, $options)
    {
        return $total;
    }

    /**
     * add.
     *
     * @method add
     *
     * @param \Recca0120\Cart\Contracts\Item $item
     * @param int                            $quantity
     *
     * @return static
     */
    public function add(ItemContract $item, $quantity = 0)
    {
        $this->items()->add($item, $quantity);
        $this->save();

        return $this;
    }

    /**
     * remove.
     *
     * @method remove
     *
     * @param \Recca0120\Cart\Contracts\Item|string $item
     *
     * @return static
     */
    public function remove($item)
    {
        $this->items()->remove($item);
        $this->save();

        return $this;
    }

    /**
     * clear.
     *
     * @method clear
     *
     * @param bool $clearAll
     *
     * @return static
     */
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

    /**
     * save.
     *
     * @method save
     *
     * @return static
     */
    public function save()
    {
        $this->storage->set($this->getName(), [
            'items' => $this->items,
            'fees' => $this->fees,
            'handler' => $this->getHandler(),
        ]);

        return $this;
    }

    /**
     * coupons.
     *
     * @method coupons
     *
     * @return \Recca0120\Cart\Collections\FeeCollection
     */
    public function coupons()
    {
        return $this->getFeeCollection('coupons');
    }

    /**
     * addCoupon.
     *
     * @method addCoupon
     *
     * @param \Recca0120\Cart\Contracts\Fee $coupon
     *
     * @return static
     */
    public function addCoupon(FeeContract $coupon)
    {
        $this->coupons()->add($coupon);
        $this->save();

        return $this;
    }

    /**
     * removeCoupon.
     *
     * @method removeCoupon
     *
     * @param \Recca0120\Cart\Contracts\Fee|string $coupon
     *
     * @return static
     */
    public function removeCoupon($coupon)
    {
        $this->coupons()->remove($coupon);
        $this->save();

        return $this;
    }

    /**
     * fees.
     *
     * @method fees
     *
     * @return \Recca0120\Cart\Collections\FeeCollection
     */
    public function fees()
    {
        return $this->getFeeCollection('fees');
    }

    /**
     * addFee.
     *
     * @method addFee
     *
     * @param \Recca0120\Cart\Contracts\Fee $fee
     *
     * @return static
     */
    public function addFee(FeeContract $fee)
    {
        $this->fees()->add($fee);
        $this->save();

        return $this;
    }

    /**
     * removeFee description.
     *
     * @method removeFee
     *
     * @param \Recca0120\Cart\Contracts\Fee|string $fee
     *
     * @return static
     */
    public function removeFee($fee)
    {
        $this->fees()->remove($fee);
        $this->save();

        return $this;
    }

    /**
     * setItemCollection.
     *
     * @method setItemCollection
     *
     * @param Recca0120\Cart\Collections\ItemCollection $itemCollection
     *
     * @return static
     */
    protected function setItemCollection(ItemCollection $itemCollection = null)
    {
        $this->items = is_null($itemCollection) === false ? $itemCollection : new ItemCollection();

        return $this;
    }

    /**
     * setFeeCollection.
     *
     * @method setFeeCollection
     *
     * @param string                                    $key
     * @param \Recca0120\Cart\Collections\FeeCollection $feeCollection
     *
     * @return static
     */
    protected function setFeeCollection($key, FeeCollection $feeCollection = null)
    {
        $this->fees[$key] = is_null($feeCollection) === false ? $feeCollection : new FeeCollection();

        return $this;
    }

    /**
     * getFeeCollection.
     *
     * @method getFeeCollection
     *
     * @param string $key
     *
     * @return Recca0120\Cart\Collections\FeeCollection
     */
    protected function getFeeCollection($key)
    {
        return Arr::get($this->fees, $key);
    }
}
