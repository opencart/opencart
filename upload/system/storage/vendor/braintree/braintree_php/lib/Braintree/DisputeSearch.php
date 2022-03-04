<?php

namespace Braintree;

/**
 * Class for running dispute searches
 */
class DisputeSearch
{
    /*
     * Create a new range node for amount disputed
     *
     * @return RangeNode
     */
    public static function amountDisputed()
    {
        return new RangeNode("amount_disputed");
    }

    /*
     * Create a new range node for amount won
     *
     * @return RangeNode
     */
    public static function amountWon()
    {
        return new RangeNode("amount_won");
    }

    /*
     * Create a new text node for case number
     *
     * @return TextNode
     */
    public static function caseNumber()
    {
        return new TextNode("case_number");
    }

    /*
     * Create a new text node for id
     *
     * @return TextNode
     */
    public static function id()
    {
        return new TextNode("id");
    }

    /*
     * Create a new text node for customer id
     *
     * @return TextNode
     */
    public static function customerId()
    {
        return new TextNode("customer_id");
    }

    /*
     * Create a new multiple value node for kind
     *
     * @return MultipleValueNode
     */
    public static function kind()
    {
        return new MultipleValueNode("kind");
    }

    /*
     * Create a new multiple value node for merchant account id
     *
     * @return MultipleValueNode
     */
    public static function merchantAccountId()
    {
        return new MultipleValueNode("merchant_account_id");
    }

    /*
     * Create a new multiple value node for reason
     *
     * @return MultipleValueNode
     */
    public static function reason()
    {
        return new MultipleValueNode("reason");
    }

    /*
     * Create a new multiple value node for reason code
     *
     * @return MultipleValueNode
     */
    public static function reasonCode()
    {
        return new MultipleValueNode("reason_code");
    }

    /*
     * Create a new range node for received date
     *
     * @return RangeNode
     */
    public static function receivedDate()
    {
        return new RangeNode("received_date");
    }

    /*
     * Create a new range node for disbursement date
     *
     * @return RangeNode
     */
    public static function disbursementDate()
    {
        return new RangeNode("disbursement_date");
    }

    /*
     * Create a new range node for effective date
     *
     * @return RangeNode
     */
    public static function effectiveDate()
    {
        return new RangeNode("effective_date");
    }

    /*
     * Create a new text node for reference number
     *
     * @return TextNode
     */
    public static function referenceNumber()
    {
        return new TextNode("reference_number");
    }

    /*
     * Create a new range node for reply by date
     *
     * @return RangeNode
     */
    public static function replyByDate()
    {
        return new RangeNode("reply_by_date");
    }

    /*
     * Create a new multiple value node for status
     *
     * @return MultipleValueNode
     */
    public static function status()
    {
        return new MultipleValueNode("status");
    }

    /*
     * Create a new multiple value node for chargeback protection level
     *
     * @return MultipleValueNode
     */
    public static function chargebackProtectionLevel()
    {
        return new MultipleValueNode("chargeback_protection_level", Dispute::allChargebackProtectionLevelTypes());
    }

    /*
     * Create a new text node for transaction id
     *
     * @return TextNode
     */
    public static function transactionId()
    {
        return new TextNode("transaction_id");
    }

    /*
     * Create a new multiple value node for transaction source
     *
     * @return MultipleValueNode
     */
    public static function transactionSource()
    {
        return new MultipleValueNode("transaction_source");
    }
}
