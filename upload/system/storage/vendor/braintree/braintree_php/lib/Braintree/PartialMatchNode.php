<?php

namespace Braintree;

/**
 * Braintree PartialMatchNode module
 *
 * PartialMatchNode is an object for partially matching node elements returned from the Braintree API
 */
class PartialMatchNode extends EqualityNode
{
    /**
     * Sets the value of the object's "starts_with" key to a string of $value
     *
     * @param object $value to have its string value set in $this
     *
     * @return object
     */
    public function startsWith($value)
    {
        $this->searchTerms["starts_with"] = strval($value);
        return $this;
    }

    /**
     * Sets the value of the object's "ends_width" key to a string of $value
     *
     * @param object $value to have its string value set in $this
     *
     * @return object
     */
    public function endsWith($value)
    {
        $this->searchTerms["ends_with"] = strval($value);
        return $this;
    }
}
