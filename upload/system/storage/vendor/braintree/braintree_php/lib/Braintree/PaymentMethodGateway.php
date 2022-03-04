<?php

namespace Braintree;

use InvalidArgumentException;

/**
 * Creates and manages Braintree PaymentMethods
 *
 * For more detailed information on PaymentMethods, see {@link https://developer.paypal.com/braintree/docs/reference/response/payment-method/php our developer docs}. <br />
 */
class PaymentMethodGateway
{
    private $_gateway;
    private $_config;
    private $_http;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($gateway)
    {
        $this->_gateway = $gateway;
        $this->_config = $gateway->config;
        $this->_config->assertHasAccessTokenOrKeys();
        $this->_http = new Http($gateway->config);
    }


    /**
     * Attempts the create operation
     * returns a Result on success or an Error on failure
     *
     * @param array $attribs containing request parameterss
     *
     * @throws Exception\ValidationError
     *
     * @return Result\Successful|Result\Error
     */
    public function create($attribs)
    {
        Util::verifyKeys(self::createSignature(), $attribs);
        return $this->_doCreate('/payment_methods', ['payment_method' => $attribs]);
    }

    /**
     * Find a PaymentMethod by token
     *
     * @param string $token payment method unique id
     *
     * @throws Exception\NotFound
     *
     * @return CreditCard|PayPalAccount
     */
    public function find($token)
    {
        $this->_validateId($token);
        try {
            $path = $this->_config->merchantPath() . '/payment_methods/any/' . $token;
            $response = $this->_http->get($path);
            return PaymentMethodParser::parsePaymentMethod($response);
        } catch (Exception\NotFound $e) {
            throw new Exception\NotFound(
                'payment method with token ' . $token . ' not found'
            );
        }
    }

    /**
     * Updates the payment method's record
     *
     * @param string $token   payment method identifier
     * @param array  $attribs containing request parameters
     *
     * @return Result\Successful|Result\Error
     */
    public function update($token, $attribs)
    {
        Util::verifyKeys(self::updateSignature(), $attribs);
        return $this->_doUpdate('/payment_methods/any/' . $token, ['payment_method' => $attribs]);
    }

    /**
     * Delete a payment method record
     *
     * @param string $token   payment method identifier
     * @param mixed  $options containing optional parameters
     *
     * @return Result
     */
    public function delete($token, $options = [])
    {
        Util::verifyKeys(self::deleteSignature(), $options);
        $this->_validateId($token);
        $queryString = "";
        if (!empty($options)) {
            $queryString = "?" . http_build_query(Util::camelCaseToDelimiterArray($options, '_'));
        }
        return $this->_doDelete('/payment_methods/any/' . $token  . $queryString);
    }

    /**
     * Grant a payment method record
     *
     * See our {@link https://developer.paypal.com/braintree/docs/reference/request/payment-method/grant developer docs} for more info on the Grant API.
     *
     * @param string $sharedPaymentMethodToken payment method identifier
     * @param mixed  $attribs                  containing request parameters
     *
     * @return Result
     */
    public function grant($sharedPaymentMethodToken, $attribs = [])
    {
        if (is_bool($attribs) === true) {
            $attribs = ['allow_vaulting' => $attribs];
        }
        $options = [ 'shared_payment_method_token' => $sharedPaymentMethodToken ];

        return $this->_doGrant(
            '/payment_methods/grant',
            [
                'payment_method' => array_merge($attribs, $options)
            ]
        );
    }

    /**
     * Deletes the version of a granted payment method from the receiving merchant's vault.
     *
     * See our {@link https://developer.paypal.com/braintree/docs/reference/request/payment-method/revoke developer docs} for more info on the Grant API.
     *
     * @param string $sharedPaymentMethodToken payment method identifier
     *
     * @return Result
     */
    public function revoke($sharedPaymentMethodToken)
    {
        return $this->_doRevoke(
            '/payment_methods/revoke',
            [
                'payment_method' => [
                    'shared_payment_method_token' => $sharedPaymentMethodToken
                ]
            ]
        );
    }

    private static function baseSignature()
    {
        $billingAddressSignature = AddressGateway::createSignature();
        $optionsSignature = [
            'failOnDuplicatePaymentMethod',
            'makeDefault',
            'skipAdvancedFraudChecking',
            'usBankAccountVerificationMethod',
            'verificationAccountType',
            'verificationAmount',
            'verificationMerchantAccountId',
            'verifyCard',
            ['paypal' => [
                'payee_email',
                'payeeEmail',
                'order_id',
                'orderId',
                'custom_field',
                'customField',
                'description',
                'amount',
                ['shipping' =>
                    [
                        'firstName', 'lastName', 'company', 'countryName',
                        'countryCodeAlpha2', 'countryCodeAlpha3', 'countryCodeNumeric',
                        'extendedAddress', 'locality', 'postalCode', 'region',
                        'streetAddress'],
                ],
            ]],
        ];
        return [
            'billingAddressId',
            'cardholderName',
            'cvv',
            'deviceData',
            'expirationDate',
            'expirationMonth',
            'expirationYear',
            'number',
            'paymentMethodNonce',
            'token',
            ['options' => $optionsSignature],
            ['billingAddress' => $billingAddressSignature]
        ];
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public static function createSignature()
    {
        $signature = array_merge(self::baseSignature(), [
            'customerId',
            'paypalRefreshToken',
            CreditCardGateway::threeDSecurePassThruSignature()
        ]);
        return $signature;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public static function updateSignature()
    {
        $billingAddressSignature = AddressGateway::updateSignature();
        array_push($billingAddressSignature, [
            'options' => [
                'updateExisting'
            ]
        ]);
        $threeDSPassThruSignature = [
            'authenticationResponse',
            'cavv',
            'cavvAlgorithm',
            'directoryResponse',
            'dsTransactionId',
            'eciFlag',
            'threeDSecureVersion',
            'xid'
        ];
        $signature = array_merge(self::baseSignature(), [
            'venmoSdkPaymentMethodCode',
            ['billingAddress' => $billingAddressSignature],
            ['threeDSecurePassThru' => $threeDSPassThruSignature]
        ]);
        return $signature;
    }

    private static function deleteSignature()
    {
        return ['revokeAllGrants'];
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function _doCreate($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->post($fullPath, $params);

        return $this->_verifyGatewayResponse($response);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function _doGrant($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->post($fullPath, $params);

        return $this->_verifyGrantResponse($response);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function _doRevoke($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->post($fullPath, $params);

        return $this->_verifyRevokeResponse($response);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function _doUpdate($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->put($fullPath, $params);

        return $this->_verifyGatewayResponse($response);
    }


    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function _doDelete($subPath)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $this->_http->delete($fullPath);
        return new Result\Successful();
    }

    /**
     * Generic method for validating incoming gateway responses
     *
     * Creates a new CreditCard or PayPalAccount object
     * and encapsulates it inside a Result\Successful object, or
     * encapsulates a Errors object inside a Result\Error
     * alternatively, throws an Unexpected exception if the response is invalid.
     */
    private function _verifyGatewayResponse($response)
    {
        if (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } elseif (($response)) {
            return new Result\Successful(
                PaymentMethodParser::parsePaymentMethod($response),
                'paymentMethod'
            );
        } else {
            throw new Exception\Unexpected(
                'Expected payment method or apiErrorResponse'
            );
        }
    }

    private function _verifyGrantResponse($response)
    {
        if (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } elseif (isset($response['paymentMethodNonce'])) {
            return new Result\Successful(
                PaymentMethodNonce::factory($response['paymentMethodNonce']),
                'paymentMethodNonce'
            );
        } else {
            throw new Exception\Unexpected(
                'Expected paymentMethodNonce or apiErrorResponse'
            );
        }
    }

    private function _verifyRevokeResponse($response)
    {
        if (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } elseif (isset($response['success'])) {
            return new Result\Successful();
        } else {
            throw new Exception\Unexpected(
                'Expected success or apiErrorResponse'
            );
        }
    }

    /**
     * Verifies that a valid payment method identifier is being used
     */
    private function _validateId($identifier = null, $identifierType = 'token')
    {
        if (empty($identifier)) {
            throw new InvalidArgumentException(
                'expected payment method id to be set'
            );
        }
        if (!preg_match('/^[0-9A-Za-z_-]+$/', $identifier)) {
            throw new InvalidArgumentException(
                $identifier . ' is an invalid payment method ' . $identifierType . '.'
            );
        }
    }
}
