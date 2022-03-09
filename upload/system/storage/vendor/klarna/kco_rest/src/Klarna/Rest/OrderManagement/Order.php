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

namespace Klarna\Rest\OrderManagement;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Order management resource.
 *
 * @example docs/examples/order/acknowledge_order.php
 * @example docs/examples/order/cancel_order.php
 * @example docs/examples/order/extend_authorization_time.php
 * @example docs/examples/order/fetch_order.php
 * @example docs/examples/order/fetch_capture.php
 * @example docs/examples/order/refund_order.php
 * @example docs/examples/order/release_remaining_authorization.php
 * @example docs/examples/order/update_customer_details.php
 * @example docs/examples/order/update_merchant_references.php
 * @example docs/examples/order/update_order_lines.php
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
    public static $path = '/ordermanagement/v1/orders';

    /**
     * Constructs an order instance.
     *
     * @param Connector $connector HTTP transport connector
     * @param string    $orderId   Order ID
     */
    public function __construct(Connector $connector, $orderId)
    {
        parent::__construct($connector);

        $this->setLocation(self::$path . "/{$orderId}");
        $this[static::ID_FIELD] = $orderId;
    }

    /**
     * Fetches the order.
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
    public function fetch()
    {
        parent::fetch();

        // Convert captures data to Capture[]

        $captures = [];
        foreach ($this['captures'] as $capture) {
            $captureId = $capture[Capture::ID_FIELD];

            $object = new Capture(
                $this->connector,
                $this->getLocation(),
                $captureId
            );
            $object->exchangeArray($capture);

            $captures[] = $object;
        }

        $this['captures'] = $captures;

        return $this;
    }

    /**
     * Acknowledges the order.
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function acknowledge()
    {
        $this->post($this->getLocation() . '/acknowledge')
            ->status('204');

        return $this;
    }

    /**
     * Cancels this order.
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function cancel()
    {
        $this->post($this->getLocation() . '/cancel')
            ->status('204');

        return $this;
    }

    /**
     * Updates the authorization data.
     *
     * @param array $data Authorization data
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function updateAuthorization(array $data)
    {
        $this->patch($this->getLocation() . '/authorization', $data)
            ->status('204');

        return $this;
    }

    /**
     * Extends the authorization time.
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function extendAuthorizationTime()
    {
        $this->post($this->getLocation() . '/extend-authorization-time')
            ->status('204');

        return $this;
    }

    /**
     * Update the merchant references.
     *
     * @param array $data Merchant references
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function updateMerchantReferences(array $data)
    {
        $this->patch($this->getLocation() . '/merchant-references', $data)
            ->status('204');

        return $this;
    }

    /**
     * Updates the customer details.
     *
     * @param array $data Customer data
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
            ->status('204');

        return $this;
    }

    /**
     * Refunds an amount of a captured order.
     *
     * @param array $data Refund data
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function refund(array $data)
    {
        $this->post($this->getLocation() . '/refunds', $data)
            ->status(['201', '204']);

        return $this;
    }

    /**
     * Release the remaining authorization for an order.
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function releaseRemainingAuthorization()
    {
        $this->post($this->getLocation() . '/release-remaining-authorization')
            ->status('204');

        return $this;
    }

    /**
     * Capture all or part of an order.
     *
     * @param array $data Capture data
     *
     * @see Capture::create() For more information on how to create a capture
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the location header is missing
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return Capture
     */
    public function createCapture(array $data)
    {
        $capture = new Capture($this->connector, $this->getLocation());

        $capture->create($data);

        $this['captures'][] = $capture;

        return $capture;
    }

    /**
     * Fetches the specified capture.
     *
     * @param string $captureId Capture ID
     *
     * @see Capture::fetch() For more information on how to fetch a capture
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return Capture
     */
    public function fetchCapture($captureId)
    {
        if ($this->offsetExists('captures')) {
            foreach ($this['captures'] as $capture) {
                if ($capture->getId() !== $captureId) {
                    continue;
                }

                return $capture->fetch();
            }
        }

        $capture = new Capture($this->connector, $this->getLocation(), $captureId);
        $capture->fetch();

        $this['captures'][] = $capture;

        return $capture;
    }
}
