<?php

namespace Braintree;

/**
 * Braintree OAuthCredentials module
 */
class OAuthResult extends Base
{
    protected function _initialize($attribs)
    {
        $this->_attributes = $attribs;
    }

    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return OAuthResult
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
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
