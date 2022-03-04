<?php

namespace Braintree;

use JsonSerializable;

/**
 * Braintree PHP Library.
 *
 * Braintree base class and initialization
 * Provides methods to child classes. This class cannot be instantiated.
 */
abstract class Base implements JsonSerializable
{
    protected $_attributes = [];

    /**
     * don't permit an explicit call of the constructor!
     * (like $t = new Transaction())
     */
    protected function __construct()
    {
    }

    /**
     * Disable cloning of objects
     */
    protected function __clone()
    {
    }

    /**
     * Accessor for instance properties stored in the private $_attributes property
     *
     * @param string $name of the key whose value is to be returned
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->_attributes['globalId'])) {
            $this->_attributes['graphQLId'] = $this->_attributes['globalId'];
        }
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        } else {
            trigger_error('Undefined property on ' . get_class($this) . ': ' . $name, E_USER_NOTICE);
            return null;
        }
    }

    /**
     * Checks for the existence of a property stored in the private $_attributes property
     *
     * @param string $name of the key
     *
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->_attributes[$name]);
    }

    /**
     * Mutator for instance properties stored in the private $_attributes property
     *
     * @param string $key   to be set
     * @param mixed  $value to be set
     *
     * @return mixed
     */
    public function _set($key, $value)
    {
        $this->_attributes[$key] = $value;
    }

    /**
     * Implementation of JsonSerializable
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
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
