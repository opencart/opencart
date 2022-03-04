<?php

namespace Braintree;

/**
 * Braintree Class Instance template
 *
 * @abstract
 */
abstract class Instance
{
    protected $_attributes = [];

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        if (!empty($attributes)) {
            $this->_initializeFromArray($attributes);
        }
    }

    /**
     * returns private/nonexistent instance properties
     *
     * @param string $name property name
     *
     * @return mixed contents of instance properties
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        } else {
            trigger_error('Undefined property on ' . get_class($this) . ': ' . $name, E_USER_NOTICE);
            return null;
        }
    }

    /**
     * used by isset() and empty()
     *
     * @param string $name property name
     *
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->_attributes[$name]);
    }

    /**
     * create a printable representation of the object as:
     * ClassName[property=value, property=value]
     *
     * @return string
     */
    public function __toString()
    {
        $objOutput = Util::implodeAssociativeArray($this->_attributes);
        return get_class($this) . '[' . $objOutput . ']';
    }
    /**
     * initializes instance properties from the keys/values of an array
     *
     * @param <type> $aAttribs array of properties to set - single level
     *
     * @return void
     */
    private function _initializeFromArray($attributes)
    {
        $this->_attributes = $attributes;
    }

    /**
     * Implementation of JsonSerializable
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->_attributes;
    }

    /**
     * Implementation of to an Array
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            if (!is_array($value)) {
                return method_exists($value, 'toArray') ? $value->toArray() : $value;
            } else {
                return $value;
            }
        }, $this->_attributes);
    }
}
