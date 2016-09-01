<?php

namespace Recca0120\Cart\Contracts;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface Storage
{
    /**
     * __construct.
     *
     * @method __construct
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(SessionInterface $session = null);

    /**
     * getSession.
     *
     * @method getSession
     *
     * @return \Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface
     */
    public static function setSession(SessionInterface $session);

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
    public function set($key, $value);

    /**
     * get.
     *
     * @method get
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($value);
}
