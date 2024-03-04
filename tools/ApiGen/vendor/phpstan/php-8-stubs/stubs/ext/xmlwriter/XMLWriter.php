<?php 

class XMLWriter
{
    /**
     * @tentative-return-type
     * @alias xmlwriter_open_uri
     * @no-verify Behaviour differs from the aliased function
     * @return bool
     */
    public function openUri(string $uri)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_open_memory
     * @no-verify Behaviour differs from the aliased function
     * @return bool
     */
    public function openMemory()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_set_indent
     * @return bool
     */
    public function setIndent(bool $enable)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_set_indent_string
     * @return bool
     */
    public function setIndentString(string $indentation)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_start_comment
     * @return bool
     */
    public function startComment()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_end_comment
     * @return bool
     */
    public function endComment()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_start_attribute
     * @return bool
     */
    public function startAttribute(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_end_attribute
     * @return bool
     */
    public function endAttribute()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_write_attribute
     * @return bool
     */
    public function writeAttribute(string $name, string $value)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_start_attribute_ns
     * @return bool
     */
    public function startAttributeNs(?string $prefix, string $name, ?string $namespace)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_write_attribute_ns
     * @return bool
     */
    public function writeAttributeNs(?string $prefix, string $name, ?string $namespace, string $value)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_start_element
     * @return bool
     */
    public function startElement(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_end_element
     * @return bool
     */
    public function endElement()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_full_end_element
     * @return bool
     */
    public function fullEndElement()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_start_element_ns
     * @return bool
     */
    public function startElementNs(?string $prefix, string $name, ?string $namespace)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_write_element
     * @return bool
     */
    public function writeElement(string $name, ?string $content = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_write_element_ns
     * @return bool
     */
    public function writeElementNs(?string $prefix, string $name, ?string $namespace, ?string $content = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_start_pi
     * @return bool
     */
    public function startPi(string $target)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_end_pi
     * @return bool
     */
    public function endPi()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_write_pi
     * @return bool
     */
    public function writePi(string $target, string $content)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_start_cdata
     * @return bool
     */
    public function startCdata()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_end_cdata
     * @return bool
     */
    public function endCdata()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_write_cdata
     * @return bool
     */
    public function writeCdata(string $content)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_text
     * @return bool
     */
    public function text(string $content)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_write_raw
     * @return bool
     */
    public function writeRaw(string $content)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_start_document
     * @return bool
     */
    public function startDocument(?string $version = "1.0", ?string $encoding = null, ?string $standalone = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_end_document
     * @return bool
     */
    public function endDocument()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_write_comment
     * @return bool
     */
    public function writeComment(string $content)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_start_dtd
     * @return bool
     */
    public function startDtd(string $qualifiedName, ?string $publicId = null, ?string $systemId = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_end_dtd
     * @return bool
     */
    public function endDtd()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_write_dtd
     * @return bool
     */
    public function writeDtd(string $name, ?string $publicId = null, ?string $systemId = null, ?string $content = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_start_dtd_element
     * @return bool
     */
    public function startDtdElement(string $qualifiedName)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_end_dtd_element
     * @return bool
     */
    public function endDtdElement()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_write_dtd_element
     * @return bool
     */
    public function writeDtdElement(string $name, string $content)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_start_dtd_attlist
     * @return bool
     */
    public function startDtdAttlist(string $name)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_end_dtd_attlist
     * @return bool
     */
    public function endDtdAttlist()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_write_dtd_attlist
     * @return bool
     */
    public function writeDtdAttlist(string $name, string $content)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_start_dtd_entity
     * @return bool
     */
    public function startDtdEntity(string $name, bool $isParam)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_end_dtd_entity
     * @return bool
     */
    public function endDtdEntity()
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_write_dtd_entity
     * @return bool
     */
    public function writeDtdEntity(string $name, string $content, bool $isParam = false, ?string $publicId = null, ?string $systemId = null, ?string $notationData = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_output_memory
     * @return string
     */
    public function outputMemory(bool $flush = true)
    {
    }
    /**
     * @tentative-return-type
     * @alias xmlwriter_flush
     * @return (string | int)
     */
    public function flush(bool $empty = true)
    {
    }
}