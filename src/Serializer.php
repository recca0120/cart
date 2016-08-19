<?php

namespace Recca0120\Cart;

use Closure;
use Illuminate\Support\Str;
use SuperClosure\Serializer;

trait HandlerSerializer
{
    public function getHandler()
    {
        return $this->unserializeClsoure($this->handler);
    }

    public function setHandler(Closure $handler = null)
    {
        $self = $this;
        $handler = is_null($handler) === false ? $handler : [$this, 'defaultHandler'];
        $this->handler = $this->serializeClosure($handler);

        return $this;
    }

    protected function serializeClosure($closure)
    {
        if (($closure instanceof Closure) === false) {
            return $closure;
        }

        $serialized = $this->useOpis() === true ?
            serialize(new \Opis\Closure\SerializableClosure($closure)) :
            (new Serializer())->serialize($closure);

        return $serialized;
    }

    protected function unserializeClsoure($serialized)
    {
        if (is_string($serialized) === false || Str::contains($serialized, 'SerializableClosure') === false) {
            return $serialized;
        }

        $closure = $this->useOpis() === true ?
             unserialize($serialized)->getClosure() :
            (new Serializer())->unserialize($serialized);

        return $closure;
    }

    protected function useOpis()
    {
        return class_exists('\\Opis\\Closure\\SerializableClosure') === true;
    }
}
