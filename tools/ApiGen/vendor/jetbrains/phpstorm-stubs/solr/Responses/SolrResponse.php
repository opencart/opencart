<?php
/**
 * Helper autocomplete for php solr extension.
 *
 * @author Pierre-Julien Mazenot <pj.mazenot@gmail.com>
 * @link   https://github.com/pjmazenot/phpsolr-phpdoc
 */

/**
 * (PECL solr &gt;= 0.9.2)<br/>
 * Class SolrResponse<br/>
 * This class represents a response from the Solr server.
 * @link https://php.net/manual/en/class.solrresponse.php
 */
abstract class SolrResponse
{
    /** @var int Documents should be parsed as SolrObject instances */
    public const PARSE_SOLR_OBJ = 0;

    /** @var int Documents should be parsed as SolrDocument instances. */
    public const PARSE_SOLR_DOC = 1;

    /** @var int The http status of the response. */
    protected $http_status;

    /** @var int Whether to parse the solr documents as SolrObject or SolrDocument instances. */
    protected $parser_mode;

    /** @var bool Was there an error during the request */
    protected $success;

    /** @var string Detailed message on http status */
    protected $http_status_message;

    /** @var string The request URL */
    protected $http_request_url;

    /** @var string A string of raw headers sent during the request. */
    protected $http_raw_request_headers;

    /** @var string The raw request sent to the server */
    protected $http_raw_request;

    /** @var string Response headers from the Solr server. */
    protected $http_raw_response_headers;

    /** @var string The response message from the server. */
    protected $http_raw_response;

    /** @var string The response in PHP serialized format. */
    protected $http_digested_response;

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns the XML response as serialized PHP data
     * @link https://php.net/manual/en/solrresponse.getdigestedresponse.php
     * @return string <p>
     * Returns the XML response as serialized PHP data
     * </p>
     */
    public function getDigestedResponse() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns the HTTP status of the response
     * @link https://php.net/manual/en/solrresponse.gethttpstatus.php
     * @return int <p>
     * Returns the HTTP status of the response.
     * </p>
     */
    public function getHttpStatus() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns more details on the HTTP status
     * @link https://php.net/manual/en/solrresponse.gethttpstatusmessage.php
     * @return string <p>
     * Returns more details on the HTTP status
     * </p>
     */
    public function getHttpStatusMessage() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns the raw request sent to the Solr server
     * @link https://php.net/manual/en/solrresponse.getrawrequest.php
     * @return string <p>
     * Returns the raw request sent to the Solr server
     * </p>
     */
    public function getRawRequest() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns the raw request headers sent to the Solr server
     * @link https://php.net/manual/en/solrresponse.getrawrequestheaders.php
     * @return string <p>
     * Returns the raw request headers sent to the Solr server
     * </p>
     */
    public function getRawRequestHeaders() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns the raw response from the server
     * @link https://php.net/manual/en/solrresponse.getrawresponse.php
     * @return string <p>
     * Returns the raw response from the server.
     * </p>
     */
    public function getRawResponse() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns the raw response headers from the server
     * @link https://php.net/manual/en/solrresponse.getrawresponseheaders.php
     * @return string <p>
     * Returns the raw response headers from the server.
     * </p>
     */
    public function getRawResponseHeaders() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns the full URL the request was sent to
     * @link https://php.net/manual/en/solrresponse.getrequesturl.php
     * @return string <p>
     * Returns the full URL the request was sent to
     * </p>
     */
    public function getRequestUrl() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Returns a SolrObject representing the XML response from the server
     * @link https://php.net/manual/en/solrresponse.getresponse.php
     * @return SolrObject <p>
     * Returns a SolrObject representing the XML response from the server
     * </p>
     */
    public function getResponse() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Sets the parse mode
     * @link https://php.net/manual/en/solrresponse.setparsemode.php
     * @param int $parser_mode <ul>
     * <li>SolrResponse::PARSE_SOLR_DOC parses documents in SolrDocument instances. </li>
     * <li>SolrResponse::PARSE_SOLR_OBJ parses document into SolrObjects.</li>
     * </ul>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function setParseMode($parser_mode = 0) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Was the request a success
     * @link https://php.net/manual/en/solrresponse.success.php
     * @return bool <p>
     * Returns <b>TRUE</b> if it was successful and <b>FALSE</b> if it was not.
     * </p>
     */
    public function success() {}
}
