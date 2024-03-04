<?php
/**
 * Helper autocomplete for php solr extension.
 *
 * @author Pierre-Julien Mazenot <pj.mazenot@gmail.com>
 * @link   https://github.com/pjmazenot/phpsolr-phpdoc
 */

/**
 * (PECL solr &gt;= 0.9.2)<br/>
 * Class SolrClient<br/>
 * This class is used to send requests to a Solr server. Currently, cloning and serialization of SolrClient instances is
 * not supported.
 * @link https://php.net/manual/en/class.solrclient.php
 */
final class SolrClient
{
    /** @var int Used when updating the search servlet. */
    public const SEARCH_SERVLET_TYPE = 1;

    /** @var int Used when updating the update servlet. */
    public const UPDATE_SERVLET_TYPE = 2;

    /** @var int Used when updating the threads servlet. */
    public const THREADS_SERVLET_TYPE = 4;

    /** @var int Used when updating the ping servlet. */
    public const PING_SERVLET_TYPE = 8;

    /** @var int Used when updating the terms servlet. */
    public const TERMS_SERVLET_TYPE = 16;

    /** @var int Used when retrieving system information from the system servlet. */
    public const SYSTEM_SERVLET_TYPE = 32;

    /** @var string This is the initial value for the search servlet. */
    public const DEFAULT_SEARCH_SERVLET = 'select';

    /** @var string This is the initial value for the update servlet. */
    public const DEFAULT_UPDATE_SERVLET = 'update';

    /** @var string This is the initial value for the threads servlet. */
    public const DEFAULT_THREADS_SERVLET = 'admin/threads';

    /** @var string This is the initial value for the ping servlet. */
    public const DEFAULT_PING_SERVLET = 'admin/ping';

    /** @var string This is the initial value for the terms servlet used for the TermsComponent. */
    public const DEFAULT_TERMS_SERVLET = 'terms';

    /** @var string This is the initial value for the system servlet used to obtain Solr Server information. */
    public const DEFAULT_SYSTEM_SERVLET = 'admin/system';

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Adds a document to the index
     * @link https://php.net/manual/en/solrclient.adddocument.php
     * @param SolrInputDocument $doc <p>
     * The SolrInputDocument instance.
     * </p>
     * @param bool $overwrite [optional] <p>
     * Whether to overwrite existing document or not. If <b>FALSE</b> there will be duplicates (several documents with
     * the same ID).
     * </p>
     * <div>
     * <b>Warning</b><br/>
     * PECL Solr < 2.0 $allowDups was used instead of $overwrite, which does the same functionality with exact opposite
     * bool flag.<br/>
     * <br/>
     * $allowDups = false is the same as $overwrite = true
     * </div>
     * @param int $commitWithin [optional] <p>
     * Number of milliseconds within which to auto commit this document. Available since Solr 1.4 . Default (0) means
     * disabled.
     * </p>
     * <p>
     * When this value specified, it leaves the control of when to do the commit to Solr itself, optimizing number of
     * commits to a minimum while still fulfilling the update latency requirements, and Solr will automatically do a
     * commit when the oldest add in the buffer is due.
     * </p>
     * @return SolrUpdateResponse <p>
     * Returns a SolrUpdateResponse object or throws an Exception on failure.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function addDocument(SolrInputDocument $doc, $overwrite = true, $commitWithin = 0) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Adds a collection of SolrInputDocument instances to the index
     * @link https://php.net/manual/en/solrclient.adddocuments.php
     * @param array $docs <p>
     * An array containing the collection of SolrInputDocument instances. This array must be an actual variable.
     * </p>
     * @param bool $overwrite [optional] <p>
     * Whether to overwrite existing document or not. If <b>FALSE</b> there will be duplicates (several documents with
     * the same ID).
     * </p>
     * <div>
     * <b>Warning</b><br/>
     * PECL Solr < 2.0 $allowDups was used instead of $overwrite, which does the same functionality with exact opposite
     * bool flag.<br/>
     * <br/>
     * $allowDups = false is the same as $overwrite = true
     * </div>
     * @param int $commitWithin [optional] <p>
     * Number of milliseconds within which to auto commit this document. Available since Solr 1.4 . Default (0) means
     * disabled.
     * </p>
     * <p>
     * When this value specified, it leaves the control of when to do the commit to Solr itself, optimizing number of
     * commits to a minimum while still fulfilling the update latency requirements, and Solr will automatically do a
     * commit when the oldest add in the buffer is due.
     * </p>
     * @return SolrUpdateResponse <p>
     * Returns a SolrUpdateResponse object or throws an Exception on failure.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function addDocuments(array $docs, $overwrite = true, $commitWithin = 0) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Finalizes all add/deletes made to the index
     * @link https://php.net/manual/en/solrclient.commit.php
     * @param bool $softCommit [optional] <p>
     * This will refresh the 'view' of the index in a more performant manner, but without "on-disk" guarantees.
     * (Solr4.0+)
     * </p>
     * <p>
     * A soft commit is much faster since it only makes index changes visible and does not fsync index files or write a
     * new index descriptor. If the JVM crashes or there is a loss of power, changes that occurred after the last hard
     * commit will be lost. Search collections that have near-real-time requirements (that want index changes to be
     * quickly visible to searches) will want to soft commit often but hard commit less frequently.
     * </p>
     * @param bool $waitSearcher [optional] <p>
     * block until a new searcher is opened and registered as the main query searcher, making the changes visible.
     * </p>
     * @param bool $expungeDeletes [optional] <p>
     * Merge segments with deletes away. (Solr1.4+)
     * </p>
     * @return SolrUpdateResponse <p>
     * Returns a SolrUpdateResponse object or throws an Exception on failure.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function commit($softCommit = false, $waitSearcher = true, $expungeDeletes = false) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * SolrClient constructor.
     * @link https://php.net/manual/en/solrclient.construct.php
     * @param array $clientOptions <p>
     * This is an array containing one of the following keys :
     * </p>
     * <ul>
     * <li><b>secure</b>: (Boolean value indicating whether or not to connect in secure mode)</li>
     * <li><b>hostname</b>: (The hostname for the Solr server)</li>
     * <li><b>port</b>: (The port number)</li>
     * <li><b>path</b>: (The path to solr)</li>
     * <li><b>wt</b>: (The name of the response writer e.g. xml, json)</li>
     * <li><b>login</b>: (The username used for HTTP Authentication, if any)</li>
     * <li><b>password</b>: (The HTTP Authentication password)</li>
     * <li><b>proxy_host</b>: (The hostname for the proxy server, if any)</li>
     * <li><b>proxy_port</b>: (The proxy port)</li>
     * <li><b>proxy_login</b>: (The proxy username)</li>
     * <li><b>proxy_password</b>: (The proxy password)</li>
     * <li><b>timeout</b>: (This is maximum time in seconds allowed for the http data transfer operation. Default is 30
     * seconds)</li>
     * <li><b>ssl_cert</b>: (File name to a PEM-formatted file containing the private key + private certificate
     * (concatenated in that order) )</li>
     * <li><b>ssl_key</b>: (File name to a PEM-formatted private key file only)</li>
     * <li><b>ssl_keypassword</b>: (Password for private key)</li>
     * <li><b>ssl_cainfo</b>: (Name of file holding one or more CA certificates to verify peer with)</li>
     * <li><b>ssl_capath</b>: (Name of directory holding multiple CA certificates to verify peer with )</li>
     * </ul>
     * <p>
     * Please note the if the ssl_cert file only contains the private certificate, you have to specify a separate
     * ssl_key file.
     * </p>
     * <p>
     * The ssl_keypassword option is required if the ssl_cert or ssl_key options are set.
     * </p>
     * @throws SolrIllegalArgumentException
     */
    public function __construct(array $clientOptions) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Delete by Id
     * @link https://php.net/manual/en/solrclient.deletebyid.php
     * @param string $id <p>
     * The value of the uniqueKey field declared in the schema
     * </p>
     * @return SolrUpdateResponse <p>
     * Returns a SolrUpdateResponse on success and throws an exception on failure.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function deleteById($id) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Deletes by Ids
     * @link https://php.net/manual/en/solrclient.deletebyids.php
     * @param array $ids <p>
     * An array of IDs representing the uniqueKey field declared in the schema for each document to be deleted. This
     * must be an actual php variable.
     * </p>
     * @return SolrUpdateResponse <p>
     * Returns a SolrUpdateResponse on success and throws an exception on failure.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function deleteByIds(array $ids) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Removes all documents matching any of the queries
     * @link https://php.net/manual/en/solrclient.deletebyqueries.php
     * @param array $queries <p>
     * The array of queries. This must be an actual php variable.
     * </p>
     * @return SolrUpdateResponse <p>
     * Returns a SolrUpdateResponse on success and throws an exception on failure.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function deleteByQueries(array $queries) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Deletes all documents matching the given query
     * @link https://php.net/manual/en/solrclient.deletebyquery.php
     * @param string $query <p>
     * The query
     * </p>
     * @return SolrUpdateResponse <p>
     * Returns a SolrUpdateResponse on success and throws an exception on failure.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function deleteByQuery($query) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Destructor for SolrClient
     * @link https://php.net/manual/en/solrclient.destruct.php
     */
    public function __destruct() {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Get Document By Id. Utilizes Solr Realtime Get (RTG).
     * @link https://php.net/manual/en/solrclient.getbyid.php
     * @param string $id <p>
     * Document ID
     * </p>
     * @return SolrQueryResponse
     */
    public function getById($id) {}

    /**
     * (PECL solr &gt;= 2.2.0)<br/>
     * Get Documents by their Ids. Utilizes Solr Realtime Get (RTG).
     * @link https://php.net/manual/en/solrclient.getbyids.php
     * @param array $ids <p>
     * Document ids
     * </p>
     * @return SolrQueryResponse
     */
    public function getByIds(array $ids) {}

    /**
     * (PECL solr &gt;= 0.9.7)<br/>
     * Returns the debug data for the last connection attempt
     * @link https://php.net/manual/en/solrclient.getdebug.php
     * @return string <p>
     * Returns a string on success and null if there is nothing to return.
     * </p>
     */
    public function getDebug() {}

    /**
     * (PECL solr &gt;= 0.9.6)<br/>
     * Returns the client options set internally
     * @link https://php.net/manual/en/solrclient.getoptions.php
     * @return array <p>
     * Returns an array containing all the options for the SolrClient object set internally.
     * </p>
     */
    public function getOptions() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Defragments the index
     * @link https://php.net/manual/en/solrclient.optimize.php
     * @param int $maxSegments <p>
     * Optimizes down to at most this number of segments. Since Solr 1.3
     * </p>
     * @param bool $softCommit <p>
     * This will refresh the 'view' of the index in a more performant manner, but without "on-disk" guarantees.
     * (Solr4.0+)
     * </p>
     * @param bool $waitSearcher <p>
     * Block until a new searcher is opened and registered as the main query searcher, making the changes visible.
     * </p>
     * @return SolrUpdateResponse <p>
     * Returns a SolrUpdateResponse on success and throws an exception on failure.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function optimize($maxSegments = 1, $softCommit = true, $waitSearcher = true) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Checks if Solr server is still up
     * @link https://php.net/manual/en/solrclient.ping.php
     * @return SolrPingResponse <p>
     * Returns a SolrPingResponse object on success and throws an exception on failure.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function ping() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Sends a query to the server
     * @link https://php.net/manual/en/solrclient.query.php
     * @param SolrParams $query <p>
     * A SolrParams object. It is recommended to use SolrQuery for advanced queries.
     * </p>
     * @return SolrQueryResponse <p>
     * Returns a SolrUpdateResponse on success and throws an exception on failure.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function query(SolrParams $query) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Sends a raw update request
     * @link https://php.net/manual/en/solrclient.request.php
     * @param string $raw_request <p>
     * An XML string with the raw request to the server.
     * </p>
     * @return SolrUpdateResponse <p>
     * Returns a SolrUpdateResponse on success and throws an exception on failure.
     * </p>
     * @throws SolrIllegalArgumentException <p>
     * Throws SolrIllegalArgumentException if raw_request was an empty string.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function request($raw_request) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Rollbacks all add/deletes made to the index since the last commit
     * @link https://php.net/manual/en/solrclient.rollback.php
     * @return SolrUpdateResponse <p>
     * Returns a SolrUpdateResponse on success and throws an exception on failure.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function rollback() {}

    /**
     * (PECL solr &gt;= 0.9.11)<br/>
     * Sets the response writer used to prepare the response from Solr
     * @link https://php.net/manual/en/solrclient.setresponsewriter.php
     * @param string $responseWriter <p>
     * One of the following:
     * </p>
     * <ul>
     * <li>json</li>
     * <li>phps</li>
     * <li>xml</li>
     * </ul>
     */
    public function setResponseWriter($responseWriter) {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Changes the specified servlet type to a new value
     * @link https://php.net/manual/en/solrclient.setservlet.php
     * @param int $type <p>
     * One of the following :
     * </p>
     * <ul>
     * <li>SolrClient::SEARCH_SERVLET_TYPE</li>
     * <li>SolrClient::UPDATE_SERVLET_TYPE</li>
     * <li>SolrClient::THREADS_SERVLET_TYPE</li>
     * <li>SolrClient::PING_SERVLET_TYPE</li>
     * <li>SolrClient::TERMS_SERVLET_TYPE</li>
     * </ul>
     * @param string $value <p>
     * The new value for the servlet
     * </p>
     * @return bool <p>
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     */
    public function setServlet($type, $value) {}

    /**
     * (PECL solr &gt;= 2.0.0)<br/>
     * Retrieve Solr Server information
     * @link https://php.net/manual/en/solrclient.system.php
     * @return SolrGenericResponse <p>
     * Returns a SolrGenericResponse object on success.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function system() {}

    /**
     * (PECL solr &gt;= 0.9.2)<br/>
     * Checks the threads status
     * @link https://php.net/manual/en/solrclient.threads.php
     * @return SolrGenericResponse <p>
     * Returns a SolrGenericResponse object on success.
     * </p>
     * @throws SolrClientException <p>
     * Throws SolrClientException if the client had failed, or there was a connection issue.
     * </p>
     * @throws SolrServerException <p>
     * Throws SolrServerException if the Solr Server had failed to satisfy the request.
     * </p>
     */
    public function threads() {}
}
