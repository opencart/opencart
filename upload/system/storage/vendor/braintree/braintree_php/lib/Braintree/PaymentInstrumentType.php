<?php
namespace Braintree;

class PaymentInstrumentType
{
    const PAYPAL_ACCOUNT      = 'paypal_account';
    const COINBASE_ACCOUNT    = 'coinbase_account';
    const EUROPE_BANK_ACCOUNT = 'europe_bank_account';
    const CREDIT_CARD         = 'credit_card';
    const VISA_CHECKOUT_CARD  = 'visa_checkout_card';
    const MASTERPASS_CARD     = 'masterpass_card';
    const APPLE_PAY_CARD      = 'apple_pay_card';
    const ANDROID_PAY_CARD    = 'android_pay_card';
    const VENMO_ACCOUNT       = 'venmo_account';
    const US_BANK_ACCOUNT     = 'us_bank_account';
    const IDEAL_PAYMENT       = 'ideal_payment';
    const SAMSUNG_PAY_CARD    = 'samsung_pay_card';
}
class_alias('Braintree\PaymentInstrumentType', 'Braintree_PaymentInstrumentType');
