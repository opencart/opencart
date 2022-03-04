<?php

namespace Braintree;

/**
 * Braintree Address module
 * Creates and manages Braintree Addresses
 *
 * An Address belongs to a Customer. It can be associated to a
 * CreditCard as the billing address. It can also be used
 * as the shipping address when creating a Transaction.
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/address developer docs} for information on attributes
 */
class Address extends Base
{
    /**
     * returns false if comparing object is not a Address,
     * or is a Address with a different id
     *
     * @param object $other address to compare against
     *
     * @return boolean
     */
    public function isEqual($other)
    {
        return !($other instanceof self) ?
            false :
            ($this->id === $other->id && $this->customerId === $other->customerId);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    protected function _initialize($addressAttribs)
    {
        // set the attributes
        $this->_attributes = $addressAttribs;
    }

    /**
     * Creates an instance of an Address from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return Address
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    /**
     * static method redirecting to gateway class
     *
     * @param array $attribs containing request parameters
     *
     * @see AddressGateway::create()
     *
     * @return Result\Successful|Result\Error
     */
    public static function create($attribs)
    {
        return Configuration::gateway()->address()->create($attribs);
    }

    /**
     * static method redirecting to gateway class
     *
     * @param array $attribs containing request parameters
     *
     * @see AddressGateway::createNoValidate()
     *
     * @throws Exception\ValidationError
     *
     * @return Address
     */
    public static function createNoValidate($attribs)
    {
        return Configuration::gateway()->address()->createNoValidate($attribs);
    }

    /**
     * static method redirecting to gateway class
     *
     * @param mixed  $customerOrId either a customer object or string ID of customer
     * @param string $addressId    optional unique identifier
     *
     * @see AddressGateway::delete()
     *
     * @return Result\Successful|Result\Error
     */
    public static function delete($customerOrId = null, $addressId = null)
    {
        return Configuration::gateway()->address()->delete($customerOrId, $addressId);
    }

    /**
     * static method redirecting to gateway class
     *
     * @param mixed  $customerOrId either a customer object or string ID of customer
     * @param string $addressId    optional unique identifier
     *
     * @see AddressGateway::find()
     *
     * @throws Exception\NotFound
     *
     * @return Address
     */
    public static function find($customerOrId, $addressId)
    {
        return Configuration::gateway()->address()->find($customerOrId, $addressId);
    }

    /**
     * static method redirecting to gateway class
     *
     * @param mixed  $customerOrId (only used in call)
     * @param string $addressId    (only used in call)
     * @param array  $attributes   containing request parameters
     *
     * @see AddressGateway::update()
     *
     * @return Result\Successful|Result\Error
     */
    public static function update($customerOrId, $addressId, $attributes)
    {
        return Configuration::gateway()->address()->update($customerOrId, $addressId, $attributes);
    }

    /**
     * static method redirecting to gateway class
     *
     * @param mixed  $customerOrId (only used in call)
     * @param string $addressId    (only used in call)
     * @param array  $attributes   containing request parameters
     *
     * @see AddressGateway::updateNoValidate()
     *
     * @throws Exception\ValidationsFailed
     *
     * @return Address
     */
    public static function updateNoValidate($customerOrId, $addressId, $attributes)
    {
        return Configuration::gateway()->address()->updateNoValidate($customerOrId, $addressId, $attributes);
    }
}
