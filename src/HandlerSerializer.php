<?php

namespace Recca0120\Cart;

use Closure;
use Recca0120\Cart\Serializers\SerializerFactory;

trait HandlerSerializer
{
    public function getHandler()
    {
        if (is_null($this->handler) === true) {
            return [$this, 'defaultHandler'];
        }

        return SerializerFactory::factory()->unserialize($this->handler);
    }

    public function setHandler(callable $handler = null)
    {
        if (is_null($handler) === true) {
            return $this;
        }

        $this->handler = ($handler instanceof Closure) ? SerializerFactory::factory()->serialize($handler) : $handler;

        return $this;
    }
}
