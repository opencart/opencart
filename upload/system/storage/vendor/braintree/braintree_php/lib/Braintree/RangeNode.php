<?php

namespace Braintree;

/**
 * Braintree RangeNode
 * RangeNode is an object for numerical elements returned from the Braintree API
 */
class RangeNode
{
    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($name)
    {
        $this->name = $name;
        $this->searchTerms = [];
    }

    /**
     * Sets the "min" value for search terms.
     *
     * @param string $value to be set for search terms
     *
     * @return object
     */
    public function greaterThanOrEqualTo($value)
    {
        $this->searchTerms['min'] = $value;
        return $this;
    }

    /**
     * Sets the "mixn" value for search terms.
     *
     * @param string $value to be set for search terms
     *
     * @return object
     */
    public function lessThanOrEqualTo($value)
    {
        $this->searchTerms['max'] = $value;
        return $this;
    }

    /**
     * Sets the "is" value for search terms.
     *
     * @param string $value to be set for search terms
     *
     * @return object
     */
    public function is($value)
    {
        $this->searchTerms['is'] = $value;
        return $this;
    }

    /**
     * Sets the "min" and "max" value for search terms.
     *
     * @param string $min minimum value to be set for search terms
     * @param string $max maximum value to be set for search terms
     *
     * @return object
     */
    public function between($min, $max)
    {
        return $this->greaterThanOrEqualTo($min)->lessThanOrEqualTo($max);
    }

    /**
     * To be used as a parameter
     *
     * @return object search terms
     */
    public function toParam()
    {
        return $this->searchTerms;
    }
}
