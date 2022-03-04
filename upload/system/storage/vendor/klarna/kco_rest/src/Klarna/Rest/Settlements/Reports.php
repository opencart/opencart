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
 * File containing the Reports class.
 */

namespace Klarna\Rest\Settlements;

use GuzzleHttp\Exception\RequestException;
use Klarna\Exceptions\NotApplicableException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\ConnectorInterface;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Reports resource.
 *
 * @example docs/examples/SettlementsAPI/Reports/payout_report.php Get payout report with transactions
 * @example docs/examples/SettlementsAPI/Reports/summary_report.php Get payouts summary report with transactions
 */
class Reports extends Resource
{
    /**
     * {@inheritDoc}
     */
    public static $path = '/settlements/v1/reports';

    /**
     * Constructs a Reports instance.
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
     * Returns CSV payout report
     *
     * @param string $paymentReference The reference id of the payout.
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return string CSV Payout report
     */
    public function getCSVPayoutReport($paymentReference)
    {
        return $this->get(self::$path . "/payout-with-transactions?payment_reference={$paymentReference}")
            ->expectSuccessfull()
            ->status('200')
            ->contentType('text/csv')
            ->getBody();
    }

    /**
     * Returns a single settlement summed up in pdf format.
     *
     * @param string $paymentReference The reference id of the payout.
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return string Binary PDF representation of Payout report
     */
    public function getPDFPayoutReport($paymentReference)
    {
        return $this->get(self::$path . "/payout?payment_reference={$paymentReference}")
            ->expectSuccessfull()
            ->status('200')
            ->contentType('application/pdf')
            ->getBody();
    }

    /**
     * Returns CSV summary.
     *
     * @param array $params Additional query params to filter payouts.
     *
     * @see https://developers.klarna.com/api/#settlements-api-get-payouts-summary-report-with-transactions
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return string CSV Summary report
     */
    public function getCSVPayoutsSummaryReport(array $params = [])
    {
        return $this->get(self::$path . '/payouts-summary-with-transactions?' . http_build_query($params))
            ->expectSuccessfull()
            ->status('200')
            ->contentType('text/csv')
            ->getBody();
    }

    /**
     * Returns PDF summary.
     *
     * @param array $params Additional query params to filter payouts.
     *
     * @see https://developers.klarna.com/api/#settlements-api-get-payouts-summary-report-with-transactions
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return string PDF Summary report
     */
    public function getPDFPayoutsSummaryReport(array $params = [])
    {
        return $this->get(self::$path . '/payouts-summary?' . http_build_query($params))
            ->expectSuccessfull()
            ->status('200')
            ->contentType('application/pdf')
            ->getBody();
    }
}
