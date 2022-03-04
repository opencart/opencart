<?php

namespace Braintree;

/**
 * Braintree IsNode
 * IsNode is an object for search elements sent to the Braintree API
 */
class IsNode
{
    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($name)
    {
        $this->name = $name;
        $this->searchTerms = [];
    }

    /**
     * Sets the value of the object's "is" key to a string of $value
     *
     * @param object $value to have its string value set in $this
     *
     * @return object
     */
    public function is($value)
    {
        $this->searchTerms['is'] = strval($value);

        return $this;
    }

    /**
     * The searchTerms
     *
     * @return array
     */
    public function toParam()
    {
        return $this->searchTerms;
    }
}
