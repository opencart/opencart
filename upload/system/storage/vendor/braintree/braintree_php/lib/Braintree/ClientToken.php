<?php

namespace Braintree;

/**
 * Braintre ClientToken create and manage client tokens for authorization
 */
class ClientToken
{
    const DEFAULT_VERSION = 2;

    /**
     * static method redirecting to gateway class
     *
     * @param array $params to be supplied in api request
     *
     * @see ClientTokenGateway::generate()
     *
     * @return string
     */
    public static function generate($params = [])
    {
        return Configuration::gateway()->clientToken()->generate($params);
    }

    /*
     * static method redirecting to gateway class
     *
     * @param array $params to be verified
     *
     * @see ClientTokenGateway::conditionallyVerifyKeys()
     *
     * @return array
     */
    public static function conditionallyVerifyKeys($params)
    {
        return Configuration::gateway()->clientToken()->conditionallyVerifyKeys($params);
    }

    /*
     * static method redirecting to gateway class
     *
     * @see ClientTokenGateway::generateWithCustomerIdSignature()
     *
     * @return array
     *
     */
    public static function generateWithCustomerIdSignature()
    {
        return Configuration::gateway()->clientToken()->generateWithCustomerIdSignature();
    }

    /*
     * static method redirecting to gateway class
     *
     * @see ClientTokenGateway::generateWithoutCustomerIdSignature()
     *
     * @return array
     */
    public static function generateWithoutCustomerIdSignature()
    {
        return Configuration::gateway()->clientToken()->generateWithoutCustomerIdSignature();
    }
}
