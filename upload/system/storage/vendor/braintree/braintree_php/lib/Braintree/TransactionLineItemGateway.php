<?php
namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree TransactionLineItemGateway processor
 * Creates and manages transaction line items
 *
 * @package    Braintree
 * @category   Resources
 */

class TransactionLineItemGateway
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
     * @access public
     * @param string id
     * @return Transaction
     */
    public function findAll($id)
    {
        $this->_validateId($id);
        try {
            $path = $this->_config->merchantPath() . '/transactions/' . $id . '/line_items';
            $response = $this->_http->get($path);

            $lineItems = [];
            if (isset($response['lineItems'])) {
                foreach ($response['lineItems'] AS $lineItem) {
                    $lineItems[] = new TransactionLineItem($lineItem);
                }
            }
            return $lineItems;
        } catch (Exception\NotFound $e) {
            throw new Exception\NotFound('transaction line items with id ' . $id . ' not found');
        }
    }

    /**
     * verifies that a valid transaction id is being used
     * @ignore
     * @param string transaction id
     * @throws InvalidArgumentException
     */
    private function _validateId($id = null) {
        if (empty($id)) {
           throw new InvalidArgumentException('expected transaction id to be set');
        }
        if (!preg_match('/^[0-9a-z]+$/', $id)) {
            throw new InvalidArgumentException($id . ' is an invalid transaction id.');
        }
    }
}
class_alias('Braintree\TransactionLineItemGateway', 'Braintree_TransactionLineItemGateway');
