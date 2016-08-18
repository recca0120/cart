<?php

namespace Recca0120\Cart;

use Closure;
use Illuminate\Support\Str;
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
        $handler = is_null($handler) === false ? $handler : [$this, 'defaultHandler'];

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

        if (class_exists('\\Opis\\Closure\\SerializableClosure') === true) {
            $serialized = serialize(new \Opis\Closure\SerializableClosure($handler));
        } else {
            $serialized = (new Serializer())->serialize($handler);
        }

        return $this->cacheHandler = $serialized;
    }

    protected function unserialize($handler)
    {
        if (is_string($handler) === false || Str::contains($handler, 'SerializableClosure') === false) {
            return $handler;
        }

        if (class_exists('\\Opis\\Closure\\SerializableClosure') === true) {
            $closure = unserialize($handler)->getClosure()->bindTo($this);
        } else {
            $closure = (new Serializer())->unserialize($handler);
        }

        return $closure;
    }
}
