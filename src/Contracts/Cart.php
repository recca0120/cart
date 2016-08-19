<?php

namespace Recca0120\Cart\Contracts;

use Recca0120\Cart\Contracts\Coupon as CouponContract;
use Recca0120\Cart\Contracts\Fee as FeeContract;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Recca0120\Cart\Contracts\Storage as StorageContract;

interface Cart extends Handler
{
    public function __construct($name = 'default', StorageContract $storage = null);
    public function setStorage(StorageContract $storage = null);
    public function setName($name = null);
    public function getName();
    public function addCoupon(CouponContract $coupon);
    public function removeCoupon($coupon);
    public function addFee(FeeContract $fee);
    public function removeFee($fee);
    public function items();
    public function count();
    public function grossTotal();
    public function total();
    public function defaultHandler($total, $options);
    public function coupons();
    public function fees();
    public function add(ItemContract $item, $quantity = 0);
    public function remove($item);
    public function clear($clearAll = false);
    public function save();
    public static function instance($name = 'default', StorageContract $storage = null);
    public static function driver($name = 'default', StorageContract $storage = null);
}
