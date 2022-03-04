<?php
/**
 * Copyright 2014 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * File containing the Tokens class.
 */

namespace Klarna\Rest\CustomerToken;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\ConnectorInterface;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Tokens resource.
 *
 * The Customer Token API is used to charge customers with a tokenized Klarna payment method
 * and can be used for recurring purchases, subscriptions and for storing a customer's payment method. Tokens are
 * created using the generate a customer token call in the payments API.
 *
 * @example docs/examples/CustomerTokenAPI/Tokens/create_order.php Create a new order using customer token
 * @example docs/examples/CustomerTokenAPI/Tokens/read_token_details.php Read customer token details
 */
class Tokens extends Resource
{
    /**
     * {@inheritDoc}
     */
    const ID_FIELD = 'customerToken';

    /**
     * {@inheritDoc}
     */
    public static $path = '/customer-token/v1/tokens';

    /**
     * Constructs a Tokens instance.
     *
     * @param ConnectorInterface $connector HTTP transport connector
     * @param string    $customerToken   Customer Token
     */
    public function __construct(ConnectorInterface $connector, $customerToken)
    {
        parent::__construct($connector);

        $this->setLocation(self::$path . "/{$customerToken}");
        $this[static::ID_FIELD] = $customerToken;
    }

    /**
     * Creates order using Customer Token.
     *
     * @param array  $data Order data
     * @param string $klarnaIdempotencyKey Idempotency Key
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the location header is missing
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return array created order data
     */
    public function createOrder(array $data, $klarnaIdempotencyKey = null)
    {
        $headers = ['Content-Type' => 'application/json'];
        if (!is_null($klarnaIdempotencyKey)) {
            $headers['Klarna-Idempotency-Key'] = $klarnaIdempotencyKey;
        }

        return $this->request(
            'POST',
            $this->getLocation() . '/order',
            $headers,
            $data !== null ? \json_encode($data) : null
        )
        ->expectSuccessfull()
        ->status('200')
        ->contentType('application/json')
        ->getJson();
    }

    /**
     * Update the status of a customer token.
     *
     * @param array $data Customer token data
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function updateTokenStatus(array $data)
    {
        $this->patch($this->getLocation() . '/status', $data)
            ->expectSuccessfull()
            ->status('202');

        return $this;
    }
}
