<?php

namespace Braintree;

/**
 * Braintree EndsWithNode
 * EndsWithNode is an object for search elements to the Braintree API
 */
class EndsWithNode
{
    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($name)
    {
        $this->name = $name;
        $this->searchTerms = [];
    }

    /*
     * sets search terms to include the value for key "ends_with"
     *
     * @param string $value to be sent
     *
     * @return self
     */
    public function endsWith($value)
    {
        $this->searchTerms["ends_with"] = strval($value);
        return $this;
    }

    /*
     * Returns params
     *
     * @return object
     */
    public function toParam()
    {
        return $this->searchTerms;
    }
}
