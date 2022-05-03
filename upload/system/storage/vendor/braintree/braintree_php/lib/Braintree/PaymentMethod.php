<?php
namespace Braintree;

/**
 * Braintree PaymentMethod module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Creates and manages Braintree PaymentMethods
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 */
class PaymentMethod extends Base
{
    // static methods redirecting to gateway

    public static function create($attribs)
    {
        return Configuration::gateway()->paymentMethod()->create($attribs);
    }

    public static function find($token)
    {
        return Configuration::gateway()->paymentMethod()->find($token);
    }

    public static function update($token, $attribs)
    {
        return Configuration::gateway()->paymentMethod()->update($token, $attribs);
    }

    public static function delete($token, $options=[])
    {
        return Configuration::gateway()->paymentMethod()->delete($token, $options);
    }
}
class_alias('Braintree\PaymentMethod', 'Braintree_PaymentMethod');
