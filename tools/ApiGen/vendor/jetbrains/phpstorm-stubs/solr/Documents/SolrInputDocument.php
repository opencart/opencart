<?php
/**
 * Helper autocomplete for php solr extension.
 *
 * @author Pierre-Julien Mazenot <pj.mazenot@gmail.com>
 * @link   https://github.com/pjmazenot/phpsolr-phpdoc
 */

/**
 * (PECL solr &gt;= 0.9.2)<br/>
 * Class SolrInputDocument<br/>
 * This class represents a Solr document that is about to be submitted to the Solr index.
 * @link https://php.net/manual/en/class.solrinputdocument.php
 */
final class SolrInputDocument
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
     * (PECL solr &gt;= 2.3.0)<br/>
     * Adds a child document for block indexing
     * @link https://php.net/manual/en/solrinputdocument.addchilddocument.php
     * @param SolrInputDocument $child <p>
     * A SolrInputDocument object.
     * </p>
     * @throws SolrIllegalArgumentException
     * @throws SolrException
     */
    public function addChildDocument(SolrInputDocument $child) {}

    /**
     * (PECL solr &gt;= 2.3.0)<br/>
     * Adds an array of child documents
     * @link https://php.net/manual/en/solrinputdocument.addchilddocuments.php
     * @param array &$docs <p>
     * An array of SolrInputDocument objects.
     * </p>
     * @throws SolrIllegalArgumentException
     * @throws SolrException
     */
    public function addChildDocuments(array &$docs) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Adds a field to the document
     * @link https://php.net/manual/en/solrinputdocument.addfield.php
     * @param string $fieldName <p>
     * The name of the field
     * </p>
     * @param string $fieldValue <p>
     * The value for the field.
     * </p>
     * @param float $fieldBoostValue [optional] <p>
     * The index time boost for the field. Though this cannot be negative, you can still pass values less than 1.0 but
     * they must be greater than zero.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function addField($fieldName, $fieldValue, $fieldBoostValue = 0.0) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Resets the input document
     * @link https://php.net/manual/en/solrinputdocument.clear.php
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function clear() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Creates a copy of a SolrDocument
     * @link https://php.net/manual/en/solrinputdocument.clone.php
     */
    public function __clone() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * SolrInputDocument constructor.
     * @link https://php.net/manual/en/solrinputdocument.construct.php
     */
    public function __construct() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Removes a field from the document
     * @link https://php.net/manual/en/solrinputdocument.construct.php
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
     * @link https://php.net/manual/en/solrinputdocument.destruct.php
     */
    public function __destruct() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Checks if a field exists
     * @link https://php.net/manual/en/solrinputdocument.fieldexists.php
     * @param string $fieldName <p>
     * Name of the field.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> if the field was found and <b>FALSE</b> if it was not found.
     * </p>
     */
    public function fieldExists($fieldName) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Retrieves the current boost value for the document
     * @link https://php.net/manual/en/solrinputdocument.getboost.php
     * @return float|false <p>
     * Returns the boost value on success and <b>FALSE</b> on failure.
     * </p>
     */
    public function getBoost() {}

    /**
     * (PECL solr &gt;= 2.3.0)<br/>
     * Returns an array of child documents (SolrInputDocument)
     * @link https://php.net/manual/en/solrinputdocument.getchilddocuments.php
     * @return SolrInputDocument[]
     */
    public function getChildDocuments() {}

    /**
     * (PECL solr &gt;= 2.3.0)<br/>
     * Returns the number of child documents
     * @link https://php.net/manual/en/solrinputdocument.getchilddocumentscount.php
     * @return int
     */
    public function getChildDocumentsCount() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Retrieves a field by name
     * @link https://php.net/manual/en/solrinputdocument.getfield.php
     * @param string $fieldName <p>
     * The name of the field.
     * </p>
     * @return SolrDocumentField|false Returns a SolrDocumentField object on success and <b>FALSE</b> on failure.
     */
    public function getField($fieldName) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Retrieves the boost value for a particular field
     * @link https://php.net/manual/en/solrinputdocument.getfieldboost.php
     * @param string $fieldName <p>
     * The name of the field.
     * </p>
     * @return float|false <p>
     * Returns the boost value for the field or <b>FALSE</b> if there was an error.
     * </p>
     */
    public function getFieldBoost($fieldName) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns the number of fields in the document
     * @link https://php.net/manual/en/solrinputdocument.getfieldcount.php
     * @return int|false <p>
     * Returns an integer on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function getFieldCount() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns an array containing all the fields in the document
     * @link https://php.net/manual/en/solrinputdocument.getfieldnames.php
     * @return array|false <p>
     * Returns an array on success and <b>FALSE</b> on failure.
     * </p>
     */
    public function getFieldNames() {}

    /**
     * (PECL solr &gt;= 2.3.0)<br/>
     * Checks whether the document has any child documents
     * @link https://php.net/manual/en/solrinputdocument.haschilddocuments.php
     * @return bool <p>
     * Returns <b>TRUE</b> if the document has any child documents
     * </p>
     */
    public function hasChildDocuments() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Merges one input document into another
     * @link https://php.net/manual/en/solrinputdocument.merge.php
     * @param SolrInputDocument $sourceDoc <p>
     * The source document.
     * </p>
     * @param bool $overwrite [optional] <p>
     * If this is <b>TRUE</b> it will replace matching fields in the destination document.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure. In the future, this will be modified to return the
     * number of fields in the new document.
     * </p>
     */
    public function merge(SolrInputDocument $sourceDoc, $overwrite = true) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * This is an alias of SolrInputDocument::clear
     * @link https://php.net/manual/en/solrinputdocument.reset.php
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function reset() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Sets the boost value for this document
     * @link https://php.net/manual/en/solrinputdocument.setboost.php
     * @param float $documentBoostValue <p>
     * The index-time boost value for this document.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function setBoost($documentBoostValue) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Sets the index-time boost value for a field
     * https://php.net/manual/en/solrinputdocument.setfieldboost.php
     * @param string $fieldName <p>
     * The name of the field.
     * </p>
     * @param float $fieldBoostValue <p>
     * The index time boost value.
     * </p>
     */
    public function setFieldBoost($fieldName, $fieldBoostValue) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Sorts the fields within the document
     * @link https://php.net/manual/en/solrinputdocument.sort.php
     * @param int $sortOrderBy <p>
     * The sort criteria, must be one of :
     * <ul>
     * <li>SolrInputDocument::SORT_FIELD_NAME</li>
     * <li>SolrInputDocument::SORT_FIELD_BOOST_VALUE</li>
     * <li>SolrInputDocument::SORT_FIELD_VALUE_COUNT</li>
     * </ul>
     * </p>
     * @param int $sortDirection [optional] <p>
     * The sort direction, can be one of :
     * <ul>
     * <li>SolrInputDocument::SORT_DEFAULT</li>
     * <li>SolrInputDocument::SORT_ASC</li>
     * <li>SolrInputDocument::SORT_DESC</li>
     * </ul>
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function sort($sortOrderBy, $sortDirection = SolrInputDocument::SORT_ASC) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns an array representation of the input document
     * @link https://php.net/manual/en/solrinputdocument.toarray.php
     * @return array|false <p>
     * Returns an array containing the fields. It returns FALSE on failure.
     * </p>
     */
    public function toArray() {}
}
