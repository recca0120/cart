<?php

namespace Recca0120\Cart;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class Storage
{
    protected static $sessionInstance = null;

    protected $session;

    public function __construct(SessionInterface $session = null)
    {
        if (is_null($session) === false) {
            static::setSession($session);
        }
        $this->session = $this->getSession();
    }

    protected function getSession()
    {
        $session = (is_null(static::$sessionInstance) === true) ?
            new Session(new PhpBridgeSessionStorage()) :
            static::$sessionInstance;

        if ($session->isStarted() === false) {
            $session->start();

            register_shutdown_function(function () use ($session) {
                if ($session->isStarted() === true) {
                    $session->save();
                }
            });
        }

        return $session;
    }

    public function set(Cart $cart)
    {
        $this->session->set(Util::hash($cart->getName()), [
            'items'   => $cart->items(),
            'coupons' => $cart->coupons(),
        ]);
    }

    public function get(Cart $cart)
    {
        return $this->session->get(Util::hash($cart->getName()));
    }

    public static function setSession(SessionInterface $session)
    {
        static::$sessionInstance = $session;
    }
}
