<?php
namespace Braintree;

/**
 * @property-read string $commercial
 * @property-read string $countryOfIssuance
 * @property-read string $debit
 * @property-read string $durbinRegulated
 * @property-read string $healthcare
 * @property-read string $issuingBank
 * @property-read string $payroll
 * @property-read string $prepaid
 * @property-read string $productId
 */
class BinData extends Base
{
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
     * returns a string representation of the bin data
     * @return string
     */
    public function  __toString()
    {
        return __CLASS__ . '[' .
            Util::attributesToString($this->_attributes) .']';
    }

}
class_alias('Braintree\BinData', 'Braintree_BinData');
