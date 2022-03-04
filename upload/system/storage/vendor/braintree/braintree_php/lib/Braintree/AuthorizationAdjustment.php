<?php

namespace Braintree;

/**
 * Creates an instance of AuthorizationAdjustment as returned from a transaction
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction#authorization-adjustments developer docs} for information on attributes
 */

class AuthorizationAdjustment extends Base
{
    /**
     * Creates an instance of an AuthorizationAdjustment from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return AuthorizationAdjustment
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);

        return $instance;
    }

    protected function _initialize($authorizationAdjustmentAttribs)
    {
        $this->_attributes = $authorizationAdjustmentAttribs;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' . Util::attributesToString($this->_attributes) . ']';
    }
}
