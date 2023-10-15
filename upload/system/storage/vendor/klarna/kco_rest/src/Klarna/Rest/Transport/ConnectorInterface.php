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
 * File containing the Connector interface.
 */

namespace Klarna\Rest\Transport;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * HTTP transport connector interface used to authenticate and make HTTP requests
 * against the Klarna APIs.
 *
 * The HTTP communication is handled by
 * {@link http://guzzle.readthedocs.org/en/guzzle4/ Guzzle}.
 */
interface ConnectorInterface
{
    /**
     * API base URL for Europe.
     */
    const EU_BASE_URL = 'https://api.klarna.com';

    /**
     * Testing API base URL for Europe.
     */
    const EU_TEST_BASE_URL = 'https://api.playground.klarna.com';

    /**
     * API base URL for North America.
     */
    const NA_BASE_URL = 'https://api-na.klarna.com';

    /**
     * Testing API base URL for North America.
     */
    const NA_TEST_BASE_URL = 'https://api-na.playground.klarna.com';

    /**
     * Creates a request object.
     *
     * @param string $url     URL
     * @param string $method  HTTP method
     * @param array  $options Request options
     *
     * @return RequestInterface
     */
    public function createRequest($url, $method = 'GET', array $options = []);

    /**
     * Sends the request.
     *
     * @param RequestInterface $request Request to send
     *
     * @throws ConnectorException If the API returned an error response
     * @throws RequestException   When an error is encountered
     * @throws \LogicException    When the adapter does not populate a response
     *
     * @return ResponseInterface
     */
    public function send(RequestInterface $request);

    /**
     * Gets the HTTP transport client.
     *
     * @return ClientInterface
     */
    public function getClient();

    /**
     * Gets the user agent.
     *
     * @return UserAgentInterface
     */
    public function getUserAgent();
}
