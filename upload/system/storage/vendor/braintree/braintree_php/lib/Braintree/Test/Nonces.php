<?php

namespace Braintree\Test;

/**
 * Nonces used for testing purposes
 */

/**
 * Nonces used for testing purposes
 *
 * The constants in this class can be used to perform nonce operations
 * with the desired status in the sandbox environment.
 */
class Nonces
{
    // phpcs:disable Generic.Files.LineLength
    public static $transactable = "fake-valid-nonce";
    public static $consumed = "fake-consumed-nonce";
    public static $paypalOneTimePayment = "fake-paypal-one-time-nonce";
    public static $paypalFuturePayment = "fake-paypal-future-nonce";
    public static $paypalBillingAgreement = "fake-paypal-billing-agreement-nonce";
    public static $applePayVisa = "fake-apple-pay-visa-nonce";
    public static $applePayMasterCard = "fake-apple-pay-visa-nonce";
    public static $applePayAmEx = "fake-apple-pay-amex-nonce";
    public static $googlePay = "fake-android-pay-nonce";
    public static $googlePayDiscover = "fake-android-pay-discover-nonce";
    public static $googlePayVisa = "fake-android-pay-visa-nonce";
    public static $googlePayMasterCard = "fake-android-pay-mastercard-nonce";
    public static $googlePayAmEx = "fake-android-pay-amex-nonce";
    public static $abstractTransactable = "fake-abstract-transactable-nonce";
    public static $europe = "fake-europe-bank-account-nonce";
    public static $transactableVisa = "fake-valid-visa-nonce";
    public static $transactableAmEx = "fake-valid-amex-nonce";
    public static $transactableMasterCard = "fake-valid-mastercard-nonce";
    public static $transactableDiscover = "fake-valid-discover-nonce";
    public static $transactableJCB = "fake-valid-jcb-nonce";
    public static $transactableMaestro = "fake-valid-maestro-nonce";
    public static $transactableDinersClub = "fake-valid-dinersclub-nonce";
    public static $transactablePrepaid = "fake-valid-prepaid-nonce";
    public static $transactableCommercial = "fake-valid-commercial-nonce";
    public static $transactableDurbinRegulated = "fake-valid-durbin-regulated-nonce";
    public static $transactableHealthcare = "fake-valid-healthcare-nonce";
    public static $transactableDebit = "fake-valid-debit-nonce";
    public static $transactablePayroll = "fake-valid-payroll-nonce";
    public static $threeDSecureVisaFullAuthenticationNonce = "fake-three-d-secure-visa-full-authentication-nonce";
    public static $threeDSecureVisaLookupTimeout = "fake-three-d-secure-visa-lookup-timeout-nonce";
    public static $threeDSecureVisaFailedSignature = "fake-three-d-secure-visa-failed-signature-nonce";
    public static $threeDSecureVisaFailedAuthentication = "fake-three-d-secure-visa-failed-authentication-nonce";
    public static $threeDSecureVisaAttemptsNonParticipating = "fake-three-d-secure-visa-attempts-non-participating-nonce";
    public static $threeDSecureVisaNoteEnrolled = "fake-three-d-secure-visa-not-enrolled-nonce";
    public static $threeDSecureVisaUnavailable = "fake-three-d-secure-visa-unavailable-nonce";
    public static $threeDSecureVisaMPILookupError = "fake-three-d-secure-visa-mpi-lookup-error-nonce";
    public static $threeDSecureVisaMPIAuthenticateError = "fake-three-d-secure-visa-mpi-authenticate-error-nonce";
    public static $threeDSecureVisaAuthenticationUnavailable = "fake-three-d-secure-visa-authentication-unavailable-nonce";
    public static $threeDSecureVisaBypassedAuthentication = "fake-three-d-secure-visa-bypassed-authentication-nonce";
    public static $threeDSecureTwoVisaSuccessfulFrictionlessAuthentication = "fake-three-d-secure-two-visa-successful-frictionless-authentication-nonce";
    public static $threeDSecureTwoVisaSuccessfulStepUpAuthentication = "fake-three-d-secure-two-visa-successful-step-up-authentication-nonce";
    public static $threeDSecureTwoVisaErrorOnLookup = "fake-three-d-secure-two-visa-error-on-lookup-nonce";
    public static $threeDSecureTwoVisaTimeoutOnLookup = "fake-three-d-secure-two-visa-timeout-on-lookup-nonce";
    public static $transactableNoIndicators = "fake-valid-no-indicators-nonce";
    public static $transactableUnknownIndicators = "fake-valid-unknown-indicators-nonce";
    public static $transactableCountryOfIssuanceUSA = "fake-valid-country-of-issuance-usa-nonce";
    public static $transactableCountryOfIssuanceCAD = "fake-valid-country-of-issuance-cad-nonce";
    public static $transactableIssuingBankNetworkOnly = "fake-valid-issuing-bank-network-only-nonce";
    public static $processorDeclinedVisa = "fake-processor-declined-visa-nonce";
    public static $processorDeclinedMasterCard = "fake-processor-declined-mastercard-nonce";
    public static $processorDeclinedAmEx = "fake-processor-declined-amex-nonce";
    public static $processorDeclinedDiscover = "fake-processor-declined-discover-nonce";
    public static $processorFailureJCB = "fake-processor-failure-jcb-nonce";
    public static $luhnInvalid = "fake-luhn-invalid-nonce";
    public static $localPayment = "fake-local-payment-method-nonce";
    public static $paypalFuturePaymentRefreshToken = "fake-paypal-future-refresh-token-nonce";
    public static $sepa = "fake-sepa-bank-account-nonce";
    public static $gatewayRejectedFraud = "fake-gateway-rejected-fraud-nonce";
    public static $gatewayRejectedTokenIssuance = "fake-token-issuance-error-venmo-account-nonce";
    public static $venmoAccount = "fake-venmo-account-nonce";
    public static $visaCheckoutAmEx = "fake-visa-checkout-amex-nonce";
    public static $visaCheckoutDiscover = "fake-visa-checkout-discover-nonce";
    public static $visaCheckoutMasterCard = "fake-visa-checkout-mastercard-nonce";
    public static $visaCheckoutVisa = "fake-visa-checkout-visa-nonce";
    public static $samsungPayAmEx = "tokensam_fake_american_express";
    public static $samsungPayDiscover = "tokensam_fake_discover";
    public static $samsungPayMasterCard = "tokensam_fake_mastercard";
    public static $samsungPayVisa = "tokensam_fake_visa";
    // phpcs:enable Generic.Files.LineLength
}
