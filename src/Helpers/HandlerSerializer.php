<?php

namespace Recca0120\Cart\Helpers;

use Closure;

trait HandlerSerializer
{
    public function getHandler()
    {
        return Serializer::unserialize($this->handler);
    }

    public function setHandler(Closure $handler = null)
    {
        $handler = is_null($handler) === false ? $handler : [$this, 'defaultHandler'];
        $this->handler = Serializer::serialize($handler);

        return $this;
    }
}
