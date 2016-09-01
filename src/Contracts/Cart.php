<?php

namespace Recca0120\Cart\Contracts;

interface Cart extends Handler
{
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
    public static function instance($name = 'default', Storage $storage = null);

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
    public static function driver($name = 'default', Storage $storage = null);

    /**
     * __construct.
     *
     * @method __construct
     *
     * @param string                           $name
     * @param \Recca0120\Cart\Contracts\Storag $storage
     */
    public function __construct($name = 'default', Storage $storage = null);

    /**
     * setStorage.
     *
     * @method setStorage
     *
     * @param \Recca0120\Cart\Contracts\Storag $storage
     */
    public function setStorage(Storage $storage = null);

    /**
     * setName.
     *
     * @method setName
     *
     * @param string $name
     */
    public function setName($name = null);

    /**
     * getName.
     *
     * @method getName
     *
     * @return string
     */
    public function getName();

    /**
     * items.
     *
     * @method items
     *
     * @return \Recca0120\Cart\Collections\ItemCollection
     */
    public function items();

    /**
     * count.
     *
     * @method count
     *
     * @return int
     */
    public function count();

    /**
     * subtotal.
     *
     * @method subtotal
     *
     * @return float
     */
    public function subtotal();

    /**
     * total.
     *
     * @method total
     *
     * @return float
     */
    public function total();

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
    public function defaultHandler($total, $options);

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
    public function add(Item $item, $quantity = 0);

    /**
     * remove.
     *
     * @method remove
     *
     * @param \Recca0120\Cart\Contracts\Item|string $item
     *
     * @return static
     */
    public function remove($item);

    /**
     * clear.
     *
     * @method clear
     *
     * @param bool $clearAll
     *
     * @return static
     */
    public function clear($clearAll = false);

    /**
     * save.
     *
     * @method save
     *
     * @return static
     */
    public function save();

    /**
     * coupons.
     *
     * @method coupons
     *
     * @return \Recca0120\Cart\Collections\FeeCollection
     */
    public function coupons();

    /**
     * addCoupon.
     *
     * @method addCoupon
     *
     * @param \Recca0120\Cart\Contracts\Fee $coupon
     *
     * @return static
     */
    public function addCoupon(Fee $coupon);

    /**
     * removeCoupon.
     *
     * @method removeCoupon
     *
     * @param \Recca0120\Cart\Contracts\Fee|string $coupon
     *
     * @return static
     */
    public function removeCoupon($coupon);

    /**
     * fees.
     *
     * @method fees
     *
     * @return \Recca0120\Cart\Collections\FeeCollection
     */
    public function fees();

    /**
     * addFee.
     *
     * @method addFee
     *
     * @param \Recca0120\Cart\Contracts\Fee $fee
     *
     * @return static
     */
    public function addFee(Fee $fee);

    /**
     * removeFee description.
     *
     * @method removeFee
     *
     * @param \Recca0120\Cart\Contracts\Fee|string $fee
     *
     * @return static
     */
    public function removeFee($fee);
}
