<?php
namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree IdealPaymentGateway module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Manages Braintree IdealPayments
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 */
class IdealPaymentGateway
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
     * find an IdealPayment by id
     *
     * @access public
     * @param string $idealPaymentId
     * @return IdealPayment
     * @throws Exception\NotFound
     */
    public function find($idealPaymentId)
    {
        try {
            $path = $this->_config->merchantPath() . '/ideal_payments/' . $idealPaymentId;
            $response = $this->_http->get($path);
            return IdealPayment::factory($response['idealPayment']);
        } catch (Exception\NotFound $e) {
            throw new Exception\NotFound(
                'iDEAL Payment with id ' . $idealPaymentId . ' not found'
            );
        }
    }

    /**
     * create a new sale for the current IdealPayment
     *
     * @param string $idealPaymentId
     * @param array $transactionAttribs
     * @return Result\Successful|Result\Error
     * @see Transaction::sale()
     */
    public function sale($idealPaymentId, $transactionAttribs)
    {
        return Transaction::sale(
            array_merge(
                $transactionAttribs,
                ['paymentMethodNonce' => $idealPaymentId]
            )
        );
    }

    /**
     * generic method for validating incoming gateway responses
     *
     * creates a new IdealPayment object and encapsulates
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
        if (isset($response['idealPayment'])) {
            // return a populated instance of IdealPayment
            return new Result\Successful(
                    IdealPayment::factory($response['idealPayment'])
            );
        } else if (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else {
            throw new Exception\Unexpected(
            'Expected Ideal Payment or apiErrorResponse'
            );
        }
    }
}
class_alias('Braintree\IdealPaymentGateway', 'Braintree_IdealPaymentGateway');
