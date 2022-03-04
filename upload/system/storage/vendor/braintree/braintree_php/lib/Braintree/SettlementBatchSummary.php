<?php

namespace Braintree;

/**
 * The total sales and credits for each batch for a particular date.
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/settlement-batch-summary developer docs} for information on attributes
 */
class SettlementBatchSummary extends Base
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return SettlementBatchSummary
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    //phpcs:ignore Generic.Commenting
    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;
    }

    /**
     * Returns the value for "records"
     *
     * @return mixed records
     */
    public function records()
    {
        return $this->_attributes['records'];
    }


    /**
     * static method redirecting to gateway
     *
     * @param string $settlement_date    Date YYYY-MM-DD
     * @param string $groupByCustomField optional
     *
     * @see SettlementBatchSummaryGateway::generate()
     *
     * @return Result\Successful|Result\Error
     */
    public static function generate($settlement_date, $groupByCustomField = null)
    {
        return Configuration::gateway()->settlementBatchSummary()->generate($settlement_date, $groupByCustomField);
    }
}
