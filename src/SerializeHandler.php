<?php

namespace Recca0120\Cart;

use Closure;
use Opis\Closure\SerializableClosure;

trait SerializeHandler
{
    public function __sleep()
    {

        // if ($this->attributes['handler'] instanceof Closure) {
        // $serializer = new Serializer();
        // $this->attributes['handler'] = $serializer->serialize($this->attributes['handler']);
        // }

        $this->attributes['handler'] = serialize(new SerializableClosure($this->attributes['handler']));

        return ['attributes'];
    }

    public function __wakeup()
    {
        $this->attributes['handler'] = unserialize($this->attributes['handler'])->getClosure();

        return ['attributes'];
    }
}
