<?php

class Braintree_SettlementBatchSummaryGateway
{
    /**
     *
     * @var Braintree_Gateway
     */
    private $_gateway;
    
    /**
     *
     * @var Braintree_Configuration
     */
    private $_config;
    
    /**
     *
     * @var Braintree_Http
     */
    private $_http;

    /**
     * 
     * @param Braintree_Gateway $gateway
     */
    public function __construct($gateway)
    {
        $this->_gateway = $gateway;
        $this->_config = $gateway->config;
        $this->_config->assertHasAccessTokenOrKeys();
        $this->_http = new Braintree_Http($gateway->config);
    }

    /**
     * 
     * @param string $settlement_date
     * @param string $groupByCustomField
     * @return Braintree_SettlementBatchSummary|Braintree_Result_Error
     */
    public function generate($settlement_date, $groupByCustomField = NULL)
    {
        $criteria = array('settlement_date' => $settlement_date);
        if (isset($groupByCustomField))
        {
            $criteria['group_by_custom_field'] = $groupByCustomField;
        }
        $params = array('settlement_batch_summary' => $criteria);
        $path = $this->_config->merchantPath() . '/settlement_batch_summary';
        $response = $this->_http->post($path, $params);

        if (isset($groupByCustomField))
        {
            $response['settlementBatchSummary']['records'] = $this->_underscoreCustomField(
                $groupByCustomField,
                $response['settlementBatchSummary']['records']
            );
        }

        return $this->_verifyGatewayResponse($response);
    }

    /**
     * 
     * @param string $groupByCustomField
     * @param array $records
     * @return array 
    */
    private function _underscoreCustomField($groupByCustomField, $records)
    {
        $updatedRecords = array();

        foreach ($records as $record)
        {
            $camelized = Braintree_Util::delimiterToCamelCase($groupByCustomField);
            $record[$groupByCustomField] = $record[$camelized];
            unset($record[$camelized]);
            $updatedRecords[] = $record;
        }

        return $updatedRecords;
    }

    /**
     * 
     * @param array $response
     * @return \Braintree_Result_Successful|\Braintree_Result_Error
     * @throws Braintree_Exception_Unexpected
     */
    private function _verifyGatewayResponse($response)
    {
        if (isset($response['settlementBatchSummary'])) {
            return new Braintree_Result_Successful(
                Braintree_SettlementBatchSummary::factory($response['settlementBatchSummary'])
            );
        } else if (isset($response['apiErrorResponse'])) {
            return new Braintree_Result_Error($response['apiErrorResponse']);
        } else {
            throw new Braintree_Exception_Unexpected(
                "Expected settlementBatchSummary or apiErrorResponse"
            );
        }
    }
}
