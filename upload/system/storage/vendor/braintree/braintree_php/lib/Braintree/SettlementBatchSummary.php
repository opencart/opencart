<?php
class Braintree_SettlementBatchSummary extends Braintree_Base
{
    /**
     * 
     * @param array $attributes
     * @return Braintree_SettlementBatchSummary
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    /**
     * @ignore
     * @param array $attributes
     */
    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;
    }

    public function records()
    {
        return $this->_attributes['records'];
    }


    /**
     * static method redirecting to gateway
     * 
     * @param string $settlement_date Date YYYY-MM-DD
     * @param string $groupByCustomField
     * @return Braintree_Result_Successful|Braintree_Result_Error
     */
    public static function generate($settlement_date, $groupByCustomField = NULL)
    {
        return Braintree_Configuration::gateway()->settlementBatchSummary()->generate($settlement_date, $groupByCustomField);
    }
}
