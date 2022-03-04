<?php

namespace Braintree\Test;

/**
 * Authentication Ids used for testing purposes
 */

/**
 * Authentication Ids used for testing purposes
 *
 * The constants in this class can be used to perform authentication id operations
 * with the desired status in the sandbox environment.
 */
class AuthenticationIds
{
    // phpcs:disable Generic.Files.LineLength
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
    // phpcs:enable Generic.Files.LineLength
}
