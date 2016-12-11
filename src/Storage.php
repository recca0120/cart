<?php

namespace Recca0120\Cart;

use Symfony\Component\HttpFoundation\Session\Session;
use Recca0120\Cart\Contracts\Storage as StorageContract;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class Storage implements StorageContract
{
    /**
     * $sessionInstance.
     *
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected static $sessionInstance = null;

    /**
     * $session.
     *
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * __construct.
     *
     * @method __construct
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(SessionInterface $session = null)
    {
        if (is_null($session) === false) {
            static::setSession($session);
        }
        $this->session = $this->getSession();
    }

    /**
     * getSession.
     *
     * @method getSession
     *
     * @return \Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface
     */
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

    /**
     * set.
     *
     * @method set
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return static
     */
    public function set($key, $value)
    {
        $this->session->set($this->hash($key), $value);

        return $this;
    }

    /**
     * get.
     *
     * @method get
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->session->get($this->hash($key), []);
    }

    /**
     * hash.
     *
     * @method hash
     *
     * @param string $key
     *
     * @return string
     */
    protected function hash($key)
    {
        return hash('sha256', $key);
    }

    /**
     * getDefaultSessionStorage.
     *
     * @method getDefaultSessionStorage
     *
     * @return \Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface
     */
    protected function getDefaultSessionStorage()
    {
        return (PHP_SESSION_ACTIVE === session_status()) ?
            new PhpBridgeSessionStorage() :
            new NativeSessionStorage();
    }

    /**
     * setSession.
     *
     * @method setSession
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public static function setSession(SessionInterface $session = null)
    {
        static::$sessionInstance = $session;
    }
}
