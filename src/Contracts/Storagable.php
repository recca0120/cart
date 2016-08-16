<?php

namespace Recca0120\Cart\Contracts;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface Storagable
{
    /**
     * getStorage.
     *
     * @method getStorage
     *
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    public function getStorage();

    /**
     * setStorage.
     *
     * @method setStorage
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     *
     * @return static
     */
    public function setStorage(SessionInterface $session);

    /**
     * updateStorage.
     *
     * @method updateStorage
     *
     * @param string    $key
     * @param mixed     $value
     *
     * @return static
     */
    public function updateStorage($key, $value);

    /**
     * registerShutdownStorage.
     *
     * @method registerShutdownStorage
     *
     * @return static
     */
    public function registerShutdownStorage();
}
