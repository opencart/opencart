<?php
namespace Braintree;

class Merchant extends Base
{
    protected function _initialize($attribs)
    {
        $this->_attributes = $attribs;

        $merchantAccountArray = [];
        if (isset($attribs['merchantAccounts'])) {
            foreach ($attribs['merchantAccounts'] AS $merchantAccount) {
                $merchantAccountArray[] = MerchantAccount::factory($merchantAccount);
            }
        }
        $this->_set('merchantAccounts', $merchantAccountArray);
    }

    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    /**
     * returns a string representation of the merchant
     * @return string
     */
    public function  __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) .']';
    }
}
class_alias('Braintree\Merchant', 'Braintree_Merchant');
