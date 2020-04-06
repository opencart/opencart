<?php
/**
 * Braintree Customer module
 * Creates and manages Customers
 *
 * <b>== More information ==</b>
 *
 * For more detailed information on Customers, see {@link http://www.braintreepayments.com/gateway/customer-api http://www.braintreepaymentsolutions.com/gateway/customer-api}
 *
 * @package    Braintree
 * @category   Resources
 * @copyright  2014 Braintree, a division of PayPal, Inc.
 *
 * @property-read array  $addresses
 * @property-read array  $paymentMethods
 * @property-read string $company
 * @property-read string $createdAt
 * @property-read array  $creditCards
 * @property-read array  $paypalAccounts
 * @property-read array  $applePayCards
 * @property-read array  $coinbaseAccounts
 * @property-read array  $customFields custom fields passed with the request
 * @property-read string $email
 * @property-read string $fax
 * @property-read string $firstName
 * @property-read string $id
 * @property-read string $lastName
 * @property-read string $phone
 * @property-read string $updatedAt
 * @property-read string $website
 */
class Braintree_Customer extends Braintree_Base
{
    /**
     * 
     * @return Braintree_Customer[]
     */
    public static function all()
    {
        return Braintree_Configuration::gateway()->customer()->all();
    }

    /**
     * 
     * @param string $query
     * @param int[] $ids
     * @return Braintree_Customer|Braintree_Customer[]
     */
    public static function fetch($query, $ids)
    {
        return Braintree_Configuration::gateway()->customer()->fetch($query, $ids);
    }

    /**
     * 
     * @param array $attribs
     * @return Braintree_Customer
     */
    public static function create($attribs = array())
    {
        return Braintree_Configuration::gateway()->customer()->create($attribs);
    }

    /**
     * 
     * @param array $attribs
     * @return Braintree_Customer
     */
    public static function createNoValidate($attribs = array())
    {
        return Braintree_Configuration::gateway()->customer()->createNoValidate($attribs);
    }

    /**
     * @deprecated since version 2.3.0
     * @param string $queryString
     * @return Braintree_Result_Successful
     */
    public static function createFromTransparentRedirect($queryString)
    {
        return Braintree_Configuration::gateway()->customer()->createFromTransparentRedirect($queryString);
    }

    /**
     * @deprecated since version 2.3.0
     * @return string
     */
    public static function createCustomerUrl()
    {
        return Braintree_Configuration::gateway()->customer()->createCustomerUrl();
    }

    /**
     * 
     * @throws Braintree_Exception_NotFound
     * @param int $id
     * @return Braintree_Customer
     */
    public static function find($id)
    {
        return Braintree_Configuration::gateway()->customer()->find($id);
    }

    /**
     * 
     * @param int $customerId
     * @param array $transactionAttribs
     * @return Braintree_Result_Successful|Braintree_Result_Error
     */
    public static function credit($customerId, $transactionAttribs)
    {
        return Braintree_Configuration::gateway()->customer()->credit($customerId, $transactionAttribs);
    }

    /**
     * 
     * @throws Braintree_Exception_ValidationError
     * @param type $customerId
     * @param type $transactionAttribs
     * @return Braintree_Transaction
     */
    public static function creditNoValidate($customerId, $transactionAttribs)
    {
        return Braintree_Configuration::gateway()->customer()->creditNoValidate($customerId, $transactionAttribs);
    }

    /**
     * 
     * @throws Braintree_Exception on invalid id or non-200 http response code
     * @param int $customerId
     * @return Braintree_Result_Successful
     */
    public static function delete($customerId)
    {
        return Braintree_Configuration::gateway()->customer()->delete($customerId);
    }

    /**
     * 
     * @param int $customerId
     * @param array $transactionAttribs
     * @return Braintree_Transaction
     */
    public static function sale($customerId, $transactionAttribs)
    {
        return Braintree_Configuration::gateway()->customer()->sale($customerId, $transactionAttribs);
    }

    /**
     * 
     * @param int $customerId
     * @param array $transactionAttribs
     * @return Braintree_Transaction
     */    
    public static function saleNoValidate($customerId, $transactionAttribs)
    {
        return Braintree_Configuration::gateway()->customer()->saleNoValidate($customerId, $transactionAttribs);
    }

    /**
     * 
     * @throws InvalidArgumentException
     * @param string $query
     * @return Braintree_ResourceCollection
     */
    public static function search($query)
    {
        return Braintree_Configuration::gateway()->customer()->search($query);
    }

    /**
     * 
     * @throws Braintree_Exception_Unexpected
     * @param int $customerId
     * @param array $attributes
     * @return Braintree_Result_Successful|Braintree_Result_Error
     */
    public static function update($customerId, $attributes)
    {
        return Braintree_Configuration::gateway()->customer()->update($customerId, $attributes);
    }

    /**
     * 
     * @throws Braintree_Exception_Unexpected
     * @param int $customerId
     * @param array $attributes
     * @return Braintree_CustomerGateway
     */
    public static function updateNoValidate($customerId, $attributes)
    {
        return Braintree_Configuration::gateway()->customer()->updateNoValidate($customerId, $attributes);
    }

    /**
     * 
     * @deprecated since version 2.3.0
     * @return string
     */
    public static function updateCustomerUrl()
    {
        return Braintree_Configuration::gateway()->customer()->updateCustomerUrl();
    }

    /**
     * 
     * @deprecated since version 2.3.0
     * @param string $queryString
     * @return Braintree_Result_Successful|Braintree_Result_Error
     */
    public static function updateFromTransparentRedirect($queryString)
    {
        return Braintree_Configuration::gateway()->customer()->updateFromTransparentRedirect($queryString);
    }

    /* instance methods */

    /**
     * sets instance properties from an array of values
     *
     * @ignore
     * @access protected
     * @param array $customerAttribs array of customer data
     */
    protected function _initialize($customerAttribs)
    {
        // set the attributes
        $this->_attributes = $customerAttribs;

        // map each address into its own object
        $addressArray = array();
        if (isset($customerAttribs['addresses'])) {

            foreach ($customerAttribs['addresses'] AS $address) {
                $addressArray[] = Braintree_Address::factory($address);
            }
        }
        $this->_set('addresses', $addressArray);

        // map each creditCard into its own object
        $creditCardArray = array();
        if (isset($customerAttribs['creditCards'])) {
            foreach ($customerAttribs['creditCards'] AS $creditCard) {
                $creditCardArray[] = Braintree_CreditCard::factory($creditCard);
            }
        }
        $this->_set('creditCards', $creditCardArray);

        // map each coinbaseAccount into its own object
        $coinbaseAccountArray = array();
        if (isset($customerAttribs['coinbaseAccounts'])) {
            foreach ($customerAttribs['coinbaseAccounts'] AS $coinbaseAccount) {
                $coinbaseAccountArray[] = Braintree_CoinbaseAccount::factory($coinbaseAccount);
            }
        }
        $this->_set('coinbaseAccounts', $coinbaseAccountArray);

        // map each paypalAccount into its own object
        $paypalAccountArray = array();
        if (isset($customerAttribs['paypalAccounts'])) {
            foreach ($customerAttribs['paypalAccounts'] AS $paypalAccount) {
                $paypalAccountArray[] = Braintree_PayPalAccount::factory($paypalAccount);
            }
        }
        $this->_set('paypalAccounts', $paypalAccountArray);

        // map each applePayCard into its own object
        $applePayCardArray = array();
        if (isset($customerAttribs['applePayCards'])) {
            foreach ($customerAttribs['applePayCards'] AS $applePayCard) {
                $applePayCardArray[] = Braintree_ApplePayCard::factory($applePayCard);
            }
        }
        $this->_set('applePayCards', $applePayCardArray);

        // map each androidPayCard into its own object
        $androidPayCardArray = array();
        if (isset($customerAttribs['androidPayCards'])) {
            foreach ($customerAttribs['androidPayCards'] AS $androidPayCard) {
                $androidPayCardArray[] = Braintree_AndroidPayCard::factory($androidPayCard);
            }
        }
        $this->_set('androidPayCards', $androidPayCardArray);

        $this->_set('paymentMethods', array_merge($this->creditCards, $this->paypalAccounts, $this->applePayCards, $this->coinbaseAccounts, $this->androidPayCards));
    }

    /**
     * returns a string representation of the customer
     * @return string
     */
    public function  __toString()
    {
        return __CLASS__ . '[' .
                Braintree_Util::attributesToString($this->_attributes) .']';
    }

    /**
     * returns false if comparing object is not a Braintree_Customer,
     * or is a Braintree_Customer with a different id
     *
     * @param object $otherCust customer to compare against
     * @return boolean
     */
    public function isEqual($otherCust)
    {
        return !($otherCust instanceof Braintree_Customer) ? false : $this->id === $otherCust->id;
    }

    /**
     * returns an array containt all of the customer's payment methods
     *
     * @deprecated since version 3.1.0 - use the paymentMethods property directly
     *
     * @return array
     */
    public function paymentMethods()
    {
        return $this->paymentMethods;
    }

    /**
     * returns the customer's default payment method
     *
     * @return object Braintree_CreditCard or Braintree_PayPalAccount
     */
    public function defaultPaymentMethod()
    {
        $defaultPaymentMethods = array_filter($this->paymentMethods, 'Braintree_Customer::_defaultPaymentMethodFilter');
        return current($defaultPaymentMethods);
    }

    public static function _defaultPaymentMethodFilter($paymentMethod)
    {
        return $paymentMethod->isDefault();
    }

    /* private class properties  */

    /**
     * @access protected
     * @var array registry of customer data
     */
    protected $_attributes = array(
        'addresses'   => '',
        'company'     => '',
        'creditCards' => '',
        'email'       => '',
        'fax'         => '',
        'firstName'   => '',
        'id'          => '',
        'lastName'    => '',
        'phone'       => '',
        'createdAt'   => '',
        'updatedAt'   => '',
        'website'     => '',
        );

    /**
     *  factory method: returns an instance of Braintree_Customer
     *  to the requesting method, with populated properties
     *
     * @ignore
     * @param array $attributes
     * @return Braintree_Customer
     */
    public static function factory($attributes)
    {
        $instance = new Braintree_Customer();
        $instance->_initialize($attributes);
        return $instance;
    }
}
