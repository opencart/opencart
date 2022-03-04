<?php

namespace Braintree;

/**
 * Class for setting credit card verification search queries
 */
class CreditCardVerificationSearch
{
    /*
     * Create a new text node for id
     *
     * @return TextNode
     */
    public static function id()
    {
        return new TextNode('id');
    }

    // NEXT_MAJOR_VERSION can this just be cardholder name?
    /*
     * Create a new text node for credit card cardholder name
     *
     * @return TextNode
     */
    public static function creditCardCardholderName()
    {
        return new TextNode('credit_card_cardholder_name');
    }

    /*
     * Create a new text node for billing address details postal code
     *
     * @return TextNode
     */
    public static function billingAddressDetailsPostalCode()
    {
        return new TextNode('billing_address_details_postal_code');
    }

    /*
     * Create a new text node for customer email
     *
     * @return TextNode
     */
    public static function customerEmail()
    {
        return new TextNode('customer_email');
    }

    /*
     * Create a new text node for customer id
     *
     * @return TextNode
     */
    public static function customerId()
    {
        return new TextNode('customer_id');
    }

    /*
     * Create a new text node for payment method token
     *
     * @return TextNode
     */
    public static function paymentMethodToken()
    {
        return new TextNode('payment_method_token');
    }

    /*
     * Create a new equality node for credit card expiration date
     *
     * @return EqualityNode
     */
    public static function creditCardExpirationDate()
    {
        return new EqualityNode('credit_card_expiration_date');
    }

    /*
     * Create a new partial match node for credit card number
     *
     * @return PartialMatchNode
     */
    public static function creditCardNumber()
    {
        return new PartialMatchNode('credit_card_number');
    }

    /*
     * Create a new multiple value node for ids
     *
     * @return MultipleValueNode
     */
    public static function ids()
    {
        return new MultipleValueNode('ids');
    }

    /*
     * Create a new range node for created at
     *
     * @return RangeNode
     */
    public static function createdAt()
    {
        return new RangeNode("created_at");
    }

    //NEXT_MAJOR_VERSION can this just be card type?
    /*
     * Create a new multiple value node for credit card card type
     *
     * @return MultipleValueNode
     */
    public static function creditCardCardType()
    {
        return new MultipleValueNode("credit_card_card_type", CreditCard::allCardTypes());
    }

    /*
     * Create a new multiple value node for status
     *
     * @return MultipleValueNode
     */
    public static function status()
    {
        return new MultipleValueNode("status", Result\CreditCardVerification::allStatuses());
    }
}
