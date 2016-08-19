<?php

namespace Recca0120\Cart\Contracts;

use Closure;

interface Handler
{
    public function getHandler();

    public function setHandler(Closure $handler = null);
}
