<?php

namespace Braintree;

/**
 * Braintree SettlementBatchSummaryGateway module
 * Creates and manages SettlementBatchSummarys
 */
class SettlementBatchSummaryGateway
{
    private $_gateway;
    private $_config;
    private $_http;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($gateway)
    {
        $this->_gateway = $gateway;
        $this->_config = $gateway->config;
        $this->_config->assertHasAccessTokenOrKeys();
        $this->_http = new Http($gateway->config);
    }

    /**
     * Create a Settlement Batch Summary report.
     *
     * @param string $settlement_date    A string representing the date of the settlement batch
     * @param string $groupByCustomField A string representing a transaction's custom field that you wish to group by
     *
     * @return SettlementBatchSummary|Result\Error
     */
    public function generate($settlement_date, $groupByCustomField = null)
    {
        $criteria = ['settlement_date' => $settlement_date];
        if (isset($groupByCustomField)) {
            $criteria['group_by_custom_field'] = $groupByCustomField;
        }
        $params = ['settlement_batch_summary' => $criteria];
        $path = $this->_config->merchantPath() . '/settlement_batch_summary';
        $response = $this->_http->post($path, $params);

        if (isset($groupByCustomField)) {
            $response['settlementBatchSummary']['records'] = $this->_underscoreCustomField(
                $groupByCustomField,
                $response['settlementBatchSummary']['records']
            );
        }

        return $this->_verifyGatewayResponse($response);
    }

    private function _underscoreCustomField($groupByCustomField, $records)
    {
        $updatedRecords = [];

        foreach ($records as $record) {
            $camelized = Util::delimiterToCamelCase($groupByCustomField);
            $record[$groupByCustomField] = $record[$camelized];
            unset($record[$camelized]);
            $updatedRecords[] = $record;
        }

        return $updatedRecords;
    }

    private function _verifyGatewayResponse($response)
    {
        if (isset($response['settlementBatchSummary'])) {
            return new Result\Successful(
                SettlementBatchSummary::factory($response['settlementBatchSummary'])
            );
        } elseif (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else {
            throw new Exception\Unexpected(
                "Expected settlementBatchSummary or apiErrorResponse"
            );
        }
    }
}
