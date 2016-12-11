<?php

namespace Recca0120\Cart;

use BadMethodCallException;
use Illuminate\Support\Fluent;
use Recca0120\Cart\Contracts\Fee as FeeContract;
use Recca0120\Cart\Contracts\Cart as CartContract;

class Fee extends Fluent implements FeeContract
{
    use HandlerSerializer;

    /**
     * __construct.
     *
     * @method __construct
     *
     * @param string   $code
     * @param string   $description
     * @param callable $handler
     */
    public function __construct($code, $description, callable $handler = null)
    {
        $this
            ->setCode($code)
            ->setDescription($description)
            ->setHandler($handler)
            ->setValue(null);
    }

    /**
     * getCode.
     *
     * @method getCode
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * setCode.
     *
     * @method setCode
     *
     * @param string $code
     *
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * getDescription.
     *
     * @method getDescription
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * setDescription.
     *
     * @method setDescription
     *
     * @param string $description
     *
     * @return static;
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * getValue.
     *
     * @method getValue
     *
     * @return float $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * setValue.
     *
     * @method setValue
     *
     * @param float $value
     *
     * @return static
     */
    public function setValue($value)
    {
        $this->value = (float) $value;

        return $this;
    }

    /**
     * apply.
     *
     * @method apply
     *
     * @param \Recca0120\Cart\Contracts\Cart CartContract $cart
     *
     * @return static
     */
    public function apply(CartContract $cart)
    {
        $value = call_user_func_array($this->getHandler(), [$cart, $this]);

        return $this->setValue($value);
    }

    /**
     * defaultHandler.
     *
     * @method defaultHandler
     *
     * @param \Recca0120\Cart\Contracts\Cart $cart
     * @param \Recca0120\Cart\Contracts\Fee  $fee
     *
     * @return mixed
     */
    public function defaultHandler(CartContract $cart, FeeContract $fee)
    {
        return 0;
    }

    /**
     * Handle dynamic calls to the container to set attributes.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return $this
     */
    public function __call($method, $parameters)
    {
        throw new BadMethodCallException("Method {$method} does not exist.");
    }
}
