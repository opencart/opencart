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
 * File containing the Sessions class.
 */

namespace Klarna\Rest\HostedPaymentPage;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\ConnectorInterface;
use Klarna\Rest\Transport\Exception\ConnectorException;
use Klarna\Exceptions\NotApplicableException;

/**
 * HPP session resource.
 */
class Sessions extends Resource
{
    /**
     * {@inheritDoc}
     */
    const ID_FIELD = 'session_id';

    /**
     * {@inheritDoc}
     */
    public static $path = '/hpp/v1/sessions';

    /**
     * Constructs a session instance.
     *
     * @param ConnectorInterface $connector HTTP transport connector
     * @param string    $sessionId   Session ID
     */
    public function __construct(ConnectorInterface $connector, $sessionId = null)
    {
        parent::__construct($connector);

        if ($sessionId !== null) {
            $this->setLocation(self::$path . "/{$sessionId}");
            $this[static::ID_FIELD] = $sessionId;
        }
    }

    /**
     * Creates the resource.
     *
     * @param array $data Creation data
     *
     * @see https://developers.klarna.com/api/#hosted-payment-page-api-create-a-new-hpp-session
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the location header is missing
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return array Session data
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
     * Disables HPP session.
     *
     * @see https://developers.klarna.com/api/#hosted-payment-page-api-disable-hpp-session
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If sessionId was not specified when creating a resource
     * @throws \RuntimeException  If the location header is missing
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function disable()
    {
        if (empty($this[static::ID_FIELD])) {
            throw new \RuntimeException('HPP Session ID is not defined');
        }

        $this->delete($this->getLocation())
            ->expectSuccessfull()
            ->status('204');

        return $this;
    }

    /**
     * Distributes link to the HPP session.
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
     * @return self
     */
    public function distributeLink(array $data)
    {
        $this->post($this->getLocation() . '/distribution', $data)
            ->expectSuccessfull()
            ->status(['200', '201']);

        return $this;
    }

    /**
     * @deprecated HPP API no longer suppors getting the status. Use fetch (getSession) to fetch data;
     * @deprecated This method will be removed in the future versions of SDK.
     */
    public function getSessionStatus()
    {
        return $this->fetch();
    }
}
