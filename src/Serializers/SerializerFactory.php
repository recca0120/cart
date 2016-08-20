<?php

namespace Recca0120\Cart\Serializers;

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

    public function serialize(Closure $closure)
    {
        return $this->doSerialize($closure);
    }
    public function unserialize($serialized)
    {
        if ($this->isUnserialized($serialized) === false) {
            return $serialized;
        }

        return $this->doUnSerialize($serialized);
    }

    abstract protected function doSerialize(Closure $closure);
    abstract protected function doUnSerialize($serialized);
}
