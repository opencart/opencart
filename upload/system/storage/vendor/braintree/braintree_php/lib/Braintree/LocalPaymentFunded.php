<?php

namespace Braintree;

/**
 * Braintree LocalPaymentFunded module
 */
class LocalPaymentFunded extends Base
{
    /**
     *  factory method: returns an instance of LocalPaymentFunded
     *  to the requesting method, with populated properties
     *
     * @param array $attributes used to create the object
     *
     * @return LocalPaymentFunded
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    protected function _initialize($localPaymentFundedAttribs)
    {
        // set the attributes
        $this->_attributes = $localPaymentFundedAttribs;

        if (isset($transactionAttribs['transaction'])) {
            $this->_set(
                'transaction',
                new Transaction(
                    $transactionAttribs['transaction']
                )
            );
        }
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
