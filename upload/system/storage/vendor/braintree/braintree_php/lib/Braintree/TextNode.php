<?php

namespace Braintree;

/**
 * Braintree TextNode
 * TextNode is an object for text elements returned from the Braintree API
 */
class TextNode extends PartialMatchNode
{
    /**
     * Sets the value of the object's "contains" key to a string of $value
     *
     * @param object $value to have its string value set in $this
     *
     * @return object
     */
    public function contains($value)
    {
        $this->searchTerms["contains"] = strval($value);
        return $this;
    }
}
