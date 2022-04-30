<?php
namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree UsBankAccountGateway module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Manages Braintree UsBankAccounts
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 */
class UsBankAccountGateway
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
     * find a usBankAccount by token
     *
     * @access public
     * @param string $token paypal accountunique id
     * @return UsBankAccount
     * @throws Exception\NotFound
     */
    public function find($token)
    {
        try {
            $path = $this->_config->merchantPath() . '/payment_methods/us_bank_account/' . $token;
            $response = $this->_http->get($path);
            return UsBankAccount::factory($response['usBankAccount']);
        } catch (Exception\NotFound $e) {
            throw new Exception\NotFound(
                'US bank account with token ' . $token . ' not found'
            );
        }

    }

    /**
     * create a new sale for the current UsBank account
     *
     * @param string $token
     * @param array $transactionAttribs
     * @return Result\Successful|Result\Error
     * @see Transaction::sale()
     */
    public function sale($token, $transactionAttribs)
    {
        return Transaction::sale(
            array_merge(
                $transactionAttribs,
                ['paymentMethodToken' => $token]
            )
        );
    }

    /**
     * generic method for validating incoming gateway responses
     *
     * creates a new UsBankAccount object and encapsulates
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
        if (isset($response['usBankAccount'])) {
            // return a populated instance of UsBankAccount
            return new Result\Successful(
                    UsBankAccount::factory($response['usBankAccount'])
            );
        } else if (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else {
            throw new Exception\Unexpected(
            'Expected US bank account or apiErrorResponse'
            );
        }
    }
}
class_alias('Braintree\UsBankAccountGateway', 'Braintree_UsBankAccountGateway');
