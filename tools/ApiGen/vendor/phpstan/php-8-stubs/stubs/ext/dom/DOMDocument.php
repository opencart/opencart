<?php 

class DOMDocument extends \DOMNode implements \DOMParentNode
{
    public function __construct(string $version = "1.0", string $encoding = "")
    {
    }
    /** @return DOMAttr|false */
    public function createAttribute(string $localName)
    {
    }
    /** @return DOMAttr|false */
    public function createAttributeNS(?string $namespace, string $qualifiedName)
    {
    }
    /** @return DOMCdataSection|false */
    public function createCDATASection(string $data)
    {
    }
    /**
     * @tentative-return-type
     * @return (DOMComment | false)
     */
    public function createComment(string $data)
    {
    }
    /**
     * @tentative-return-type
     * @return (DOMDocumentFragment | false)
     */
    public function createDocumentFragment()
    {
    }
    /** @return DOMElement|false */
    public function createElement(string $localName, string $value = "")
    {
    }
    /** @return DOMElement|false */
    public function createElementNS(?string $namespace, string $qualifiedName, string $value = "")
    {
    }
    /** @return DOMEntityReference|false */
    public function createEntityReference(string $name)
    {
    }
    /** @return DOMProcessingInstruction|false */
    public function createProcessingInstruction(string $target, string $data = "")
    {
    }
    /**
     * @tentative-return-type
     * @return (DOMText | false)
     */
    public function createTextNode(string $data)
    {
    }
    /**
     * @tentative-return-type
     * @return (DOMElement | null)
     */
    public function getElementById(string $elementId)
    {
    }
    /**
     * @tentative-return-type
     * @return DOMNodeList
     */
    public function getElementsByTagName(string $qualifiedName)
    {
    }
    /**
     * @tentative-return-type
     * @return DOMNodeList
     */
    public function getElementsByTagNameNS(?string $namespace, string $localName)
    {
    }
    /** @return DOMNode|false */
    public function importNode(DOMNode $node, bool $deep = false)
    {
    }
    /** @return DOMDocument|bool */
    public function load(string $filename, int $options = 0)
    {
    }
    /** @return DOMDocument|bool */
    public function loadXML(string $source, int $options = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function normalizeDocument()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function registerNodeClass(string $baseClass, ?string $extendedClass)
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function save(string $filename, int $options = 0)
    {
    }
    #ifdef LIBXML_HTML_ENABLED
    /** @return DOMDocument|bool */
    public function loadHTML(string $source, int $options = 0)
    {
    }
    /** @return DOMDocument|bool */
    public function loadHTMLFile(string $filename, int $options = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function saveHTML(?DOMNode $node = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function saveHTMLFile(string $filename)
    {
    }
    #endif
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function saveXML(?DOMNode $node = null, int $options = 0)
    {
    }
    #ifdef LIBXML_SCHEMAS_ENABLED
    /**
     * @tentative-return-type
     * @return bool
     */
    public function schemaValidate(string $filename, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function schemaValidateSource(string $source, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function relaxNGValidate(string $filename)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function relaxNGValidateSource(string $source)
    {
    }
    #endif
    /**
     * @tentative-return-type
     * @return bool
     */
    public function validate()
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function xinclude(int $options = 0)
    {
    }
    /** @return DOMNode|false */
    public function adoptNode(DOMNode $node)
    {
    }
    /** @param DOMNode|string $nodes */
    public function append(...$nodes) : void
    {
    }
    /** @param DOMNode|string $nodes */
    public function prepend(...$nodes) : void
    {
    }
}