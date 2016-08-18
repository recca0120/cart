<?php

namespace Recca0120\Cart;

use Closure;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Recca0120\Cart\Contracts\Cart as CartContract;
use SuperClosure\Serializer;

abstract class CouponOrFee extends Fluent
{
    public function __construct($code, $description, Closure $handler = null)
    {
        $this
            ->setCode($code)
            ->setDescription($description)
            ->setHandler($handler)
            ->setValue(null);
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function apply(CartContract $cart)
    {
        $value = call_user_func_array($this->getHandler(), [$cart, $this]);

        return $this->setValue($value);
    }

    public function getHandler()
    {
        return $this->unserializeClsoure($this->handler);
    }

    public function setHandler(Closure $handler = null)
    {
        $self = $this;
        $handler = is_null($handler) === false ? $handler : [$this, 'defaultHandler'];
        $this->handler = $this->serializeClosure($handler);

        return $this;
    }

    protected function serializeClosure($closure)
    {
        if (($closure instanceof Closure) === false) {
            return $closure;
        }

        $serialized = $this->useOpis() === true ?
            serialize(new \Opis\Closure\SerializableClosure($closure)) :
            (new Serializer())->serialize($closure);

        return $serialized;
    }

    protected function unserializeClsoure($serialized)
    {
        if (is_string($serialized) === false || Str::contains($serialized, 'SerializableClosure') === false) {
            return $serialized;
        }

        $closure = $this->useOpis() === true ?
             unserialize($serialized)->getClosure() :
            (new Serializer())->unserialize($serialized);

        return $closure;
    }

    protected function useOpis()
    {
        return class_exists('\\Opis\\Closure\\SerializableClosure') === true;
    }
}
