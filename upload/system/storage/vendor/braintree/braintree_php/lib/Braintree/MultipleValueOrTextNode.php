<?php

namespace Braintree;

/**
 * Braintree MultipleValueOrTextNode
 * MultipleValueOrTextNode is an object that could be multiple values or a single string value returned from the Braintree API
 */
class MultipleValueOrTextNode extends MultipleValueNode
{
    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($name)
    {
        parent::__construct($name);
        $this->textNode = new TextNode($name);
    }

    /**
     * Sets the value of the object's "contains" key to a string of $value
     *
     * @param object $value to have its string value set in $this
     *
     * @return object
     */
    public function contains($value)
    {
        $this->textNode->contains($value);
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
        $this->textNode->endsWith($value);
        return $this;
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
        $this->textNode->is($value);
        return $this;
    }

    /**
     * Sets the value of the object's "is_not" key to a string of $value
     *
     * @param object $value to have its string value set in $this
     *
     * @return object
     */
    public function isNot($value)
    {
        $this->textNode->isNot($value);
        return $this;
    }

    /**
     * Sets the value of the object's "starts_with" key to a string of $value
     *
     * @param object $value to have its string value set in $this
     *
     * @return object
     */
    public function startsWith($value)
    {
        $this->textNode->startsWith($value);
        return $this;
    }

    /**
     * Merges searchTerms into the parent element's params
     *
     * @return array
     */
    public function toParam()
    {
        return array_merge(parent::toParam(), $this->textNode->toParam());
    }
}
