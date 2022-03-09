<?php
namespace Braintree;

/**
 * @property-read string $oauthApplicationClientId
 * @property-read string $oauthApplicationName
 * @property-read string $sourcePaymentMethodToken
 */
class FacilitatorDetails extends Base
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
     * returns a string representation of the facilitator details
     * @return string
     */
    public function  __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) .']';
    }

}
class_alias('Braintree\FacilitatorDetails', 'Braintree_FacilitatorDetails');
