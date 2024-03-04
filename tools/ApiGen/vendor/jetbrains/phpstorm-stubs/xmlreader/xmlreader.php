<?php

// Start of xmlreader v.0.2
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;

/**
 * The XMLReader extension is an XML Pull parser. The reader acts as a
 * cursor going forward on the document stream and stopping at each node
 * on the way.
 * @link https://php.net/manual/en/class.xmlreader.php
 *
 * @property-read int $attributeCount The number of attributes on the node
 * @property-read string $baseURI The base URI of the node
 * @property-read int $depth Depth of the node in the tree, starting at 0
 * @property-read bool $hasAttributes Indicates if node has attributes
 * @property-read bool $hasValue Indicates if node has a text value
 * @property-read bool $isDefault Indicates if attribute is defaulted from DTD
 * @property-read bool $isEmptyElement Indicates if node is an empty element tag
 * @property-read string $localName The local name of the node
 * @property-read string $name The qualified name of the node
 * @property-read string $namespaceURI The URI of the namespace associated with the node
 * @property-read int $nodeType The node type for the node
 * @property-read string $prefix The prefix of the namespace associated with the node
 * @property-read string $value The text value of the node
 * @property-read string $xmlLang The xml:lang scope which the node resides
 */
class XMLReader
{
    /**
     * No node type
     */
    public const NONE = 0;

    /**
     * Start element
     */
    public const ELEMENT = 1;

    /**
     * Attribute node
     */
    public const ATTRIBUTE = 2;

    /**
     * Text node
     */
    public const TEXT = 3;

    /**
     * CDATA node
     */
    public const CDATA = 4;

    /**
     * Entity Reference node
     */
    public const ENTITY_REF = 5;

    /**
     * Entity Declaration node
     */
    public const ENTITY = 6;

    /**
     * Processing Instruction node
     */
    public const PI = 7;

    /**
     * Comment node
     */
    public const COMMENT = 8;

    /**
     * Document node
     */
    public const DOC = 9;

    /**
     * Document Type node
     */
    public const DOC_TYPE = 10;

    /**
     * Document Fragment node
     */
    public const DOC_FRAGMENT = 11;

    /**
     * Notation node
     */
    public const NOTATION = 12;

    /**
     * Whitespace node
     */
    public const WHITESPACE = 13;

    /**
     * Significant Whitespace node
     */
    public const SIGNIFICANT_WHITESPACE = 14;

    /**
     * End Element
     */
    public const END_ELEMENT = 15;

    /**
     * End Entity
     */
    public const END_ENTITY = 16;

    /**
     * XML Declaration node
     */
    public const XML_DECLARATION = 17;

    /**
     * Load DTD but do not validate
     */
    public const LOADDTD = 1;

    /**
     * Load DTD and default attributes but do not validate
     */
    public const DEFAULTATTRS = 2;

    /**
     * Load DTD and validate while parsing
     */
    public const VALIDATE = 3;

    /**
     * Substitute entities and expand references
     */
    public const SUBST_ENTITIES = 4;

    /**
     * Close the XMLReader input
     * @link https://php.net/manual/en/xmlreader.close.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    public function close() {}

    /**
     * Get the value of a named attribute
     * @link https://php.net/manual/en/xmlreader.getattribute.php
     * @param string $name <p>
     * The name of the attribute.
     * </p>
     * @return string|null The value of the attribute, or <b>NULL</b> if no attribute with the given
     * <i>name</i> is found or not positioned on an element node.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getAttribute(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name): ?string {}

    /**
     * Get the value of an attribute by index
     * @link https://php.net/manual/en/xmlreader.getattributeno.php
     * @param int $index <p>
     * The position of the attribute.
     * </p>
     * @return string|null The value of the attribute, or <b>NULL</b> if no attribute exists
     * at <i>index</i> or not positioned of element.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getAttributeNo(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $index): ?string {}

    /**
     * Get the value of an attribute by localname and URI
     * @link https://php.net/manual/en/xmlreader.getattributens.php
     * @param string $name <p>
     * The local name.
     * </p>
     * @param string $namespace <p>
     * The namespace URI.
     * </p>
     * @return string|null The value of the attribute, or <b>NULL</b> if no attribute with the
     * given <i>localName</i> and
     * <i>namespaceURI</i> is found or not positioned of element.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getAttributeNs(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $namespace
    ): ?string {}

    /**
     * Indicates if specified property has been set
     * @link https://php.net/manual/en/xmlreader.getparserproperty.php
     * @param int $property <p>
     * One of the parser option
     * constants.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function getParserProperty(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $property): bool {}

    /**
     * Indicates if the parsed document is valid
     * @link https://php.net/manual/en/xmlreader.isvalid.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function isValid(): bool {}

    /**
     * Lookup namespace for a prefix
     * @link https://php.net/manual/en/xmlreader.lookupnamespace.php
     * @param string $prefix <p>
     * String containing the prefix.
     * </p>
     * @return string|null <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function lookupNamespace(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $prefix): ?string {}

    /**
     * Move cursor to an attribute by index
     * @link https://php.net/manual/en/xmlreader.movetoattributeno.php
     * @param int $index <p>
     * The position of the attribute.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function moveToAttributeNo(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $index): bool {}

    /**
     * Move cursor to a named attribute
     * @link https://php.net/manual/en/xmlreader.movetoattribute.php
     * @param string $name <p>
     * The name of the attribute.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function moveToAttribute(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name): bool {}

    /**
     * Move cursor to a named attribute
     * @link https://php.net/manual/en/xmlreader.movetoattributens.php
     * @param string $name <p>
     * The local name.
     * </p>
     * @param string $namespace <p>
     * The namespace URI.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function moveToAttributeNs(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $namespace
    ): bool {}

    /**
     * Position cursor on the parent Element of current Attribute
     * @link https://php.net/manual/en/xmlreader.movetoelement.php
     * @return bool <b>TRUE</b> if successful and <b>FALSE</b> if it fails or not positioned on
     * Attribute when this method is called.
     * @since 5.1.2
     */
    #[TentativeType]
    public function moveToElement(): bool {}

    /**
     * Position cursor on the first Attribute
     * @link https://php.net/manual/en/xmlreader.movetofirstattribute.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function moveToFirstAttribute(): bool {}

    /**
     * Position cursor on the next Attribute
     * @link https://php.net/manual/en/xmlreader.movetonextattribute.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function moveToNextAttribute(): bool {}

    /**
     * Set the URI containing the XML to parse
     * @link https://php.net/manual/en/xmlreader.open.php
     * @param string $uri <p>
     * URI pointing to the document.
     * </p>
     * @param string $encoding [optional] <p>
     * The document encoding or <b>NULL</b>.
     * </p>
     * @param int $flags [optional] <p>
     * A bitmask of the LIBXML_*
     * constants.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure. If called statically, returns an
     * <b>XMLReader</b> or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    public static function open(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $uri,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $encoding = null,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = 0
    ) {}

    /**
     * Move to next node in document
     * @link https://php.net/manual/en/xmlreader.read.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function read(): bool {}

    /**
     * Move cursor to next node skipping all subtrees
     * @link https://php.net/manual/en/xmlreader.next.php
     * @param string $name [optional] <p>
     * The name of the next node to move to.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function next(#[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $name = null): bool {}

    /**
     * Retrieve XML from current node
     * @link https://php.net/manual/en/xmlreader.readinnerxml.php
     * @return string the contents of the current node as a string. Empty string on failure.
     */
    #[TentativeType]
    public function readInnerXml(): string {}

    /**
     * Retrieve XML from current node, including it self
     * @link https://php.net/manual/en/xmlreader.readouterxml.php
     * @return string the contents of current node, including itself, as a string. Empty string on failure.
     */
    #[TentativeType]
    public function readOuterXml(): string {}

    /**
     * Reads the contents of the current node as a string
     * @link https://php.net/manual/en/xmlreader.readstring.php
     * @return string the content of the current node as a string. Empty string on
     * failure.
     */
    #[TentativeType]
    public function readString(): string {}

    /**
     * Validate document against XSD
     * @link https://php.net/manual/en/xmlreader.setschema.php
     * @param string $filename <p>
     * The filename of the XSD schema.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function setSchema(#[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $filename): bool {}

    /**
     * Set parser options
     * @link https://php.net/manual/en/xmlreader.setparserproperty.php
     * @param int $property <p>
     * One of the parser option
     * constants.
     * </p>
     * @param bool $value <p>
     * If set to <b>TRUE</b> the option will be enabled otherwise will
     * be disabled.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function setParserProperty(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $property,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $value
    ): bool {}

    /**
     * Set the filename or URI for a RelaxNG Schema
     * @link https://php.net/manual/en/xmlreader.setrelaxngschema.php
     * @param string $filename <p>
     * filename or URI pointing to a RelaxNG Schema.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function setRelaxNGSchema(#[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $filename): bool {}

    /**
     * Set the data containing a RelaxNG Schema
     * @link https://php.net/manual/en/xmlreader.setrelaxngschemasource.php
     * @param string $source <p>
     * String containing the RelaxNG Schema.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    #[TentativeType]
    public function setRelaxNGSchemaSource(#[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $source): bool {}

    /**
     * Set the data containing the XML to parse
     * @link https://php.net/manual/en/xmlreader.xml.php
     * @param string $source <p>
     * String containing the XML to be parsed.
     * </p>
     * @param string $encoding [optional] <p>
     * The document encoding or <b>NULL</b>.
     * </p>
     * @param int $flags [optional] <p>
     * A bitmask of the LIBXML_*
     * constants.
     * </p>
     * @return XMLReader|bool <b>TRUE</b> on success or <b>FALSE</b> on failure. If called statically, returns an
     * <b>XMLReader</b> or <b>FALSE</b> on failure.
     * @since 5.1.2
     */
    public static function XML(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $source,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $encoding = null,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = 0
    ) {}

    /**
     * Returns a copy of the current node as a DOM object
     * @link https://php.net/manual/en/xmlreader.expand.php
     * @param null|DOMNode $baseNode [optional]
     * @return DOMNode|false The resulting <b>DOMNode</b> or <b>FALSE</b> on error.
     * @since 5.1.2
     */
    #[TentativeType]
    public function expand(
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'DOMNode|null'], default: '')] $baseNode = null
    ): DOMNode|false {}
}
// End of xmlreader v.0.2
