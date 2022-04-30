<?php
namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree PaymentMethodGateway module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Creates and manages Braintree PaymentMethods
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 */
class PaymentMethodGateway
{
    private $_gateway;
    private $_config;
    private $_http;

    public function __construct($gateway)
    {
        $this->_gateway = $gateway;
        $this->_config = $gateway->config;
        $this->_config->assertHasAccessTokenOrKeys();
        $this->_http = new Http($gateway->config);
    }


    public function create($attribs)
    {
        Util::verifyKeys(self::createSignature(), $attribs);
        return $this->_doCreate('/payment_methods', ['payment_method' => $attribs]);
    }

    /**
     * find a PaymentMethod by token
     *
     * @param string $token payment method unique id
     * @return CreditCard|PayPalAccount
     * @throws Exception\NotFound
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

    public function update($token, $attribs)
    {
        Util::verifyKeys(self::updateSignature(), $attribs);
        return $this->_doUpdate('/payment_methods/any/' . $token, ['payment_method' => $attribs]);
    }

    public function delete($token, $options=[])
    {
        Util::verifyKeys(self::deleteSignature(), $options);
        $this->_validateId($token);
        $queryString = "";
        if (!empty($options)) {
            $queryString = "?" . http_build_query(Util::camelCaseToDelimiterArray($options, '_'));
        }
        return $this->_doDelete('/payment_methods/any/' . $token  . $queryString);
    }

    public function grant($sharedPaymentMethodToken, $attribs=[])
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
            'verificationMerchantAccountId',
            'verifyCard',
            'verificationAccountType',
            'verificationAmount',
            'usBankAccountVerificationMethod',
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

    public static function createSignature()
    {
        $signature = array_merge(self::baseSignature(), ['customerId', 'paypalRefreshToken', 'paypalVaultWithoutUpgrade']);
        return $signature;
    }

    public static function updateSignature()
    {
        $billingAddressSignature = AddressGateway::updateSignature();
        array_push($billingAddressSignature, [
            'options' => [
                'updateExisting'
            ]
        ]);
        $signature = array_merge(self::baseSignature(), [
            'deviceSessionId',
            'venmoSdkPaymentMethodCode',
            'fraudMerchantId',
            ['billingAddress' => $billingAddressSignature]
        ]);
        return $signature;
    }

    private static function deleteSignature()
    {
        return ['revokeAllGrants'];
    }

    /**
     * sends the create request to the gateway
     *
     * @ignore
     * @param string $subPath
     * @param array $params
     * @return mixed
     */
    public function _doCreate($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->post($fullPath, $params);

        return $this->_verifyGatewayResponse($response);
    }

    public function _doGrant($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->post($fullPath, $params);

        return $this->_verifyGrantResponse($response);
    }

    public function _doRevoke($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->post($fullPath, $params);

        return $this->_verifyRevokeResponse($response);
    }

    /**
     * sends the update request to the gateway
     *
     * @ignore
     * @param string $subPath
     * @param array $params
     * @return mixed
     */
    public function _doUpdate($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->put($fullPath, $params);

        return $this->_verifyGatewayResponse($response);
    }


    /**
     * sends the delete request to the gateway
     *
     * @ignore
     * @param string $subPath
     * @return mixed
     */
    public function _doDelete($subPath)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $this->_http->delete($fullPath);
        return new Result\Successful();
    }

    /**
     * generic method for validating incoming gateway responses
     *
     * creates a new CreditCard or PayPalAccount object
     * and encapsulates it inside a Result\Successful object, or
     * encapsulates a Errors object inside a Result\Error
     * alternatively, throws an Unexpected exception if the response is invalid.
     *
     * @ignore
     * @param array $response gateway response values
     * @return Result\Successful|Result\Error
     * @throws Exception\Unexpected
     */
    private function _verifyGatewayResponse($response)
    {
        if (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else if (($response)) {
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

    private function _verifyGrantResponse($response) {
        if (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else if (isset($response['paymentMethodNonce'])) {
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

    private function _verifyRevokeResponse($response) {
        if (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else if (isset($response['success'])) {
            return new Result\Successful();
        } else {
            throw new Exception\Unexpected(
                'Expected success or apiErrorResponse'
            );
        }
    }

    /**
     * verifies that a valid payment method identifier is being used
     * @ignore
     * @param string $identifier
     * @param Optional $string $identifierType type of identifier supplied, default 'token'
     * @throws InvalidArgumentException
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
class_alias('Braintree\PaymentMethodGateway', 'Braintree_PaymentMethodGateway');
