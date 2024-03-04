<?php
/**
 * Helper autocomplete for php solr extension.
 *
 * @author Pierre-Julien Mazenot <pj.mazenot@gmail.com>
 * @link   https://github.com/pjmazenot/phpsolr-phpdoc
 */

/**
 * (PECL solr &gt;= 0.9.2)<br/>
 * Class SolrClientException<br/>
 * An exception thrown when there is an error while making a request to the server from the client.
 * @link https://php.net/manual/en/class.solrclientexception.php
 */
class SolrClientException extends SolrException
{
    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns internal information where the Exception was thrown
     * @link https://php.net/manual/en/solrclientexception.getinternalinfo.php
     * @return array <p>
     * Returns an array containing internal information where the error was thrown. Used only for debugging by extension
     * developers.
     * </p>
     */
    public function getInternalInfo() {}
}
