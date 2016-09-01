<?php

namespace Recca0120\Cart\Contracts;

interface Handler
{
    /**
     * getHandler.
     *
     * @method getHandler
     *
     * @return callable
     */
    public function getHandler();

    /**
     * setHandler.
     *
     * @method setHandler
     *
     * @param callable $handler
     *
     * @return static
     */
    public function setHandler(callable $handler = null);
}
