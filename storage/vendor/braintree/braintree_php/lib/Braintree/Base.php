<?php

/**
 * Base functionality for library classes
 */
abstract class Braintree_Base
{
    /**
     * Disable the default constructor
     *
     * Objects that inherit from Braintree_Base should be constructed with
     * the static factory() method.
     *
     * @ignore
     */
    protected function __construct()
    {
    }

    /**
     * Disable cloning of objects
     *
     * @ignore
     */
    protected function __clone()
    {
    }

    /**
     * Accessor for instance properties stored in the private $_attributes property
     *
     * @ignore
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }
        else {
            trigger_error('Undefined property on ' . get_class($this) . ': ' . $name, E_USER_NOTICE);
            return null;
        }
    }

    /**
     * Checks for the existance of a property stored in the private $_attributes property
     *
     * @ignore
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->_attributes);
    }

    /**
     * Mutator for instance properties stored in the private $_attributes property
     *
     * @ignore
     * @param string $key
     * @param mixed $value
     */
    public function _set($key, $value)
    {
        $this->_attributes[$key] = $value;
    }
}
