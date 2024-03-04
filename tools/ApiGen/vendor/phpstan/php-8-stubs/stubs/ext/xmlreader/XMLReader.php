<?php 

/** @generate-function-entries */
class XMLReader
{
    /** @return bool */
    public function close()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function getAttribute(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function getAttributeNo(int $index)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function getAttributeNs(string $name, string $namespace)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function getParserProperty(int $property)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function isValid()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function lookupNamespace(string $prefix)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function moveToAttribute(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function moveToAttributeNo(int $index)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function moveToAttributeNs(string $name, string $namespace)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function moveToElement()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function moveToFirstAttribute()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function moveToNextAttribute()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function read()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function next(?string $name = null)
    {
    }
    /** @return bool|XMLReader */
    public static function open(string $uri, ?string $encoding = null, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function readInnerXml()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function readOuterXml()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function readString()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setSchema(?string $filename)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setParserProperty(int $property, bool $value)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setRelaxNGSchema(?string $filename)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setRelaxNGSchemaSource(?string $source)
    {
    }
    /** @return bool|XMLReader */
    public static function XML(string $source, ?string $encoding = null, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return (DOMNode | false)
     */
    public function expand(?DOMNode $baseNode = null)
    {
    }
}