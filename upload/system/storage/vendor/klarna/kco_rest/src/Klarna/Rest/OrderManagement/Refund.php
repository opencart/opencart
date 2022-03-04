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
 * File containing the Refund class.
 */

namespace Klarna\Rest\OrderManagement;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\ConnectorInterface;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Order Management: Refund resource.
 *
 * @example docs/examples/OrderManagementAPI/Refunds/fetch_refund.php Read information on a refund
 * @example docs/examples/OrderManagementAPI/Refunds/refund_order.php Refund an amount of a captured order
 */
class Refund extends Resource
{
    /**
     * {@inheritDoc}
     */
    const ID_FIELD = 'refund_id';

    /**
     * {@inheritDoc}
     */
    public static $path = '/refunds';

    /**
     * Constructs a Refund instance.
     *
     * @param ConnectorInterface $connector HTTP transport connector
     * @param string    $orderUrl  Parent order resource url
     * @param string    $refundId  Refund ID
     */
    public function __construct(ConnectorInterface $connector, $orderUrl, $refundId = null)
    {
        parent::__construct($connector);

        $url = $orderUrl . self::$path;
        if ($refundId !== null) {
            $url = "{$url}/{$refundId}";
            $this[static::ID_FIELD] = $refundId;
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
}
