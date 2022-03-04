<?php

namespace Braintree;

/**
 * Braintree Customer module
 * Creates and manages Customers
 *
 * <b>== More information ==</b>
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/customer developer docs} for information on attributes
 */
class Customer extends Base
{
    /*
     * Static method redirecting to gateway class
     *
     * @see CustomerGateway::all()
     *
     * @return ResourceCollection
     */
    public static function all()
    {
        return Configuration::gateway()->customer()->all();
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param array $query containing request params
     * @param int[] $ids   containing customer IDs
     *
     * @see CustomerGateway::fetch()
     *
     * @return Customer|Customer[]
     */
    public static function fetch($query, $ids)
    {
        return Configuration::gateway()->customer()->fetch($query, $ids);
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param array $attribs containing request parameters
     *
     * @see CustomerGateway::create()
     *
     * @return Result\Successful|Result\Error
     */
    public static function create($attribs = [])
    {
        return Configuration::gateway()->customer()->create($attribs);
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param array $attribs of request parameters
     *
     * @see CustomerGateway::createNoValidate()
     *
     * @throws Exception\ValidationError
     *
     * @return Customer
     */
    public static function createNoValidate($attribs = [])
    {
        return Configuration::gateway()->customer()->createNoValidate($attribs);
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param string $id                  customer Id
     * @param string $associationFilterId association filter Id
     *
     * @see CustomerGateway::find()
     *
     * @throws Exception\NotFound
     *
     * @return Customer|boolean The customer object or false if the request fails.
     */
    public static function find($id, $associationFilterId = null)
    {
        return Configuration::gateway()->customer()->find($id, $associationFilterId);
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param integer $customerId         unique identifier
     * @param array   $transactionAttribs containing request parameters
     *
     * @see CustomerGateway::credit()
     *
     * @return Result\Successful|Result\Error
     */
    public static function credit($customerId, $transactionAttribs)
    {
        return Configuration::gateway()->customer()->credit($customerId, $transactionAttribs);
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param integer $customerId         unique identifier
     * @param array   $transactionAttribs containing request parameters
     *
     * @see CustomerGateway::creditNoValidate()
     *
     * @throws Exception\ValidationError
     *
     * @return Transaction
     */
    public static function creditNoValidate($customerId, $transactionAttribs)
    {
        return Configuration::gateway()->customer()->creditNoValidate($customerId, $transactionAttribs);
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param string $customerId unique identifier
     *
     * @see CustomerGateway::delete()
     *
     * @return Result\Successful
     */
    public static function delete($customerId)
    {
        return Configuration::gateway()->customer()->delete($customerId);
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param string $customerId         unique identifier
     * @param array  $transactionAttribs containing request parameters
     *
     * @see CustomerGateway::sale()
     *
     * @return Result\Successful|Result\Error
     */
    public static function sale($customerId, $transactionAttribs)
    {
        return Configuration::gateway()->customer()->sale($customerId, $transactionAttribs);
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param string $customerId         unique identifier
     * @param array  $transactionAttribs containing request parameters
     *
     * @see CustomerGateway::saleNoValidate()
     *
     * @throws Exception\ValidationsFailed
     *
     * @return Transaction
     */
    public static function saleNoValidate($customerId, $transactionAttribs)
    {
        return Configuration::gateway()->customer()->saleNoValidate($customerId, $transactionAttribs);
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param mixed $query search query
     *
     * @see CustomerGateway::search()
     *
     * @throws InvalidArgumentException
     *
     * @return ResourceCollection
     */
    public static function search($query)
    {
        return Configuration::gateway()->customer()->search($query);
    }

    /**
     * Static method redirecting to gateway class
     *
     * @param string $customerId to be updated
     * @param array  $attributes containing request params
     *
     * @see CustomerGateway::update()
     *
     * @return Result\Successful|Result\Error
     */
    public static function update($customerId, $attributes)
    {
        return Configuration::gateway()->customer()->update($customerId, $attributes);
    }

    /**
     * update a customer record, assuming validations will pass
     *
     * if calling this method in static context, customerId
     * is the 2nd attribute. customerId is not sent in object context.
     * returns a Customer object on success
     *
     * @param string $customerId unique identifier
     * @param array  $attributes request parameters
     *
     * @see CustomerGateway::updateNoValidate()
     *
     * @throws Exception\ValidationsFailed
     *
     * @return Customer
     */
    public static function updateNoValidate($customerId, $attributes)
    {
        return Configuration::gateway()->customer()->updateNoValidate($customerId, $attributes);
    }

    protected function _initialize($customerAttribs)
    {
        $this->_attributes = $customerAttribs;

        $addressArray = [];
        if (isset($customerAttribs['addresses'])) {
            foreach ($customerAttribs['addresses'] as $address) {
                $addressArray[] = Address::factory($address);
            }
        }
        $this->_set('addresses', $addressArray);

        $creditCardArray = [];
        if (isset($customerAttribs['creditCards'])) {
            foreach ($customerAttribs['creditCards'] as $creditCard) {
                $creditCardArray[] = CreditCard::factory($creditCard);
            }
        }
        $this->_set('creditCards', $creditCardArray);

        $paypalAccountArray = [];
        if (isset($customerAttribs['paypalAccounts'])) {
            foreach ($customerAttribs['paypalAccounts'] as $paypalAccount) {
                $paypalAccountArray[] = PayPalAccount::factory($paypalAccount);
            }
        }
        $this->_set('paypalAccounts', $paypalAccountArray);

        $applePayCardArray = [];
        if (isset($customerAttribs['applePayCards'])) {
            foreach ($customerAttribs['applePayCards'] as $applePayCard) {
                $applePayCardArray[] = ApplePayCard::factory($applePayCard);
            }
        }
        $this->_set('applePayCards', $applePayCardArray);

        $googlePayCardArray = [];
        if (isset($customerAttribs['androidPayCards'])) {
            foreach ($customerAttribs['androidPayCards'] as $googlePayCard) {
                $googlePayCardArray[] = GooglePayCard::factory($googlePayCard);
            }
        }
        $this->_set('googlePayCards', $googlePayCardArray);

        $venmoAccountArray = array();
        if (isset($customerAttribs['venmoAccounts'])) {
            foreach ($customerAttribs['venmoAccounts'] as $venmoAccount) {
                $venmoAccountArray[] = VenmoAccount::factory($venmoAccount);
            }
        }
        $this->_set('venmoAccounts', $venmoAccountArray);

        $visaCheckoutCardArray = [];
        if (isset($customerAttribs['visaCheckoutCards'])) {
            foreach ($customerAttribs['visaCheckoutCards'] as $visaCheckoutCard) {
                $visaCheckoutCardArray[] = VisaCheckoutCard::factory($visaCheckoutCard);
            }
        }
        $this->_set('visaCheckoutCards', $visaCheckoutCardArray);

        $samsungPayCardArray = [];
        if (isset($customerAttribs['samsungPayCards'])) {
            foreach ($customerAttribs['samsungPayCards'] as $samsungPayCard) {
                $samsungPayCardArray[] = SamsungPayCard::factory($samsungPayCard);
            }
        }
        $this->_set('samsungPayCards', $samsungPayCardArray);

        $usBankAccountArray = array();
        if (isset($customerAttribs['usBankAccounts'])) {
            foreach ($customerAttribs['usBankAccounts'] as $usBankAccount) {
                $usBankAccountArray[] = UsBankAccount::factory($usBankAccount);
            }
        }
        $this->_set('usBankAccounts', $usBankAccountArray);

        $this->_set('paymentMethods', array_merge(
            $this->creditCards,
            $this->paypalAccounts,
            $this->applePayCards,
            $this->googlePayCards,
            $this->venmoAccounts,
            $this->visaCheckoutCards,
            $this->samsungPayCards,
            $this->usBankAccounts
        ));

        $customFields = [];
        if (isset($customerAttribs['customFields'])) {
            $customFields = $customerAttribs['customFields'];
        }
        $this->_set('customFields', $customFields);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }

    /**
     * returns false if comparing object is not a Customer,
     * or is a Customer with a different id
     *
     * @param object $otherCust customer to compare against
     *
     * @return boolean
     */
    public function isEqual($otherCust)
    {
        return !($otherCust instanceof Customer) ? false : $this->id === $otherCust->id;
    }

    /**
     * returns the customer's default payment method
     *
     * @return CreditCard|PayPalAccount
     */
    public function defaultPaymentMethod()
    {
        $defaultPaymentMethods = array_filter($this->paymentMethods, 'Braintree\Customer::_defaultPaymentMethodFilter');
        return current($defaultPaymentMethods);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public static function _defaultPaymentMethodFilter($paymentMethod)
    {
        return $paymentMethod->isDefault();
    }

    protected $_attributes = [
        'addresses'      => '',
        'company'        => '',
        'creditCards'    => '',
        'email'          => '',
        'fax'            => '',
        'firstName'      => '',
        'id'             => '',
        'lastName'       => '',
        'phone'          => '',
        'taxIdentifiers' => '',
        'createdAt'      => '',
        'updatedAt'      => '',
        'website'        => '',
        ];

    /**
     * Creates an instance of a Customer from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return Customer
     */
    public static function factory($attributes)
    {
        $instance = new Customer();
        $instance->_initialize($attributes);
        return $instance;
    }
}
