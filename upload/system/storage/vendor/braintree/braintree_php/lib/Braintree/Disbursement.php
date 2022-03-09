<?php
namespace Braintree;

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
            $this->_set('merchantAccount',
                MerchantAccount::factory($disbursementAttribs['merchantAccount'])
            );
        }
    }

    public function transactions()
    {
        $collection = Transaction::search([
            TransactionSearch::ids()->in($this->transactionIds),
        ]);

        return $collection;
    }

    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    public function  __toString()
    {
        $display = [
            'id', 'merchantAccountDetails', 'exceptionMessage', 'amount',
            'disbursementDate', 'followUpAction', 'retry', 'success',
            'transactionIds', 'disbursementType'
            ];

        $displayAttributes = [];
        foreach ($display AS $attrib) {
            $displayAttributes[$attrib] = $this->$attrib;
        }
        return __CLASS__ . '[' .
                Util::attributesToString($displayAttributes) .']';
    }

    public function isDebit()
    {
        return $this->disbursementType == Disbursement::TYPE_DEBIT;
    }

    public function isCredit()
    {
        return $this->disbursementType == Disbursement::TYPE_CREDIT;
    }
}
class_alias('Braintree\Disbursement', 'Braintree_Disbursement');
