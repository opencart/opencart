<?php

namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree CreditCardGateway module
 * Creates and manages Braintree CreditCards
 *
 * For more detailed information on CreditCards, see {@link https://developer.paypal.com/braintree/docs/reference/response/credit-card/php our developer docs}<br />
 * For more detailed information on CreditCard verifications, see {@link https://developer.paypal.com/braintree/docs/reference/response/credit-card-verification/php our reference documentation}
 */
class CreditCardGateway
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
        return $this->_doCreate('/payment_methods', ['credit_card' => $attribs]);
    }

    /**
     * Attempts the create operation assuming all data will validate
     * returns a CreditCard object instead of a Result
     *
     * @param array $attribs containing request parameters
     *
     * @throws Exception\ValidationError
     *
     * @return CreditCard
     */
    public function createNoValidate($attribs)
    {
        $result = $this->create($attribs);
        return Util::returnObjectOrThrowException(__CLASS__, $result);
    }

    /**
     * Returns a ResourceCollection of expired credit cards
     *
     * @return ResourceCollection
     */
    public function expired()
    {
        $path = $this->_config->merchantPath() . '/payment_methods/all/expired_ids';
        $response = $this->_http->post($path);
        $pager = [
            'object' => $this,
            'method' => 'fetchExpired',
            'methodArgs' => []
        ];

        return new ResourceCollection($response, $pager);
    }

    /**
     * Returns a ResourceCollection of expired credit cards
     *
     * @param string $ids containing credit card IDs
     *
     * @return ResourceCollection
     */
    public function fetchExpired($ids)
    {
        $path = $this->_config->merchantPath() . "/payment_methods/all/expired";
        $response = $this->_http->post($path, ['search' => ['ids' => $ids]]);

        return Util::extractattributeasarray(
            $response['paymentMethods'],
            'creditCard'
        );
    }

    /**
     * Returns a ResourceCollection of credit cards expiring between start/end
     *
     * @param string $startDate the start date of search
     * @param string $endDate   the end date of search
     *
     * @return ResourceCollection
     */
    public function expiringBetween($startDate, $endDate)
    {
        $start = date('mY', $startDate);
        $end = date('mY', $endDate);
        $query = '/payment_methods/all/expiring_ids?start=' . $start . '&end=' . $end;
        $queryPath = $this->_config->merchantPath() . $query;
        $response = $this->_http->post($queryPath);
        $pager = [
            'object' => $this,
            'method' => 'fetchExpiring',
            'methodArgs' => [$startDate, $endDate]
        ];

        return new ResourceCollection($response, $pager);
    }

    /**
     * Returns a ResourceCollection of credit cards expiring between start/end given a set of IDs
     *
     * @param string $startDate the start date of search
     * @param string $endDate   the end date of search
     * @param string $ids       containing ids to search
     *
     * @return ResourceCollection
     */
    public function fetchExpiring($startDate, $endDate, $ids)
    {
        $start = date('mY', $startDate);
        $end = date('mY', $endDate);
        $query = '/payment_methods/all/expiring?start=' . $start . '&end=' . $end;
        $queryPath = $this->_config->merchantPath() . $query;
        $response = $this->_http->post($queryPath, ['search' => ['ids' => $ids]]);

        return Util::extractAttributeAsArray(
            $response['paymentMethods'],
            'creditCard'
        );
    }

    /**
     * Find a creditcard by token
     *
     * @param string $token credit card unique id
     *
     * @throws Exception\NotFound
     *
     * @return CreditCard
     */
    public function find($token)
    {
        $this->_validateId($token);
        try {
            $path = $this->_config->merchantPath() . '/payment_methods/credit_card/' . $token;
            $response = $this->_http->get($path);
            return CreditCard::factory($response['creditCard']);
        } catch (Exception\NotFound $e) {
            throw new Exception\NotFound(
                'credit card with token ' . $token . ' not found'
            );
        }
    }

    /**
     * Convert a payment method nonce to a credit card
     *
     * @param string $nonce payment method nonce
     *
     * @throws Exception\NotFound
     *
     * @return CreditCard
     */
    public function fromNonce($nonce)
    {
        $this->_validateId($nonce, "nonce");
        try {
            $path = $this->_config->merchantPath() . '/payment_methods/from_nonce/' . $nonce;
            $response = $this->_http->get($path);
            return CreditCard::factory($response['creditCard']);
        } catch (Exception\NotFound $e) {
            throw new Exception\NotFound(
                'credit card with nonce ' . $nonce . ' locked, consumed or not found'
            );
        }
    }

   /**
     * Create a credit on the card for the passed transaction
     *
     * @param string $token              belonging to the credit card
     * @param array  $transactionAttribs containing request parameters
     *
     * @return Result\Successful|Result\Error
     */
    public function credit($token, $transactionAttribs)
    {
        $this->_validateId($token);
        return Transaction::credit(
            array_merge(
                $transactionAttribs,
                ['paymentMethodToken' => $token]
            )
        );
    }

    /**
     * Create a credit on this card, assuming validations will pass
     *
     * Returns a Transaction object on success
     *
     * @param string $token              belonging to the credit card
     * @param array  $transactionAttribs containing request parameters
     *
     * @throws Exception\ValidationError
     *
     * @return Transaction
     */
    public function creditNoValidate($token, $transactionAttribs)
    {
        $result = $this->credit($token, $transactionAttribs);
        return Util::returnObjectOrThrowException('Braintree\Transaction', $result);
    }

    /**
     * Create a new sale for the current card
     *
     * @param string $token              belonging to the credit card
     * @param array  $transactionAttribs containing request parameters
     *
     * @return Result\Successful|Result\Error
     */
    public function sale($token, $transactionAttribs)
    {
        $this->_validateId($token);
        return Transaction::sale(
            array_merge(
                $transactionAttribs,
                ['paymentMethodToken' => $token]
            )
        );
    }

    /**
     * Create a new sale using this card, assuming validations will pass
     *
     * Returns a Transaction object on success
     *
     * @param string $token              belonging to the credit card
     * @param array  $transactionAttribs containing request parameters
     *
     * @throws Exception\ValidationsFailed
     *
     * @return Transaction
     */
    public function saleNoValidate($token, $transactionAttribs)
    {
        $result = $this->sale($token, $transactionAttribs);
        return Util::returnObjectOrThrowException('Braintree\Transaction', $result);
    }

    /**
     * Updates the creditcard record
     *
     * If calling this method in context, $token
     * is the 2nd attribute. $token is not sent in object context.
     *
     * @param string $token      (optional)
     * @param array  $attributes containing request parameters
     *
     * @return Result\Successful|Result\Error
     */
    public function update($token, $attributes)
    {
        Util::verifyKeys(self::updateSignature(), $attributes);
        $this->_validateId($token);
        return $this->_doUpdate('put', '/payment_methods/credit_card/' . $token, ['creditCard' => $attributes]);
    }

    /**
     * Update a creditcard record, assuming validations will pass
     *
     * If calling this method in context, $token
     * is the 2nd attribute. $token is not sent in object context.
     * returns a CreditCard object on success
     *
     * @param string $token      (optional)
     * @param array  $attributes containing request parameters
     *
     * @return CreditCard
     *
     * @throws Exception\ValidationsFailed
     */
    public function updateNoValidate($token, $attributes)
    {
        $result = $this->update($token, $attributes);
        return Util::returnObjectOrThrowException(__CLASS__, $result);
    }

    /**
     * Delete a credit card record
     *
     * @param string $token credit card identifier
     *
     * @return Result
     */
    public function delete($token)
    {
        $this->_validateId($token);
        $path = $this->_config->merchantPath() . '/payment_methods/credit_card/' . $token;
        $this->_http->delete($path);
        return new Result\Successful();
    }

    private static function baseOptions()
    {
        return [
            'makeDefault',
            'skipAdvancedFraudChecking',
            'venmoSdkSession',
            'verificationAccountType',
            'verificationAmount',
            'verificationMerchantAccountId',
            'verifyCard',
        ];
    }

    private static function baseSignature($options)
    {
         return [
             'billingAddressId', 'cardholderName', 'cvv', 'number',
             'expirationDate', 'expirationMonth', 'expirationYear', 'token', 'venmoSdkPaymentMethodCode',
             'deviceData', 'paymentMethodNonce',
             ['options' => $options],
             [
                 'billingAddress' => self::billingAddressSignature()
             ],
         ];
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public static function billingAddressSignature()
    {
        return [
            'firstName',
            'lastName',
            'company',
            'countryCodeAlpha2',
            'countryCodeAlpha3',
            'countryCodeNumeric',
            'countryName',
            'extendedAddress',
            'locality',
            'region',
            'postalCode',
            'streetAddress'
        ];
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public static function createSignature()
    {
        $options = self::baseOptions();
        $options[] = "failOnDuplicatePaymentMethod";
        $signature = self::baseSignature($options);
        $signature[] = 'customerId';
        $signature[] = self::threeDSecurePassThruSignature();
        return $signature;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public static function threeDSecurePassThruSignature()
    {
        return [
            'threeDSecurePassThru' => [
                'eciFlag',
                'cavv',
                'xid',
                'threeDSecureVersion',
                'authenticationResponse',
                'directoryResponse',
                'cavvAlgorithm',
                'dsTransactionId',
            ]
        ];
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public static function updateSignature()
    {
        $options = self::baseOptions();
        $options[] = "failOnDuplicatePaymentMethod";
        $signature = self::baseSignature($options);
        $signature[] = self::threeDSecurePassThruSignature();

        $updateExistingBillingSignature = [
            [
                'options' => [
                    'updateExisting'
                ]
            ]
        ];

        foreach ($signature as $key => $value) {
            if (is_array($value) and array_key_exists('billingAddress', $value)) {
                // phpcs:ignore
                $signature[$key]['billingAddress'] = array_merge_recursive($value['billingAddress'], $updateExistingBillingSignature);
            }
        }

        return $signature;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function _doCreate($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->post($fullPath, $params);

        return $this->_verifyGatewayResponse($response);
    }

    private function _validateId($identifier = null, $identifierType = "token")
    {
        if (empty($identifier)) {
            throw new InvalidArgumentException(
                'expected credit card id to be set'
            );
        }
        if (!preg_match('/^[0-9A-Za-z_-]+$/', $identifier)) {
            throw new InvalidArgumentException(
                $identifier . ' is an invalid credit card ' . $identifierType . '.'
            );
        }
    }

    private function _doUpdate($httpVerb, $subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->$httpVerb($fullPath, $params);
        return $this->_verifyGatewayResponse($response);
    }

    /**
     * Generic method for validating incoming gateway responses
     *
     * Creates a new CreditCard object and encapsulates
     * it inside a Result\Successful object, or
     * encapsulates a Errors object inside a Result\Error
     * alternatively, throws an Unexpected exception if the response is invalid
     */
    private function _verifyGatewayResponse($response)
    {
        if (isset($response['creditCard'])) {
            // return a populated instance of Address
            return new Result\Successful(
                CreditCard::factory($response['creditCard'])
            );
        } elseif (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else {
            throw new Exception\Unexpected(
                "Expected address or apiErrorResponse"
            );
        }
    }
}
