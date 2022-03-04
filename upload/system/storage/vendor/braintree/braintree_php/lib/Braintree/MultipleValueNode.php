<?php

namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree MultipleValueNode
 * MultipleValueNode is an object for elements with possible values returned from the Braintree API
 */
class MultipleValueNode
{
    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($name, $allowedValues = [])
    {
        $this->name = $name;
        $this->items = [];
        $this->allowedValues = $allowedValues;
    }

    /**
     * Sets the value of the object's items key to $values
     *
     * @param array $values to be set
     *
     * @throws InvalidArgumentException
     *
     * @return object
     */
    public function in($values)
    {
        $bad_values = array_diff($values, $this->allowedValues);
        if (count($this->allowedValues) > 0 && count($bad_values) > 0) {
            $message = 'Invalid argument(s) for ' . $this->name . ':';
            foreach ($bad_values as $bad_value) {
                $message .= ' ' . $bad_value;
            }

            throw new InvalidArgumentException($message);
        }

        $this->items = $values;
        return $this;
    }

    /**
     * Sets the value of the object's items key to [$value]
     *
     * @param object $value to be set
     *
     * @return object
     */
    public function is($value)
    {
        return $this->in([$value]);
    }

    /**
     * Retrieves items(params) from the object
     *
     * @return object
     */
    public function toParam()
    {
        return $this->items;
    }
}
