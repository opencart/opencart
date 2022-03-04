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
 * File containing the Payouts class.
 */

namespace Klarna\Rest\Settlements;

use GuzzleHttp\Exception\RequestException;
use Klarna\Exceptions\NotApplicableException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\ConnectorInterface;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Payouts resource.
 *
 * @example docs/examples/SettlementsAPI/Payouts/get_all_payouts.php Returns a collection of payouts
 * @example docs/examples/SettlementsAPI/Payouts/get_payout.php Returns a specific payout based on a payment reference
 * @example docs/examples/SettlementsAPI/Payouts/get_summary.php Returns a summary of payouts for each currency code
 */
class Payouts extends Resource
{
    /**
     * {@inheritDoc}
     */
    const ID_FIELD = 'payment_reference';

    /**
     * {@inheritDoc}
     */
    public static $path = '/settlements/v1/payouts';

    /**
     * Constructs Payouts instance.
     *
     * @param ConnectorInterface $connector HTTP transport connector
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
     * Returns a specific payout based on a given payment reference.
     *
     * @param string $paymentReference The reference id of the payout
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return array Payout data
     */
    public function getPayout($paymentReference)
    {
        return $this->get(self::$path . "/{$paymentReference}")
            ->expectSuccessfull()
            ->status('200')
            ->contentType('application/json')
            ->getJson();
    }

    /**
     * Returns a collection of payouts.
     *
     * @param array $params Additional query params to filter payouts.
     *
     * @see https://developers.klarna.com/api/#settlements-api-get-all-payouts
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return array Payouts data
     */
    public function getAllPayouts(array $params = [])
    {
        return $this->get(self::$path . '?' . http_build_query($params))
            ->expectSuccessfull()
            ->status('200')
            ->contentType('application/json')
            ->getJson();
    }

    /**
     * Returns a summary of payouts for each currency code in a date range.
     *
     * @param array $params Additional query params to filter summary data.
     *
     * @see https://developers.klarna.com/api/#settlements-api-get-summary-of-payouts
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return array summary of payouts
     */
    public function getSummary(array $params = [])
    {
        return $this->get(self::$path . '/summary?' . http_build_query($params))
            ->expectSuccessfull()
            ->status('200')
            ->contentType('application/json')
            ->getJson();
    }
}
