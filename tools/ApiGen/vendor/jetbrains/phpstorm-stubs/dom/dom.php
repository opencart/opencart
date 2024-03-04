<?php

// Start of dom v.20031129
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

/**
 * Gets a <b>DOMElement</b> object from a <b>SimpleXMLElement</b> object
 * @link https://php.net/manual/en/function.dom-import-simplexml.php
 * @param SimpleXMLElement $node <p>
 * The <b>SimpleXMLElement</b> node.
 * </p>
 * @return DOMElement|null The <b>DOMElement</b> node added or <b>NULL</b> if any errors occur.
 */
#[LanguageLevelTypeAware(['8.0' => 'DOMElement'], default: 'DOMElement|null')]
function dom_import_simplexml(object $node) {}

/**
 * Node is a <b>DOMElement</b>
 * @link https://php.net/manual/en/dom.constants.php
 */
define('XML_ELEMENT_NODE', 1);

/**
 * Node is a <b>DOMAttr</b>
 * @link https://php.net/manual/en/dom.constants.php
 */
define('XML_ATTRIBUTE_NODE', 2);

/**
 * Node is a <b>DOMText</b>
 * @link https://php.net/manual/en/dom.constants.php
 */
define('XML_TEXT_NODE', 3);

/**
 * Node is a <b>DOMCharacterData</b>
 * @link https://php.net/manual/en/dom.constants.php
 */
define('XML_CDATA_SECTION_NODE', 4);

/**
 * Node is a <b>DOMEntityReference</b>
 * @link https://php.net/manual/en/dom.constants.php
 */
define('XML_ENTITY_REF_NODE', 5);

/**
 * Node is a <b>DOMEntity</b>
 * @link https://php.net/manual/en/dom.constants.php
 */
define('XML_ENTITY_NODE', 6);

/**
 * Node is a <b>DOMProcessingInstruction</b>
 * @link https://php.net/manual/en/dom.constants.php
 */
define('XML_PI_NODE', 7);

/**
 * Node is a <b>DOMComment</b>
 * @link https://php.net/manual/en/dom.constants.php
 */
define('XML_COMMENT_NODE', 8);

/**
 * Node is a <b>DOMDocument</b>
 * @link https://php.net/manual/en/dom.constants.php
 */
define('XML_DOCUMENT_NODE', 9);

/**
 * Node is a <b>DOMDocumentType</b>
 * @link https://php.net/manual/en/dom.constants.php
 */
define('XML_DOCUMENT_TYPE_NODE', 10);

/**
 * Node is a <b>DOMDocumentFragment</b>
 * @link https://php.net/manual/en/dom.constants.php
 */
define('XML_DOCUMENT_FRAG_NODE', 11);

/**
 * Node is a <b>DOMNotation</b>
 * @link https://php.net/manual/en/dom.constants.php
 */
define('XML_NOTATION_NODE', 12);
define('XML_HTML_DOCUMENT_NODE', 13);
define('XML_DTD_NODE', 14);
define('XML_ELEMENT_DECL_NODE', 15);
define('XML_ATTRIBUTE_DECL_NODE', 16);
define('XML_ENTITY_DECL_NODE', 17);
define('XML_NAMESPACE_DECL_NODE', 18);
define('XML_LOCAL_NAMESPACE', 18);
define('XML_ATTRIBUTE_CDATA', 1);
define('XML_ATTRIBUTE_ID', 2);
define('XML_ATTRIBUTE_IDREF', 3);
define('XML_ATTRIBUTE_IDREFS', 4);
define('XML_ATTRIBUTE_ENTITY', 6);
define('XML_ATTRIBUTE_NMTOKEN', 7);
define('XML_ATTRIBUTE_NMTOKENS', 8);
define('XML_ATTRIBUTE_ENUMERATION', 9);
define('XML_ATTRIBUTE_NOTATION', 10);

/**
 * Error code not part of the DOM specification. Meant for PHP errors.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_PHP_ERR', 0);

/**
 * If index or size is negative, or greater than the allowed value.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_INDEX_SIZE_ERR', 1);

/**
 * If the specified range of text does not fit into a
 * <b>DOMString</b>.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOMSTRING_SIZE_ERR', 2);

/**
 * If any node is inserted somewhere it doesn't belong
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_HIERARCHY_REQUEST_ERR', 3);

/**
 * If a node is used in a different document than the one that created it.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_WRONG_DOCUMENT_ERR', 4);

/**
 * If an invalid or illegal character is specified, such as in a name.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_INVALID_CHARACTER_ERR', 5);

/**
 * If data is specified for a node which does not support data.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_NO_DATA_ALLOWED_ERR', 6);

/**
 * If an attempt is made to modify an object where modifications are not allowed.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_NO_MODIFICATION_ALLOWED_ERR', 7);

/**
 * If an attempt is made to reference a node in a context where it does not exist.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_NOT_FOUND_ERR', 8);

/**
 * If the implementation does not support the requested type of object or operation.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_NOT_SUPPORTED_ERR', 9);

/**
 * If an attempt is made to add an attribute that is already in use elsewhere.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_INUSE_ATTRIBUTE_ERR', 10);

/**
 * If an attempt is made to use an object that is not, or is no longer, usable.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_INVALID_STATE_ERR', 11);

/**
 * If an invalid or illegal string is specified.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_SYNTAX_ERR', 12);

/**
 * If an attempt is made to modify the type of the underlying object.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_INVALID_MODIFICATION_ERR', 13);

/**
 * If an attempt is made to create or change an object in a way which is
 * incorrect with regard to namespaces.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_NAMESPACE_ERR', 14);

/**
 * If a parameter or an operation is not supported by the underlying object.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_INVALID_ACCESS_ERR', 15);

/**
 * If a call to a method such as insertBefore or removeChild would make the Node
 * invalid with respect to "partial validity", this exception would be raised and
 * the operation would not be done.
 * @link https://php.net/manual/en/dom.constants.php
 */
define('DOM_VALIDATION_ERR', 16);

// End of dom v.20031129
