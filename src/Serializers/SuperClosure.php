<?php

namespace Recca0120\Cart\Serializers;

use Closure;
use SuperClosure\Serializer;

class SuperClosure extends SerializerFactory
{
    public function doSerialize(Closure $closure)
    {
        return (new Serializer())->serialize($closure);
    }

    public function doUnSerialize($serialized)
    {
        return (new Serializer())->unserialize($serialized);
    }
}
