<?php

namespace Recca0120\Cart\Helpers;

use Closure;
use Illuminate\Support\Str;
use SuperClosure\Serializer as SuperClosureSerializer;

class Serializer
{
    public static function serialize($closure)
    {
        if (($closure instanceof Closure) === false) {
            return $closure;
        }

        $serialized = static::useOpis() === true ?
            serialize(new \Opis\Closure\SerializableClosure($closure)) :
            (new SuperClosureSerializer())->serialize($closure);

        return $serialized;
    }

    public static function unserialize($serialized)
    {
        if (is_string($serialized) === false || Str::contains($serialized, 'SerializableClosure') === false) {
            return $serialized;
        }

        $closure = static::useOpis() === true ?
             unserialize($serialized)->getClosure() :
            (new SuperClosureSerializer())->unserialize($serialized);

        return $closure;
    }

    protected static function useOpis()
    {
        return class_exists('\\Opis\\Closure\\SerializableClosure') === true;
    }
}
