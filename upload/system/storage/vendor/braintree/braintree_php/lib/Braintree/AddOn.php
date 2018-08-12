<?php

class Braintree_AddOn extends Braintree_Modification
{
    /**
     *
     * @param array $attributes
     * @return Braintree_AddOn
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }


    /**
     * static methods redirecting to gateway
     *
     * @return Braintree_AddOn[]
     */
    public static function all()
    {
        return Braintree_Configuration::gateway()->addOn()->all();
    }
}
