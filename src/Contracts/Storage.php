<?php

namespace Recca0120\Cart\Contracts;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface Storage
{
    public function __construct(SessionInterface $session = null);

    public function set(Cart $cart);

    public function get(Cart $cart);

    public static function setSession(SessionInterface $session);
}
