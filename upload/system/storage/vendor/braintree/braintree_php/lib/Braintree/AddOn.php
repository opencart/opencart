<?php

namespace Braintree;

/**
 * Braintree AddOn class
 *
 * Object containing information on AddOns of a subscription
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/add-on developer docs} for information on attributes
 */
class AddOn extends Modification
{
    /**
     * Creates an instance of an AddOn from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return AddOn
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }


    /**
     * static methods redirecting to gateway class
     *
     * @see AddOnGateway::all()
     *
     * @return AddOn[]
     */
    public static function all()
    {
        return Configuration::gateway()->addOn()->all();
    }
}
