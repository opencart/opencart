<?php

namespace Braintree;

/**
 * {@inheritdoc}
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/credit-card-verification developer docs} for information on attributes
 */
class CreditCardVerification extends Result\CreditCardVerification
{
    /**
     * Creates an instance of a CreditCardVerification from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return CreditCardVerification
     */
    public static function factory($attributes)
    {
        $instance = new self($attributes);
        return $instance;
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param array $attributes containing request parameters
     *
     * @see CreditCardVerificationGateway::create()
     *
     * @return Result\Successful|Result\Error
     */
    public static function create($attributes)
    {
        Util::verifyKeys(self::createSignature(), $attributes);
        return Configuration::gateway()->creditCardVerification()->create($attributes);
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param array $query search parameters
     * @param array $ids   of verifications to search
     *
     * @see CreditCardVerificationGateway::fetch()
     *
     * @return Array of CreditCardVerification objects
     */
    public static function fetch($query, $ids)
    {
        return Configuration::gateway()->creditCardVerification()->fetch($query, $ids);
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param mixed $query search query
     *
     * @see CreditCardVerificationGateway::search()
     *
     * @return ResourceCollection
     */
    public static function search($query)
    {
        return Configuration::gateway()->creditCardVerification()->search($query);
    }

    /*
     * Returns keys that are acceptable for create requests
     */
    public static function createSignature()
    {
        return [
                ['options' => ['amount', 'merchantAccountId', 'accountType']],
                ['creditCard' =>
                    [
                        'cardholderName', 'cvv', 'number',
                        'expirationDate', 'expirationMonth', 'expirationYear',
                        ['billingAddress' => CreditCardGateway::billingAddressSignature()]
                    ]
                ]];
    }
}
