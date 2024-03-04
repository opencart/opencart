<?php

//20120405 AG synced to official docs
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;
use JetBrains\PhpStorm\Language;

/**
 * The DOMNode class
 * @link https://php.net/manual/en/class.domnode.php
 */
class DOMNode
{
    /**
     * @var string
     * Returns the most accurate name for the current node type
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.nodename
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $nodeName;

    /**
     * @var string|null
     * The value of this node, depending on its type
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.nodevalue
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $nodeValue;

    /**
     * @var int
     * Gets the type of the node. One of the predefined
     * <a href="https://secure.php.net/manual/en/dom.constants.php">XML_xxx_NODE</a> constants
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.nodetype
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $nodeType;

    /**
     * @var DOMNode|null
     * The parent of this node. If there is no such node, this returns NULL.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.parentnode
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMNode|null'], default: '')]
    public $parentNode;

    /**
     * @var DOMNodeList
     * A <classname>DOMNodeList</classname> that contains all children of this node. If there are no children, this is an empty <classname>DOMNodeList</classname>.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.childnodes
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMNodeList'], default: '')]
    public $childNodes;

    /**
     * @var DOMNode|null
     * The first child of this node. If there is no such node, this returns NULL.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.firstchild
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMNode|null'], default: '')]
    public $firstChild;

    /**
     * @var DOMNode|null
     * The last child of this node. If there is no such node, this returns NULL.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.lastchild
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMNode|null'], default: '')]
    public $lastChild;

    /**
     * @var DOMNode|null
     * The node immediately preceding this node. If there is no such node, this returns NULL.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.previoussibling
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMNode|null'], default: '')]
    public $previousSibling;

    /**
     * @var DOMNode|null
     * The node immediately following this node. If there is no such node, this returns NULL.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.nextsibling
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMNode|null'], default: '')]
    public $nextSibling;

    /**
     * @var DOMNamedNodeMap|null
     * A <classname>DOMNamedNodeMap</classname> containing the attributes of this node (if it is a <classname>DOMElement</classname>) or NULL otherwise.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.attributes
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMNamedNodeMap|null'], default: '')]
    public $attributes;

    /**
     * @var DOMDocument|null
     * The <classname>DOMDocument</classname> object associated with this node, or NULL if this node is a <classname>DOMDocument</classname>.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.ownerdocument
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMDocument|null'], default: '')]
    public $ownerDocument;

    /**
     * @var string|null
     * The namespace URI of this node, or NULL if it is unspecified.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.namespaceuri
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $namespaceURI;

    /**
     * @var string|null
     * The namespace prefix of this node, or NULL if it is unspecified.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.prefix
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $prefix;

    /**
     * @var string|null
     * Returns the local part of the qualified name of this node.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.localname
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $localName;

    /**
     * @var string|null
     * The absolute base URI of this node or NULL if the implementation wasn't able to obtain an absolute URI.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.baseuri
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $baseURI;

    /**
     * @var string
     * This attribute returns the text content of this node and its descendants.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.textcontent
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $textContent;

    /**
     * Adds a new child before a reference node
     * @link https://php.net/manual/en/domnode.insertbefore.php
     * @param DOMNode $node <p>
     * The new node.
     * </p>
     * @param null|DOMNode $child [optional] <p>
     * The reference node. If not supplied, newnode is
     * appended to the children.
     * </p>
     * @return DOMNode The inserted node.
     */
    public function insertBefore(
        DOMNode $node,
        #[LanguageLevelTypeAware(['8.0' => 'DOMNode|null'], default: 'DOMNode')] $child = null
    ) {}

    /**
     * Replaces a child
     * @link https://php.net/manual/en/domnode.replacechild.php
     * @param DOMNode $node <p>
     * The new node. It must be a member of the target document, i.e.
     * created by one of the DOMDocument->createXXX() methods or imported in
     * the document by .
     * </p>
     * @param DOMNode $child <p>
     * The old node.
     * </p>
     * @return DOMNode|false The old node or false if an error occur.
     */
    public function replaceChild(DOMNode $node, DOMNode $child) {}

    /**
     * Removes child from list of children
     * @link https://php.net/manual/en/domnode.removechild.php
     * @param DOMNode $child <p>
     * The removed child.
     * </p>
     * @return DOMNode If the child could be removed the functions returns the old child.
     */
    public function removeChild(DOMNode $child) {}

    /**
     * Adds new child at the end of the children
     * @link https://php.net/manual/en/domnode.appendchild.php
     * @param DOMNode $node <p>
     * The appended child.
     * </p>
     * @return DOMNode The node added.
     */
    public function appendChild(DOMNode $node) {}

    /**
     * Checks if node has children
     * @link https://php.net/manual/en/domnode.haschildnodes.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function hasChildNodes(): bool {}

    /**
     * Clones a node
     * @link https://php.net/manual/en/domnode.clonenode.php
     * @param bool $deep <p>
     * Indicates whether to copy all descendant nodes. This parameter is
     * defaulted to false.
     * </p>
     * @return static The cloned node.
     */
    public function cloneNode(
        #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] $deep,
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $deep = false
    ) {}

    /**
     * Normalizes the node
     * @link https://php.net/manual/en/domnode.normalize.php
     * @return void
     */
    #[TentativeType]
    public function normalize(): void {}

    /**
     * Checks if feature is supported for specified version
     * @link https://php.net/manual/en/domnode.issupported.php
     * @param string $feature <p>
     * The feature to test. See the example of
     * DOMImplementation::hasFeature for a
     * list of features.
     * </p>
     * @param string $version <p>
     * The version number of the feature to test.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function isSupported(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $feature,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $version
    ): bool {}

    /**
     * Checks if node has attributes
     * @link https://php.net/manual/en/domnode.hasattributes.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function hasAttributes(): bool {}

    /**
     * @param DOMNode $other
     */
    public function compareDocumentPosition(DOMNode $other) {}

    /**
     * Indicates if two nodes are the same node
     * @link https://php.net/manual/en/domnode.issamenode.php
     * @param DOMNode $otherNode <p>
     * The compared node.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function isSameNode(DOMNode $otherNode): bool {}

    /**
     * Gets the namespace prefix of the node based on the namespace URI
     * @link https://php.net/manual/en/domnode.lookupprefix.php
     * @param string $namespace <p>
     * The namespace URI.
     * </p>
     * @return string The prefix of the namespace.
     */
    #[TentativeType]
    public function lookupPrefix(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $namespace): ?string {}

    /**
     * Checks if the specified namespaceURI is the default namespace or not
     * @link https://php.net/manual/en/domnode.isdefaultnamespace.php
     * @param string $namespace <p>
     * The namespace URI to look for.
     * </p>
     * @return bool Return true if namespaceURI is the default
     * namespace, false otherwise.
     */
    #[TentativeType]
    public function isDefaultNamespace(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $namespace): bool {}

    /**
     * Gets the namespace URI of the node based on the prefix
     * @link https://php.net/manual/en/domnode.lookupnamespaceuri.php
     * @param string|null $prefix <p>
     * The prefix of the namespace.
     * </p>
     * @return string The namespace URI of the node.
     */
    #[PhpStormStubsElementAvailable(from: '8.0')]
    #[TentativeType]
    public function lookupNamespaceURI(?string $prefix): ?string {}

    /**
     * Gets the namespace URI of the node based on the prefix
     * @link https://php.net/manual/en/domnode.lookupnamespaceuri.php
     * @param string $prefix <p>
     * The prefix of the namespace.
     * </p>
     * @return string The namespace URI of the node.
     */
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')]
    public function lookupNamespaceUri($prefix) {}

    /**
     * @param DOMNode $arg
     * @return bool
     */
    public function isEqualNode(DOMNode $arg) {}

    /**
     * @param $feature
     * @param $version
     * @return mixed
     */
    public function getFeature($feature, $version) {}

    /**
     * @param $key
     * @param $data
     * @param $handler
     */
    public function setUserData($key, $data, $handler) {}

    /**
     * @param $key
     * @return mixed
     */
    public function getUserData($key) {}

    /**
     * Gets an XPath location path for the node
     * @return string|null the XPath, or NULL in case of an error.
     * @link https://secure.php.net/manual/en/domnode.getnodepath.php
     */
    #[TentativeType]
    public function getNodePath(): ?string {}

    /**
     * Get line number for a node
     * @link https://php.net/manual/en/domnode.getlineno.php
     * @return int Always returns the line number where the node was defined in.
     */
    #[TentativeType]
    public function getLineNo(): int {}

    /**
     * Canonicalize nodes to a string
     * @param bool $exclusive [optional] Enable exclusive parsing of only the nodes matched by the provided xpath or namespace prefixes.
     * @param bool $withComments [optional] Retain comments in output.
     * @param null|array $xpath [optional] An array of xpaths to filter the nodes by.
     * @param null|array $nsPrefixes [optional] An array of namespace prefixes to filter the nodes by.
     * @return string|false Canonicalized nodes as a string or FALSE on failure
     */
    #[TentativeType]
    public function C14N(
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $exclusive = false,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $withComments = false,
        #[LanguageLevelTypeAware(['8.0' => 'array|null'], default: 'array')] $xpath = null,
        #[LanguageLevelTypeAware(['8.0' => 'array|null'], default: 'array')] $nsPrefixes = null
    ): string|false {}

    /**
     * Canonicalize nodes to a file.
     * @link https://www.php.net/manual/en/domnode.c14nfile
     * @param string $uri Number of bytes written or FALSE on failure
     * @param bool $exclusive [optional] Enable exclusive parsing of only the nodes matched by the provided xpath or namespace prefixes.
     * @param bool $withComments [optional]  Retain comments in output.
     * @param null|array $xpath [optional] An array of xpaths to filter the nodes by.
     * @param null|array $nsPrefixes [optional] An array of namespace prefixes to filter the nodes by.
     * @return int|false Number of bytes written or FALSE on failure
     */
    #[TentativeType]
    public function C14NFile(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $uri,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $exclusive = false,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $withComments = false,
        #[LanguageLevelTypeAware(['8.0' => 'array|null'], default: 'array')] $xpath = null,
        #[LanguageLevelTypeAware(['8.0' => 'array|null'], default: 'array')] $nsPrefixes = null
    ): int|false {}
}

/**
 * DOM operations raise exceptions under particular circumstances, i.e.,
 * when an operation is impossible to perform for logical reasons.
 * @link https://php.net/manual/en/class.domexception.php
 */
final class DOMException extends Exception
{
    /**
     * @link https://php.net/manual/en/class.domexception.php#domexception.props.code
     * @var int An integer indicating the type of error generated
     */
    public $code;
}

class DOMStringList
{
    /**
     * @param $index
     * @return mixed
     */
    public function item($index) {}
}

/**
 * @link https://php.net/manual/en/ref.dom.php
 * @removed 8.0
 */
class DOMNameList
{
    /**
     * @param $index
     * @return mixed
     */
    public function getName($index) {}

    /**
     * @param $index
     * @return mixed
     */
    public function getNamespaceURI($index) {}
}

/**
 * @removed 8.0
 */
class DOMImplementationList
{
    /**
     * @param $index
     * @return mixed
     */
    public function item($index) {}
}

/**
 * @removed 8.0
 */
class DOMImplementationSource
{
    /**
     * @param $features
     * @return mixed
     */
    public function getDomimplementation($features) {}

    /**
     * @param $features
     * @return mixed
     */
    public function getDomimplementations($features) {}
}

/**
 * The DOMImplementation interface provides a number
 * of methods for performing operations that are independent of any
 * particular instance of the document object model.
 * @link https://php.net/manual/en/class.domimplementation.php
 */
class DOMImplementation
{
    /**
     * @param string $feature
     * @param string $version
     * @return mixed
     */
    #[TentativeType]
    public function getFeature(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $feature,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $version
    ): never {}

    /**
     * Test if the DOM implementation implements a specific feature
     * @link https://php.net/manual/en/domimplementation.hasfeature.php
     * @param string $feature <p>
     * The feature to test.
     * </p>
     * @param string $version <p>
     * The version number of the feature to test. In
     * level 2, this can be either 2.0 or 1.0.
     * </p>
     * @return bool true on success or false on failure.
     */
    public function hasFeature($feature, $version) {}

    /**
     * Creates an empty DOMDocumentType object
     * @link https://php.net/manual/en/domimplementation.createdocumenttype.php
     * @param string $qualifiedName <p>
     * The qualified name of the document type to create.
     * </p>
     * @param string $publicId <p>
     * The external subset public identifier.
     * </p>
     * @param string $systemId <p>
     * The external subset system identifier.
     * </p>
     * @return DOMDocumentType|false A new DOMDocumentType node with its
     * ownerDocument set to null.
     * @throws DOMException If there is an error with the namespace
     */
    public function createDocumentType(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName,
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $publicId,
        #[PhpStormStubsElementAvailable(from: '8.0')] string $publicId = '',
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $systemId,
        #[PhpStormStubsElementAvailable(from: '8.0')] string $systemId = ''
    ) {}

    /**
     * Creates a DOMDocument object of the specified type with its document element
     * @link https://php.net/manual/en/domimplementation.createdocument.php
     * @param string|null $namespace <p>
     * The namespace URI of the document element to create.
     * </p>
     * @param string $qualifiedName <p>
     * The qualified name of the document element to create.
     * </p>
     * @param DOMDocumentType|null $doctype <p>
     * The type of document to create or null.
     * </p>
     * @return DOMDocument|false A new DOMDocument object. If
     * namespaceURI, qualifiedName, and doctype are null, the
     * returned DOMDocument is empty with no document element.
     * @throws DOMException If $doctype has already been used
     * with adifferent document or was created from a different
     * implementation. If there is an error with the namespace,
     * as determined by $namespace and $qualifiedName.
     */
    public function createDocument(
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $namespace,
        #[PhpStormStubsElementAvailable(from: '8.0')] ?string $namespace = null,
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $qualifiedName,
        #[PhpStormStubsElementAvailable(from: '8.0')] string $qualifiedName = '',
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.3')] DOMDocumentType $doctype,
        #[PhpStormStubsElementAvailable(from: '7.4')] #[LanguageLevelTypeAware(['8.0' => 'DOMDocumentType|null'], default: 'DOMDocumentType')] $doctype = null
    ) {}
}

class DOMNameSpaceNode
{
    #[LanguageLevelTypeAware(['8.1' => 'DOMNode|null'], default: '')]
    public $parentNode;

    #[LanguageLevelTypeAware(['8.1' => 'DOMDocument|null'], default: '')]
    public $ownerDocument;

    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $namespaceURI;

    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $localName;

    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $prefix;

    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $nodeType;

    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $nodeValue;

    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $nodeName;
}

/**
 * The DOMDocumentFragment class
 * @link https://php.net/manual/en/class.domdocumentfragment.php
 */
class DOMDocumentFragment extends DOMNode implements DOMParentNode
{
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $childElementCount;

    #[LanguageLevelTypeAware(['8.1' => 'DOMElement|null'], default: '')]
    public $lastElementChild;

    #[LanguageLevelTypeAware(['8.1' => 'DOMElement|null'], default: '')]
    public $firstElementChild;

    public function __construct() {}

    /**
     * Append raw XML data
     * @link https://php.net/manual/en/domdocumentfragment.appendxml.php
     * @param string $data <p>
     * XML to append.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function appendXML(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data): bool {}

    /**
     * {@inheritDoc}
     */
    public function append(...$nodes): void {}

    /**
     * {@inheritDoc}
     */
    public function prepend(...$nodes): void {}
}

/**
 * The DOMDocument class represents an entire HTML or XML
 * document; serves as the root of the document tree.
 * @link https://php.net/manual/en/class.domdocument.php
 */
class DOMDocument extends DOMNode implements DOMParentNode
{
    /**
     * @var string|null
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.actualencoding
     */
    #[Deprecated("Actual encoding of the document, is a readonly equivalent to encoding.")]
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $actualEncoding;

    /**
     * @var DOMConfiguration
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.config
     * @see DOMDocument::normalizeDocument()
     */
    #[Deprecated("Configuration used when DOMDocument::normalizeDocument() is invoked.")]
    #[LanguageLevelTypeAware(['8.1' => 'mixed'], default: '')]
    public $config;

    /**
     * @var DOMDocumentType
     * The Document Type Declaration associated with this document.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.doctype
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMDocumentType|null'], default: '')]
    public $doctype;

    /**
     * @var DOMElement
     * This is a convenience attribute that allows direct access to the child node
     * that is the document element of the document.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.documentelement
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMElement|null'], default: '')]
    public $documentElement;

    /**
     * @var string|null
     * The location of the document or NULL if undefined.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.documenturi
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $documentURI;

    /**
     * @var string|null
     * Encoding of the document, as specified by the XML declaration. This attribute is not present
     * in the final DOM Level 3 specification, but is the only way of manipulating XML document
     * encoding in this implementation.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.encoding
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $encoding;

    /**
     * @var bool
     * Nicely formats output with indentation and extra space.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.formatoutput
     */
    #[LanguageLevelTypeAware(['8.1' => 'bool'], default: '')]
    public $formatOutput;

    /**
     * @var DOMImplementation
     * The <classname>DOMImplementation</classname> object that handles this document.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.implementation
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMImplementation'], default: '')]
    public $implementation;

    /**
     * @var bool
     * Do not remove redundant white space. Default to TRUE.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.preservewhitespace
     */
    #[LanguageLevelTypeAware(['8.1' => 'bool'], default: '')]
    public $preserveWhiteSpace = true;

    /**
     * @var bool
     * Proprietary. Enables recovery mode, i.e. trying to parse non-well formed documents.
     * This attribute is not part of the DOM specification and is specific to libxml.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.recover
     */
    #[LanguageLevelTypeAware(['8.1' => 'bool'], default: '')]
    public $recover;

    /**
     * @var bool
     * Set it to TRUE to load external entities from a doctype declaration. This is useful for
     * including character entities in your XML document.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.resolveexternals
     */
    #[LanguageLevelTypeAware(['8.1' => 'bool'], default: '')]
    public $resolveExternals;

    /**
     * @var bool
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.standalone
     */
    #[Deprecated("Whether or not the document is standalone, as specified by the XML declaration, corresponds to xmlStandalone.")]
    #[LanguageLevelTypeAware(['8.1' => 'bool'], default: '')]
    public $standalone;

    /**
     * @var bool
     * Throws <classname>DOMException</classname> on errors. Default to TRUE.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.stricterrorchecking
     */
    #[LanguageLevelTypeAware(['8.1' => 'bool'], default: '')]
    public $strictErrorChecking = true;

    /**
     * @var bool
     * Proprietary. Whether or not to substitute entities. This attribute is not part of the DOM
     * specification and is specific to libxml.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.substituteentities
     */
    #[LanguageLevelTypeAware(['8.1' => 'bool'], default: '')]
    public $substituteEntities;

    /**
     * @var bool
     * Loads and validates against the DTD. Default to FALSE.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.validateonparse
     */
    #[LanguageLevelTypeAware(['8.1' => 'bool'], default: '')]
    public $validateOnParse = false;

    /**
     * @var string
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.version
     */
    #[Deprecated('Version of XML, corresponds to xmlVersion')]
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $version;

    /**
     * @var string|null
     * An attribute specifying, as part of the XML declaration, the encoding of this document. This is NULL when
     * unspecified or when it is not known, such as when the Document was created in memory.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.xmlencoding
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $xmlEncoding;

    /**
     * @var bool
     * An attribute specifying, as part of the XML declaration, whether this document is standalone.
     * This is FALSE when unspecified.
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.xmlstandalone
     */
    #[LanguageLevelTypeAware(['8.1' => 'bool'], default: '')]
    public $xmlStandalone;

    /**
     * @var string|null
     * An attribute specifying, as part of the XML declaration, the version number of this document. If there is no
     * declaration and if this document supports the "XML" feature, the value is "1.0".
     * @link https://php.net/manual/en/class.domdocument.php#domdocument.props.xmlversion
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $xmlVersion;

    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $childElementCount;

    #[LanguageLevelTypeAware(['8.1' => 'DOMElement|null'], default: '')]
    public $lastElementChild;

    #[LanguageLevelTypeAware(['8.1' => 'DOMElement|null'], default: '')]
    public $firstElementChild;

    /**
     * Create new element node
     * @link https://php.net/manual/en/domdocument.createelement.php
     * @param string $localName <p>
     * The tag name of the element.
     * </p>
     * @param string $value [optional] <p>
     * The value of the element. By default, an empty element will be created.
     * You can also set the value later with DOMElement->nodeValue.
     * </p>
     * @return DOMElement|false A new instance of class DOMElement or false
     * if an error occurred.
     * @throws DOMException If invalid $localName
     */
    public function createElement(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $localName,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $value = ''
    ) {}

    /**
     * Create new document fragment
     * @link https://php.net/manual/en/domdocument.createdocumentfragment.php
     * @return DOMDocumentFragment|false The new DOMDocumentFragment or false if an error occurred.
     */
    #[TentativeType]
    public function createDocumentFragment(): DOMDocumentFragment {}

    /**
     * Create new text node
     * @link https://php.net/manual/en/domdocument.createtextnode.php
     * @param string $data <p>
     * The content of the text.
     * </p>
     * @return DOMText|false The new DOMText or false if an error occurred.
     */
    #[TentativeType]
    public function createTextNode(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data): DOMText {}

    /**
     * Create new comment node
     * @link https://php.net/manual/en/domdocument.createcomment.php
     * @param string $data <p>
     * The content of the comment.
     * </p>
     * @return DOMComment|false The new DOMComment or false if an error occurred.
     */
    #[TentativeType]
    public function createComment(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data): DOMComment {}

    /**
     * Create new cdata node
     * @link https://php.net/manual/en/domdocument.createcdatasection.php
     * @param string $data <p>
     * The content of the cdata.
     * </p>
     * @return DOMCDATASection|false The new DOMCDATASection or false if an error occurred.
     */
    public function createCDATASection(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data) {}

    /**
     * Creates new PI node
     * @link https://php.net/manual/en/domdocument.createprocessinginstruction.php
     * @param string $target <p>
     * The target of the processing instruction.
     * </p>
     * @param string $data <p>
     * The content of the processing instruction.
     * </p>
     * @return DOMProcessingInstruction|false The new DOMProcessingInstruction or false if an error occurred.
     */
    public function createProcessingInstruction(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $target,
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.3')] $data,
        #[PhpStormStubsElementAvailable(from: '7.4')] #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data = null
    ) {}

    /**
     * Create new attribute
     * @link https://php.net/manual/en/domdocument.createattribute.php
     * @param string $localName <p>
     * The name of the attribute.
     * </p>
     * @return DOMAttr|false The new DOMAttr or false if an error occurred.
     * @throws DOMException If invalid $localName
     */
    public function createAttribute(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $localName) {}

    /**
     * Create new entity reference node
     * @link https://php.net/manual/en/domdocument.createentityreference.php
     * @param string $name <p>
     * The content of the entity reference, e.g. the entity reference minus
     * the leading &amp; and the trailing
     * ; characters.
     * </p>
     * @return DOMEntityReference|false The new DOMEntityReference or false if an error
     * occurred.
     */
    public function createEntityReference(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name) {}

    /**
     * Searches for all elements with given tag name
     * @link https://php.net/manual/en/domdocument.getelementsbytagname.php
     * @param string $qualifiedName <p>
     * The name of the tag to match on. The special value *
     * matches all tags.
     * </p>
     * @return DOMNodeList A new DOMNodeList object containing all the matched
     * elements.
     */
    #[TentativeType]
    public function getElementsByTagName(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName): DOMNodeList {}

    /**
     * Import node into current document
     * @link https://php.net/manual/en/domdocument.importnode.php
     * @param DOMNode $node <p>
     * The node to import.
     * </p>
     * @param bool $deep <p>
     * If set to true, this method will recursively import the subtree under
     * the importedNode.
     * </p>
     * <p>
     * To copy the nodes attributes deep needs to be set to true
     * </p>
     * @return DOMNode|false The copied node or false, if it cannot be copied.
     */
    public function importNode(
        DOMNode $node,
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.3')] $deep,
        #[PhpStormStubsElementAvailable(from: '7.4')] #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $deep = false
    ) {}

    /**
     * Create new element node with an associated namespace
     * @link https://php.net/manual/en/domdocument.createelementns.php
     * @param string|null $namespace <p>
     * The URI of the namespace.
     * </p>
     * @param string $qualifiedName <p>
     * The qualified name of the element, as prefix:tagname.
     * </p>
     * @param string $value [optional] <p>
     * The value of the element. By default, an empty element will be created.
     * You can also set the value later with DOMElement->nodeValue.
     * </p>
     * @return DOMElement|false The new DOMElement or false if an error occurred.
     * @throws DOMException If invalid $namespace or $qualifiedName
     */
    public function createElementNS(
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $namespace,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $value = ''
    ) {}

    /**
     * Create new attribute node with an associated namespace
     * @link https://php.net/manual/en/domdocument.createattributens.php
     * @param string|null $namespace <p>
     * The URI of the namespace.
     * </p>
     * @param string $qualifiedName <p>
     * The tag name and prefix of the attribute, as prefix:tagname.
     * </p>
     * @return DOMAttr|false The new DOMAttr or false if an error occurred.
     * @throws DOMException If invalid $namespace or $qualifiedName
     */
    public function createAttributeNS(
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $namespace,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName
    ) {}

    /**
     * Searches for all elements with given tag name in specified namespace
     * @link https://php.net/manual/en/domdocument.getelementsbytagnamens.php
     * @param string $namespace <p>
     * The namespace URI of the elements to match on.
     * The special value * matches all namespaces.
     * </p>
     * @param string $localName <p>
     * The local name of the elements to match on.
     * The special value * matches all local names.
     * </p>
     * @return DOMNodeList A new DOMNodeList object containing all the matched
     * elements.
     */
    #[TentativeType]
    public function getElementsByTagNameNS(
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $namespace,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $localName
    ): DOMNodeList {}

    /**
     * Searches for an element with a certain id
     * @link https://php.net/manual/en/domdocument.getelementbyid.php
     * @param string $elementId <p>
     * The unique id value for an element.
     * </p>
     * @return DOMElement|null The DOMElement or null if the element is
     * not found.
     */
    #[TentativeType]
    public function getElementById(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $elementId): ?DOMElement {}

    /**
     * @param DOMNode $node
     */
    public function adoptNode(DOMNode $node) {}

    /**
     * {@inheritDoc}
     */
    public function append(...$nodes): void {}

    /**
     * {@inheritDoc}
     */
    public function prepend(...$nodes): void {}

    /**
     * Normalizes the document
     * @link https://php.net/manual/en/domdocument.normalizedocument.php
     * @return void
     */
    #[TentativeType]
    public function normalizeDocument(): void {}

    /**
     * @param DOMNode $node
     * @param $namespace
     * @param $qualifiedName
     */
    public function renameNode(DOMNode $node, $namespace, $qualifiedName) {}

    /**
     * Load XML from a file
     * @link https://php.net/manual/en/domdocument.load.php
     * @param string $filename <p>
     * The path to the XML document.
     * </p>
     * @param int $options [optional] <p>
     * Bitwise OR
     * of the libxml option constants.
     * </p>
     * @return DOMDocument|bool true on success or false on failure. If called statically, returns a
     * DOMDocument and issues E_STRICT
     * warning.
     */
    public function load(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $options = null
    ) {}

    /**
     * Dumps the internal XML tree back into a file
     * @link https://php.net/manual/en/domdocument.save.php
     * @param string $filename <p>
     * The path to the saved XML document.
     * </p>
     * @param int $options [optional] <p>
     * Additional Options. Currently only LIBXML_NOEMPTYTAG is supported.
     * </p>
     * @return int|false the number of bytes written or false if an error occurred.
     */
    public function save($filename, $options = null) {}

    /**
     * Load XML from a string
     * @link https://php.net/manual/en/domdocument.loadxml.php
     * @param string $source <p>
     * The string containing the XML.
     * </p>
     * @param int $options [optional] <p>
     * Bitwise OR
     * of the libxml option constants.
     * </p>
     * @return DOMDocument|bool true on success or false on failure. If called statically, returns a
     * DOMDocument and issues E_STRICT
     * warning.
     */
    public function loadXML(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $source,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $options = null
    ) {}

    /**
     * Dumps the internal XML tree back into a string
     * @link https://php.net/manual/en/domdocument.savexml.php
     * @param null|DOMNode $node [optional] <p>
     * Use this parameter to output only a specific node without XML declaration
     * rather than the entire document.
     * </p>
     * @param int $options [optional] <p>
     * Additional Options. Currently only LIBXML_NOEMPTYTAG is supported.
     * </p>
     * @return string|false the XML, or false if an error occurred.
     */
    #[TentativeType]
    public function saveXML(
        ?DOMNode $node = null,
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $options = null
    ): string|false {}

    /**
     * Creates a new DOMDocument object
     * @link https://php.net/manual/en/domdocument.construct.php
     * @param string $version [optional] The version number of the document as part of the XML declaration.
     * @param string $encoding [optional] The encoding of the document as part of the XML declaration.
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $version = '1.0',
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $encoding = ''
    ) {}

    /**
     * Validates the document based on its DTD
     * @link https://php.net/manual/en/domdocument.validate.php
     * @return bool true on success or false on failure.
     * If the document have no DTD attached, this method will return false.
     */
    #[TentativeType]
    public function validate(): bool {}

    /**
     * Substitutes XIncludes in a DOMDocument Object
     * @link https://php.net/manual/en/domdocument.xinclude.php
     * @param int $options [optional] <p>
     * libxml parameters. Available
     * since PHP 5.1.0 and Libxml 2.6.7.
     * </p>
     * @return int|false the number of XIncludes in the document.
     */
    #[TentativeType]
    public function xinclude(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $options = null): int|false {}

    /**
     * Load HTML from a string
     * @link https://php.net/manual/en/domdocument.loadhtml.php
     * @param string $source <p>
     * The HTML string.
     * </p>
     * @param int $options [optional] <p>
     * Since PHP 5.4.0 and Libxml 2.6.0, you may also
     * use the options parameter to specify additional Libxml parameters.
     * </p>
     * @return DOMDocument|bool true on success or false on failure. If called statically, returns a
     * DOMDocument and issues E_STRICT
     * warning.
     */
    public function loadHTML(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $source,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $options = 0
    ) {}

    /**
     * Load HTML from a file
     * @link https://php.net/manual/en/domdocument.loadhtmlfile.php
     * @param string $filename <p>
     * The path to the HTML file.
     * </p>
     * @param int $options [optional] <p>
     * Since PHP 5.4.0 and Libxml 2.6.0, you may also
     * use the options parameter to specify additional Libxml parameters.
     * </p>
     * @return DOMDocument|bool true on success or false on failure. If called statically, returns a
     * DOMDocument and issues E_STRICT
     * warning.
     */
    public function loadHTMLFile(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $options = 0
    ) {}

    /**
     * Dumps the internal document into a string using HTML formatting
     * @link https://php.net/manual/en/domdocument.savehtml.php
     * @param null|DOMNode $node [optional] parameter to output a subset of the document.
     * @return string|false The HTML, or false if an error occurred.
     */
    public function saveHTML(DOMNode $node = null) {}

    /**
     * Dumps the internal document into a file using HTML formatting
     * @link https://php.net/manual/en/domdocument.savehtmlfile.php
     * @param string $filename <p>
     * The path to the saved HTML document.
     * </p>
     * @return int|false the number of bytes written or false if an error occurred.
     */
    #[TentativeType]
    public function saveHTMLFile(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename): int|false {}

    /**
     * Validates a document based on a schema
     * @link https://php.net/manual/en/domdocument.schemavalidate.php
     * @param string $filename <p>
     * The path to the schema.
     * </p>
     * @param int $options [optional] <p>
     * Bitwise OR
     * of the libxml option constants.
     * </p>
     * @return bool true on success or false on failure.
     */
    public function schemaValidate($filename, $options = null) {}

    /**
     * Validates a document based on a schema
     * @link https://php.net/manual/en/domdocument.schemavalidatesource.php
     * @param string $source <p>
     * A string containing the schema.
     * </p>
     * @param int $flags [optional] <p>A bitmask of Libxml schema validation flags. Currently the only supported value is <b>LIBXML_SCHEMA_CREATE</b>.
     * Available since PHP 5.5.2 and Libxml 2.6.14.</p>
     * @return bool true on success or false on failure.
     */
    public function schemaValidateSource($source, $flags) {}

    /**
     * Performs relaxNG validation on the document
     * @link https://php.net/manual/en/domdocument.relaxngvalidate.php
     * @param string $filename <p>
     * The RNG file.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function relaxNGValidate(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename): bool {}

    /**
     * Performs relaxNG validation on the document
     * @link https://php.net/manual/en/domdocument.relaxngvalidatesource.php
     * @param string $source <p>
     * A string containing the RNG schema.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function relaxNGValidateSource(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $source): bool {}

    /**
     * Register extended class used to create base node type
     * @link https://php.net/manual/en/domdocument.registernodeclass.php
     * @param string $baseClass <p>
     * The DOM class that you want to extend. You can find a list of these
     * classes in the chapter introduction.
     * </p>
     * @param string $extendedClass <p>
     * Your extended class name. If null is provided, any previously
     * registered class extending baseclass will
     * be removed.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function registerNodeClass(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $baseClass,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $extendedClass
    ): bool {}
}

/**
 * The DOMNodeList class
 * @link https://php.net/manual/en/class.domnodelist.php
 */
class DOMNodeList implements IteratorAggregate, Countable
{
    /**
     * @var int
     * The number of nodes in the list. The range of valid child node indices is 0 to length - 1 inclusive.
     * @link https://php.net/manual/en/class.domnodelist.php#domnodelist.props.length
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    #[Immutable]
    public $length;

    /**
     * Retrieves a node specified by index
     * @link https://php.net/manual/en/domnodelist.item.php
     * @param int $index <p>
     * Index of the node into the collection.
     * The range of valid child node indices is 0 to length - 1 inclusive.
     * </p>
     * @return DOMNode|null The node at the indexth position in the
     * DOMNodeList, or null if that is not a valid
     * index.
     */
    public function item(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $index) {}

    /**
     * @return int<0, max>
     * @since 7.2
     */
    #[TentativeType]
    public function count(): int {}

    /**
     * @return Iterator
     * @since 8.0
     */
    public function getIterator(): Iterator {}
}

/**
 * The DOMNamedNodeMap class
 * @link https://php.net/manual/en/class.domnamednodemap.php
 * @property-read int $length The number of nodes in the map. The range of valid child node indices is 0 to length - 1 inclusive.
 */
class DOMNamedNodeMap implements IteratorAggregate, Countable
{
    /**
     * Retrieves a node specified by name
     * @link https://php.net/manual/en/domnamednodemap.getnameditem.php
     * @param string $qualifiedName <p>
     * The nodeName of the node to retrieve.
     * </p>
     * @return DOMNode|null A node (of any type) with the specified nodeName, or
     * null if no node is found.
     */
    #[TentativeType]
    public function getNamedItem(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName): ?DOMNode {}

    /**
     * @param DOMNode $arg
     */
    public function setNamedItem(DOMNode $arg) {}

    /**
     * @param $name [optional]
     */
    public function removeNamedItem($name) {}

    /**
     * Retrieves a node specified by index
     * @link https://php.net/manual/en/domnamednodemap.item.php
     * @param int $index <p>
     * Index into this map.
     * </p>
     * @return DOMNode|null The node at the indexth position in the map, or null
     * if that is not a valid index (greater than or equal to the number of nodes
     * in this map).
     */
    #[TentativeType]
    public function item(
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] $index = 0,
        #[PhpStormStubsElementAvailable(from: '7.1')] #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $index
    ): ?DOMNode {}

    /**
     * Retrieves a node specified by local name and namespace URI
     * @link https://php.net/manual/en/domnamednodemap.getnameditemns.php
     * @param string $namespace <p>
     * The namespace URI of the node to retrieve.
     * </p>
     * @param string $localName <p>
     * The local name of the node to retrieve.
     * </p>
     * @return DOMNode|null A node (of any type) with the specified local name and namespace URI, or
     * null if no node is found.
     */
    #[TentativeType]
    public function getNamedItemNS(
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $namespaceURI = '',
        #[PhpStormStubsElementAvailable(from: '8.0')] ?string $namespace,
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $localName = '',
        #[PhpStormStubsElementAvailable(from: '8.0')] string $localName
    ): ?DOMNode {}

    /**
     * @param DOMNode $arg [optional]
     */
    public function setNamedItemNS(DOMNode $arg) {}

    /**
     * @param $namespace [optional]
     * @param $localName [optional]
     */
    public function removeNamedItemNS($namespace, $localName) {}

    /**
     * @return int<0,max>
     * @since 7.2
     */
    #[TentativeType]
    public function count(): int {}

    /**
     * @return Iterator
     * @since 8.0
     */
    public function getIterator(): Iterator {}
}

/**
 * The DOMCharacterData class represents nodes with character data.
 * No nodes directly correspond to this class, but other nodes do inherit from it.
 * @link https://php.net/manual/en/class.domcharacterdata.php
 */
class DOMCharacterData extends DOMNode implements DOMChildNode
{
    /**
     * @var string
     * The contents of the node.
     * @link https://php.net/manual/en/class.domcharacterdata.php#domcharacterdata.props.data
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $data;

    /**
     * @var int
     * The length of the contents.
     * @link https://php.net/manual/en/class.domcharacterdata.php#domcharacterdata.props.length
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $length;

    #[LanguageLevelTypeAware(['8.1' => 'DOMElement|null'], default: '')]
    public $nextElementSibling;

    #[LanguageLevelTypeAware(['8.1' => 'DOMElement|null'], default: '')]
    public $previousElementSibling;

    /**
     * Extracts a range of data from the node
     * @link https://php.net/manual/en/domcharacterdata.substringdata.php
     * @param int $offset <p>
     * Start offset of substring to extract.
     * </p>
     * @param int $count <p>
     * The number of characters to extract.
     * </p>
     * @return string The specified substring. If the sum of offset
     * and count exceeds the length, then all 16-bit units
     * to the end of the data are returned.
     */
    public function substringData(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $offset,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $count
    ) {}

    /**
     * Append the string to the end of the character data of the node
     * @link https://php.net/manual/en/domcharacterdata.appenddata.php
     * @param string $data <p>
     * The string to append.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function appendData(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data): bool {}

    /**
     * Insert a string at the specified 16-bit unit offset
     * @link https://php.net/manual/en/domcharacterdata.insertdata.php
     * @param int $offset <p>
     * The character offset at which to insert.
     * </p>
     * @param string $data <p>
     * The string to insert.
     * </p>
     * @return bool
     */
    #[TentativeType]
    public function insertData(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $offset,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data
    ): bool {}

    /**
     * Remove a range of characters from the node
     * @link https://php.net/manual/en/domcharacterdata.deletedata.php
     * @param int $offset <p>
     * The offset from which to start removing.
     * </p>
     * @param int $count <p>
     * The number of characters to delete. If the sum of
     * offset and count exceeds
     * the length, then all characters to the end of the data are deleted.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function deleteData(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $offset,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $count
    ): bool {}

    /**
     * Replace a substring within the DOMCharacterData node
     * @link https://php.net/manual/en/domcharacterdata.replacedata.php
     * @param int $offset <p>
     * The offset from which to start replacing.
     * </p>
     * @param int $count <p>
     * The number of characters to replace. If the sum of
     * offset and count exceeds
     * the length, then all characters to the end of the data are replaced.
     * </p>
     * @param string $data <p>
     * The string with which the range must be replaced.
     * </p>
     * @return bool
     */
    #[TentativeType]
    public function replaceData(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $offset,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $count,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data
    ): bool {}

    /**
     * {@inheritDoc}
     */
    public function remove(): void {}

    /**
     * {@inheritDoc}
     */
    public function before(...$nodes): void {}

    /**
     * {@inheritDoc}
     */
    public function after(...$nodes): void {}

    /**
     * {@inheritDoc}
     */
    public function replaceWith(...$nodes): void {}
}

/**
 * The DOMAttr interface represents an attribute in an DOMElement object.
 * @link https://php.net/manual/en/class.domattr.php
 */
class DOMAttr extends DOMNode
{
    /**
     * @var string
     * (PHP5)<br/>
     * The name of the attribute
     * @link https://php.net/manual/en/class.domattr.php#domattr.props.name
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $name;

    /**
     * @var DOMElement
     * (PHP5)<br/>
     * The element which contains the attribute
     * @link https://php.net/manual/en/class.domattr.php#domattr.props.ownerelement
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMElement|null'], default: '')]
    public $ownerElement;

    /**
     * @var bool
     * (PHP5)<br/>
     * Not implemented yet, always is NULL
     * @link https://php.net/manual/en/class.domattr.php#domattr.props.schematypeinfo
     */
    #[LanguageLevelTypeAware(['8.1' => 'mixed'], default: '')]
    public $schemaTypeInfo;

    /**
     * @var bool
     * (PHP5)<br/>
     * Not implemented yet, always is NULL
     * @link https://php.net/manual/en/class.domattr.php#domattr.props.specified
     */
    #[LanguageLevelTypeAware(['8.1' => 'bool'], default: '')]
    public $specified;

    /**
     * @var string
     * (PHP5)<br/>
     * The value of the attribute
     * @link https://php.net/manual/en/class.domattr.php#domattr.props.value
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $value;

    /**
     * Checks if attribute is a defined ID
     * @link https://php.net/manual/en/domattr.isid.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function isId(): bool {}

    /**
     * Creates a new {@see DOMAttr} object
     * @link https://php.net/manual/en/domattr.construct.php
     * @param string $name <p>The tag name of the attribute.</p>
     * @param string $value [optional] <p>The value of the attribute.</p>
     * @throws DOMException If invalid $name
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $value = ''
    ) {}
}

/**
 * The DOMElement class
 * @link https://php.net/manual/en/class.domelement.php
 */
class DOMElement extends DOMNode implements DOMParentNode, DOMChildNode
{
    /**
     * @var DOMNode|null
     * The parent of this node. If there is no such node, this returns NULL.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.parentnode
     */
    public $parentNode;

    /**
     * @var DOMNode|null
     * The first child of this node. If there is no such node, this returns NULL.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.firstchild
     */
    public $firstChild;

    /**
     * @var DOMNode|null
     * The last child of this node. If there is no such node, this returns NULL.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.lastchild
     */
    public $lastChild;

    /**
     * @var DOMNode|null
     * The node immediately preceding this node. If there is no such node, this returns NULL.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.previoussibling
     */
    public $previousSibling;

    /**
     * @var DOMNode|null
     * The node immediately following this node. If there is no such node, this returns NULL.
     * @link https://php.net/manual/en/class.domnode.php#domnode.props.nextsibling
     */
    public $nextSibling;

    /**
     * @var bool
     * Not implemented yet, always return NULL
     * @link https://php.net/manual/en/class.domelement.php#domelement.props.schematypeinfo
     */
    #[LanguageLevelTypeAware(['8.1' => 'mixed'], default: '')]
    public $schemaTypeInfo;

    /**
     * @var string
     * The element name
     * @link https://php.net/manual/en/class.domelement.php#domelement.props.tagname
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $tagName;

    #[LanguageLevelTypeAware(['8.1' => 'DOMElement|null'], default: '')]
    public $firstElementChild;

    #[LanguageLevelTypeAware(['8.1' => 'DOMElement|null'], default: '')]
    public $lastElementChild;

    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $childElementCount;

    #[LanguageLevelTypeAware(['8.1' => 'DOMElement|null'], default: '')]
    public $previousElementSibling;

    #[LanguageLevelTypeAware(['8.1' => 'DOMElement|null'], default: '')]
    public $nextElementSibling;

    /**
     * Returns value of attribute
     * @link https://php.net/manual/en/domelement.getattribute.php
     * @param string $qualifiedName <p>
     * The name of the attribute.
     * </p>
     * @return string The value of the attribute, or an empty string if no attribute with the
     * given name is found.
     */
    #[TentativeType]
    public function getAttribute(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName): string {}

    /**
     * Adds new attribute
     * @link https://php.net/manual/en/domelement.setattribute.php
     * @param string $qualifiedName <p>
     * The name of the attribute.
     * </p>
     * @param string $value <p>
     * The value of the attribute.
     * </p>
     * @return DOMAttr|false The new DOMAttr or false if an error occurred.
     */
    public function setAttribute(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $value
    ) {}

    /**
     * Removes attribute
     * @link https://php.net/manual/en/domelement.removeattribute.php
     * @param string $qualifiedName <p>
     * The name of the attribute.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function removeAttribute(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName): bool {}

    /**
     * Returns attribute node
     * @link https://php.net/manual/en/domelement.getattributenode.php
     * @param string $qualifiedName <p>
     * The name of the attribute.
     * </p>
     * @return DOMAttr The attribute node.
     */
    public function getAttributeNode(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName) {}

    /**
     * Adds new attribute node to element
     * @link https://php.net/manual/en/domelement.setattributenode.php
     * @param DOMAttr $attr <p>
     * The attribute node.
     * </p>
     * @return DOMAttr|null Old node if the attribute has been replaced or null.
     */
    public function setAttributeNode(DOMAttr $attr) {}

    /**
     * Removes attribute
     * @link https://php.net/manual/en/domelement.removeattributenode.php
     * @param DOMAttr $attr <p>
     * The attribute node.
     * </p>
     * @return bool true on success or false on failure.
     */
    public function removeAttributeNode(DOMAttr $attr) {}

    /**
     * Gets elements by tagname
     * @link https://php.net/manual/en/domelement.getelementsbytagname.php
     * @param string $qualifiedName <p>
     * The tag name. Use * to return all elements within
     * the element tree.
     * </p>
     * @return DOMNodeList This function returns a new instance of the class
     * DOMNodeList of all matched elements.
     */
    #[TentativeType]
    public function getElementsByTagName(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName): DOMNodeList {}

    /**
     * Returns value of attribute
     * @link https://php.net/manual/en/domelement.getattributens.php
     * @param string $namespace <p>
     * The namespace URI.
     * </p>
     * @param string $localName <p>
     * The local name.
     * </p>
     * @return string The value of the attribute, or an empty string if no attribute with the
     * given localName and namespaceURI
     * is found.
     */
    #[TentativeType]
    public function getAttributeNS(
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $namespace,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $localName
    ): string {}

    /**
     * Adds new attribute
     * @link https://php.net/manual/en/domelement.setattributens.php
     * @param string $namespace <p>
     * The namespace URI.
     * </p>
     * @param string $qualifiedName <p>
     * The qualified name of the attribute, as prefix:tagname.
     * </p>
     * @param string $value <p>
     * The value of the attribute.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setAttributeNS(
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $namespace,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $value
    ): void {}

    /**
     * Removes attribute
     * @link https://php.net/manual/en/domelement.removeattributens.php
     * @param string $namespace <p>
     * The namespace URI.
     * </p>
     * @param string $localName <p>
     * The local name.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function removeAttributeNS(
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $namespace,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $localName
    ): void {}

    /**
     * Returns attribute node
     * @link https://php.net/manual/en/domelement.getattributenodens.php
     * @param string $namespace <p>
     * The namespace URI.
     * </p>
     * @param string $localName <p>
     * The local name.
     * </p>
     * @return DOMAttr The attribute node.
     */
    public function getAttributeNodeNS(
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $namespace,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $localName
    ) {}

    /**
     * Adds new attribute node to element
     * @link https://php.net/manual/en/domelement.setattributenodens.php
     * @param DOMAttr $attr
     * @return DOMAttr the old node if the attribute has been replaced.
     */
    public function setAttributeNodeNS(DOMAttr $attr) {}

    /**
     * Get elements by namespaceURI and localName
     * @link https://php.net/manual/en/domelement.getelementsbytagnamens.php
     * @param string $namespace <p>
     * The namespace URI.
     * </p>
     * @param string $localName <p>
     * The local name. Use * to return all elements within
     * the element tree.
     * </p>
     * @return DOMNodeList This function returns a new instance of the class
     * DOMNodeList of all matched elements in the order in
     * which they are encountered in a preorder traversal of this element tree.
     */
    #[TentativeType]
    public function getElementsByTagNameNS(
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $namespace,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $localName
    ): DOMNodeList {}

    /**
     * Checks to see if attribute exists
     * @link https://php.net/manual/en/domelement.hasattribute.php
     * @param string $qualifiedName <p>
     * The attribute name.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function hasAttribute(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName): bool {}

    /**
     * Checks to see if attribute exists
     * @link https://php.net/manual/en/domelement.hasattributens.php
     * @param string $namespace <p>
     * The namespace URI.
     * </p>
     * @param string $localName <p>
     * The local name.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function hasAttributeNS(
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $namespace,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $localName
    ): bool {}

    /**
     * Declares the attribute specified by name to be of type ID
     * @link https://php.net/manual/en/domelement.setidattribute.php
     * @param string $qualifiedName <p>
     * The name of the attribute.
     * </p>
     * @param bool $isId <p>
     * Set it to true if you want name to be of type
     * ID, false otherwise.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setIdAttribute(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $isId
    ): void {}

    /**
     * Declares the attribute specified by local name and namespace URI to be of type ID
     * @link https://php.net/manual/en/domelement.setidattributens.php
     * @param string $namespace <p>
     * The namespace URI of the attribute.
     * </p>
     * @param string $qualifiedName <p>
     * The local name of the attribute, as prefix:tagname.
     * </p>
     * @param bool $isId <p>
     * Set it to true if you want name to be of type
     * ID, false otherwise.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setIdAttributeNS(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $namespace,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $isId
    ): void {}

    /**
     * Declares the attribute specified by node to be of type ID
     * @link https://php.net/manual/en/domelement.setidattributenode.php
     * @param DOMAttr $attr <p>
     * The attribute node.
     * </p>
     * @param bool $isId <p>
     * Set it to true if you want name to be of type
     * ID, false otherwise.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setIdAttributeNode(DOMAttr $attr, #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $isId): void {}

    /**
     * {@inheritDoc}
     */
    public function remove(): void {}

    /**
     * {@inheritDoc}
     */
    public function before(...$nodes): void {}

    /**
     * {@inheritDoc}
     */
    public function after(...$nodes): void {}

    /**
     * {@inheritDoc}
     */
    public function replaceWith(...$nodes): void {}

    /**
     * {@inheritDoc}
     */
    public function append(...$nodes): void {}

    /**
     * {@inheritDoc}
     */
    public function prepend(...$nodes): void {}

    /**
     * Creates a new DOMElement object
     * @link https://php.net/manual/en/domelement.construct.php
     * @param string $qualifiedName The tag name of the element. When also passing in namespaceURI, the element name may take a prefix to be associated with the URI.
     * @param string|null $value [optional] The value of the element.
     * @param string $namespace [optional] A namespace URI to create the element within a specific namespace.
     * @throws DOMException If invalid $qualifiedName
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $qualifiedName,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $value = null,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $namespace = null
    ) {}
}

/**
 * The DOMText class inherits from <classname>DOMCharacterData</classname> and represents the textual content of
 * a <classname>DOMElement</classname> or <classname>DOMAttr</classname>.
 * @link https://php.net/manual/en/class.domtext.php
 */
class DOMText extends DOMCharacterData
{
    /**
     * Holds all the text of logically-adjacent (not separated by Element, Comment or Processing Instruction) Text nodes.
     * @link https://php.net/manual/en/class.domtext.php#domtext.props.wholeText
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $wholeText;

    /**
     * Breaks this node into two nodes at the specified offset
     * @link https://php.net/manual/en/domtext.splittext.php
     * @param int $offset <p>
     * The offset at which to split, starting from 0.
     * </p>
     * @return DOMText The new node of the same type, which contains all the content at and after the
     * offset.
     */
    public function splitText(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $offset) {}

    /**
     * Indicates whether this text node contains whitespace
     * @link https://php.net/manual/en/domtext.iswhitespaceinelementcontent.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function isWhitespaceInElementContent(): bool {}

    #[TentativeType]
    public function isElementContentWhitespace(): bool {}

    /**
     * @param $content
     */
    public function replaceWholeText($content) {}

    /**
     * Creates a new <classname>DOMText</classname> object
     * @link https://php.net/manual/en/domtext.construct.php
     * @param string $data [optional] The value of the text node. If not supplied an empty text node is created.
     */
    public function __construct(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data) {}
}

/**
 * The DOMComment class represents comment nodes,
 * characters delimited by lt;!-- and --&gt;.
 * @link https://php.net/manual/en/class.domcomment.php
 */
class DOMComment extends DOMCharacterData
{
    /**
     * Creates a new DOMComment object
     * @link https://php.net/manual/en/domcomment.construct.php
     * @param string $data [optional] The value of the comment
     */
    public function __construct(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data) {}
}

/**
 * @removed 8.0
 */
class DOMTypeinfo {}

/**
 * @removed 8.0
 */
class DOMUserDataHandler
{
    public function handle() {}
}

/**
 * @removed 8.0
 */
class DOMDomError {}

/**
 * @removed 8.0
 */
class DOMErrorHandler
{
    /**
     * @param DOMDomError $error
     */
    public function handleError(DOMDomError $error) {}
}

/**
 * @removed 8.0
 */
class DOMLocator {}

/**
 * @removed 8.0
 */
class DOMConfiguration
{
    /**
     * @param $name
     * @param $value
     */
    public function setParameter($name, $value) {}

    /**
     * @param $name [optional]
     */
    public function getParameter($name) {}

    /**
     * @param $name [optional]
     * @param $value [optional]
     */
    public function canSetParameter($name, $value) {}
}

/**
 * The DOMCdataSection inherits from DOMText for textural representation of CData constructs.
 * @link https://secure.php.net/manual/en/class.domcdatasection.php
 */
class DOMCdataSection extends DOMText
{
    /**
     * The value of the CDATA node. If not supplied, an empty CDATA node is created.
     * @param string $data The value of the CDATA node. If not supplied, an empty CDATA node is created.
     * @link https://secure.php.net/manual/en/domcdatasection.construct.php
     */
    public function __construct(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data) {}
}

/**
 * The DOMDocumentType class
 * @link https://php.net/manual/en/class.domdocumenttype.php
 */
class DOMDocumentType extends DOMNode
{
    /**
     * @var string
     * The public identifier of the external subset.
     * @link https://php.net/manual/en/class.domdocumenttype.php#domdocumenttype.props.publicid
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $publicId;

    /**
     * @var string
     * The system identifier of the external subset. This may be an absolute URI or not.
     * @link https://php.net/manual/en/class.domdocumenttype.php#domdocumenttype.props.systemid
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $systemId;

    /**
     * @var string
     * The name of DTD; i.e., the name immediately following the DOCTYPE keyword.
     * @link https://php.net/manual/en/class.domdocumenttype.php#domdocumenttype.props.name
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $name;

    /**
     * @var DOMNamedNodeMap
     * A <classname>DOMNamedNodeMap</classname> containing the general entities, both external and internal, declared in the DTD.
     * @link https://php.net/manual/en/class.domdocumenttype.php#domdocumenttype.props.entities
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMNamedNodeMap'], default: '')]
    public $entities;

    /**
     * @var DOMNamedNodeMap
     * A <clasname>DOMNamedNodeMap</classname> containing the notations declared in the DTD.
     * @link https://php.net/manual/en/class.domdocumenttype.php#domdocumenttype.props.notations
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMNamedNodeMap'], default: '')]
    public $notations;

    /**
     * @var string|null
     * The internal subset as a string, or null if there is none. This is does not contain the delimiting square brackets.
     * @link https://php.net/manual/en/class.domdocumenttype.php#domdocumenttype.props.internalsubset
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $internalSubset;
}

/**
 * The DOMNotation class
 * @link https://php.net/manual/en/class.domnotation.php
 */
class DOMNotation extends DOMNode
{
    /**
     * @var string
     *
     * @link https://php.net/manual/en/class.domnotation.php#domnotation.props.publicid
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $publicId;

    /**
     * @var string
     *
     * @link https://php.net/manual/en/class.domnotation.php#domnotation.props.systemid
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $systemId;
}

/**
 * The DOMEntity class represents a known entity, either parsed or unparsed, in an XML document.
 * @link https://php.net/manual/en/class.domentity.php
 */
class DOMEntity extends DOMNode
{
    /**
     * @var string|null
     * The public identifier associated with the entity if specified, and NULL otherwise.
     * @link https://php.net/manual/en/class.domentity.php#domentity.props.publicid
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $publicId;

    /**
     * @var string|null
     * The system identifier associated with the entity if specified, and NULL otherwise. This may be an
     * absolute URI or not.
     * @link https://php.net/manual/en/class.domentity.php#domentity.props.systemid
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $systemId;

    /**
     * @var string|null
     * For unparsed entities, the name of the notation for the entity. For parsed entities, this is NULL.
     * @link https://php.net/manual/en/class.domentity.php#domentity.props.notationname
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $notationName;

    /**
     * @var string|null
     * An attribute specifying the encoding used for this entity at the time of parsing, when it is an external
     * parsed entity. This is NULL if it an entity from the internal subset or if it is not known.
     * @link https://php.net/manual/en/class.domentity.php#domentity.props.actualencoding
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $actualEncoding;

    /**
     * @var string|null
     * An attribute specifying, as part of the text declaration, the encoding of this entity, when it is an external
     * parsed entity. This is NULL otherwise.
     * @link https://php.net/manual/en/class.domentity.php#domentity.props.encoding
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $encoding;

    /**
     * @var string|null
     * An attribute specifying, as part of the text declaration, the version number of this entity, when it is an
     * external parsed entity. This is NULL otherwise.
     * @link https://php.net/manual/en/class.domentity.php#domentity.props.version
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $version;
}

/**
 * Extends DOMNode.
 * @link https://php.net/manual/en/class.domentityreference.php
 */
class DOMEntityReference extends DOMNode
{
    /**
     * Creates a new DOMEntityReference object
     * @link https://php.net/manual/en/domentityreference.construct.php
     * @param string $name The name of the entity reference.
     */
    public function __construct(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name) {}
}

/**
 * The DOMProcessingInstruction class
 * @link https://php.net/manual/en/class.domprocessinginstruction.php
 */
class DOMProcessingInstruction extends DOMNode
{
    /**
     * @link https://php.net/manual/en/class.domprocessinginstruction.php#domprocessinginstruction.props.target
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $target;

    /**
     * @link https://php.net/manual/en/class.domprocessinginstruction.php#domprocessinginstruction.props.data
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $data;

    /**
     * Creates a new <classname>DOMProcessingInstruction</classname> object
     * @link https://php.net/manual/en/domprocessinginstruction.construct.php
     * @param string $name The tag name of the processing instruction.
     * @param string $value [optional] The value of the processing instruction.
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $value
    ) {}
}

class DOMStringExtend
{
    /**
     * @param $offset32
     */
    public function findOffset16($offset32) {}

    /**
     * @param $offset16
     */
    public function findOffset32($offset16) {}
}

/**
 * The DOMXPath class (supports XPath 1.0)
 * @link https://php.net/manual/en/class.domxpath.php
 */
class DOMXPath
{
    /**
     * @var DOMDocument
     *
     * @link https://php.net/manual/en/class.domxpath.php#domxpath.props.document
     */
    #[LanguageLevelTypeAware(['8.1' => 'DOMDocument'], default: '')]
    public $document;

    #[LanguageLevelTypeAware(['8.1' => 'bool'], default: '')]
    public $registerNodeNamespaces;

    /**
     * Creates a new <classname>DOMXPath</classname> object
     * @link https://php.net/manual/en/domxpath.construct.php
     * @param DOMDocument $document The <classname>DOMDocument</classname> associated with the <classname>DOMXPath</classname>.
     * @param bool $registerNodeNS [optional] allow global flag to configure query() or evaluate() calls. Since 8.0.
     */
    public function __construct(DOMDocument $document, #[PhpStormStubsElementAvailable(from: '8.0')] bool $registerNodeNS = true) {}

    /**
     * Registers the namespace with the <classname>DOMXPath</classname> object
     * @link https://php.net/manual/en/domxpath.registernamespace.php
     * @param string $prefix <p>
     * The prefix.
     * </p>
     * @param string $namespace <p>
     * The URI of the namespace.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function registerNamespace(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $prefix,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $namespace
    ): bool {}

    /**
     * Evaluates the given XPath expression
     * @link https://php.net/manual/en/domxpath.query.php
     * @param string $expression <p>
     * The XPath expression to execute.
     * </p>
     * @param DOMNode $contextNode [optional] <p>
     * The optional contextnode can be specified for
     * doing relative XPath queries. By default, the queries are relative to
     * the root element.
     * </p>
     * @param bool $registerNodeNS [optional] <p>The optional registerNodeNS can be specified to
     * disable automatic registration of the context node.</p>
     * @return DOMNodeList|false a DOMNodeList containing all nodes matching
     * the given XPath expression. Any expression which does not return nodes
     * will return an empty DOMNodeList. The return is false if the expression
     * is malformed or the contextnode is invalid.
     */
    #[TentativeType]
    public function query(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] #[Language('XPath')] $expression,
        #[LanguageLevelTypeAware(['8.0' => 'DOMNode|null'], default: '')] $contextNode = null,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $registerNodeNS = true
    ): mixed {}

    /**
     * Evaluates the given XPath expression and returns a typed result if possible.
     * @link https://php.net/manual/en/domxpath.evaluate.php
     * @param string $expression <p>
     * The XPath expression to execute.
     * </p>
     * @param DOMNode $contextNode [optional] <p>
     * The optional contextnode can be specified for
     * doing relative XPath queries. By default, the queries are relative to
     * the root element.
     * </p>
     * @param bool $registerNodeNS [optional]
     * <p>
     * The optional registerNodeNS can be specified to disable automatic registration of the context node.
     * </p>
     * @return mixed a typed result if possible or a DOMNodeList
     * containing all nodes matching the given XPath expression.
     */
    #[TentativeType]
    public function evaluate(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] #[Language('XPath')] $expression,
        #[LanguageLevelTypeAware(['8.0' => 'DOMNode|null'], default: '')] $contextNode = null,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $registerNodeNS = true
    ): mixed {}

    /**
     * Register PHP functions as XPath functions
     * @link https://php.net/manual/en/domxpath.registerphpfunctions.php
     * @param string|string[] $restrict [optional] <p>
     * Use this parameter to only allow certain functions to be called from XPath.
     * </p>
     * <p>
     * This parameter can be either a string (a function name) or
     * an array of function names.
     * </p>
     * @return void
     */
    public function registerPhpFunctions($restrict = null) {}
}

/**
 * @property-read DOMElement|null $firstElementChild
 * @property-read DOMElement|null $lastElementChild
 * @property-read int $childElementCount
 *
 * @since 8.0
 */
interface DOMParentNode
{
    /**
     * Appends one or many nodes to the list of children behind the last
     * child node.
     *
     * @param DOMNode|string|null ...$nodes
     * @return void
     * @since 8.0
     */
    public function append(...$nodes): void;

    /**
     * Prepends one or many nodes to the list of children before the first
     * child node.
     *
     * @param DOMNode|string|null ...$nodes
     * @return void
     * @since 8.0
     */
    public function prepend(...$nodes): void;
}

/**
 * @property-read DOMElement|null $previousElementSibling
 * @property-read DOMElement|null $nextElementSibling
 *
 * @since 8.0
 */
interface DOMChildNode
{
    /**
     * Acts as a simpler version of {@see DOMNode::removeChild()}.
     *
     * @return void
     * @since 8.0
     */
    public function remove(): void;

    /**
     * Add passed node(s) before the current node
     *
     * @param DOMNode|string|null ...$nodes
     * @return void
     * @since 8.0
     */
    public function before(...$nodes): void;

    /**
     * Add passed node(s) after  the current node
     *
     * @param DOMNode|string|null ...$nodes
     * @return void
     * @since 8.0
     */
    public function after(...$nodes): void;

    /**
     * Replace current node with new node(s), a combination
     * of {@see DOMChildNode::remove()} + {@see DOMChildNode::append()}.
     *
     * @param DOMNode|string|null ...$nodes
     * @return void
     * @since 8.0
     */
    public function replaceWith(...$nodes): void;
}
