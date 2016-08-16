<?php

namespace Recca0120\Cart\Contracts;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

interface Item extends ArrayAccess, Arrayable, Jsonable, JsonSerializable
{
    /**
     * getId.
     *
     * @method getId
     *
     * @return int|string
     */
    public function getId();

    /**
     * setId.
     *
     * @method setId
     *
     * @param int|string $id
     *
     * @return static
     */
    public function setId($id);

    /**
     * getName.
     *
     * @method getName
     *
     * @return string
     */
    public function getName();

    /**
     * setName.
     *
     * @method setName
     *
     * @param string $name
     *
     * @return static
     */
    public function setName($name);

    /**
     * getPrice.
     *
     * @method getPrice
     *
     * @return float
     */
    public function getPrice();

    /**
     * setPrice.
     *
     * @method setPrice
     *
     * @param string $price
     *
     * @return static
     */
    public function setPrice($price);

    /**
     * getQuantity.
     *
     * @method getQuantity
     *
     * @return float
     */
    public function getQuantity();

    /**
     * setQuantity.
     *
     * @method setQuantity
     *
     * @param string $price
     *
     * @return static
     */
    public function setQuantity($quantity);

    /**
     * getTotal.
     *
     * @method getTotal
     *
     * @return float
     */
    public function getTotal();

    /**
     * getOptions.
     *
     * @method getOptions
     *
     * @return array
     */
    public function getOptions();

    /**
     * setOptions.
     *
     * @method setOptions
     *
     * @param string $options
     *
     * @return static
     */
    public function setOptions($options);

    /**
     * getOption.
     *
     * @method getOption
     *
     * @return mixed
     */
    public function getOption($key);

    /**
     * setOption.
     *
     * @method setOption
     *
     * @param string $key
     * @param string $value
     *
     * @return static
     */
    public function setOption($key, $value);
}
