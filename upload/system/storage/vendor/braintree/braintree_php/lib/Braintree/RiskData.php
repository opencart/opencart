<?php

namespace Braintree;

/**
 * Any applicable risk data associated with the transaction. For detailed reference information on properties, see the {@link developer docs https://developer.paypal.com/braintree/docs/reference/response/transaction#risk_data}.
 */
class RiskData extends Base
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return RiskData
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

    /**
     * returns the rules triggered by the fraud provider when generating the decision.
     *
     * @return array of strings
     */
    public function decisionReasons()
    {
        return $this->_attributes['decisionReasons'];
    }


    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
