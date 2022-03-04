<?php

namespace Braintree;

/**
 * Braintree FacilitatedDetails class
 *
 * If the transaction request was performed using payment information from a third party via the Grant API or Shared Vault, these fields will capture information about the merchant of record. These fields are primarily useful for the third party.
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction/php#facilitated_details developer docs} for information on attributes
 */
class FacilitatedDetails extends Base
{
    /**
     * Creates an instance of a FacilitatedDetails from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return FacilitatedDetails
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
