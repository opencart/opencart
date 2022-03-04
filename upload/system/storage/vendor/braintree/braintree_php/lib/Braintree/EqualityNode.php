<?php

namespace Braintree;

/**
 * Braintree IsNode
 * IsNode is an object for search elements to the Braintree API
 */
class EqualityNode extends IsNode
{
    /**
     * Sets the value of the object's "is_not" key to a string of $value
     *
     * @param object $value to have its string value set in $this
     *
     * @return object
     */
    public function isNot($value)
    {
        $this->searchTerms['is_not'] = strval($value);
        return $this;
    }
}
