<?php
namespace Braintree;

/**
 * @property-read string $merchantId
 * @property-read string $merchantName
 * @property-read string $paymentMethodNonce
 */
class FacilitatedDetails extends Base
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
     * returns a string representation of the facilitated details
     * @return string
     */
    public function  __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) .']';
    }

}
class_alias('Braintree\FacilitatedDetails', 'Braintree_FacilitatedDetails');
