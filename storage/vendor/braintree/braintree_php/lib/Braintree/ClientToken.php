<?php

class Braintree_ClientToken
{
    const DEFAULT_VERSION = 2;


    // static methods redirecting to gateway

    /**
     * 
     * @param array $params
     * @return array
     */
    public static function generate($params=array())
    {
        return Braintree_Configuration::gateway()->clientToken()->generate($params);
    }

    /**
     * 
     * @param type $params
     * @throws InvalidArgumentException
     */
    public static function conditionallyVerifyKeys($params)
    {
        return Braintree_Configuration::gateway()->clientToken()->conditionallyVerifyKeys($params);
    }

    /**
     * 
     * @return string client token retrieved from server
     */
    public static function generateWithCustomerIdSignature()
    {
        return Braintree_Configuration::gateway()->clientToken()->generateWithCustomerIdSignature();
    }

    /**
     * 
     * @return string client token retrieved from server
     */
    public static function generateWithoutCustomerIdSignature()
    {
        return Braintree_Configuration::gateway()->clientToken()->generateWithoutCustomerIdSignature();
    }
}
