<?php

namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree UsBankAccountVerificationGateway module
 */

/**
 * Manages Braintree UsBankAccountVerifications
 *
 * <b>== More information ==</b>
 */
class UsBankAccountVerificationGateway
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
     * find a usBankAccountVerification by token
     *
     * @param string $token unique id
     *
     * @throws Exception\NotFound
     *
     * @return UsBankAccountVerification
     */
    public function find($token)
    {
        try {
            $path = $this->_config->merchantPath() . '/us_bank_account_verifications/' . $token;
            $response = $this->_http->get($path);
            return UsBankAccountVerification::factory($response['usBankAccountVerification']);
        } catch (Exception\NotFound $e) {
            throw new Exception\NotFound(
                'US bank account with token ' . $token . ' not found'
            );
        }
    }

    /**
     * Returns a ResourceCollection of US bank account verifications matching the search query.
     *
     * @param mixed $query search query
     *
     * @return ResourceCollection
     */
    public function search($query)
    {
        $criteria = [];
        foreach ($query as $term) {
            $criteria[$term->name] = $term->toparam();
        }

        $path = $this->_config->merchantPath() . '/us_bank_account_verifications/advanced_search_ids';
        $response = $this->_http->post($path, ['search' => $criteria]);
        $pager = [
            'object' => $this,
            'method' => 'fetch',
            'methodArgs' => [$query]
        ];

        return new ResourceCollection($response, $pager);
    }

    /**
     * complete micro transfer verification by confirming the transfer amounts
     *
     * @param string $token   unique id
     * @param array  $amounts amounts deposited in micro transfer
     *
     * @throws Exception\Unexpected
     *
     * @return UsBankAccountVerification
     */
    public function confirmMicroTransferAmounts($token, $amounts)
    {
        try {
            // phpcs:ignore Generic.Files.LineLength
            $path = $this->_config->merchantPath() . '/us_bank_account_verifications/' . $token . '/confirm_micro_transfer_amounts';
            $response = $this->_http->put($path, [
                "us_bank_account_verification" => ["deposit_amounts" => $amounts]
            ]);
            return $this->_verifyGatewayResponse($response);
        } catch (Exception\Unexpected $e) {
            throw new Exception\Unexpected(
                'Unexpected exception.'
            );
        }
    }

    /**
     * generic method for validating incoming gateway responses
     *
     * creates a new UsBankAccountVerification object and encapsulates
     * it inside a Result\Successful object, or
     * encapsulates a Errors object inside a Result\Error
     * alternatively, throws an Unexpected exception if the response is invalid.
     *
     * @param array $response gateway response values
     *
     * @throws Exception\Unexpected
     *
     * @return Result\Successful|Result\Error
     */
    private function _verifyGatewayResponse($response)
    {
        if (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } elseif (isset($response['usBankAccountVerification'])) {
            // return a populated instance of UsBankAccountVerification
            return new Result\Successful(
                UsBankAccountVerification::factory($response['usBankAccountVerification'])
            );
        } else {
            throw new Exception\Unexpected(
                'Expected US bank account or apiErrorResponse'
            );
        }
    }
}
