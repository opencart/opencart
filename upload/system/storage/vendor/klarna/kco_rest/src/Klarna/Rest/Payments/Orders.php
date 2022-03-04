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
 * File containing the Orders class.
 */

namespace Klarna\Rest\Payments;

use GuzzleHttp\Exception\RequestException;
use Klarna\Exceptions\NotApplicableException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\ConnectorInterface;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Payments order resource.
 *
 * @example docs/examples/PaymentsAPI/Orders/cancel_existing_authorization.php Cancel an existing authorization
 * @example docs/examples/PaymentsAPI/Orders/create_order.php Create a new order
 * @example docs/examples/PaymentsAPI/Orders/generate_customer_token.php Generate a consumer token
 */
class Orders extends Resource
{
    /**
     * {@inheritDoc}
     */
    const ID_FIELD = 'authorization_token';

    /**
     * {@inheritDoc}
     */
    public static $path = '/payments/v1/authorizations';

    /**
     * Constructs an order instance.
     *
     * @param ConnectorInterface $connector HTTP transport connector
     * @param string    $authorizationToken   Authorization Token
     */
    public function __construct(ConnectorInterface $connector, $authorizationToken)
    {
        parent::__construct($connector);

        $this->setLocation(self::$path . "/{$authorizationToken}");
        $this[static::ID_FIELD] = $authorizationToken;
    }

    /**
     * Not applicable.
     *
     * @throws NotApplicableException
     */
    public function fetch()
    {
        throw new NotApplicableException('Not applicable');
    }

    /**
     * Creates the resource.
     *
     * @param array $data Creation data
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the location header is missing
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return array Order data
     */
    public function create(array $data)
    {
        return $this->post($this->getLocation() . '/order', $data)
            ->expectSuccessfull()
            ->status('200')
            ->contentType('application/json')
            ->getJson();
    }

    /**
     * Cancels the authorization.
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return self
     */
    public function cancelAuthorization()
    {
        $this->delete($this->getLocation())
            ->expectSuccessfull()
            ->status('204');
        // ->contentType('application/json');
        // TODO: We cannot check the Content-type here because of an inconsistency
        // between service and documentation. The real Content-Type is
        // "application/octet-stream but not the "application/json" as in the docs.

        return $this;
    }

    /**
     * Generates consumer token.
     *
     * @param array $data Token data
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the location header is missing
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return array Token data
     */
    public function generateToken(array $data)
    {
        $response = $this->post($this->getLocation() . '/customer-token', $data)
            ->expectSuccessfull()
            ->status('200')
            ->contentType('application/json')
            ->getJson();

        return $response;
    }
}
