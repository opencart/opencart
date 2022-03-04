<?php

namespace Braintree\Result;

use Braintree\Base;
use Braintree\Transaction;
use Braintree\Subscription;
use Braintree\MerchantAccount;
use Braintree\Util;
use Braintree\Error\ErrorCollection;

/**
 * Braintree Error Result
 *
 * An Error Result will be returned from gateway methods when
 * the gateway responds with an error. It will provide access
 * to the original request.
 * For example, when voiding a transaction, Error Result will
 * respond to the void request if it failed:
 *
 * <code>
 * $result = Transaction::void('abc123');
 * if ($result->success) {
 *     // Successful Result
 * } else {
 *     // Result\Error
 * }
 * </code>
 *
 * @property-read array $params original passed params
 * @property-read \Braintree\Error\ErrorCollection $errors
 * @property-read \Braintree\Result\CreditCardVerification $creditCardVerification credit card verification data
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/general/result-objects developer docs} for more information
 */
class Error extends Base
{
    /**
    * used to determine whether an API request was successful or not
     *
    * @var boolean always false
    */
    public $success = false;

    /**
     * return original value for a field
     * For example, if a user tried to submit 'invalid-email' in the html field transaction[customer][email],
     * $result->valueForHtmlField("transaction[customer][email]") would yield "invalid-email"
     *
     * @param string $field to check submitted value
     *
     * @return string
     */
    public function valueForHtmlField($field)
    {
        $pieces = preg_split("/[\[\]]+/", $field, 0, PREG_SPLIT_NO_EMPTY);
        $params = $this->params;
        foreach (array_slice($pieces, 0, -1) as $key) {
            $params = $params[Util::delimiterToCamelCase($key)];
        }
        if ($key != 'custom_fields') {
            $finalKey = Util::delimiterToCamelCase(end($pieces));
        } else {
            $finalKey = end($pieces);
        }
        $fieldValue = isset($params[$finalKey]) ? $params[$finalKey] : null;
        return $fieldValue;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($response)
    {
        $this->_attributes = $response;
        $this->_set('errors', new ErrorCollection($response['errors']));

        if (isset($response['verification'])) {
            $this->_set('creditCardVerification', new CreditCardVerification($response['verification']));
        } else {
            $this->_set('creditCardVerification', null);
        }

        if (isset($response['transaction'])) {
            $this->_set('transaction', Transaction::factory($response['transaction']));
        } else {
            $this->_set('transaction', null);
        }

        if (isset($response['subscription'])) {
            $this->_set('subscription', Subscription::factory($response['subscription']));
        } else {
            $this->_set('subscription', null);
        }

        if (isset($response['merchantAccount'])) {
            $this->_set('merchantAccount', MerchantAccount::factory($response['merchantAccount']));
        } else {
            $this->_set('merchantAccount', null);
        }

        if (isset($response['verification'])) {
            $this->_set('verification', new CreditCardVerification($response['verification']));
        } else {
            $this->_set('verification', null);
        }
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        $output = Util::attributesToString($this->_attributes);
        if (isset($this->_creditCardVerification)) {
            $output .= sprintf('%s', $this->_creditCardVerification);
        }
        return __CLASS__ . '[' . $output . ']';
    }
}
