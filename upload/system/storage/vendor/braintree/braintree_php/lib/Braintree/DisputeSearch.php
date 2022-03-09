<?php
namespace Braintree;

class DisputeSearch
{
    public static function amountDisputed() {
        return new RangeNode("amount_disputed");
    }

    public static function amountWon()
    {
        return new RangeNode("amount_won");
    }

    public static function caseNumber()
    {
        return new TextNode("case_number");
    }

    public static function id()
    {
        return new TextNode("id");
    }

    public static function customerId()
    {
        return new TextNode("customer_id");
    }

    public static function kind()
    {
        return new MultipleValueNode("kind");
    }

    public static function merchantAccountId()
    {
        return new MultipleValueNode("merchant_account_id");
    }

    public static function reason()
    {
        return new MultipleValueNode("reason");
    }

    public static function reasonCode()
    {
        return new MultipleValueNode("reason_code");
    }

    public static function receivedDate()
    {
        return new RangeNode("received_date");
    }

    public static function disbursementDate()
    {
        return new RangeNode("disbursement_date");
    }

    public static function effectiveDate()
    {
        return new RangeNode("effective_date");
    }

    public static function referenceNumber()
    {
        return new TextNode("reference_number");
    }

    public static function replyByDate()
    {
        return new RangeNode("reply_by_date");
    }

    public static function status()
    {
        return new MultipleValueNode("status");
    }

    public static function transactionId()
    {
        return new TextNode("transaction_id");
    }

    public static function transactionSource()
    {
        return new MultipleValueNode("transaction_source");
    }
}
class_alias('Braintree\DisputeSearch', 'Braintree_DisputeSearch');
