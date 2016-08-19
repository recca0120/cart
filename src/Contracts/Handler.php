<?php

namespace Recca0120\Cart\Contracts;

interface Handler
{
    public function getHandler();

    public function setHandler(callable $handler = null);
}
