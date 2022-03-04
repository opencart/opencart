<?php

namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree TransactionLineItemGateway processor
 * Creates and manages transaction line items
 */

class TransactionLineItemGateway
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
     * Find all Transaction Line Items or return an error
     *
     * @param string $id of the Transaction to search for line items
     *
     * @return TransactionLineItem|Exception
     */
    public function findAll($id)
    {
        $this->_validateId($id);
        try {
            $path = $this->_config->merchantPath() . '/transactions/' . $id . '/line_items';
            $response = $this->_http->get($path);

            $lineItems = [];
            if (isset($response['lineItems'])) {
                foreach ($response['lineItems'] as $lineItem) {
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
     *
     * @param string transaction id
     *
     * @throws InvalidArgumentException
     */
    private function _validateId($id = null)
    {
        if (empty($id)) {
            throw new InvalidArgumentException('expected transaction id to be set');
        }
        if (!preg_match('/^[0-9a-z]+$/', $id)) {
            throw new InvalidArgumentException($id . ' is an invalid transaction id.');
        }
    }
}
