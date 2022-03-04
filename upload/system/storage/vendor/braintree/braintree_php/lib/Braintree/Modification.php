<?php

namespace Braintree;

/**
 * Modification class
 * For changes to Subscriptions
 *
 * @see AddOn
 */
class Modification extends Base
{
    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;
    }

    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return Modification
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return get_called_class() . '[' . Util::attributesToString($this->_attributes) . ']';
    }
}
