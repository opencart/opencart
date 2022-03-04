<?php

namespace Braintree;

/**
 * Class for running customer searches
 */
class CustomerSearch
{
    /*
     * Create a new text node for address country name
     *
     * @return TextNode
     */
    public static function addressCountryName()
    {
        return new TextNode('address_country_name');
    }

    /*
     * Create a new text node for address extended address
     *
     * @return TextNode
     */
    public static function addressExtendedAddress()
    {
        return new TextNode('address_extended_address');
    }

    // NEXT_MAJOR_VERSION this should be changed to Given name. First name is US ethnocentric
    /*
     * Create a new text node for address first or given name
     *
     * @return TextNode
     */
    public static function addressFirstName()
    {
        return new TextNode('address_first_name');
    }

    // NEXT_MAJOR_VERSION this should be changed to Surname. Last name is US ethnocentric
    /*
     * Create a new text node for address last or surname
     *
     * @return TextNode
     */
    public static function addressLastName()
    {
        return new TextNode('address_last_name');
    }

    /*
     * Create a new text node for address locality or city
     *
     * @return TextNode
     */
    public static function addressLocality()
    {
        return new TextNode('address_locality');
    }

    /*
     * Create a new text node for address postal code
     *
     * @return TextNode
     */
    public static function addressPostalCode()
    {
        return new TextNode('address_postal_code');
    }

    /*
     * Create a new text node for address region or state
     *
     * @return TextNode
     */
    public static function addressRegion()
    {
        return new TextNode('address_region');
    }

    /*
     * Create a new text node for address street address
     *
     * @return TextNode
     */
    public static function addressStreetAddress()
    {
        return new TextNode('address_street_address');
    }

    /*
     * Create a new text node for cardholder name
     *
     * @return TextNode
     */
    public static function cardholderName()
    {
        return new TextNode('cardholder_name');
    }

    /*
     * Create a new text node for company
     *
     * @return TextNode
     */
    public static function company()
    {
        return new TextNode('company');
    }

    /*
     * Create a new text node for email
     *
     * @return TextNode
     */
    public static function email()
    {
        return new TextNode('email');
    }

    /*
     * Create a new text node for fax
     *
     * @return TextNode
     */
    public static function fax()
    {
        return new TextNode('fax');
    }

    // NEXT_MAJOR_VERSION this should be changed to Given name. First name is US ethnocentric
    /*
     * Create a new text node for customer first or given name
     *
     * @return TextNode
     */
    public static function firstName()
    {
        return new TextNode('first_name');
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

    // NEXT_MAJOR_VERSION this should be changed to Surname. Last name is US ethnocentric
    /*
     * Create a new text node for customer last or Surname
     *
     * @return TextNode
     */
    public static function lastName()
    {
        return new TextNode('last_name');
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
     * Create a new is node for payment method token with duplicate
     *
     * @return IsNode
     */
    public static function paymentMethodTokenWithDuplicates()
    {
        return new IsNode('payment_method_token_with_duplicates');
    }

    /*
     * Create a new is node for paypal account email
     *
     * @return IsNode
     */
    public static function paypalAccountEmail()
    {
        return new IsNode('paypal_account_email');
    }

    /*
     * Create a new text node for phone
     *
     * @return TextNode
     */
    public static function phone()
    {
        return new TextNode('phone');
    }

    /*
     * Create a new text node for website
     *
     * @return TextNode
     */
    public static function website()
    {
        return new TextNode('website');
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
     * Create a new equality node for credit card number
     *
     * @return EqualityNode
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
}
