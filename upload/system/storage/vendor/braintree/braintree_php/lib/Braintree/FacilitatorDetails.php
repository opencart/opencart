<?php

namespace Braintree;

/**
 * Braintree FacilitatorDetails class
 *
 * If a transaction request was performed using payment information from a third party via the Grant API, Shared Vault or Google Pay, thise object will have information about the third party. These fields are primarily useful for the merchant of record.
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction/#facilitator_details developer docs} for information on attributes
 */
class FacilitatorDetails extends Base
{
    /**
     * Creates an instance of an FacilitatorDetails from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return FacilitatorDetails
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
