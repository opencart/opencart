<?php

namespace Braintree;

/**
 * Braintree Xml parser and generator
 * superclass for Braintree XML parsing and generation
 */
class Xml
{
    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    protected function __construct()
    {
    }

    /**
     * Create an array from XML element(s)
     *
     * @param string $xml elements(s)
     *
     * @return array
     */
    public static function buildArrayFromXml($xml)
    {
        return Xml\Parser::arrayFromXml($xml);
    }

    /**
     * Create an XML string from an Array object
     *
     * @param array $array object
     *
     * @return string
     */
    public static function buildXmlFromArray($array)
    {
        return Xml\Generator::arrayToXml($array);
    }
}
