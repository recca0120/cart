<?php

namespace Recca0120\Cart;

use Closure;
use Exception;
use Illuminate\Support\Str;
use Opis\Closure\SerializableClosure;
use SuperClosure\Serializer;

trait SerializeHandler
{
    protected $cacheHandler;

    public function getHandler()
    {
        return $this->unserialize($this->handler);
    }

    public function setHandler(Closure $handler = null)
    {
        $this->cacheHandler = null;
        $handler = is_null($handler) === false ? $handler : function () {};

        $this->handler = $this->serialize($handler);

        return $this;
    }

    protected function serialize($handler)
    {
        if (is_null($this->cacheHandler) === false) {
            return $this->cacheHandler;
        }

        if (($handler instanceof Closure) === false) {
            return $this->cacheHandler = $handler;
        }

        try {
            $serialized = (new Serializer())->serialize($handler);
        } catch (Exception $e) {
            $serialized = serialize(new SerializableClosure($handler));
        }

        return $this->cacheHandler = $serialized;
    }

    protected function unserialize($handler)
    {
        if (is_string($handler) === false || Str::contains($handler, 'SerializableClosure') === false) {
            return $handler;
        }

        try {
            $closure = (new Serializer())->unserialize($handler);
        } catch (Exception $e) {
            $closure = unserialize($handler)->getClosure()->bindTo($this);
        }

        return $closure;
    }
}
