<?php

namespace Recca0120\Cart\Serializers;

use Closure;
use SuperClosure\Serializer;

class SuperClosure extends SerializerFactory
{
    /**
     * doSerialize.
     *
     * @method doSerialize
     *
     * @param \Closure $closure
     *
     * @return string
     */
    public function doSerialize(Closure $closure)
    {
        return (new Serializer())->serialize($closure);
    }

    /**
     * doUnSerialize.
     *
     * @method doUnSerialize
     *
     * @param string $serialized
     *
     * @return \Closure
     */
    public function doUnSerialize($serialized)
    {
        return (new Serializer())->unserialize($serialized);
    }
}
