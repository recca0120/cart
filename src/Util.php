<?php

namespace Recca0120\Cart;

class Util
{
    public static function hash($key)
    {
        return hash('sha256', $key);
    }
}
