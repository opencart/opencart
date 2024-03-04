<?php
/**
 * Helper autocomplete for php solr extension.
 *
 * @author Pierre-Julien Mazenot <pj.mazenot@gmail.com>
 * @link   https://github.com/pjmazenot/phpsolr-phpdoc
 */

/**
 * (PECL solr &gt;= 1.1.0, &gt;=2.0.0)<br/>
 * Class SolrServerException<br/>
 * An exception thrown when there is an error produced by the Solr Server itself.
 * @link https://php.net/manual/en/class.solrserverexception.php
 */
class SolrServerException extends SolrException
{
    /**
     * (PECL solr &gt;= 1.1.0, &gt;=2.0.0)<br/>
     * Returns internal information where the Exception was thrown
     * @link https://php.net/manual/en/solrserverexception.getinternalinfo.php
     * @return array <p>
     * Returns an array containing internal information where the error was thrown. Used only for debugging by extension
     * developers.
     * </p>
     */
    public function getInternalInfo() {}
}
