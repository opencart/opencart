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
 * File containing the Capture class.
 */

namespace Klarna\Rest\OrderManagement;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\ConnectorInterface;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Order Management: Capture resource.
 *
 * @example docs/examples/OrderManagementAPI/Captures/add_shipping_info.php
 * @example docs/examples/OrderManagementAPI/Captures/fetch_captures.php
 * @example docs/examples/OrderManagementAPI/Captures/trigger_send_out.php
 * @example docs/examples/OrderManagementAPI/Captures/update_customer_details.php
 */
class Capture extends Resource
{
    /**
     * {@inheritDoc}
     */
    const ID_FIELD = 'capture_id';

    /**
     * {@inheritDoc}
     */
    public static $path = '/captures';

    /**
     * Constructs a Capture instance.
     *
     * @param ConnectorInterface $connector HTTP transport connector
     * @param string    $orderUrl  Parent order resource url
     * @param string    $captureId Capture ID
     */
    public function __construct(ConnectorInterface $connector, $orderUrl, $captureId = null)
    {
        parent::__construct($connector);

        $url = $orderUrl . self::$path;
        if ($captureId !== null) {
            $url = "{$url}/{$captureId}";
            $this[static::ID_FIELD] = $captureId;
        }

        $this->setLocation($url);
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
        $url = $this->post($this->getLocation(), $data)
            ->expectSuccessfull()
            ->status('201')
            ->getLocation();

        $this->setLocation($url);

        return $this;
    }

    /**
     * Appends shipping information to the capture.
     *
     * @param array $data Shipping info data
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function addShippingInfo(array $data)
    {
        $this->post($this->getLocation() . '/shipping-info', $data)
            ->expectSuccessfull()
            ->status('204');

        return $this;
    }

    /**
     * Updates the customers details.
     *
     * @param array $data Customer details data
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function updateCustomerDetails(array $data)
    {
        $this->patch($this->getLocation() . '/customer-details', $data)
            ->expectSuccessfull()
            ->status('204');

        return $this;
    }

    /**
     * Trigger send outs for this capture.
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function triggerSendout()
    {
        $this->post($this->getLocation() . '/trigger-send-out')
            ->expectSuccessfull()
            ->status('204');

        return $this;
    }
}
