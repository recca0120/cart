<?php

namespace Recca0120\Cart;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Recca0120\Cart\Contracts\Cart as CartContract;
use Recca0120\Cart\Contracts\Coupon as CouponContract;
use Recca0120\Cart\Contracts\Item as ItemContract;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class Cart implements CartContract
{
    protected static $session = null;

    protected static $instance = [];

    protected $items;

    protected $coupons;

    /**
     * Create a new collection.
     *
     * @param  mixed  $items
     * @return void
     */
    public function __construct($name = null, SessionInterface $session = null)
    {
        $this->setName($name);
        self::$instance[$this->getName()] = $this;
        if (is_null($session) === false) {
            self::setSession($session);
        }

        $data = $this->getSession()->get($this->getName());
        $this->items = Arr::get($data, 'items', new Collection());
        $this->coupons = Arr::get($data, 'coupons', new Collection());
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
     * setName.
     *
     * @method setName
     *
     * @param string $name
     *
     * @return static
     */
    public function setName($name)
    {
        $name = is_null($name) === true ? static::class : $name;
        $this->name = Util::hash($name);

        return $this;
    }

    public function add(ItemContract $item, $quantity = 1)
    {
        return $this->addItem($item, $quantity);
    }

    public function remove($item)
    {
        return $this->removeItem($item);
    }

    public function items()
    {
        return $this->getItems();
    }

    public function count()
    {
        return $this->getItemCount();
    }

    public function grossTotal()
    {
        return $this->getGrossTotal();
    }

    public function total()
    {
        return $this->grossTotal() + $this->getDiscounts()->reduce(function ($prev, $next) {
            return $prev + Arr::get($next, 'discount');
        }, 0);
    }

    public function addItem(ItemContract $item, $quantity = 1)
    {
        $item->setQuantity($quantity);
        $this->items->put($item->getId(), $item);
        $this->save();

        return $this;
    }

    public function removeItem($item)
    {
        $itemId = ($item instanceof ItemContract) ? $item->getId() : $item;
        $this->items->forget($itemId);
        $this->save();

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getItemCount()
    {
        return $this->getItems()->count();
    }

    public function getGrossTotal()
    {
        return $this->getItemTotal();
    }

    public function getItemTotal()
    {
        return $this->items->sum(function ($item) {
            return $item->getPrice() * $item->getQuantity();
        });
    }

    public function getDiscounts()
    {
        return $this->coupons->map(function ($coupon) {
            $discount = $coupon->discount($this);
            $description = $coupon->getDescription();

            return compact('discount', 'description');
        });
    }

    public function addCoupon(CouponContract $coupon)
    {
        $this->coupons->put($coupon->getName(), $coupon);
        $this->save();

        return $this;
    }

    public function save()
    {
        $this->getSession()->set($this->getName(), [
            'items'     => $this->items,
            'coupons'   => $this->coupons,
        ]);
    }

    public function clear()
    {
        $this->items = new Collection();
        $this->coupons = new Collection();
        $this->save();
    }

    protected function getSession()
    {
        return is_null(self::$session) === false ?
            self::$session :
            static::setSession(new Session(new PhpBridgeSessionStorage()));
    }

    public static function setSession(SessionInterface $storage)
    {
        self::$session = $storage;
        static::startSession(self::$session);

        return self::$session;
    }

    protected static function startSession()
    {
        if (self::$session->isStarted() === false) {
            self::$session->start();
            register_shutdown_function(function () {
                if (self::$session->isStarted() === true) {
                    self::$session->save();
                }
            });
        }
    }

    public static function instance($name = null, SessionInterface $session = null)
    {
        $name = is_null($name) === true ? static::class : $name;

        return (isset(self::$instance[$name]) === true) ? self::$instance[$name] : new static($name, $session);
    }

    public static function driver($driver = null)
    {
        return static::instance($driver);
    }
}
