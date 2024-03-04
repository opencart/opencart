<?php
/**
 * Helper autocomplete for php solr extension.
 *
 * @author Pierre-Julien Mazenot <pj.mazenot@gmail.com>
 * @link   https://github.com/pjmazenot/phpsolr-phpdoc
 */

/**
 * (PECL solr &gt;= 0.9.2)<br/>
 * Class SolrDocument<br/>
 * This class represents a Solr document retrieved from a query response.
 * @link https://php.net/manual/en/class.solrinputdocument.php
 */
final class SolrDocument implements ArrayAccess, Iterator, Serializable
{
    /** @var int Sorts the fields in ascending order. */
    public const SORT_DEFAULT = 1;

    /** @var int Sorts the fields in ascending order. */
    public const SORT_ASC = 1;

    /** @var int Sorts the fields in descending order. */
    public const SORT_DESC = 2;

    /** @var int Sorts the fields by name */
    public const SORT_FIELD_NAME = 1;

    /** @var int Sorts the fields by number of values. */
    public const SORT_FIELD_VALUE_COUNT = 2;

    /** @var int Sorts the fields by boost value. */
    public const SORT_FIELD_BOOST_VALUE = 4;

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Adds a field to the document
     * @link https://php.net/manual/en/solrdocument.addfield.php
     * @param string $fieldName <p>
     * The name of the field
     * </p>
     * @param string $fieldValue <p>
     * The value for the field.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function addField($fieldName, $fieldValue) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Drops all the fields in the document
     * @link https://php.net/manual/en/solrdocument.clear.php
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function clear() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Creates a copy of a SolrDocument object
     * @link https://php.net/manual/en/solrdocument.clone.php
     */
    public function __clone() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * SolrDocument constructor.
     * @link https://php.net/manual/en/solrdocument.construct.php
     */
    public function __construct() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Retrieves the current field
     * @link https://php.net/manual/en/solrdocument.current.php
     * @return SolrDocumentField <p>
     * Returns the field
     * </p>
     */
    public function current() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Removes a field from the document
     * @link https://php.net/manual/en/solrdocument.deletefield.php
     * @param string $fieldName <p>
     * The name of the field.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function deleteField($fieldName) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Destructor
     * @link https://php.net/manual/en/solrdocument.destruct.php
     */
    public function __destruct() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Checks if a field exists in the document
     * @link https://php.net/manual/en/solrdocument.fieldexists.php
     * @param string $fieldName <p>
     * Name of the field.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> if the field is present and <b>FALSE</b> if it does not.
     * </p>
     */
    public function fieldExists($fieldName) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Access the field as a property
     * @link https://php.net/manual/en/solrdocument.get.php
     * @param string $fieldName <p>
     * The name of the field.
     * </p>
     * @return SolrDocumentField <p>
     * Returns a SolrDocumentField instance.
     * </p>
     */
    public function __get($fieldName) {}

    /**
     * (PECL solr &gt;= 2.3.0)<br/>
     * Returns an array of child documents (SolrInputDocument)
     * @link https://php.net/manual/en/solrdocument.getchilddocuments.php
     * @return SolrInputDocument[]
     */
    public function getChildDocuments() {}

    /**
     * (PECL solr &gt;= 2.3.0)<br/>
     * Returns the number of child documents
     * @link https://php.net/manual/en/solrdocument.getchilddocumentscount.php
     * @return int
     */
    public function getChildDocumentsCount() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Retrieves a field by name
     * @link https://php.net/manual/en/solrdocument.getfield.php
     * @param string $fieldName <p>
     * The name of the field.
     * </p>
     * @return SolrDocumentField|false Returns a SolrDocumentField object on success and <b>FALSE</b> on failure
     */
    public function getField($fieldName) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns the number of fields in this document
     * @link https://php.net/manual/en/solrdocument.getfieldcount.php
     * @return int|false <p>
     * Returns an integer on success and <b>FALSE</b> on failure.
     * </p>
     */
    public function getFieldCount() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns an array containing all the fields in the document
     * @link https://php.net/manual/en/solrdocument.getfieldnames.php
     * @return array|false <p>
     * Returns an array on success and <b>FALSE</b> on failure.
     * </p>
     */
    public function getFieldNames() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns a SolrInputDocument equivalent of the object
     * @link https://php.net/manual/en/solrdocument.getinputdocument.php
     * @return SolrInputDocument <p>
     * Returns a SolrInputDocument on success and <b>NULL</b> on failure.
     * </p>
     */
    public function getInputDocument() {}

    /**
     * (PECL solr &gt;= 2.3.0)<br/>
     * Checks whether the document has any child documents
     * @link https://php.net/manual/en/solrdocument.haschilddocuments.php
     * @return bool <p>
     * Returns <b>TRUE</b> if the document has any child documents
     * </p>
     */
    public function hasChildDocuments() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Checks if a field exists
     * @link https://php.net/manual/en/solrdocument.isset.php
     * @param string $fieldName <p>
     * Name of the field.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function __isset($fieldName) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Retrieves the current key
     * @link https://php.net/manual/en/solrdocument.key.php
     * @return string <p>
     * Returns the current key.
     * </p>
     */
    public function key() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Merges one input document into another
     * @link https://php.net/manual/en/solrdocument.merge.php
     * @param SolrInputDocument $sourceDoc <p>
     * The source document.
     * </p>
     * @param bool $overwrite [optional] <p>
     * If this is <b>TRUE</b> then fields with the same name in the destination document will be overwritten.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function merge(SolrInputDocument $sourceDoc, $overwrite = true) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Moves the internal pointer to the next field
     * @link https://php.net/manual/en/solrdocument.next.php
     */
    public function next() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Checks if a particular field exists
     * @link https://php.net/manual/en/solrdocument.offsetexists.php
     * @param string $fieldName <p>
     * The name of the field.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function offsetExists($fieldName) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Retrieves a field
     * @link https://php.net/manual/en/solrdocument.offsetget.php
     * @param string $fieldName <p>
     * The name of the field.
     * </p>
     * @return SolrDocumentField <p>
     * Returns a SolrDocumentField object.
     * </p>
     */
    public function offsetGet($fieldName) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Adds a field to the document
     * @link https://php.net/manual/en/solrdocument.offsetset.php
     * @param string $fieldName <p>
     * The name of the field.
     * </p>
     * @param string $fieldValue <p>
     * The value for this field.
     * </p>
     * @return bool
     */
    public function offsetSet($fieldName, $fieldValue) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Removes a field
     * @link https://php.net/manual/en/solrdocument.offsetunset.php
     * @param string $fieldName <p>
     * The name of the field.
     * </p>
     */
    public function offsetUnset($fieldName) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * This is an alias of SolrDocument::clear
     * @link https://php.net/manual/en/solrdocument.reset.php
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function reset() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Resets the internal pointer to the beginning
     * @link https://php.net/manual/en/solrdocument.rewind.php
     */
    public function rewind() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Used for custom serialization
     * @link https://php.net/manual/en/solrdocument.serialize.php
     * @return string <p>
     * Returns a string representing the serialized Solr document.
     * </p>
     */
    public function serialize() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Adds another field to the document
     * @link https://php.net/manual/en/solrdocument.set.php
     * @param string $fieldName <p>
     * Name of the field.
     * </p>
     * @param string $fieldValue <p>
     * Field value.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function __set($fieldName, $fieldValue) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Sorts the fields within the document
     * @link https://php.net/manual/en/solrdocument.sort.php
     * @param int $sortOrderBy <p>
     * The sort criteria, must be one of :
     * <ul>
     * <li>SolrDocument::SORT_FIELD_NAME</li>
     * <li>SolrDocument::SORT_FIELD_BOOST_VALUE</li>
     * <li>SolrDocument::SORT_FIELD_VALUE_COUNT</li>
     * </ul>
     * </p>
     * @param int $sortDirection [optional] <p>
     * The sort direction, can be one of :
     * <ul>
     * <li>SolrDocument::SORT_DEFAULT</li>
     * <li>SolrDocument::SORT_ASC</li>
     * <li>SolrDocument::SORT_DESC</li>
     * </ul>
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function sort($sortOrderBy, $sortDirection = SolrInputDocument::SORT_ASC) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns an array representation of the document
     * @link https://secure.php.net/manual/en/solrdocument.toarray.php
     * @return array <p>
     * Returns an array representation of the document.
     * </p>
     */
    public function toArray() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Custom serialization of SolrDocument objects
     * @link https://php.net/manual/en/solrdocument.unserialize.php
     * @param string $serialized <p>
     * An XML representation of the document.
     * </p>
     */
    public function unserialize($serialized) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Removes a field from the document
     * @link https://php.net/manual/en/solrdocument.unset.php
     * @param string $fieldName <p>
     * The name of the field.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function __unset($fieldName) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Checks if the current position internally is still valid
     * @link https://php.net/manual/en/solrdocument.valid.php
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function valid() {}
}
