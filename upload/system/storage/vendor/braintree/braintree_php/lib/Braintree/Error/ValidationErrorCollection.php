<?php

namespace Braintree\Error;

use Braintree\Collection;

/**
 * collection of errors enumerating all validation errors for a given request
 *
 * <b>== More information ==</b>
 *
 * // phpcs:ignore Generic.Files.LineLength
 * See our {@link https://developer.paypal.com/braintree/docs/reference/general/result-objects#error-results developer docs} for information on attributes
 */
class ValidationErrorCollection extends Collection
{
    private $_errors = [];
    private $_nested = [];

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($data)
    {
        foreach ($data as $key => $errorData) {
            // map errors to new collections recursively
            if ($key == 'errors') {
                foreach ($errorData as $error) {
                    $this->_errors[] = new Validation($error);
                }
            } else {
                $this->_nested[$key] = new ValidationErrorCollection($errorData);
            }
        }
    }

    /*
     * Deeply retrieve all validation errors
     *
     * @return array
     */
    public function deepAll()
    {
        $validationErrors = array_merge([], $this->_errors);
        foreach ($this->_nested as $nestedErrors) {
            $validationErrors = array_merge($validationErrors, $nestedErrors->deepAll());
        }
        return $validationErrors;
    }

    /*
     * Deeply retrieve a count of errors
     *
     * @return int
     */
    public function deepSize()
    {
        $total = sizeof($this->_errors);
        foreach ($this->_nested as $_nestedErrors) {
            $total = $total + $_nestedErrors->deepSize();
        }
        return $total;
    }

    /*
     * Checks if index if a set variable
     *
     * @return bool
     */
    public function forIndex($index)
    {
        return $this->forKey("index" . $index);
    }

    /*
     * Checks if the value for a given key is a set variable
     *
     * @return bool
     */
    public function forKey($key)
    {
        return isset($this->_nested[$key]) ? $this->_nested[$key] : null;
    }

    /*
     * Returns any errors that match on a given attribute
     *
     * @param string $attribute to be checked for matching errors
     *
     * @return array
     */
    public function onAttribute($attribute)
    {
        $matches = [];
        foreach ($this->_errors as $key => $error) {
            if ($error->attribute == $attribute) {
                $matches[] = $error;
            }
        }
        return $matches;
    }

    /*
     * Get all errors
     *
     * @return object
     */
    public function shallowAll()
    {
        return $this->_errors;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __get($name)
    {
        $varName = "_$name";
        return isset($this->$varName) ? $this->$varName : null;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        $output = [];

        if (!empty($this->_errors)) {
            $output[] = $this->_inspect($this->_errors);
        }
        if (!empty($this->_nested)) {
            foreach ($this->_nested as $key => $values) {
                $output[] = $this->_inspect($this->_nested);
            }
        }
        return join(', ', $output);
    }

    private function _inspect($errors, $scope = null)
    {
        $eOutput = '[' . __CLASS__ . '/errors:[';
        $outputErrs = [];
        foreach ($errors as $error => $errorObj) {
            if (is_array($errorObj->error)) {
                $outputErrs[] = "({$errorObj->error['code']} {$errorObj->error['message']})";
            }
        }
        $eOutput .= join(', ', $outputErrs) . ']]';

        return $eOutput;
    }
}
