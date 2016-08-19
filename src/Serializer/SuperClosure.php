<?php

namespace Recca0120\Cart\Serializer;

use Closure;
use SuperClosure\Serializer;

class SuperClosure extends SerializerFactory
{
    public function serialize(Closure $closure)
    {
        return (new Serializer())->serialize($closure);
    }

    public function unserialize($serialized)
    {
        if ($this->isUnserialized($serialized) === false) {
            return $serialized;
        }

        return (new Serializer())->unserialize($serialized);
    }
}
