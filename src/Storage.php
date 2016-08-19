<?php

namespace Recca0120\Cart;

use Recca0120\Cart\Contracts\Storage as StorageContract;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class Storage implements StorageContract
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
            new Session($this->getDefaultSessionStorage()) :
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

    public function set($key, $value)
    {
        $this->session->set($this->hash($key), $value);
    }

    public function get($key)
    {
        return $this->session->get($this->hash($key), []);
    }

    protected function hash($key)
    {
        return hash('sha256', $key);
    }

    protected function getDefaultSessionStorage()
    {
        return (PHP_SESSION_ACTIVE === session_status()) ?
            new PhpBridgeSessionStorage() :
            new NativeSessionStorage();
    }

    public static function setSession(SessionInterface $session)
    {
        static::$sessionInstance = $session;
    }
}
