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

namespace Klarna\Rest\Payments;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\ConnectorInterface;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Payments session resource.
 *
 * @example docs/examples/PaymentsAPI/Sessions/create_new_credit_session.php
 * @example docs/examples/PaymentsAPI/Sessions/read_credit_session.php
 * @example docs/examples/PaymentsAPI/Sessions/update_credit_session.php
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
    public static $path = '/payments/v1/sessions';

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
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the location header is missing
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function create(array $data)
    {
        $response = $this->post(self::$path, $data)
            ->expectSuccessfull()
            ->status('200')
            ->contentType('application/json');

        $this->exchangeArray($response->getJson());

        // Payments API does not send Location header after creating a new session.
        // Use workaround to set new location.
        $this->setLocation(self::$path . '/' . $this->getId());

        return $this;
    }

    /**
     * Updates the resource.
     *
     * @param array $data Update data
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
    public function update(array $data)
    {
        $this->post($this->getLocation(), $data)
            ->expectSuccessfull()
            ->status('204');
        // ->contentType('application/json');
        // TODO: We cannot check the Content-type here because of an inconsistency
        // between service and documentation. The real Content-Type is
        // "application/octet-stream but not the "application/json" as in the docs.

        return $this;
    }
}
