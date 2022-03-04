<?php

namespace Braintree;

/**
 * Merchant class
 */
class Merchant extends Base
{
    protected function _initialize($attribs)
    {
        $this->_attributes = $attribs;

        $merchantAccountArray = [];
        if (isset($attribs['merchantAccounts'])) {
            foreach ($attribs['merchantAccounts'] as $merchantAccount) {
                $merchantAccountArray[] = MerchantAccount::factory($merchantAccount);
            }
        }
        $this->_set('merchantAccounts', $merchantAccountArray);
    }

    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return Merchant
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
