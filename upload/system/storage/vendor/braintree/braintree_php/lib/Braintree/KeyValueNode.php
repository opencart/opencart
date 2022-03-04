<?php

namespace Braintree;

/**
 * KeyValueNode class
 *
 * @see TransactionSearch refund
 */
class KeyValueNode
{
    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($name)
    {
        $this->name = $name;
        $this->searchTerm = true;
    }

    /*
     * Sets search term to a value
     *
     * @param string $value to be assigned
     *
     * @return object $this
     */
    public function is($value)
    {
        $this->searchTerm = $value;
        return $this;
    }

    /*
     * turns a search term into a param
     *
     * @return object searchTerm
     */
    public function toParam()
    {
        return $this->searchTerm;
    }
}
