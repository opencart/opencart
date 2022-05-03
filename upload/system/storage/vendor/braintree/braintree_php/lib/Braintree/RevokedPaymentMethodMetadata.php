<?php
namespace Braintree;

/**
 * Braintree RevokedPaymentMethodMetadata module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Manages Braintree RevokedPaymentMethodMetadata
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 *
 * @property-read string $customerId
 * @property-read string $token
 * @property-read string $revokedPaymentMethod
 */
class RevokedPaymentMethodMetadata extends Base
{
    /**
     *  factory method: returns an instance of RevokedPaymentMethodMetadata
     *  to the requesting method, with populated properties
     *
     * @ignore
     * @return RevokedPaymentMethodMetadata
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->revokedPaymentMethod = PaymentMethodParser::parsePaymentMethod($attributes);
        $instance->customerId = $instance->revokedPaymentMethod->customerId;
        $instance->token = $instance->revokedPaymentMethod->token;
        return $instance;
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
class_alias('Braintree\RevokedPaymentMethodMetadata', 'Braintree_RevokedPaymentMethodMetadata');
