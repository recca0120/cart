<?php

namespace Recca0120\Cart\Serializers;

use Closure;
use Opis\Closure\SerializableClosure;

class OpisClosure extends SerializerFactory
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
        return serialize(new SerializableClosure($closure));
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
        return unserialize($serialized)->getClosure();
    }
}
