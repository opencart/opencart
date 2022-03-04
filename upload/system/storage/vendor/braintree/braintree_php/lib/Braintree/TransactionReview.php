<?php

namespace Braintree;

/**
 * Braintree TransactionReview
 *
 * A class of information related to when a transaction is manually reviewed in the Fraud Protection Dashboard.
 *
 * For more information, see {@link https://developer.paypal.com/braintree/docs/guides/premium-fraud-management-tools/overview our developer docs}
 */
class TransactionReview extends Base
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return TransactionReview
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);

        return $instance;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;
    }
}
