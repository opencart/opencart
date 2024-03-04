<?php
/**
 * Helper autocomplete for php solr extension.
 *
 * @author Pierre-Julien Mazenot <pj.mazenot@gmail.com>
 * @link   https://github.com/pjmazenot/phpsolr-phpdoc
 */

/**
 * (PECL solr &gt;= 0.9.2)<br/>
 * Class SolrIllegalArgumentException<br/>
 * This object is thrown when an illegal or invalid argument is passed to a method.
 * @link https://php.net/manual/en/class.solrillegalargumentexception.php
 */
class SolrIllegalArgumentException extends SolrException
{
    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns internal information where the Exception was thrown
     * @link https://php.net/manual/en/solrillegalargumentexception.getinternalinfo.php
     * @return array <p>
     * Returns an array containing internal information where the error was thrown. Used only for debugging by extension
     * developers.
     * </p>
     */
    public function getInternalInfo() {}
}
