<?php
class Braintree_CreditCardVerificationSearch
{
    static function id() {
	    return new Braintree_TextNode('id');
    }

    static function creditCardCardholderName() {
	    return new Braintree_TextNode('credit_card_cardholder_name');
    }

    static function billingAddressDetailsPostalCode() {
        return new Braintree_TextNode('billing_address_details_postal_code');
    }

    static function customerEmail() {
        return new Braintree_TextNode('customer_email');
    }

    static function customerId() {
        return new Braintree_TextNode('customer_id');
    }

    static function paymentMethodToken(){
        return new Braintree_TextNode('payment_method_token');
    }

    static function creditCardExpirationDate() {
	    return new Braintree_EqualityNode('credit_card_expiration_date');
    }

    static function creditCardNumber() {
	    return new Braintree_PartialMatchNode('credit_card_number');
    }

    static function ids() {
        return new Braintree_MultipleValueNode('ids');
    }

    static function createdAt() {
	    return new Braintree_RangeNode("created_at");
    }

    static function creditCardCardType()
    {
        return new Braintree_MultipleValueNode("credit_card_card_type", Braintree_CreditCard::allCardTypes());
    }

    static function status()
    {
        return new Braintree_MultipleValueNode("status", Braintree_Result_CreditCardVerification::allStatuses());
    }
}
