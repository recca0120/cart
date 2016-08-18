<?php

namespace Recca0120\Cart;

use Closure;
use Illuminate\Support\Str;
use Opis\Closure\SerializableClosure;

trait SerializeHandler
{
    protected function serializeHandler($handler)
    {
        if (($handler instanceof Closure) === false) {
            return $handler;
        }

        return serialize(new SerializableClosure($handler));
    }

    protected function unserializeHandler($handler)
    {
        if (is_string($handler) === false || Str::contains($handler, 'SerializableClosure') === false) {
            return $handler;
        }

        return unserialize($handler)->getClosure();
    }

    public function __sleep()
    {
        $this->attributes['handler'] = $this->serializeHandler($this->attributes['handler']);

        return ['attributes'];
    }

    public function __wakeup()
    {
        $this->attributes['handler'] = $this->unserializeHandler($this->attributes['handler']);

        return ['attributes'];
    }
}
