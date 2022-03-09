<?php
namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree UsBankAccountVerificationGateway module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Manages Braintree UsBankAccountVerifications
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 */
class UsBankAccountVerificationGateway
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

    /**
     * find a usBankAccountVerification by token
     *
     * @access public
     * @param string $token unique id
     * @return UsBankAccountVerification
     * @throws Exception\NotFound
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
     * @access public
     * @param string $token unique id
     * @param array $amounts amounts deposited in micro transfer
     * @return UsBankAccountVerification
     * @throws Exception\Unexpected
     */
    public function confirmMicroTransferAmounts($token, $amounts)
    {
        try {
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
     * @ignore
     * @param array $response gateway response values
     * @return Result\Successful|Result\Error
     * @throws Exception\Unexpected
     */
    private function _verifyGatewayResponse($response)
    {
        if (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else if (isset($response['usBankAccountVerification'])) {
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

class_alias('Braintree\UsBankAccountVerificationGateway', 'Braintree_UsBankAccountVerificationGateway');
