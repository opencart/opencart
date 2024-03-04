<?php 

class DOMNamedNodeMap implements \IteratorAggregate, \Countable
{
    /**
     * @tentative-return-type
     * @return (DOMNode | null)
     */
    public function getNamedItem(string $qualifiedName)
    {
    }
    /**
     * @tentative-return-type
     * @return (DOMNode | null)
     */
    public function getNamedItemNS(?string $namespace, string $localName)
    {
    }
    /**
     * @tentative-return-type
     * @return (DOMNode | null)
     */
    public function item(int $index)
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function count()
    {
    }
    public function getIterator() : Iterator
    {
    }
}