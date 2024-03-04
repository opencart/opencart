<?php
/**
 * Helper autocomplete for php solr extension.
 *
 * @author Pierre-Julien Mazenot <pj.mazenot@gmail.com>
 * @link   https://github.com/pjmazenot/phpsolr-phpdoc
 */

/**
 * (PECL solr &gt;= 0.9.2)<br/>
 * Class SolrDocumentField<br/>
 * This class represents a field in a Solr document. All its properties are read-only.
 * @link https://php.net/manual/en/class.solrdocumentfield.php
 */
final class SolrDocumentField
{
    /** @var string [readonly] The name of the field. */
    public $name;

    /** @var string [readonly] The boost value for the field */
    public $boost;

    /** @var string [readonly] An array of values for this field */
    public $values;

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * SolrDocument constructor.
     * @link https://php.net/manual/en/solrdocumentfield.construct.php
     */
    public function __construct() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Destructor
     * @link https://php.net/manual/en/solrdocumentfield.destruct.php
     */
    public function __destruct() {}
}
