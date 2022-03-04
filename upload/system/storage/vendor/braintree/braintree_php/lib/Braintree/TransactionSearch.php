<?php

namespace Braintree;

/**
 * Class for setting transaction search queries
 */
class TransactionSearch
{
    /*
     * Create a new range node for amount
     *
     * @return RangeNode
     */
    public static function amount()
    {
        return new RangeNode("amount");
    }

    /*
     * Create a new range node for authorization expired at
     *
     * @return RangeNode
     */
    public static function authorizationExpiredAt()
    {
        return new RangeNode("authorizationExpiredAt");
    }

    /*
     * Create a new range node for authorization at
     *
     * @return RangeNode
     */
    public static function authorizedAt()
    {
        return new RangeNode("authorizedAt");
    }

    /*
     * Create a new text node for billing company
     *
     * @return TextNode
     */
    public static function billingCompany()
    {
        return new TextNode('billing_company');
    }

    /*
     * Create a new text node for billing country name
     *
     * @return TextNode
     */
    public static function billingCountryName()
    {
        return new TextNode('billing_country_name');
    }

    /*
     * Create a new text node for billing extended address
     *
     * @return TextNode
     */
    public static function billingExtendedAddress()
    {
        return new TextNode('billing_extended_address');
    }

    // NEXT_MAJOR_VERSION this should be changed to Given name. First name is US ethnocentric
    /*
     * Create a new text node for billing first or given name
     *
     * @return TextNode
     */
    public static function billingFirstName()
    {
        return new TextNode('billing_first_name');
    }

    // NEXT_MAJOR_VERSION this should be changed to Surname. Last name is US ethnocentric
    /*
     * Create a new text node for billing last or surname
     *
     * @return TextNode
     */
    public static function billingLastName()
    {
        return new TextNode('billing_last_name');
    }

    /*
     * Create a new text node for billing locality or city
     *
     * @return TextNode
     */
    public static function billingLocality()
    {
        return new TextNode('billing_locality');
    }

    /*
     * Create a new text node for billing postal code
     *
     * @return TextNode
     */
    public static function billingPostalCode()
    {
        return new TextNode('billing_postal_code');
    }

    /*
     * Create a new text node for billing region or state
     *
     * @return TextNode
     */
    public static function billingRegion()
    {
        return new TextNode('billing_region');
    }

    /*
     * Create a new text node for billing street address
     *
     * @return TextNode
     */
    public static function billingStreetAddress()
    {
        return new TextNode('billing_street_address');
    }

    /*
     * Create a new range node for created at
     *
     * @return RangeNode
     */
    public static function createdAt()
    {
        return new RangeNode("createdAt");
    }

    // NEXT_MAJOR_VERSION we may want to rename this to just cardholder name
    /*
     * Create a new text node for cardholder name
     *
     * @return TextNode
     */
    public static function creditCardCardholderName()
    {
        return new TextNode('credit_card_cardholderName');
    }

    /*
     * Create a new equality node for card expiration date
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
     * Create a new text node for card unigue identifier
     *
     * @return TextNode
     */
    public static function creditCardUniqueIdentifier()
    {
        return new TextNode('credit_card_unique_identifier');
    }

    /*
     * Create a new text node for currency
     *
     * @return TextNode
     */
    public static function currency()
    {
        return new TextNode('currency');
    }

    /*
     * Create a new text node for customer company
     *
     * @return TextNode
     */
    public static function customerCompany()
    {
        return new TextNode('customer_company');
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
     * Create a new text node for customer fax
     *
     * @return TextNode
     */
    public static function customerFax()
    {
        return new TextNode('customer_fax');
    }

    // NEXT_MAJOR_VERSION this should be changed to Given name. First name is US ethnocentric
    /*
     * Create a new text node for billing first or given name
     *
     * @return TextNode
     */
    public static function customerFirstName()
    {
        return new TextNode('customer_first_name');
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

    // NEXT_MAJOR_VERSION this should be changed to Surname. Last name is US ethnocentric
    /*
     * Create a new text node for billing last or surname
     *
     * @return TextNode
     */
    public static function customerLastName()
    {
        return new TextNode('customer_last_name');
    }

    /*
     * Create a new text node for customer phone
     *
     * @return TextNode
     */
    public static function customerPhone()
    {
        return new TextNode('customer_phone');
    }

    /*
     * Create a new text node for customer website
     *
     * @return TextNode
     */
    public static function customerWebsite()
    {
        return new TextNode('customer_website');
    }

    /*
     * Create a new range node for disbursement date
     *
     * @return RangeNode
     */
    public static function disbursementDate()
    {
        return new RangeNode("disbursementDate");
    }
    /*
     * Create a new range node for dispute date
     *
     * @return RangeNode
     */
    public static function disputeDate()
    {
        return new RangeNode("disputeDate");
    }
    /*
     * Create a new range node for failed at date
     *
     * @return RangeNode
     */
    public static function failedAt()
    {
        return new RangeNode("failedAt");
    }

    /*
     * Create a new range node for gateway rejected at date
     *
     * @return RangeNode
     */
    public static function gatewayRejectedAt()
    {
        return new RangeNode("gatewayRejectedAt");
    }

    /*
     * Create a new text node for id
     *
     * @return TextNode
     */
    public static function id()
    {
        return new TextNode('id');
    }

    /*
     * Create a multiple value node for customer ids
     *
     * @return MultipleValueNode
     */
    public static function ids()
    {
        return new MultipleValueNode('ids');
    }

    /*
     * Create a multiple value node for merchant account id
     *
     * @return MultipleValueNode
     */
    public static function merchantAccountId()
    {
        return new MultipleValueNode("merchant_account_id");
    }

    /*
     * Create a new text node for order id
     *
     * @return TextNode
     */
    public static function orderId()
    {
        return new TextNode('order_id');
    }

    /*
     * Create a multiple value node for payment instrument type
     *
     * @return MultipleValueNode
     */
    public static function paymentInstrumentType()
    {
        return new MultipleValueNode('paymentInstrumentType');
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
     * Create a new text node for paypal authorization id
     *
     * @return TextNode
     */
    public static function paypalAuthorizationId()
    {
        return new TextNode('paypal_authorization_id');
    }

    /*
     * Create a new text node for paypal payer email
     *
     * @return TextNode
     */
    public static function paypalPayerEmail()
    {
        return new TextNode('paypal_payer_email');
    }

    /*
     * Create a new text node for paypal payment id
     *
     * @return TextNode
     */
    public static function paypalPaymentId()
    {
        return new TextNode('paypal_payment_id');
    }

    /*
     * Create a new text node for processor authorization code
     *
     * @return TextNode
     */
    public static function processorAuthorizationCode()
    {
        return new TextNode('processor_authorization_code');
    }

    /*
     * Create a new range node for processor declined at
     *
     * @return RangeNode
     */
    public static function processorDeclinedAt()
    {
        return new RangeNode("processorDeclinedAt");
    }

    /*
     * Create a new key value node for refund
     *
     * @return KeyValueNode
     */
    public static function refund()
    {
        return new KeyValueNode("refund");
    }

    /*
     * Create a new range node for settled at
     *
     * @return RangeNode
     */
    public static function settledAt()
    {
        return new RangeNode("settledAt");
    }

    /*
     * Create a new text node for settlement batch id
     *
     * @return TextNode
     */
    public static function settlementBatchId()
    {
        return new TextNode('settlement_batch_id');
    }

    /*
     * Create a new text node for shipping company
     *
     * @return TextNode
     */
    public static function shippingCompany()
    {
        return new TextNode('shipping_company');
    }

    /*
     * Create a new text node for shipping country name
     *
     * @return TextNode
     */
    public static function shippingCountryName()
    {
        return new TextNode('shipping_country_name');
    }

    /*
     * Create a new text node for shipping extended address
     *
     * @return TextNode
     */
    public static function shippingExtendedAddress()
    {
        return new TextNode('shipping_extended_address');
    }

    // NEXT_MAJOR_VERSION this should be changed to Given name. First name is US ethnocentric
    /*
     * Create a new text node for shipping first or given name
     *
     * @return TextNode
     */
    public static function shippingFirstName()
    {
        return new TextNode('shipping_first_name');
    }

    // NEXT_MAJOR_VERSION this should be changed to Surname. Last name is US ethnocentric
    /*
     * Create a new text node for shipping last or surname
     *
     * @return TextNode
     */
    public static function shippingLastName()
    {
        return new TextNode('shipping_last_name');
    }

    /*
     * Create a new text node for shipping locality or city
     *
     * @return TextNode
     */
    public static function shippingLocality()
    {
        return new TextNode('shipping_locality');
    }

    /*
     * Create a new text node for shipping postal code
     *
     * @return TextNode
     */
    public static function shippingPostalCode()
    {
        return new TextNode('shipping_postal_code');
    }

    /*
     * Create a new text node for shipping region or state
     *
     * @return TextNode
     */
    public static function shippingRegion()
    {
        return new TextNode('shipping_region');
    }

    /*
     * Create a new text node for shipping street address
     *
     * @return TextNode
     */
    public static function shippingStreetAddress()
    {
        return new TextNode('shipping_street_address');
    }

    /*
     * Create a new range node for submitted for settlement at date
     *
     * @return RangeNode
     */
    public static function submittedForSettlementAt()
    {
        return new RangeNode("submittedForSettlementAt");
    }

    /*
     * Create a new text node for store id
     *
     * @return TextNode
     */
    public static function storeId()
    {
        return new TextNode('store_id');
    }

    /*
     * Create a new multiple value node for store ids
     *
     * @return MultipleValueNode
     */
    public static function storeIds()
    {
        return new MultipleValueNode('store_ids');
    }

    /*
     * Create a new multiple value node for user
     *
     * @return MultipleValueNode
     */
    public static function user()
    {
        return new MultipleValueNode('user');
    }

    /*
     * Create a new range node for submitted for voided at date
     *
     * @return RangeNode
     */
    public static function voidedAt()
    {
        return new RangeNode("voidedAt");
    }

    /*
     * Create a new multiple value node for created using
     *
     * @return MultipleValueNode
     */
    public static function createdUsing()
    {
        return new MultipleValueNode('created_using', [
            Transaction::FULL_INFORMATION,
            Transaction::TOKEN
        ]);
    }

    /*
     * Create a new multiple value node for credit card type
     *
     * @return MultipleValueNode
     */
    public static function creditCardCardType()
    {
        return new MultipleValueNode('credit_card_card_type', [
            CreditCard::AMEX,
            CreditCard::CARTE_BLANCHE,
            CreditCard::CHINA_UNION_PAY,
            CreditCard::DINERS_CLUB_INTERNATIONAL,
            CreditCard::DISCOVER,
            CreditCard::ELO,
            CreditCard::JCB,
            CreditCard::LASER,
            CreditCard::MAESTRO,
            CreditCard::MASTER_CARD,
            CreditCard::SOLO,
            CreditCard::SWITCH_TYPE,
            CreditCard::VISA,
            CreditCard::UNKNOWN
        ]);
    }

    /*
     * Create a new multiple value node for credit card customer location
     *
     * @return MultipleValueNode
     */
    public static function creditCardCustomerLocation()
    {
        return new MultipleValueNode('credit_card_customer_location', [
            CreditCard::INTERNATIONAL,
            CreditCard::US
        ]);
    }

    /*
     * Create a new multiple value node for source
     *
     * @return MultipleValueNode
     */
    public static function source()
    {
        return new MultipleValueNode('source', []);
    }

    /*
     * Create a new multiple value node for status
     *
     * @return MultipleValueNode
     */
    public static function status()
    {
        return new MultipleValueNode('status', [
            Transaction::AUTHORIZATION_EXPIRED,
            Transaction::AUTHORIZING,
            Transaction::AUTHORIZED,
            Transaction::GATEWAY_REJECTED,
            Transaction::FAILED,
            Transaction::PROCESSOR_DECLINED,
            Transaction::SETTLED,
            Transaction::SETTLING,
            Transaction::SUBMITTED_FOR_SETTLEMENT,
            Transaction::VOIDED,
            Transaction::SETTLEMENT_DECLINED,
            Transaction::SETTLEMENT_PENDING
        ]);
    }

    /*
     * Create a new multiple value node for type
     *
     * @return MultipleValueNode
     */
    public static function type()
    {
        return new MultipleValueNode('type', [
            Transaction::SALE,
            Transaction::CREDIT
        ]);
    }
}
