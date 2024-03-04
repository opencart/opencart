<?php
/**
 * Helper autocomplete for php solr extension.
 *
 * @author Pierre-Julien Mazenot <pj.mazenot@gmail.com>
 * @link   https://github.com/pjmazenot/phpsolr-phpdoc
 */

/**
 * (PECL solr &gt;= 2.2.0)<br/>
 * Class SolrCollapseFunction
 * @link https://php.net/manual/en/class.solrcollapsefunction.php
 */
class SolrCollapseFunction
{
    /** @var string */
    public const NULLPOLICY_IGNORE = 'ignore';

    /** @var string */
    public const NULLPOLICY_EXPAND = 'expand';

    /** @var string */
    public const NULLPOLICY_COLLAPSE = 'collapse';

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * SolrCollapseFunction constructor.
     * @link https://php.net/manual/en/solrcollapsefunction.construct.php
     * @param string $field [optional] <p>
     * The field name to collapse on.<br/>
     * In order to collapse a result. The field type must be a single valued String, Int or Float.
     * </p>
     */
    public function __construct($field) {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Returns the field that is being collapsed on.
     * @link https://php.net/manual/en/solrcollapsefunction.getfield.php
     * @return string
     */
    public function getField() {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Returns collapse hint
     * @link https://php.net/manual/en/solrcollapsefunction.gethint.php
     * @return string
     */
    public function getHint() {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Returns max parameter
     * @link https://php.net/manual/en/solrcollapsefunction.getmax.php
     * @return string
     */
    public function getMax() {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Returns min parameter
     * @link https://php.net/manual/en/solrcollapsefunction.getmin.php
     * @return string
     */
    public function getMin() {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Returns null policy
     * @link https://php.net/manual/en/solrcollapsefunction.getnullpolicy.php
     * @return string
     */
    public function getNullPolicy() {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Returns size parameter
     * @link https://php.net/manual/en/solrcollapsefunction.getsize.php
     * @return int
     */
    public function getSize() {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Sets the field to collapse on
     * @link https://php.net/manual/en/solrcollapsefunction.setfield.php
     * @param string $fieldName <p>
     * The field name to collapse on. In order to collapse a result. The field type must be a single valued String, Int
     * or Float.
     * </p>
     * @return SolrCollapseFunction
     */
    public function setField($fieldName) {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Sets collapse hint
     * @link https://php.net/manual/en/solrcollapsefunction.sethint.php
     * @param string $hint <p>
     * Currently there is only one hint available "top_fc", which stands for top level FieldCache
     * </p>
     * @return SolrCollapseFunction
     */
    public function setHint($hint) {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Selects the group heads by the max value of a numeric field or function query.
     * @link https://php.net/manual/en/solrcollapsefunction.setmax.php
     * @param string $max
     * @return SolrCollapseFunction
     */
    public function setMax($max) {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Sets the initial size of the collapse data structures when collapsing on a numeric field only
     * @link https://php.net/manual/en/solrcollapsefunction.setmin.php
     * @param string $min
     * @return SolrCollapseFunction
     */
    public function setMin($min) {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Sets the NULL Policy
     * @link https://php.net/manual/en/solrcollapsefunction.setnullpolicy.php
     * @param string $nullPolicy
     * @return SolrCollapseFunction
     */
    public function setNullPolicy($nullPolicy) {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Sets the initial size of the collapse data structures when collapsing on a numeric field only.
     * @link https://php.net/manual/en/solrcollapsefunction.setsize.php
     * @param int $size
     * @return SolrCollapseFunction
     */
    public function setSize($size) {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Returns a string representing the constructed collapse function
     * @link https://php.net/manual/en/solrcollapsefunction.tostring.php
     * @return string
     */
    public function __toString() {}
}
