<?php
namespace Braintree;

/**
 * Braintree GrantedPaymentInstrumentUpdate module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Manages Braintree GrantedPaymentInstrumentUpdate 
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 *
 * @property-read string $grantOwnerMerchantId
 * @property-read string $grantRecipientMerchantId
 * @property-read string $paymentMethodNonce
 * @property-read string $token
 * @property-read string $updatedFields
 */
class GrantedPaymentInstrumentUpdate extends Base
{
    /**
     *  factory method: returns an instance of GrantedPaymentInstrumentUpdate
     *  to the requesting method, with populated properties
     *
     * @ignore
     * @return GrantedPaymentInstrumentUpdate
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    /* instance methods */

    /**
     * sets instance properties from an array of values
     *
     * @access protected
     * @param array $GrantedPaymentInstrumentAttribs array of grantedPaymentInstrumentUpdate data
     * @return void
     */
    protected function _initialize($grantedPaymentInstrumentUpdateAttribs)
    {
        // set the attributes
        $this->_attributes = $grantedPaymentInstrumentUpdateAttribs;

        $paymentMethodNonce = isset($grantedPaymentInstrumentUpdateAttribs['paymentMethodNonce']) ?
            GrantedPaymentInstrumentUpdate::factory($grantedPaymentInstrumentUpdateAttribs['paymentMethodNonce']) :
            null;
        $this->_set('paymentMethodNonce', $paymentMethodNonce);
    }

    /**
     * create a printable representation of the object as:
     * ClassName[property=value, property=value]
     * @return string
     */
    public function  __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
class_alias('Braintree\GrantedPaymentInstrumentUpdate', 'Braintree_GrantedPaymentInstrumentUpdate');
