<?php
namespace Braintree;

/**
 * {@inheritdoc}
 * 
 * @property-read string $amount
 * @property-read mixed $billing
 * @property-read string $company
 * @property-read string $countryName
 * @property-read string $extendedAddress
 * @property-read string $firstName
 * @property-read string $lastName
 * @property-read string $locality
 * @property-read string $postalCode
 * @property-read string $region
 * @property-read string $streetAddress
 * @property-read \DateTime $createdAt
 * @property-read \Braintree\CreditCard $creditCard
 * @property-read string|null $gatewayRejectionReason
 * @property-read string $id
 * @property-read string $merchantAccountId
 * @property-read string $processorResponseCode
 * @property-read string $processorResponseText
 * @property-read string $processorResponseType
 * @property-read \Braintree\RiskData|null $riskData
 */
class CreditCardVerification extends Result\CreditCardVerification
{
    public static function factory($attributes)
    {
        $instance = new self($attributes);
        return $instance;
    }

    // static methods redirecting to gateway
    //
    public static function create($attributes)
    {
        Util::verifyKeys(self::createSignature(), $attributes);
        return Configuration::gateway()->creditCardVerification()->create($attributes);
    }

    public static function fetch($query, $ids)
    {
        return Configuration::gateway()->creditCardVerification()->fetch($query, $ids);
    }

    public static function search($query)
    {
        return Configuration::gateway()->creditCardVerification()->search($query);
    }

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
class_alias('Braintree\CreditCardVerification', 'Braintree_CreditCardVerification');
