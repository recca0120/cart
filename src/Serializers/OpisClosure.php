<?php

namespace Recca0120\Cart\Serializers;

use Closure;
use Opis\Closure\SerializableClosure;

class OpisClosure extends SerializerFactory
{
    public function doSerialize(Closure $closure)
    {
        return serialize(new SerializableClosure($closure));
    }

    public function doUnSerialize($serialized)
    {
        return unserialize($serialized)->getClosure();
    }
}
