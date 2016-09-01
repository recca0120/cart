<?php

namespace Recca0120\Cart\Serializers;

use Closure;
use Illuminate\Support\Str;

abstract class SerializerFactory
{
    /**
     * $instance.
     *
     * @var static
     */
    private static $instance = [];

    /**
     * useOpis.
     *
     * @method useOpis
     *
     * @return bool
     */
    protected static function useOpis()
    {
        return class_exists('\\Opis\\Closure\\SerializableClosure') === true;
    }

    /**
     * factory.
     *
     * @method factory
     *
     * @param string $className
     *
     * @return static
     */
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

    /**
     * isUnserialized.
     *
     * @method isUnserialized
     *
     * @param string $serialized
     *
     * @return bool
     */
    protected function isUnserialized($serialized)
    {
        return is_string($serialized) === true && Str::contains($serialized, 'SerializableClosure') === true;
    }

    /**
     * serialize.
     *
     * @method serialize
     *
     * @param \Closure $closure
     *
     * @return string
     */
    public function serialize(Closure $closure)
    {
        return $this->doSerialize($closure);
    }

    /**
     * unserialize.
     *
     * @method unserialize
     *
     * @param string $serialized
     *
     * @return \Closure
     */
    public function unserialize($serialized)
    {
        if ($this->isUnserialized($serialized) === false) {
            return $serialized;
        }

        return $this->doUnSerialize($serialized);
    }

    /**
     * doSerialize.
     *
     * @method doSerialize
     *
     * @param \Closure $closure
     *
     * @return string
     */
    abstract protected function doSerialize(Closure $closure);

    /**
     * doUnSerialize.
     *
     * @method doUnSerialize
     *
     * @param string $serialized
     *
     * @return \Closure
     */
    abstract protected function doUnSerialize($serialized);
}
