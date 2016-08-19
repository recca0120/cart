<?php

namespace Recca0120\Cart\Serializer;

use Closure;
use Illuminate\Support\Str;

abstract class SerializerFactory
{
    private static $instance = [];

    protected static function useOpis()
    {
        return class_exists('\\Opis\\Closure\\SerializableClosure') === true;
    }

    public static function factory($className = null)
    {
        if (is_null($className) === true) {
            $className = (self::useOpis() === true) ? OpisClosure::class : SuperClosure::class;
        }

        if (isset(self::$instance[$className]) === true) {
            return self::$instance[$className];
        }

        return self::$instance[$className] = new $className();
    }

    protected function isUnserialized($serialized)
    {
        return (is_string($serialized) === true && Str::contains($serialized, 'SerializableClosure') === true);
    }

    abstract public function serialize(Closure $closure);
    abstract public function unserialize($serialized);
}
