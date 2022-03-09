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
 * File containing the Order class.
 */

namespace Klarna\Rest\Checkout;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Checkout order resource.
 *
 * @example docs/examples/checkout/create_checkout.php Create the checkout order
 * @example docs/examples/checkout/create_checkout_attachment.php EMD attachment
 * @example docs/examples/checkout/fetch_checkout.php  Retrieve a checkout order
 * @example docs/examples/checkout/update_checkout.php Update a checkout order
 */
class Order extends Resource
{
    /**
     * {@inheritDoc}
     */
    const ID_FIELD = 'order_id';

    /**
     * {@inheritDoc}
     */
    public static $path = '/checkout/v3/orders';

    /**
     * Constructs an order instance.
     *
     * @param Connector $connector HTTP transport connector
     * @param string    $orderId   Order ID
     */
    public function __construct(Connector $connector, $orderId = null)
    {
        parent::__construct($connector);

        if ($orderId !== null) {
            $this->setLocation(self::$path . "/{$orderId}");
            $this[static::ID_FIELD] = $orderId;
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
        $url = $this->post(self::$path, $data)
            ->status('201')
            ->getLocation();

        $this->setLocation($url);

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
        $data = $this->post($this->getLocation(), $data)
            ->status('200')
            ->contentType('application/json')
            ->getJson();

        $this->exchangeArray($data);

        return $this;
    }
}
