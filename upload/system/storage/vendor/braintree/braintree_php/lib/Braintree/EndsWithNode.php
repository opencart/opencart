<?php
namespace Braintree;

class EndsWithNode
{
    public function __construct($name)
    {
        $this->name = $name;
        $this->searchTerms = [];
    }

    public function endsWith($value)
    {
        $this->searchTerms["ends_with"] = strval($value);
        return $this;
    }

    public function toParam()
    {
        return $this->searchTerms;
    }
}
class_alias('Braintree\EndsWithNode', 'Braintree_EndsWithNode');
