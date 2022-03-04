<?php

namespace Braintree;

/**
 * Braintree UsBankAccountVerificationSearch
 * UsBankAccountVerificationSearch is used in searching US Bank Account verifications (ACH)
 */
class UsBankAccountVerificationSearch
{
    /**
     * Sets account holder name in search terms
     *
     * @return TextNode
     */
    public static function accountHolderName()
    {
        return new TextNode('account_holder_name');
    }

    /**
     * Sets customer email in search terms
     *
     * @return TextNode
     */
    public static function customerEmail()
    {
        return new TextNode('customer_email');
    }

    /**
     * Sets customer Id in search terms
     *
     * @return TextNode
     */
    public static function customerId()
    {
        return new TextNode('customer_id');
    }

    /**
     * Sets Id in search terms
     *
     * @return TextNode
     */
    public static function id()
    {
        return new TextNode('id');
    }

    /**
     * Sets payment method token in search terms
     *
     * @return TextNode
     */
    public static function paymentMethodToken()
    {
        return new TextNode('payment_method_token');
    }

    /**
     * Sets routing number in search terms
     *
     * @return TextNode
     */
    public static function routingNumber()
    {
        return new TextNode('routiner_number');
    }

    /**
     * Sets Ids in search terms
     *
     * @return TextNode
     */
    public static function ids()
    {
        return new MultipleValueNode('ids');
    }

    /**
     * Sets US bank account verification statuses in search terms
     *
     * @return MultipleValueNode
     */
    public static function status()
    {
        return new MultipleValueNode(
            "status",
            Result\UsBankAccountVerification::allStatuses()
        );
    }

    /**
     * Sets US bank account verification methods in search terms
     *
     * @return MultipleValueNode
     */
    public static function verificationMethod()
    {
        return new MultipleValueNode(
            "verification_method",
            Result\UsBankAccountVerification::allVerificationMethods()
        );
    }

    /**
     * Sets created at date range in search terms
     *
     * @return RangeNode
     */
    public static function createdAt()
    {
        return new RangeNode("created_at");
    }

    /**
     * Sets account type in search terms
     *
     * @return EqualityNode
     */
    public static function accountType()
    {
        return new EqualityNode("account_type");
    }

    /**
     * Sets account number in search terms
     *
     * @return EndsWithNode
     */
    public static function accountNumber()
    {
        return new EndsWithNode("account_number");
    }
}
