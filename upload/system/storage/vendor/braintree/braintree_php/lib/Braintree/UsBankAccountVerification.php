<?php

namespace Braintree;

/**
 * Braintree UsBankAccountVerification module
 */

/**
 * Manages Braintree UsBankAccountVerifications
 *
 * <b>== More information ==</b>
 */
class UsBankAccountVerification extends Result\UsBankAccountVerification
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return UsBankAccountVerification
     */
    public static function factory($attributes)
    {
        $instance = new self($attributes);
        $instance->_initialize($attributes);
        return $instance;
    }

    /* instance methods */

    /**
     * sets instance properties from an array of values
     *
     * @param array $usBankAccountVerificationAttribs array of usBankAccountVerification data
     *
     * @return void
     */
    protected function _initialize($usBankAccountVerificationAttribs)
    {
        // set the attributes
        $this->_attributes = $usBankAccountVerificationAttribs;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' . Util::attributesToString($this->_attributes) . ']';
    }


    /**
     * Static methods redirecting to gateway class
     *
     * @param string $token unique id
     *
     * @see UsBankAccountVerificationGateway::find()
     *
     * @return UsBankAccountVerification
     */
    public static function find($token)
    {
        return Configuration::gateway()->usBankAccountVerification()->find($token);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param mixed $query search query
     *
     * @see UsBankAccountVerificationGateway::search()
     *
     * @return ResourceCollection
     */
    public static function search($query)
    {
        return Configuration::gateway()->usBankAccountVerification()->search($query);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $token   unique id
     * @param array  $amounts micro transfer amounts
     *
     * @see UsBankAccountVerificationGateway::confirmMicroTransferAmounts()
     *
     * @return ResourceCollection
     */
    public static function confirmMicroTransferAmounts($token, $amounts)
    {
        return Configuration::gateway()->usBankAccountVerification()->confirmMicroTransferAmounts($token, $amounts);
    }
}
