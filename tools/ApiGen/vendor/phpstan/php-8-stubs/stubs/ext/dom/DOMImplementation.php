<?php 

class DOMImplementation
{
    /**
     * @tentative-return-type
     * @return void
     */
    public function getFeature(string $feature, string $version)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function hasFeature(string $feature, string $version)
    {
    }
    /** @return DOMDocumentType|false */
    public function createDocumentType(string $qualifiedName, string $publicId = "", string $systemId = "")
    {
    }
    /** @return DOMDocument|false */
    public function createDocument(?string $namespace = null, string $qualifiedName = "", ?DOMDocumentType $doctype = null)
    {
    }
}