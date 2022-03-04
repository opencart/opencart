<?php

namespace Braintree;

/**
 * Disbursement class
 * Module used in parsing Webhooks
 */
class Disbursement extends Base
{
    const TYPE_CREDIT = "credit";
    const TYPE_DEBIT  = "debit";

    private $_merchantAccount;

    protected function _initialize($disbursementAttribs)
    {
        $this->_attributes = $disbursementAttribs;
        $this->merchantAccountDetails = $disbursementAttribs['merchantAccount'];

        if (isset($disbursementAttribs['merchantAccount'])) {
            $this->_set(
                'merchantAccount',
                MerchantAccount::factory($disbursementAttribs['merchantAccount'])
            );
        }
    }

    /*
     * Retrieve the transactions associated with a disbursement
     *
     * @return ResourceCollection
     */
    public function transactions()
    {
        $collection = Transaction::search([
            TransactionSearch::ids()->in($this->transactionIds),
        ]);

        return $collection;
    }

    /**
     * Creates an instance of a Disbursement from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return Disbursement
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        $display = [
            'id', 'merchantAccountDetails', 'exceptionMessage', 'amount',
            'disbursementDate', 'followUpAction', 'retry', 'success',
            'transactionIds', 'disbursementType'
            ];

        $displayAttributes = [];
        foreach ($display as $attrib) {
            $displayAttributes[$attrib] = $this->$attrib;
        }
        return __CLASS__ . '[' .
                Util::attributesToString($displayAttributes) . ']';
    }

    /*
     * Determines if a Disbursement is a debit
     *
     * @return bool
     */
    public function isDebit()
    {
        return $this->disbursementType == Disbursement::TYPE_DEBIT;
    }

    /*
     * Determines if a Disbursement is a credit
     *
     * @return bool
     */
    public function isCredit()
    {
        return $this->disbursementType == Disbursement::TYPE_CREDIT;
    }
}
