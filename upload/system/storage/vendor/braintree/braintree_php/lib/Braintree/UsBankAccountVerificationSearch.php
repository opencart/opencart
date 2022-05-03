<?php
namespace Braintree;

class UsBankAccountVerificationSearch
{
    public static function accountHolderName() {
        return new TextNode('account_holder_name');
    }

    public static function customerEmail() {
        return new TextNode('customer_email');
    }

    public static function customerId() {
        return new TextNode('customer_id');
    }

    public static function id() {
        return new TextNode('id');
    }

    public static function paymentMethodToken(){
        return new TextNode('payment_method_token');
    }

    public static function routingNumber() {
        return new TextNode('routiner_number');
    }

    public static function ids() {
        return new MultipleValueNode('ids');
    }

    public static function status()
    {
        return new MultipleValueNode(
            "status",
            Result\UsBankAccountVerification::allStatuses()
        );
    }

    public static function verificationMethod()
    {
        return new MultipleValueNode(
            "verification_method",
            Result\UsBankAccountVerification::allVerificationMethods()
        );
    }

    public static function createdAt() {
        return new RangeNode("created_at");
    }

    public static function accountType()
    {
        return new EqualityNode("account_type");
    }

    public static function accountNumber()
    {
        return new EndsWithNode("account_number");
    }
}
class_alias('Braintree\UsBankAccountVerificationSearch', 'Braintree_UsBankAccountVerificationSearch');
