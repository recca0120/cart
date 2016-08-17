<?php

namespace Recca0120\Cart\Contracts;

interface Cart
{
    public function __construct($name = 'default', Storage $storage = null);

    public function setStorage(Storage $storage = null);

    public function setName($name = null);

    public function getName();

    public function add(Item $item, $quantity = 0);

    public function remove($item);

    public function clear();

    public function items();

    public function count();

    public function grossTotal();

    public function total();

    public function coupons();

    public function addCoupon(Coupon $coupon);

    public function removeCoupon($coupon);

    public function fees();

    public function addFee(Fee $fee);

    public function removeFee($fee);

    public static function instance($name = 'default', Storage $storage = null);

    public static function driver($name = 'default', Storage $storage = null);
}
