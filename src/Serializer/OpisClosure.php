<?php

namespace Recca0120\Cart\Serializer;

use Closure;
use Opis\Closure\SerializableClosure;

class OpisClosure extends SerializerFactory
{
    public function serialize(Closure $closure)
    {
        return serialize(new SerializableClosure($closure));
    }

    public function unserialize($serialized)
    {
        if ($this->isUnserialized($serialized) === false) {
            return $serialized;
        }

        return unserialize($serialized)->getClosure();
    }
}
