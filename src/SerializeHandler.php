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
        if (is_null($this->cacheHandler) === true) {
            $this->cacheHandler = $this->unserialize($this->handler);
        }

        return $this->cacheHandler;
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
        if (($handler instanceof Closure) === false) {
            return $handler;
        }

        $serialized = $this->useOpis() === true ?
            serialize(new \Opis\Closure\SerializableClosure($handler)) :
            (new Serializer())->serialize($handler);

        return $serialized;
    }

    protected function unserialize($handler)
    {
        if (is_string($handler) === false || Str::contains($handler, 'SerializableClosure') === false) {
            return $handler;
        }

        $closure = $this->useOpis() === true ?
             unserialize($handler)->getClosure()->bindTo($this) :
            (new Serializer())->unserialize($handler);

        return $closure;
    }

    protected function useOpis()
    {
        return class_exists('\\Opis\\Closure\\SerializableClosure') === true;
    }
}
