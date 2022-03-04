<?php //phpcs:disable

namespace Braintree;

class MerchantAccountGateway
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

    public function create($attribs)
    {
        Util::verifyKeys(self::createSignature(), $attribs);
        return $this->_doCreate('/merchant_accounts/create_via_api', ['merchant_account' => $attribs]);
    }

    public function find($merchant_account_id)
    {
        try {
            $path = $this->_config->merchantPath() . '/merchant_accounts/' . $merchant_account_id;
            $response = $this->_http->get($path);
            return MerchantAccount::factory($response['merchantAccount']);
        } catch (Exception\NotFound $e) {
            throw new Exception\NotFound('merchant account with id ' . $merchant_account_id . ' not found');
        }
    }

    public function update($merchant_account_id, $attributes)
    {
        Util::verifyKeys(self::updateSignature(), $attributes);
        $queryPath = '/merchant_accounts/' . $merchant_account_id . '/update_via_api';
        return $this->_doUpdate($queryPath, ['merchant_account' => $attributes]);
    }

    public static function updateSignature()
    {
        $signature = self::createSignature();
        unset($signature['tosAccepted']);
        return $signature;
    }

    public function createForCurrency($attribs)
    {
        $queryPath = $this->_config->merchantPath() . '/merchant_accounts/create_for_currency';
        $response = $this->_http->post($queryPath, ['merchant_account' => $attribs]);
        return $this->_verifyGatewayResponse($response);
    }

    public function all()
    {
        $pager = [
            'object' => $this,
            'method' => 'fetchMerchantAccounts',
        ];
        return new PaginatedCollection($pager);
    }

    public function fetchMerchantAccounts($page)
    {
        $response = $this->_http->get($this->_config->merchantPath() . '/merchant_accounts?page=' . $page);
        $body = $response['merchantAccounts'];
        $merchantAccounts = Util::extractattributeasarray($body, 'merchantAccount');
        $totalItems = $body['totalItems'][0];
        $pageSize = $body['pageSize'][0];
        return new PaginatedResult($totalItems, $pageSize, $merchantAccounts);
    }

    public static function createSignature()
    {
        $addressSignature = ['streetAddress', 'postalCode', 'locality', 'region'];
        $individualSignature = [
            'firstName',
            'lastName',
            'email',
            'phone',
            'dateOfBirth',
            'ssn',
            ['address' => $addressSignature]
        ];

        $businessSignature = [
            'dbaName',
            'legalName',
            'taxId',
            ['address' => $addressSignature]
        ];

        $fundingSignature = [
            'routingNumber',
            'accountNumber',
            'destination',
            'email',
            'mobilePhone',
            'descriptor',
        ];

        return [
            'id',
            'tosAccepted',
            'masterMerchantAccountId',
            ['individual' => $individualSignature],
            ['funding' => $fundingSignature],
            ['business' => $businessSignature]
        ];
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function _doCreate($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->post($fullPath, $params);

        return $this->_verifyGatewayResponse($response);
    }

    private function _doUpdate($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->put($fullPath, $params);

        return $this->_verifyGatewayResponse($response);
    }

    private function _verifyGatewayResponse($response)
    {
        if (isset($response['response'])) {
            $response = $response['response'];
        }
        if (isset($response['merchantAccount'])) {
            // return a populated instance of merchantAccount
            return new Result\Successful(
                MerchantAccount::factory($response['merchantAccount'])
            );
        } elseif (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else {
            throw new Exception\Unexpected(
                "Expected merchant account or apiErrorResponse"
            );
        }
    }
}
