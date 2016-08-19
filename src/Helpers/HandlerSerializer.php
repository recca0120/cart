<?php

namespace Recca0120\Cart\Helpers;

use Closure;

trait HandlerSerializer
{
    public function getHandler()
    {
        if (is_null($this->handler) === true) {
            return [$this, 'defaultHandler'];
        }

        return Serializer::unserialize($this->handler);
    }

    public function setHandler(callable $handler = null)
    {
        if (is_null($handler) === true) {
            return $this;
        }

        $this->handler = ($handler instanceof Closure) ? Serializer::serialize($handler) : $handler;

        return $this;
    }
}
