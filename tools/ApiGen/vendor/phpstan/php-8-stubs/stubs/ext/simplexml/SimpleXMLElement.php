<?php 

class SimpleXMLElement implements \Stringable, \Countable, \RecursiveIterator
{
    #[\Until('8.1')]
    public function current()
    {
    }
    /**
     * @tentative-return-type
     * @return (array | null | false)
     */
    public function xpath(string $expression)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function registerXPathNamespace(string $prefix, string $namespace)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | bool)
     */
    public function asXML(?string $filename = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias SimpleXMLElement::asXML
     * @return (string | bool)
     */
    public function saveXML(?string $filename = null)
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public function getNamespaces(bool $recursive = false)
    {
    }
    /**
     * @tentative-return-type
     * @return (array | false)
     */
    public function getDocNamespaces(bool $recursive = false, bool $fromRoot = true)
    {
    }
    /**
     * @tentative-return-type
     * @return (SimpleXMLElement | null)
     */
    public function children(?string $namespaceOrPrefix = null, bool $isPrefix = false)
    {
    }
    /**
     * @tentative-return-type
     * @return (SimpleXMLElement | null)
     */
    public function attributes(?string $namespaceOrPrefix = null, bool $isPrefix = false)
    {
    }
    public function __construct(string $data, int $options = 0, bool $dataIsURL = false, string $namespaceOrPrefix = "", bool $isPrefix = false)
    {
    }
    /**
     * @tentative-return-type
     * @return (SimpleXMLElement | null)
     */
    public function addChild(string $qualifiedName, ?string $value = null, ?string $namespace = null)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function addAttribute(string $qualifiedName, string $value, ?string $namespace = null)
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getName()
    {
    }
    public function __toString() : string
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function count()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function rewind()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function valid()
    {
    }
    #[\Since('8.1')]
    public function current() : SimpleXMLElement
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function key()
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function next()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function hasChildren()
    {
    }
    /**
     * @tentative-return-type
     * @return (SimpleXMLElement | null)
     */
    public function getChildren()
    {
    }
}