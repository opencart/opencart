<?php
/**
 * Helper autocomplete for php solr extension.
 *
 * @author Pierre-Julien Mazenot <pj.mazenot@gmail.com>
 * @link   https://github.com/pjmazenot/phpsolr-phpdoc
 */

/**
 * (PECL solr &gt;= 0.9.2)<br/>
 * Class SolrObject<br/>
 * This class represents an object whose properties can also by accessed using the array syntax. All its properties are
 * read-only.
 * @link https://php.net/manual/en/class.solrobject.php
 */
final class SolrObject implements ArrayAccess
{
    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * SolrObject constructor.
     * @link https://php.net/manual/en/solrobject.construct.php
     */
    public function __construct() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Destructor
     * @link https://php.net/manual/en/solrobject.destruct.php
     */
    public function __destruct() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns an array of all the names of the properties
     * @link https://php.net/manual/en/solrobject.getpropertynames.php
     * @return array <p>
     * Returns an array.
     * </p>
     */
    public function getPropertyNames() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Checks if the property exists
     * @link https://php.net/manual/en/solrobject.offsetexists.php
     * @param string $property_name <p>
     * The name of the property.
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function offsetExists($property_name) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Used to retrieve a property
     * @link https://php.net/manual/en/solrobject.offsetget.php
     * @param string $property_name <p>
     * The name of the property.
     * </p>
     * @return SolrDocumentField <p>
     * Returns the property value.
     * </p>
     */
    public function offsetGet($property_name) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Sets the value for a property
     * @link https://php.net/manual/en/solrobject.offsetset.php
     * @param string $property_name <p>
     * The name of the property.
     * </p>
     * @param string $property_value <p>
     * The new value.
     * </p>
     */
    public function offsetSet($property_name, $property_value) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Unsets the value for the property
     * @link https://php.net/manual/en/solrobject.offsetunset.php
     * @param string $property_name <p>
     * The name of the property.
     * </p>
     * @return bool
     */
    public function offsetUnset($property_name) {}
}
