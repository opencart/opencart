<?php
/**
 * Copyright 2019 Klarna AB
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
 * File containing the Instant Shopping ButtonKeys class.
 */

namespace Klarna\Rest\InstantShopping;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\ConnectorInterface;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Instant shopping ButtonKeys resource.
 */
class ButtonKeys extends Resource
{
    /**
     * {@inheritDoc}
     */
    const ID_FIELD = 'button_key';

    /**
     * {@inheritDoc}
     */
    public static $path = '/instantshopping/v1/buttons';

    /**
     * Constructs a ButtonKey instance.
     *
     * @param ConnectorInterface $connector HTTP transport connector
     * @param string    $buttonKey Button identifier
     * @param string    $key Button key based on setup options
     */
    public function __construct(ConnectorInterface $connector, $buttonKey = null)
    {
        parent::__construct($connector);

        if ($buttonKey !== null) {
            $this->setLocation(self::$path . "/{$buttonKey}");
            $this[static::ID_FIELD] = $buttonKey;
        }
    }

    /**
     * Creates a button key based on setup options.
     *
     * @param array $data Creation data
     *
     * @see https://developers.klarna.com/api/#instant-shopping-api-create-a-button-key-based-on-setup-options
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return array Button properties
     */
    public function create(array $data)
    {
        $response = $this->post(self::$path, $data)
            ->expectSuccessfull()
            ->status('201')
            ->contentType('application/json');

        $url = $response->getLocation();
        $this->setLocation($url);

        return $response->getJson();
    }

    /**
     * Updates the setup options for a specific button key.
     *
     * @param array $data Update data
     *
     * @see https://developers.klarna.com/api/#instant-shopping-api-update-the-setup-options-for-a-specific-button-key
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \RuntimeException  If key was not specified when creating a resource
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return array Button properties
     */
    public function update(array $data)
    {
        if (empty($this[static::ID_FIELD])) {
            throw new \RuntimeException(static::ID_FIELD . ' property is not defined');
        }

        return $this->put($this->getLocation(), $data)
            ->expectSuccessfull()
            ->status('200')
            ->contentType('application/json')
            ->getJson();
    }

    /**
     * See the setup options for a specific button key.
     *
     * @see https://developers.klarna.com/api/#instant-shopping-api-see-the-setup-options-for-a-specific-button-key
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         If key was not specified when creating a resource
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return self
     */
    public function retrieve()
    {
        if (empty($this[static::ID_FIELD])) {
            throw new \RuntimeException(static::ID_FIELD . ' property is not defined');
        }

        return $this->fetch();
    }
}
