<?php

namespace Braintree;

/**
 * PaymentInstrumentType module
 *
 * Contains constants for all payment methods that are possible to integrate with Braintree
 */
class PaymentInstrumentType
{
    const GOOGLE_PAY_CARD    = 'android_pay_card';
    const APPLE_PAY_CARD      = 'apple_pay_card';
    const CREDIT_CARD         = 'credit_card';
    const LOCAL_PAYMENT       = 'local_payment';
    const PAYPAL_ACCOUNT      = 'paypal_account';
    const PAYPAL_HERE         = 'paypal_here';
    const SAMSUNG_PAY_CARD    = 'samsung_pay_card';
    const US_BANK_ACCOUNT     = 'us_bank_account';
    const VENMO_ACCOUNT       = 'venmo_account';
    const VISA_CHECKOUT_CARD  = 'visa_checkout_card';
}
