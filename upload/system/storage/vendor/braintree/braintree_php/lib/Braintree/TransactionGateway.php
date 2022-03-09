<?php
namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree TransactionGateway processor
 * Creates and manages transactions
 *
 *
 * <b>== More information ==</b>
 *
 * For more detailed information on Transactions, see {@link https://developers.braintreepayments.com/reference/response/transaction/php https://developers.braintreepayments.com/reference/response/transaction/php}
 *
 * @package    Braintree
 * @category   Resources
 */

class TransactionGateway
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

    public function cloneTransaction($transactionId, $attribs)
    {
        Util::verifyKeys(self::cloneSignature(), $attribs);
        return $this->_doCreate('/transactions/' . $transactionId . '/clone', ['transactionClone' => $attribs]);
    }

    /**
     * @ignore
     * @access private
     * @param array $attribs
     * @return Result\Successful|Result\Error
     */
    private function create($attribs)
    {
        Util::verifyKeys(self::createSignature(), $attribs);
        return $this->_doCreate('/transactions', ['transaction' => $attribs]);
    }

    /**
     * @ignore
     * @access private
     * @param array $attribs
     * @return object
     * @throws Exception\ValidationError
     */
    private function createNoValidate($attribs)
    {
        $result = $this->create($attribs);
        return Util::returnObjectOrThrowException(__CLASS__, $result);
    }
    /**
     *
     * @deprecated since version 2.3.0
     * @access public
     * @param array $attribs
     * @return object
     */
    public function createFromTransparentRedirect($queryString)
    {
        trigger_error("DEPRECATED: Please use TransparentRedirectRequest::confirm", E_USER_NOTICE);
        $params = TransparentRedirect::parseAndValidateQueryString(
                $queryString
        );
        return $this->_doCreate(
                '/transactions/all/confirm_transparent_redirect_request',
                ['id' => $params['id']]
        );
    }
    /**
     *
     * @deprecated since version 2.3.0
     * @access public
     * @param none
     * @return string
     */
    public function createTransactionUrl()
    {
        trigger_error("DEPRECATED: Please use TransparentRedirectRequest::url", E_USER_NOTICE);
        return $this->_config->baseUrl() . $this->_config->merchantPath() .
                '/transactions/all/create_via_transparent_redirect_request';
    }

    public static function cloneSignature()
    {
        return ['amount', 'channel', ['options' => ['submitForSettlement']]];
    }

    /**
     * creates a full array signature of a valid gateway request
     * @return array gateway request signature format
     */
    public static function createSignature()
    {
        return [
            'amount',
            'billingAddressId',
            'channel',
            'customerId',
            'deviceData',
            'deviceSessionId',
            'fraudMerchantId',
            'merchantAccountId',
            'orderId',
            'paymentMethodNonce',
            'paymentMethodToken',
            'purchaseOrderNumber',
            'recurring',
            'serviceFeeAmount',
            'sharedPaymentMethodToken',
            'sharedPaymentMethodNonce',
            'sharedCustomerId',
            'sharedShippingAddressId',
            'sharedBillingAddressId',
            'shippingAddressId',
            'taxAmount',
            'taxExempt',
            'threeDSecureToken',
            'transactionSource',
            'type',
            'venmoSdkPaymentMethodCode',
            'shippingAmount',
            'discountAmount',
            'shipsFromPostalCode',
            ['riskData' =>
                ['customerBrowser', 'customerIp', 'customer_browser', 'customer_ip']
            ],
            ['creditCard' =>
                ['token', 'cardholderName', 'cvv', 'expirationDate', 'expirationMonth', 'expirationYear', 'number'],
            ],
            ['customer' =>
                [
                    'id', 'company', 'email', 'fax', 'firstName',
                    'lastName', 'phone', 'website'],
            ],
            ['billing' =>
                [
                    'firstName', 'lastName', 'company', 'countryName',
                    'countryCodeAlpha2', 'countryCodeAlpha3', 'countryCodeNumeric',
                    'extendedAddress', 'locality', 'postalCode', 'region',
                    'streetAddress'],
            ],
            ['shipping' =>
                [
                    'firstName', 'lastName', 'company', 'countryName',
                    'countryCodeAlpha2', 'countryCodeAlpha3', 'countryCodeNumeric',
                    'extendedAddress', 'locality', 'postalCode', 'region',
                    'streetAddress'],
            ],
            ['threeDSecurePassThru' =>
                [
                    'eciFlag',
                    'cavv',
                    'xid'],
            ],
            ['options' =>
                [
                    'holdInEscrow',
                    'storeInVault',
                    'storeInVaultOnSuccess',
                    'submitForSettlement',
                    'addBillingAddressToPaymentMethod',
                    'venmoSdkSession',
                    'storeShippingAddressInVault',
                    'payeeId',
                    'payeeEmail',
                    'skipAdvancedFraudChecking',
                    'skipAvs',
                    'skipCvv',
                    ['creditCard' =>
                        ['accountType']
                    ],
                    ['threeDSecure' =>
                        ['required']
                    ],
                    # TODO: Snake case version included for backwards compatiblity. Remove in the next major version
                    ['three_d_secure' =>
                        ['required']
                    ],
                    ['paypal' =>
                        [
                            'payeeId',
                            'payeeEmail',
                            'customField',
                            'description',
                            ['supplementaryData' => ['_anyKey_']],
                        ]
                    ],
                    ['amexRewards' =>
                        [
                            'requestId',
                            'points',
                            'currencyAmount',
                            'currencyIsoCode'
                        ]
                    ],
                    ['venmo' =>
                        [
                            # TODO: Snake case version included for backwards compatiblity. Remove in the next major version
                            'profile_id',
                            'profileId'
                        ]
                    ]
                ],
            ],
            ['customFields' => ['_anyKey_']],
            ['descriptor' => ['name', 'phone', 'url']],
            ['paypalAccount' => ['payeeId', 'payeeEmail', 'payerId', 'paymentId']],
            # TODO: Snake case version included for backwards compatiblity. Remove in the next major version
            ['apple_pay_card' => ['number', 'cardholder_name', 'cryptogram', 'expiration_month', 'expiration_year', 'eci_indicator']], 

            ['applePayCard' => ['number', 'cardholderName', 'cryptogram', 'expirationMonth', 'expirationYear', 'eciIndicator']],
            ['industry' =>
                ['industryType',
                    ['data' =>
                        [
                            'folioNumber',
                            'checkInDate',
                            'checkOutDate',
                            'travelPackage',
                            'departureDate',
                            'lodgingCheckInDate',
                            'lodgingCheckOutDate',
                            'lodgingName',
                            'roomRate',
                            'passengerFirstName',
                            'passengerLastName',
                            'passengerMiddleInitial',
                            'passengerTitle',
                            'issuedDate',
                            'travelAgencyName',
                            'travelAgencyCode',
                            'ticketNumber',
                            'issuingCarrierCode',
                            'customerCode',
                            'fareAmount',
                            'feeAmount',
                            'taxAmount',
                            'restrictedTicket',
                            ['legs' =>
                                [
                                    'conjunctionTicket',
                                    'exchangeTicket',
                                    'couponNumber',
                                    'serviceClass',
                                    'carrierCode',
                                    'fareBasisCode',
                                    'flightNumber',
                                    'departureDate',
                                    'departureAirportCode',
                                    'departureTime',
                                    'arrivalAirportCode',
                                    'arrivalTime',
                                    'stopoverPermitted',
                                    'fareAmount',
                                    'feeAmount',
                                    'taxAmount',
                                    'endorsementOrRestrictions'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            ['lineItems' => ['quantity', 'name', 'description', 'kind', 'unitAmount', 'unitTaxAmount', 'totalAmount', 'discountAmount', 'taxAmount', 'unitOfMeasure', 'productCode', 'commodityCode', 'url']],
            ['externalVault' =>
                ['status' , 'previousNetworkTransactionId'],
            ]
        ];
    }

    public static function submitForSettlementSignature()
    {
        return ['orderId', ['descriptor' => ['name', 'phone', 'url']]];
    }

    public static function updateDetailsSignature()
    {
        return ['amount', 'orderId', ['descriptor' => ['name', 'phone', 'url']]];
    }

    public static function refundSignature()
    {
        return ['amount', 'orderId'];
    }

    /**
     *
     * @access public
     * @param array $attribs
     * @return Result\Successful|Result\Error
     */
    public function credit($attribs)
    {
        return $this->create(array_merge($attribs, ['type' => Transaction::CREDIT]));
    }

    /**
     *
     * @access public
     * @param array $attribs
     * @return Result\Successful|Result\Error
     * @throws Exception\ValidationError
     */
    public function creditNoValidate($attribs)
    {
        $result = $this->credit($attribs);
        return Util::returnObjectOrThrowException(__CLASS__, $result);
    }

    /**
     * @access public
     * @param string id
     * @return Transaction
     */
    public function find($id)
    {
        $this->_validateId($id);
        try {
            $path = $this->_config->merchantPath() . '/transactions/' . $id;
            $response = $this->_http->get($path);
            return Transaction::factory($response['transaction']);
        } catch (Exception\NotFound $e) {
            throw new Exception\NotFound(
            'transaction with id ' . $id . ' not found'
            );
        }
    }
    /**
     * new sale
     * @param array $attribs
     * @return Result\Successful|Result\Error
     */
    public function sale($attribs)
    {
        return $this->create(array_merge(['type' => Transaction::SALE], $attribs));
    }

    /**
     * roughly equivalent to the ruby bang method
     * @access public
     * @param array $attribs
     * @return array
     * @throws Exception\ValidationsFailed
     */
    public function saleNoValidate($attribs)
    {
        $result = $this->sale($attribs);
        return Util::returnObjectOrThrowException(__CLASS__, $result);
    }

    /**
     * Returns a ResourceCollection of transactions matching the search query.
     *
     * If <b>query</b> is a string, the search will be a basic search.
     * If <b>query</b> is a hash, the search will be an advanced search.
     * For more detailed information and examples, see {@link https://developers.braintreepayments.com/reference/request/transaction/search/php https://developers.braintreepayments.com/reference/request/transaction/search/php}
     *
     * @param mixed $query search query
     * @param array $options options such as page number
     * @return ResourceCollection
     * @throws InvalidArgumentException
     */
    public function search($query)
    {
        $criteria = [];
        foreach ($query as $term) {
            $criteria[$term->name] = $term->toparam();
        }

        $path = $this->_config->merchantPath() . '/transactions/advanced_search_ids';
        $response = $this->_http->post($path, ['search' => $criteria]);
        if (array_key_exists('searchResults', $response)) {
            $pager = [
                'object' => $this,
                'method' => 'fetch',
                'methodArgs' => [$query]
                ];

            return new ResourceCollection($response, $pager);
        } else {
            throw new Exception\DownForMaintenance();
        }
    }

    public function fetch($query, $ids)
    {
        $criteria = [];
        foreach ($query as $term) {
            $criteria[$term->name] = $term->toparam();
        }
        $criteria["ids"] = TransactionSearch::ids()->in($ids)->toparam();
        $path = $this->_config->merchantPath() . '/transactions/advanced_search';
        $response = $this->_http->post($path, ['search' => $criteria]);

        if (array_key_exists('creditCardTransactions', $response)) {
            return Util::extractattributeasarray(
                $response['creditCardTransactions'],
                'transaction'
            );
        } else {
            throw new Exception\DownForMaintenance();
        }
    }

    /**
     * void a transaction by id
     *
     * @param string $id transaction id
     * @return Result\Successful|Result\Error
     */
    public function void($transactionId)
    {
        $this->_validateId($transactionId);

        $path = $this->_config->merchantPath() . '/transactions/'. $transactionId . '/void';
        $response = $this->_http->put($path);
        return $this->_verifyGatewayResponse($response);
    }
    /**
     *
     */
    public function voidNoValidate($transactionId)
    {
        $result = $this->void($transactionId);
        return Util::returnObjectOrThrowException(__CLASS__, $result);
    }

    public function submitForSettlement($transactionId, $amount = null, $attribs = [])
    {
        $this->_validateId($transactionId);
        Util::verifyKeys(self::submitForSettlementSignature(), $attribs);
        $attribs['amount'] = $amount;

        $path = $this->_config->merchantPath() . '/transactions/'. $transactionId . '/submit_for_settlement';
        $response = $this->_http->put($path, ['transaction' => $attribs]);
        return $this->_verifyGatewayResponse($response);
    }

    public function submitForSettlementNoValidate($transactionId, $amount = null, $attribs = [])
    {
        $result = $this->submitForSettlement($transactionId, $amount, $attribs);
        return Util::returnObjectOrThrowException(__CLASS__, $result);
    }

    public function updateDetails($transactionId, $attribs = [])
    {
        $this->_validateId($transactionId);
        Util::verifyKeys(self::updateDetailsSignature(), $attribs);

        $path = $this->_config->merchantPath() . '/transactions/'. $transactionId . '/update_details';
        $response = $this->_http->put($path, ['transaction' => $attribs]);
        return $this->_verifyGatewayResponse($response);
    }

    public function submitForPartialSettlement($transactionId, $amount, $attribs = [])
    {
        $this->_validateId($transactionId);
        Util::verifyKeys(self::submitForSettlementSignature(), $attribs);
        $attribs['amount'] = $amount;

        $path = $this->_config->merchantPath() . '/transactions/'. $transactionId . '/submit_for_partial_settlement';
        $response = $this->_http->post($path, ['transaction' => $attribs]);
        return $this->_verifyGatewayResponse($response);
    }

    public function holdInEscrow($transactionId)
    {
        $this->_validateId($transactionId);

        $path = $this->_config->merchantPath() . '/transactions/' . $transactionId . '/hold_in_escrow';
        $response = $this->_http->put($path, []);
        return $this->_verifyGatewayResponse($response);
    }

    public function releaseFromEscrow($transactionId)
    {
        $this->_validateId($transactionId);

        $path = $this->_config->merchantPath() . '/transactions/' . $transactionId . '/release_from_escrow';
        $response = $this->_http->put($path, []);
        return $this->_verifyGatewayResponse($response);
    }

    public function cancelRelease($transactionId)
    {
        $this->_validateId($transactionId);

        $path = $this->_config->merchantPath() . '/transactions/' . $transactionId . '/cancel_release';
        $response = $this->_http->put($path, []);
        return $this->_verifyGatewayResponse($response);
    }

    public function refund($transactionId, $amount_or_options = null)
    {
        self::_validateId($transactionId);

        if(gettype($amount_or_options) == "array") {
            $options = $amount_or_options;
        } else {
            $options = [
                "amount" => $amount_or_options
            ];
        }
        Util::verifyKeys(self::refundSignature(), $options);

        $params = ['transaction' => $options];
        $path = $this->_config->merchantPath() . '/transactions/' . $transactionId . '/refund';
        $response = $this->_http->post($path, $params);
        return $this->_verifyGatewayResponse($response);
    }

    /**
     * sends the create request to the gateway
     *
     * @ignore
     * @param var $subPath
     * @param array $params
     * @return Result\Successful|Result\Error
     */
    public function _doCreate($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->post($fullPath, $params);

        return $this->_verifyGatewayResponse($response);
    }

    /**
     * verifies that a valid transaction id is being used
     * @ignore
     * @param string transaction id
     * @throws InvalidArgumentException
     */
    private function _validateId($id = null) {
        if (empty($id)) {
           throw new InvalidArgumentException(
                   'expected transaction id to be set'
                   );
        }
        if (!preg_match('/^[0-9a-z]+$/', $id)) {
            throw new InvalidArgumentException(
                    $id . ' is an invalid transaction id.'
                    );
        }
    }

    /**
     * generic method for validating incoming gateway responses
     *
     * creates a new Transaction object and encapsulates
     * it inside a Result\Successful object, or
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
        if (isset($response['transaction'])) {
            // return a populated instance of Transaction
            return new Result\Successful(
                    Transaction::factory($response['transaction'])
            );
        } else if (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else {
            throw new Exception\Unexpected(
            "Expected transaction or apiErrorResponse"
            );
        }
    }
}
class_alias('Braintree\TransactionGateway', 'Braintree_TransactionGateway');
