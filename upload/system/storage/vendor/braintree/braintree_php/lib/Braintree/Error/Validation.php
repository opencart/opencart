<?php

namespace Braintree\Error;

use Braintree\Util;

/**
 * error object returned as part of a validation error collection
 * provides read-only access to $attribute, $code, and $message
 *
 * <b>== More information ==</b>
 *
 * // phpcs:ignore Generic.Files.LineLength
 * See our {@link https://developer.paypal.com/braintree/docs/reference/general/result-objects#error-results developer docs} for more information
 */
class Validation
{
    private $_attribute;
    private $_code;
    private $_message;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        $this->_initializeFromArray($attributes);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    private function _initializeFromArray($attributes)
    {
        foreach ($attributes as $name => $value) {
            $varName = "_$name";
            $this->$varName = Util::delimiterToCamelCase($value, '_');
        }
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __get($name)
    {
        $varName = "_$name";
        return isset($this->$varName) ? $this->$varName : null;
    }
}
