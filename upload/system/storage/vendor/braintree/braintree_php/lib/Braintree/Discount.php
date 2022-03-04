<?php

namespace Braintree;

/**
 * Discount class
 *
 * Object containing information on Discountss of a subscription
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/discount developer docs} for information on attributes
 */
class Discount extends Modification
{
    /**
     * Creates an instance of a Discount from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return Discount
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
     * @see DiscountGateway::all()
     *
     * @return Discount[]
     */
    public static function all()
    {
        return Configuration::gateway()->discount()->all();
    }
}
