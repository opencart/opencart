<?php
namespace Braintree;

/**
 * Braintree PaymentMethodParser module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Manages Braintree PaymentMethodParser
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 *
 */
class PaymentMethodParser
{
    public static function parsePaymentMethod($response)
    {
        if (isset($response['creditCard'])) {
            return CreditCard::factory($response['creditCard']);
        } else if (isset($response['paypalAccount'])) {
            return PayPalAccount::factory($response['paypalAccount']);
        } else if (isset($response['coinbaseAccount'])) {
            return CoinbaseAccount::factory($response['coinbaseAccount']);
        } else if (isset($response['applePayCard'])) {
            return ApplePayCard::factory($response['applePayCard']);
        } else if (isset($response['androidPayCard'])) {
            return AndroidPayCard::factory($response['androidPayCard']);
        } else if (isset($response['amexExpressCheckoutCard'])) {
            return AmexExpressCheckoutCard::factory($response['amexExpressCheckoutCard']);
        } else if (isset($response['europeBankAccount'])) {
            return EuropeBankAccount::factory($response['europeBankAccount']);
        } else if (isset($response['usBankAccount'])) {
            return UsBankAccount::factory($response['usBankAccount']);
        } else if (isset($response['venmoAccount'])) {
            return VenmoAccount::factory($response['venmoAccount']);
        } else if (isset($response['visaCheckoutCard'])) {
            return VisaCheckoutCard::factory($response['visaCheckoutCard']);
        } else if (isset($response['masterpassCard'])) {
            return MasterpassCard::factory($response['masterpassCard']);
        } else if (isset($response['samsungPayCard'])) {
            return SamsungPayCard::factory($response['samsungPayCard']);
        } else if (is_array($response)) {
            return UnknownPaymentMethod::factory($response);
        } else {
            throw new Exception\Unexpected(
                'Expected payment method'
            );
        }
    }
}
class_alias('Braintree\PaymentMethodParser', 'Braintree_PaymentMethodParser');
