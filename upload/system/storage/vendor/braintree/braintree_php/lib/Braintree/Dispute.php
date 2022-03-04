<?php

namespace Braintree;

/**
 * Creates an instance of Dispute as returned from a transaction
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/dispute developer docs} for information on attributes
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

    /* Dispute ChargebackProtectionLevel */
    const EFFORTLESS      = 'effortless';
    const STANDARD        = 'standard';
    const NOT_PROTECTED   = 'not_protected';

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
            $evidenceArray = array_map(function ($evidence) {
                return new Dispute\EvidenceDetails($evidence);
            }, $disputeAttribs['evidence']);
            $this->_set('evidence', $evidenceArray);
        }

        if (isset($disputeAttribs['paypalMessages'])) {
            $paypalMessagesArray = array_map(function ($paypalMessages) {
                return new Dispute\PayPalMessageDetails($paypalMessages);
            }, $disputeAttribs['paypalMessages']);
            $this->_set('paypalMessages', $paypalMessagesArray);
        }

        if (isset($disputeAttribs['statusHistory'])) {
            $statusHistoryArray = array_map(function ($statusHistory) {
                return new Dispute\StatusHistoryDetails($statusHistory);
            }, $disputeAttribs['statusHistory']);
            $this->_set('statusHistory', $statusHistoryArray);
        }
    }

    /**
     * Creates an instance of a Dispute from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return Dispute
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
            'amount', 'reason', 'status',
            'replyByDate', 'receivedDate', 'currencyIsoCode'
            ];

        $displayAttributes = [];
        foreach ($display as $attrib) {
            $displayAttributes[$attrib] = $this->$attrib;
        }
        return __CLASS__ . '[' .
                Util::attributesToString($displayAttributes) . ']';
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $id unique identifier
     *
     * @see DisputeGateway::accept()
     *
     * @return Result\Successful|Result\Error
     */
    public static function accept($id)
    {
        return Configuration::gateway()->dispute()->accept($id);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string        $disputeId           unique identifier
     * @param string|object $documentIdOrRequest either a unique identifier string or request object
     *
     * @see DisputeGateway::addFileEvidence()
     *
     * @return Result\Successful|Result\Error
     */
    public static function addFileEvidence($disputeId, $documentIdOrRequest)
    {
        return Configuration::gateway()->dispute()->addFileEvidence($disputeId, $documentIdOrRequest);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string       $id               unique identifier
     * @param string|mixed $contentOrRequest If a string, $contentOrRequest is the text-based content
     *                                       for the dispute evidence.
     *                                       Alternatively, the second argument can also be an array containing:
     *                                       - string $content The text-based content for the dispute evidence, and
     *                                       - string $category The category for this piece of evidence
     *                                       Note: (optional) string $tag parameter is deprecated, use $category instead.
     *
     * @see DisputeGateway::addTextEvidence()
     *
     * @return Result\Successful|Result\Error
     */
    public static function addTextEvidence($id, $contentOrRequest)
    {
        return Configuration::gateway()->dispute()->addTextEvidence($id, $contentOrRequest);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $id unique identifier
     *
     * @see DisputeGateway::finalize()
     *
     * @return Result\Successful|Result\Error
     */
    public static function finalize($id)
    {
        return Configuration::gateway()->dispute()->finalize($id);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $id unique identifier
     *
     * @see DisputeGateway::find()
     *
     * @return Result\Successful|Result\Error
     */
    public static function find($id)
    {
        return Configuration::gateway()->dispute()->find($id);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $disputeId  unique identifier
     * @param string $evidenceId unique identifier
     *
     * @see DisputeGateway::removeEvidence()
     *
     * @return Result\Successful|Result\Error
     */
    public static function removeEvidence($disputeId, $evidenceId)
    {
        return Configuration::gateway()->dispute()->removeEvidence($disputeId, $evidenceId);
    }

    /*
     * Static methods redirecting to gateway class
     *
     * @param DisputeSearch $query
     *
     * @see DisputeGateway::search()
     *
     * @return ResourceCollection|Result\Error
     */
    public static function search($query)
    {
        return Configuration::gateway()->dispute()->search($query);
    }

    /*
     * Retrive all types of chargeback protection level types
     *
     * @return array
     */
    public static function allChargebackProtectionLevelTypes()
    {
        return [
            Dispute::EFFORTLESS,
            Dispute::STANDARD,
            Dispute::NOT_PROTECTED
        ];
    }
}
