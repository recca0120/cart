<?php

namespace Recca0120\Cart\Contracts;

interface Fee extends Handler
{
    /**
     * __construct.
     *
     * @method __construct
     *
     * @param string   $code
     * @param string   $description
     * @param callable $handler
     */
    public function __construct($code, $description, callable $handler = null);

    /**
     * getCode.
     *
     * @method getCode
     *
     * @return string
     */
    public function getCode();

    /**
     * setCode.
     *
     * @method setCode
     *
     * @param string $code
     *
     * @return static
     */
    public function setCode($code);

    /**
     * getDescription.
     *
     * @method getDescription
     *
     * @return string
     */
    public function getDescription();

    /**
     * setDescription.
     *
     * @method setDescription
     *
     * @param string $description
     *
     * @return static;
     */
    public function setDescription($description);

    /**
     * getValue.
     *
     * @method getValue
     *
     * @return float $value
     */
    public function getValue();

    /**
     * setValue.
     *
     * @method setValue
     *
     * @param float $value
     *
     * @return static
     */
    public function setValue($value);

    /**
     * apply.
     *
     * @method apply
     *
     * @param \Recca0120\Cart\Contracts\Cart CartContract $cart
     *
     * @return static
     */
    public function apply(Cart $cart);

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
    public function defaultHandler(Cart $cart, Fee $coupon);
}
