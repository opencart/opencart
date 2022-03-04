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
 * File containing the Virtual Credit Card Settlements class.
 */

namespace Klarna\Rest\MerchantCardService;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\ConnectorInterface;
use Klarna\Rest\Transport\Exception\ConnectorException;
use Klarna\Exceptions\NotApplicableException;

/**
 * Virtual Credit Card Settlements resource.
 */
class VCCSettlements extends Resource
{
    /**
     * {@inheritDoc}
     */
    const ID_FIELD = 'settlement_id';

    /**
     * {@inheritDoc}
     */
    public static $path = '/merchantcard/v3/settlements';

    /**
     * Constructs a session instance.
     *
     * @param ConnectorInterface $connector HTTP transport connector
     * @param string    $sessionId   Session ID
     */
    public function __construct(ConnectorInterface $connector)
    {
        parent::__construct($connector);
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
     * Creates a new settlement.
     *
     * @param array $data Creation data
     *
     * @see https://developers.klarna.com/api/#merchant-card-service-api-create-a-new-settlement
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the location header is missing
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return array Settlement data
     */
    public function create(array $data)
    {
        $response = $this->post(self::$path, $data)
            ->expectSuccessfull()
            ->status('201')
            ->contentType('application/json')
            ->getJson();

        return $response;
    }

    /**
     * Retrieve an existing settlement.
     *
     * @see https://developers.klarna.com/api/#hosted-payment-page-api-distribute-link-to-the-hpp-session
     *
     * @param array $data Distribute data
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return array Settlement data
     */
    public function retrieveSettlement($settlementId, $keyId)
    {
        $response = $this->request(
            'GET',
            self::$path . "/$settlementId",
            ['KeyId' => $keyId]
        )
        ->expectSuccessfull()
        ->status('200')
        ->contentType('application/json')
        ->getJson();

        return $response;
    }

    /**
     * Retrieves a settled order's settlement.
     *
     * @see https://developers.klarna.com/api/#hosted-payment-page-api-distribute-link-to-the-hpp-session
     *
     * @param array $data Distribute data
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return array Order's settlement data
     */
    public function retrieveOrderSettlement($orderId, $keyId)
    {
        $response = $this->request(
            'GET',
            self::$path . "/order/$orderId",
            ['KeyId' => $keyId]
        )
        ->expectSuccessfull()
        ->status('200')
        ->contentType('application/json')
        ->getJson();

        return $response;
    }
}
