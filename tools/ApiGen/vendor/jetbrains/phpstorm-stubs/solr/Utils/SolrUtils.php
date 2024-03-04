<?php
/**
 * Helper autocomplete for php solr extension.
 *
 * @author Pierre-Julien Mazenot <pj.mazenot@gmail.com>
 * @link   https://github.com/pjmazenot/phpsolr-phpdoc
 */

/**
 * (PECL solr &gt;= 0.9.2)<br/>
 * Class SolrUtils<br/>
 * Contains utility methods for retrieving the current extension version and preparing query phrases.
 * Also contains method for escaping query strings and parsing XML responses.
 * @link https://php.net/manual/en/class.solrutils.php
 */
abstract class SolrUtils
{
    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Parses an response XML string into a SolrObject
     * @link https://php.net/manual/en/solrutils.digestxmlresponse.php
     * @param string $xmlresponse <p>
     * The XML response string from the Solr server.
     * </p>
     * @param int $parse_mode [optional] <p>
     * Use SolrResponse::PARSE_SOLR_OBJ or SolrResponse::PARSE_SOLR_DOC
     * </p>
     * @return SolrObject <p>
     * Returns the SolrObject representing the XML response.
     * </p>
     * <p>
     * If the parse_mode parameter is set to SolrResponse::PARSE_SOLR_OBJ Solr documents will be parses as SolrObject instances.
     * </p>
     * <p>
     * If it is set to SolrResponse::PARSE_SOLR_DOC, they will be parsed as SolrDocument instances.
     * </p>
     * @throws SolrException
     */
    public static function digestXmlResponse($xmlresponse, $parse_mode = 0) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Escapes a lucene query string
     * @link https://php.net/manual/en/solrutils.escapequerychars.php
     * @param string $str <p>
     * This is the query string to be escaped.
     * </p>
     * @return string|false <p>
     * Returns the escaped string or <b>FALSE</b> on failure.
     * </p>
     */
    public static function escapeQueryChars($str) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns the current version of the Solr extension
     * @link https://php.net/manual/en/solrutils.getsolrversion.php
     * @return string <p>
     * The current version of the Apache Solr extension.
     * </p>
     */
    public static function getSolrVersion() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Prepares a phrase from an unescaped lucene string
     * @link https://php.net/manual/en/solrutils.queryphrase.php
     * @param string $str <p>
     * The lucene phrase.
     * </p>
     * @return string <p>
     * Returns the phrase contained in double quotes.
     * </p>
     */
    public static function queryPhrase($str) {}
}
