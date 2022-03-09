<?php
namespace Braintree;

/**
 * Creates an instance of Dispute as returned from a transaction
 *
 *
 * @package    Braintree
 *
 * @property-read string $amount
 * @property-read \DateTime $createdAt
 * @property-read string $currencyIsoCode
 * @property-read string $disbursementDate
 * @property-read \Braintree\Dispute\EvidenceDetails $evidence
 * @property-read string $id
 * @property-read string $kind
 * @property-read string $merchantAccountId
 * @property-read string $originalDisputeId
 * @property-read string $processorComments
 * @property-read string $reason
 * @property-read string $reasonCode
 * @property-read string $reasonDescription
 * @property-read \DateTime $receivedDate
 * @property-read string $referenceNumber
 * @property-read \DateTime $replyByDate
 * @property-read string $status
 * @property-read \Braintree\Dispute\StatusHistoryDetails[] $statusHistory
 * @property-read \Braintree\Dispute\TransactionDetails $transaction
 * @property-read \Braintree\Dispute\TransactionDetails $transactionDetails
 * @property-read \DateTime $updatedAt
 */
class Dispute extends Base
{
    protected $_attributes = [];

    /* Dispute Status */
    const ACCEPTED = 'accepted';
    const DISPUTED = 'disputed';
    const EXPIRED = 'expired';
    const OPEN  = 'open';
    const WON  = 'won';
    const LOST = 'lost';

    /* deprecated; for backwards compatibilty */
    const Open  = 'open';

    /* Dispute Reason */
    const CANCELLED_RECURRING_TRANSACTION = "cancelled_recurring_transaction";
    const CREDIT_NOT_PROCESSED            = "credit_not_processed";
    const DUPLICATE                       = "duplicate";
    const FRAUD                           = "fraud";
    const GENERAL                         = "general";
    const INVALID_ACCOUNT                 = "invalid_account";
    const NOT_RECOGNIZED                  = "not_recognized";
    const PRODUCT_NOT_RECEIVED            = "product_not_received";
    const PRODUCT_UNSATISFACTORY          = "product_unsatisfactory";
    const TRANSACTION_AMOUNT_DIFFERS      = "transaction_amount_differs";
    const RETRIEVAL                       = "retrieval";

    /* Dispute Kind */
    const CHARGEBACK      = 'chargeback';
    const PRE_ARBITRATION = 'pre_arbitration';
    // RETRIEVAL for kind already defined under Dispute Reason

    protected function _initialize($disputeAttribs)
    {
        $this->_attributes = $disputeAttribs;

        if (isset($disputeAttribs['transaction'])) {
            $transactionDetails = new Dispute\TransactionDetails($disputeAttribs['transaction']);
            $this->_set('transactionDetails', $transactionDetails);
            $this->_set('transaction', $transactionDetails);
        }

        if (isset($disputeAttribs['evidence'])) {
            $evidenceArray = array_map(function($evidence) {
                return new Dispute\EvidenceDetails($evidence);
            }, $disputeAttribs['evidence']);
            $this->_set('evidence', $evidenceArray);
        }

        if (isset($disputeAttribs['statusHistory'])) {
            $statusHistoryArray = array_map(function($statusHistory) {
                return new Dispute\StatusHistoryDetails($statusHistory);
            }, $disputeAttribs['statusHistory']);
            $this->_set('statusHistory', $statusHistoryArray);
        }

        if (isset($disputeAttribs['transaction'])) {
            $this->_set('transaction',
                new Dispute\TransactionDetails($disputeAttribs['transaction'])
            );
        }
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
            'amount', 'reason', 'status',
            'replyByDate', 'receivedDate', 'currencyIsoCode'
            ];

        $displayAttributes = [];
        foreach ($display AS $attrib) {
            $displayAttributes[$attrib] = $this->$attrib;
        }
        return __CLASS__ . '[' .
                Util::attributesToString($displayAttributes) .']';
    }

    /**
     * Accepts a dispute, given a dispute ID
     *
     * @param string $id
     */
    public static function accept($id)
    {
        return Configuration::gateway()->dispute()->accept($id);
    }

    /**
     * Adds file evidence to a dispute, given a dispute ID and a document ID
     *
     * @param string $disputeId
     * @param string $documentIdOrRequest
     */
    public static function addFileEvidence($disputeId, $documentIdOrRequest)
    {
        return Configuration::gateway()->dispute()->addFileEvidence($disputeId, $documentIdOrRequest);
    }

    /**
     * Adds text evidence to a dispute, given a dispute ID and content
     *
     * @param string $id
     * @param string $contentOrRequest
     */
    public static function addTextEvidence($id, $contentOrRequest)
    {
        return Configuration::gateway()->dispute()->addTextEvidence($id, $contentOrRequest);
    }

    /**
     * Finalize a dispute, given a dispute ID
     *
     * @param string $id
     */
    public static function finalize($id)
    {
        return Configuration::gateway()->dispute()->finalize($id);
    }

    /**
     * Find a dispute, given a dispute ID
     *
     * @param string $id
     */
    public static function find($id)
    {
        return Configuration::gateway()->dispute()->find($id);
    }

    /**
     * Remove evidence from a dispute, given a dispute ID and evidence ID
     *
     * @param string $disputeId
     * @param string $evidenceId
     */
    public static function removeEvidence($disputeId, $evidenceId)
    {
        return Configuration::gateway()->dispute()->removeEvidence($disputeId, $evidenceId);
    }

    /**
     * Search for Disputes, given a DisputeSearch query
     *
     * @param DisputeSearch $query
     */
    public static function search($query)
    {
        return Configuration::gateway()->dispute()->search($query);
    }
}
class_alias('Braintree\Dispute', 'Braintree_Dispute');
