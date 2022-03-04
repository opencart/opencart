<?php

namespace Braintree;

/**
 * Braintree PaymentMethod module
 */

/**
 * Creates and manages Braintree PaymentMethods
 *
 * <b>== More information ==</b>
 */
class PaymentMethod extends Base
{
    /**
     * Static method from gateway class
     *
     * @param array $attribs containing request parameterss
     *
     * @see PaymentMethodGateway::create()
     *
     * @throws Exception\ValidationError
     *
     * @return Result\Successful|Result\Error
     */
    public static function create($attribs)
    {
        return Configuration::gateway()->paymentMethod()->create($attribs);
    }

    /**
     * Static method from gateway class
     *
     * @param string $token payment method unique id
     *
     * @see PaymentMethodGateway::find()
     *
     * @throws Exception\NotFound
     *
     * @return CreditCard|PayPalAccount
     */
    public static function find($token)
    {
        return Configuration::gateway()->paymentMethod()->find($token);
    }

    /**
     * Static method from gateway class
     *
     * @param string $token   payment method identifier
     * @param array  $attribs containing request parameters
     *
     * @see PaymentMethodGateway::update()
     *
     * @return Result\Successful|Result\Error
     */
    public static function update($token, $attribs)
    {
        return Configuration::gateway()->paymentMethod()->update($token, $attribs);
    }

    /**
     * Static method from gateway class
     *
     * @param string $token   payment method identifier
     * @param mixed  $options containing optional parameters
     *
     * @see PaymentMethodGateway::delete()
     *
     * @return Result
     */
    public static function delete($token, $options = [])
    {
        return Configuration::gateway()->paymentMethod()->delete($token, $options);
    }
}
