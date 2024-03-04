<?php
/**
 * Couchbase extension stubs
 * Gathered from https://docs.couchbase.com/sdk-api/couchbase-php-client-2.3.0/index.html
 * Maintainer: sergey@couchbase.com
 *
 * https://github.com/couchbase/php-couchbase/tree/master/api
 */

/**
 * INI entries:
 *
 * * `couchbase.log_level` (string), default: `"WARN"`
 *
 *   controls amount of information, the module will send to PHP error log. Accepts the following values in order of
 *   increasing verbosity: `"FATAL"`, `"ERROR"`, `"WARN"`, `"INFO"`, `"DEBUG"`, `"TRACE"`.
 *
 * * `couchbase.encoder.format` (string), default: `"json"`
 *
 *   selects serialization format for default encoder (\Couchbase\defaultEncoder). Accepts the following values:
 *   * `"json"` - encodes objects and arrays as JSON object (using `json_encode()`), primitives written in stringified form,
 *      which is allowed for most of the JSON parsers as valid values. For empty arrays JSON array preferred, if it is
 *      necessary, use `new stdClass()` to persist empty JSON object. Note, that only JSON format considered supported by
 *      all Couchbase SDKs, everything else is private implementation (i.e. `"php"` format won't be readable by .NET SDK).
 *   * `"php"` - uses PHP serialize() method to encode the document.
 *   * `"igbinary"` - uses pecl/igbinary to encode the document in even more efficient than `"php"` format. Might not be
 *      available, if the Couchbase PHP SDK didn't find it during build phase, in this case constant
 *      \Couchbase\HAVE_IGBINARY will be false.
 *
 * * `couchbase.encoder.compression` (string), default: `"none"`
 *
 *   selects compression algorithm. Also see related compression options below. Accepts the following values:
 *   * `"fastlz"` - uses FastLZ algorithm. The module might be configured to use system fastlz library during build,
 *     othewise vendored version will be used. This algorithm is always available.
 *   * `"zlib"` - uses compression implemented by libz. Might not be available, if the system didn't have libz headers
 *     during build phase. In this case \Couchbase\HAVE_ZLIB will be false.
 *   * `"off"` or `"none"` - compression will be disabled, but the library will still read compressed values.
 *
 * * `couchbase.encoder.compression_threshold` (long), default: `0`
 *
 *   controls minimum size of the document value in bytes to use compression. For example, if threshold 100 bytes,
 *   and the document size is 50, compression will be disabled for this particular document.
 *
 * * `couchbase.encoder.compression_factor` (float), default: `0.0`
 *
 *   controls the minimum ratio of the result value and original document value to proceed with persisting compressed
 *   bytes. For example, the original document consists of 100 bytes. In this case factor 1.0 will require compressor
 *   to yield values not larger than 100 bytes (100/1.0), and 1.5 -- not larger than 66 bytes (100/1.5).
 *
 * * `couchbase.decoder.json_arrays` (boolean), default: `false`
 *
 *   controls the form of the documents, returned by the server if they were in JSON format. When true, it will generate
 *   arrays of arrays, otherwise instances of stdClass.
 *
 * * `couchbase.pool.max_idle_time_sec` (long), default: `60`
 *
 *   controls the maximum interval the underlying connection object could be idle, i.e. without any data/query
 *   operations. All connections which idle more than this interval will be closed automatically. Cleanup function
 *   executed after each request using RSHUTDOWN hook.
 *
 * @package Couchbase
 */

namespace Couchbase;

/** If igbinary extension was not found during build phase this constant will store 0 */
define("Couchbase\\HAVE_IGBINARY", 1);
/** If libz headers was not found during build phase this constant will store 0 */
define("Couchbase\\HAVE_ZLIB", 1);

/** Encodes documents as JSON objects (see INI section for details)
 * @see \Couchbase\basicEncoderV1
 */
define("Couchbase\\ENCODER_FORMAT_JSON", 0);
/** Encodes documents using pecl/igbinary encoder (see INI section for details)
 * @see \Couchbase\basicEncoderV1
 */
define("Couchbase\\ENCODER_FORMAT_IGBINARY", 1);
/** Encodes documents using PHP serialize() (see INI section for details)
 * @see \Couchbase\basicEncoderV1
 */
define("Couchbase\\ENCODER_FORMAT_PHP", 2);

/** Do not use compression for the documents
 * @see \Couchbase\basicEncoderV1
 */
define("Couchbase\\ENCODER_COMPRESSION_NONE", 0);
/** Use zlib compressor for the documents
 * @see \Couchbase\basicEncoderV1
 */
define("Couchbase\\ENCODER_COMPRESSION_ZLIB", 1);
/** Use FastLZ compressor for the documents
 * @see \Couchbase\basicEncoderV1
 */
define("Couchbase\\ENCODER_COMPRESSION_FASTLZ", 2);

/**
 * Compress input using FastLZ algorithm.
 *
 * @param string $data original data
 * @return string compressed binary string
 */
function fastlzCompress($data) {}

/**
 * Decompress input using FastLZ algorithm.
 *
 * @param string $data compressed binary string
 * @return string original data
 */
function fastlzDecompress($data) {}

/**
 * Compress input using zlib. Raises Exception when extension compiled without zlib support.
 *
 * @param string $data original data
 * @return string compressed binary string
 * @see \Couchbase\HAVE_ZLIB
 */
function zlibCompress($data) {}

/**
 * Compress input using zlib. Raises Exception when extension compiled without zlib support.
 *
 * @param string $data compressed binary string
 * @return string original data
 * @see \Couchbase\HAVE_ZLIB
 */
function zlibDecompress($data) {}

/**
 * Returns value as it received from the server without any transformations.
 *
 * It is useful for debug purpose to inspect bare value.
 *
 * @param string $bytes
 * @param int $flags
 * @param int $datatype
 * @return string Document as it received from the Couchbase.
 *
 * @see \Couchbase\Bucket::setTranscoder()
 */
function passthruDecoder($bytes, $flags, $datatype) {}

/**
 * Returns the value, which has been passed and zero as flags and datatype.
 *
 * It is useful for debug purposes, or when the value known to be a string, otherwise behavior is not defined (most
 * likely it will generate error).
 *
 * @param string $value document to be stored in the Couchbase
 * @return array Array with three values: [bytes, 0, 0]
 *
 * @see \Couchbase\Bucket::setTranscoder()
 */
function passthruEncoder($value) {}

/**
 * Decodes value using \Couchbase\basicDecoderV1.
 *
 * It passes `couchbase.decoder.*` INI properties as $options.
 *
 * @param string $bytes Binary string received from the Couchbase, which contains encoded document
 * @param int $flags Flags which describes document encoding
 * @param int $datatype Extra field for datatype (not used at the moment)
 * @return mixed Decoded document object
 *
 * @see \Couchbase\basicDecoderV1
 * @see \Couchbase\Bucket::setTranscoder()
 */
function defaultDecoder($bytes, $flags, $datatype) {}

/**
 * Encodes value using \Couchbase\basicDecoderV1.
 *
 * It passes `couchbase.encoder.*` INI properties as $options.
 *
 * @param mixed $value document to be stored in the Couchbase
 * @return array Array with three values: [bytes, flags, datatype]
 *
 * @see \Couchbase\basicDecoderV1
 * @see \Couchbase\Bucket::setTranscoder()
 */
function defaultEncoder($value) {}

/**
 * Decodes value according to Common Flags (RFC-20)
 *
 * @param string $bytes Binary string received from the Couchbase, which contains encoded document
 * @param int $flags Flags which describes document encoding
 * @param int $datatype Extra field for datatype (not used at the moment)
 * @param array $options
 * @return mixed Decoded document object
 *
 * @see https://github.com/couchbaselabs/sdk-rfcs RFC-20 at SDK RFCs repository
 */
function basicDecoderV1($bytes, $flags, $datatype, $options) {}

/**
 * Encodes value according to Common Flags (RFC-20)
 *
 * @param mixed $value document to be stored in the Couchbase
 * @param array $options Encoder options (see detailed description in INI section)
 *   * "sertype" (default: \Couchbase::ENCODER_FORMAT_JSON) encoding format to use
 *   * "cmprtype" (default: \Couchbase::ENCODER_COMPRESSION_NONE) compression type
 *   * "cmprthresh" (default: 0) compression threshold
 *   * "cmprfactor" (default: 0) compression factor
 * @return array Array with three values: [bytes, flags, datatype]
 *
 * @see https://github.com/couchbaselabs/sdk-rfcs RFC-20 at SDK RFCs repository
 */
function basicEncoderV1($value, $options) {}

/**
 * Exception represeting all errors generated by the extension
 */
class Exception extends \Exception {}

/**
 * Represents Couchbase Document, which stores metadata and the value.
 *
 * The instances of this class returned by K/V commands of the \Couchbase\Bucket
 *
 * @see \Couchbase\Bucket
 */
class Document
{
    /**
     * @var Exception exception object in case of error, or NULL
     */
    public $error;

    /**
     * @var mixed The value stored in the Couchbase.
     */
    public $value;

    /**
     * @var int Flags, describing the encoding of the document on the server side.
     */
    public $flags;

    /**
     * @var string The last known CAS value of the document
     */
    public $cas;

    /**
     * @var MutationToken
     * The optional, opaque mutation token set after a successful mutation.
     *
     * Note that the mutation token is always NULL, unless they are explicitly enabled on the
     * connection string (`?fetch_mutation_tokens=true`), the server version is supported (>= 4.0.0)
     * and the mutation operation succeeded.
     *
     * If set, it can be used for enhanced durability requirements, as well as optimized consistency
     * for N1QL queries.
     */
    public $token;
}

/**
 * A fragment of a JSON Document returned by the sub-document API.
 *
 * @see \Couchbase\Bucket::mutateIn()
 * @see \Couchbase\Bucket::lookupIn()
 */
class DocumentFragment
{
    /**
     * @var Exception exception object in case of error, or NULL
     */
    public $error;

    /**
     * @var mixed The value sub-document command returned.
     */
    public $value;

    /**
     * @var string The last known CAS value of the document
     */
    public $cas;

    /**
     * @var MutationToken
     * The optional, opaque mutation token related to updated document the environment.
     *
     * Note that the mutation token is always NULL, unless they are explicitly enabled on the
     * connection string (`?fetch_mutation_tokens=true`), the server version is supported (>= 4.0.0)
     * and the mutation operation succeeded.
     *
     * If set, it can be used for enhanced durability requirements, as well as optimized consistency
     * for N1QL queries.
     */
    public $token;
}

/**
 * Represents a Couchbase Server Cluster.
 *
 * It is an entry point to the library, and in charge of opening connections to the Buckets.
 * In addition it can instantiate \Couchbase\ClusterManager to peform cluster-wide operations.
 *
 * @see \Couchbase\Bucket
 * @see \Couchbase\ClusterManager
 * @see \Couchbase\Authenticator
 */
class Cluster
{
    /**
     * Create cluster object
     *
     * @param string $connstr connection string
     */
    public function __construct($connstr) {}

    /**
     * Open connection to the Couchbase bucket
     *
     * @param string $name Name of the bucket.
     * @param string $password Password of the bucket to override authenticator.
     * @return Bucket
     *
     * @see \Couchbase\Authenticator
     */
    public function openBucket($name = "default", $password = "") {}

    /**
     * Open management connection to the Couchbase cluster.
     *
     * @param string $username Name of the administrator to override authenticator or NULL.
     * @param string $password Password of the administrator to override authenticator or NULL.
     * @return ClusterManager
     *
     * @see \Couchbase\Authenticator
     */
    public function manager($username = null, $password = null) {}

    /**
     * Associate authenticator with Cluster
     *
     * @param Authenticator $authenticator
     * @return null
     *
     * @see \Couchbase\Authenticator
     * @see \Couchbase\ClassicAuthenticator
     * @see \Couchbase\PasswordAuthenticator
     */
    public function authenticate($authenticator) {}

    /**
     * Create \Couchbase\PasswordAuthenticator from given credentials and associate it with Cluster
     *
     * @param string $username
     * @param string $password
     * @return null
     *
     * @see \Couchbase\Authenticator
     * @see \Couchbase\PasswordAuthenticator
     */
    public function authenticateAs($username, $password) {}
}

/**
 * Provides management capabilities for a Couchbase Server Cluster
 *
 * @see \Couchbase\Cluster
 */
class ClusterManager
{
    /**
     * The user account managed by Couchbase Cluster.
     */
    public const RBAC_DOMAIN_LOCAL = 1;

    /**
     * The user account managed by external system (e.g. LDAP).
     */
    public const RBAC_DOMAIN_EXTERNAL = 2;

    final private function __construct() {}

    /**
     * Lists all buckets on this cluster.
     *
     * @return array
     */
    public function listBuckets() {}

    /**
     * Creates new bucket
     *
     * @param string $name Name of the bucket
     * @param array $options Bucket options
     *   * "authType" (default: "sasl") type of the bucket authentication
     *   * "bucketType" (default: "couchbase") type of the bucket
     *   * "ramQuotaMB" (default: 100) memory quota of the bucket
     *   * "replicaNumber" (default: 1) number of replicas.
     *
     * @see https://developer.couchbase.com/documentation/server/current/rest-api/rest-bucket-create.html
     *   More options and details
     */
    public function createBucket($name, $options = []) {}

    /**
     * Removes a bucket identified by its name.
     *
     * @param string $name name of the bucket
     *
     * @see https://developer.couchbase.com/documentation/server/current/rest-api/rest-bucket-delete.html
     *   More details
     */
    public function removeBucket($name) {}

    /**
     * Provides information about the cluster.
     *
     * Returns an associative array of status information as seen on the cluster.  The exact structure of the returned
     * data can be seen in the Couchbase Manual by looking at the cluster /info endpoint.
     *
     * @return array
     *
     * @see https://developer.couchbase.com/documentation/server/current/rest-api/rest-cluster-get.html
     *   Retrieving Cluster Information
     */
    public function info() {}

    /**
     * Lists all users on this cluster.
     *
     * @param int $domain RBAC domain
     *
     * @return array
     *
     * @see \Couchbase\ClusterManager::RBAC_DOMAIN_LOCAL
     * @see \Couchbase\ClusterManager::RBAC_DOMAIN_EXTERNAL
     */
    public function listUsers($domain = RBAC_DOMAIN_LOCAL) {}

    /**
     * Fetch single user by its name
     *
     * @param string $username The user's identifier
     * @param int $domain RBAC domain
     *
     * @return array
     *
     * @see \Couchbase\ClusterManager::RBAC_DOMAIN_LOCAL
     * @see \Couchbase\ClusterManager::RBAC_DOMAIN_EXTERNAL
     */
    public function getUser($username, $domain = RBAC_DOMAIN_LOCAL) {}

    /**
     * Creates new user
     *
     * @param string $name Name of the user
     * @param \Couchbase\UserSettings $settings settings (credentials and roles)
     * @param int $domain RBAC domain
     *
     * @see https://developer.couchbase.com/documentation/server/5.0/rest-api/rbac.html
     *   More options and details
     * @see \Couchbase\ClusterManager::RBAC_DOMAIN_LOCAL
     * @see \Couchbase\ClusterManager::RBAC_DOMAIN_EXTERNAL
     */
    public function upsertUser($name, $settings, $domain = RBAC_DOMAIN_LOCAL) {}

    /**
     * Removes a user identified by its name.
     *
     * @param string $name name of the bucket
     * @param int $domain RBAC domain
     *
     * @see https://developer.couchbase.com/documentation/server/5.0/rest-api/rbac.html
     *   More details
     * @see \Couchbase\ClusterManager::RBAC_DOMAIN_LOCAL
     * @see \Couchbase\ClusterManager::RBAC_DOMAIN_EXTERNAL
     */
    public function removeUser($name, $domain = RBAC_DOMAIN_LOCAL) {}
}

/**
 * Represents settings for new/updated user.
 *
 * @see https://developer.couchbase.com/documentation/server/5.0/rest-api/rbac.html
 */
class UserSettings
{
    /**
     * Sets full name of the user (optional).
     *
     * @param string $fullName Full name of the user
     *
     * @return \Couchbase\UserSettings
     *
     * @see https://developer.couchbase.com/documentation/server/5.0/rest-api/rbac.html
     *   More details
     */
    public function fullName($fullName) {}

    /**
     * Sets password of the user.
     *
     * @param string $password Password of the user
     *
     * @return \Couchbase\UserSettings
     *
     * @see https://developer.couchbase.com/documentation/server/5.0/rest-api/rbac.html
     *   More details
     */
    public function password($password) {}

    /**
     * Adds role to the list of the accessible roles of the user.
     *
     * @param string $role identifier of the role
     * @param string $bucket the bucket where this role applicable (or `*` for all buckets)
     *
     * @return \Couchbase\UserSettings
     *
     * @see https://developer.couchbase.com/documentation/server/5.0/rest-api/rbac.html
     *   More details
     */
    public function role($role, $bucket = null) {}
}

/**
 * Represents connection to the Couchbase Server
 *
 * @property int $operationTimeout
 *   The operation timeout (in microseconds) is the maximum amount of time the
 *   library will wait for an operation to receive a response before invoking
 *   its callback with a failure status.
 *
 *   An operation may timeout if:
 *
 *   * A server is taking too long to respond
 *   * An updated cluster configuration has not been promptly received
 *
 * @property int $viewTimeout
 *   The I/O timeout (in microseconds) for HTTP requests to Couchbase Views API
 *
 * @property int $n1qlTimeout
 *   The I/O timeout (in microseconds) for N1QL queries.
 *
 * @property int $httpTimeout
 *   The I/O timeout (in microseconds) for HTTP queries (management API).
 *
 * @property int $configTimeout
 *   How long (in microseconds) the client will wait to obtain the initial
 *   configuration.
 *
 * @property int $configNodeTimeout
 *   Per-node configuration timeout (in microseconds).
 *
 *   This timeout sets the amount of time to wait for each node within
 *   the bootstrap/configuration process. This interval is a subset of
 *   the $configTimeout option mentioned above and is intended to ensure
 *   that the bootstrap process does not wait too long for a given node.
 *   Nodes that are physically offline may never respond and it may take
 *   a long time until they are detected as being offline.
 *
 * @property int $configDelay
 *   Config refresh throttling
 *
 *   Modify the amount of time (in microseconds) before the configiration
 *   error threshold will forcefully be set to its maximum number forcing
 *   a configuration refresh.
 *
 *   Note that if you expect a high number of timeouts in your operations,
 *   you should set this to a high number. If you are using the default
 *   timeout setting, then this value is likely optimal.
 *
 * @property int $htconfigIdleTimeout
 *   Idling/Persistence for HTTP bootstrap (in microseconds)
 *
 *   By default the behavior of the library for HTTP bootstrap is to keep
 *   the stream open at all times (opening a new stream on a different host
 *   if the existing one is broken) in order to proactively receive
 *   configuration updates.
 *
 *   The default value for this setting is -1. Changing this to another
 *   number invokes the following semantics:
 *
 *   * The configuration stream is not kept alive indefinitely. It is kept
 *     open for the number of seconds specified in this setting. The socket
 *     is closed after a period of inactivity (indicated by this setting).
 *
 *   * If the stream is broken (and no current refresh was requested by
 *     the client) then a new stream is not opened.
 *
 * @property int $durabilityInterval
 *   The time (in microseconds) the client will wait between repeated probes
 *   to a given server.
 *
 * @property int $durabilityTimeout
 *   The time (in microseconds) the client will spend sending repeated probes
 *   to a given key's vBucket masters and replicas before they are deemed not
 *   to have satisfied the durability requirements
 *
 * @see https://developer.couchbase.com/documentation/server/current/sdk/php/start-using-sdk.html
 *   Start Using SDK
 */
class Bucket
{
    /** Ping data (Key/Value) service. */
    public const PINGSVC_KV = 0x01;

    /** Ping query (N1QL) service. */
    public const PINGSVC_N1QL = 0x02;

    /** Ping views (Map/Reduce) service. */
    public const PINGSVC_VIEWS = 0x04;

    /** Ping full text search (FTS) service. */
    public const PINGSVC_FTS = 0x08;

    final private function __construct() {}

    /**
     * @param string $name
     * @return int
     */
    final private function __get($name) {}

    /**
     * @param string $name
     * @param int $value
     * @return int
     */
    final private function __set($name, $value) {}

    /**
     * Returns the name of the bucket for current connection
     *
     * @return string
     */
    public function getName() {}

    /**
     * Returns an instance of a CouchbaseBucketManager for performing management operations against a bucket.
     *
     * @return BucketManager
     */
    public function manager() {}

    /**
     * Sets custom encoder and decoder functions for handling serialization.
     *
     * @param callable $encoder
     * @param callable $decoder
     *
     * @see \Couchbase\defaultEncoder
     * @see \Couchbase\defaultDecoder
     * @see \Couchbase\passthruEncoder
     * @see \Couchbase\passthruDecoder
     */
    public function setTranscoder($encoder, $decoder) {}

    /**
     * Retrieves a document
     *
     * @param string|array $ids one or more IDs
     * @param array $options options
     *   * "lockTime" non zero if the documents have to be locked
     *   * "expiry" non zero if the expiration time should be updated
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see \Couchbase\Bucket::getAndLock()
     * @see \Couchbase\Bucket::getAndTouch()
     * @see \Couchbase\Bucket::unlock()
     * @see \Couchbase\Bucket::touch()
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     */
    public function get($ids, $options = []) {}

    /**
     * Retrieves a document and locks it.
     *
     * After the document has been locked on the server, its CAS would be masked,
     * and all mutations of it will be rejected until the server unlocks the document
     * automatically or it will be done manually with \Couchbase\Bucket::unlock() operation.
     *
     * @param string|array $ids one or more IDs
     * @param int $lockTime time to lock the documents
     * @param array $options options
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see \Couchbase\Bucket::unlock()
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     * @see https://forums.couchbase.com/t/is-there-a-way-to-do-pessimistic-locking-for-more-than-30-seconds/10666/3
     *   Forum post about getting server defaults for the $lockTime
     */
    public function getAndLock($ids, $lockTime, $options = []) {}

    /**
     * Retrieves a document and updates its expiration time.
     *
     * @param string|array $ids one or more IDs
     * @param int $expiry time after which the document will not be accessible.
     *      If larger than 30 days (60*60*24*30), it will be interpreted by the
     *      server as absolute UNIX time (seconds from epoch 1970-01-01T00:00:00).
     * @param array $options options
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     */
    public function getAndTouch($ids, $expiry, $options = []) {}

    /**
     * Retrieves a document from a replica.
     *
     * @param string|array $ids one or more IDs
     * @param array $options options
     *   * "index" the replica index. If the index is zero, it will return
     *      first successful replica, otherwise it will read only selected node.
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/failure-considerations.html
     *  More about failure considerations.
     */
    public function getFromReplica($ids, $options = []) {}

    /**
     * Inserts or updates a document, depending on whether the document already exists on the cluster.
     *
     * @param string|array $ids one or more IDs
     * @param mixed $value value of the document
     * @param array $options options
     *   * "expiry" document expiration time in seconds. If larger than 30 days (60*60*24*30),
     *      it will be interpreted by the server as absolute UNIX time (seconds from epoch
     *      1970-01-01T00:00:00).
     *   * "persist_to" how many nodes the key should be persisted to (including master).
     *      If set to 0 then persistence will not be checked. If set to a negative
     *      number, will be set to the maximum number of nodes to which persistence
     *      is possible (which will always contain at least the master node).
     *   * "replicate_to" how many nodes the key should be persisted to (excluding master).
     *      If set to 0 then replication will not be checked. If set to a negative
     *      number, will be set to the maximum number of nodes to which replication
     *      is possible (which may be 0 if the bucket is not configured for replicas).
     *   * "flags" override flags (not recommended to use)
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     */
    public function upsert($ids, $value, $options = []) {}

    /**
     * Inserts a document. This operation will fail if the document already exists on the cluster.
     *
     * @param string|array $ids one or more IDs
     * @param mixed $value value of the document
     * @param array $options options
     *   * "expiry" document expiration time in seconds. If larger than 30 days (60*60*24*30),
     *      it will be interpreted by the server as absolute UNIX time (seconds from epoch
     *      1970-01-01T00:00:00).
     *   * "persist_to" how many nodes the key should be persisted to (including master).
     *      If set to 0 then persistence will not be checked. If set to a negative
     *      number, will be set to the maximum number of nodes to which persistence
     *      is possible (which will always contain at least the master node).
     *   * "replicate_to" how many nodes the key should be persisted to (excluding master).
     *      If set to 0 then replication will not be checked. If set to a negative
     *      number, will be set to the maximum number of nodes to which replication
     *      is possible (which may be 0 if the bucket is not configured for replicas).
     *   * "flags" override flags (not recommended to use)
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     */
    public function insert($ids, $value, $options = []) {}

    /**
     * Replaces a document. This operation will fail if the document does not exists on the cluster.
     *
     * @param string|array $ids one or more IDs
     * @param mixed $value value of the document
     * @param array $options options
     *   * "cas" last known document CAS, which serves for optimistic locking.
     *   * "expiry" document expiration time in seconds. If larger than 30 days (60*60*24*30),
     *      it will be interpreted by the server as absolute UNIX time (seconds from epoch
     *      1970-01-01T00:00:00).
     *   * "persist_to" how many nodes the key should be persisted to (including master).
     *      If set to 0 then persistence will not be checked. If set to a negative
     *      number, will be set to the maximum number of nodes to which persistence
     *      is possible (which will always contain at least the master node).
     *   * "replicate_to" how many nodes the key should be persisted to (excluding master).
     *      If set to 0 then replication will not be checked. If set to a negative
     *      number, will be set to the maximum number of nodes to which replication
     *      is possible (which may be 0 if the bucket is not configured for replicas).
     *   * "flags" override flags (not recommended to use)
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     */
    public function replace($ids, $value, $options = []) {}

    /**
     * Appends content to a document.
     *
     * On the server side it just contatenate passed value to the existing one.
     * Note that this might make the value un-decodable. Consider sub-document API
     * for partial updates of the JSON documents.
     *
     * @param string|array $ids one or more IDs
     * @param mixed $value value of the document
     * @param array $options options
     *   * "cas" last known document CAS, which serves for optimistic locking.
     *   * "expiry" document expiration time in seconds. If larger than 30 days (60*60*24*30),
     *      it will be interpreted by the server as absolute UNIX time (seconds from epoch
     *      1970-01-01T00:00:00).
     *   * "persist_to" how many nodes the key should be persisted to (including master).
     *      If set to 0 then persistence will not be checked. If set to a negative
     *      number, will be set to the maximum number of nodes to which persistence
     *      is possible (which will always contain at least the master node).
     *   * "replicate_to" how many nodes the key should be persisted to (excluding master).
     *      If set to 0 then replication will not be checked. If set to a negative
     *      number, will be set to the maximum number of nodes to which replication
     *      is possible (which may be 0 if the bucket is not configured for replicas).
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see \Couchbase\Bucket::mutateIn()
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     */
    public function append($ids, $value, $options = []) {}

    /**
     * Prepends content to a document.
     *
     * On the server side it just contatenate existing value to the passed one.
     * Note that this might make the value un-decodable. Consider sub-document API
     * for partial updates of the JSON documents.
     *
     * @param string|array $ids one or more IDs
     * @param mixed $value value of the document
     * @param array $options options
     *   * "cas" last known document CAS, which serves for optimistic locking.
     *   * "expiry" document expiration time in seconds. If larger than 30 days (60*60*24*30),
     *      it will be interpreted by the server as absolute UNIX time (seconds from epoch
     *      1970-01-01T00:00:00).
     *   * "persist_to" how many nodes the key should be persisted to (including master).
     *      If set to 0 then persistence will not be checked. If set to a negative
     *      number, will be set to the maximum number of nodes to which persistence
     *      is possible (which will always contain at least the master node).
     *   * "replicate_to" how many nodes the key should be persisted to (excluding master).
     *      If set to 0 then replication will not be checked. If set to a negative
     *      number, will be set to the maximum number of nodes to which replication
     *      is possible (which may be 0 if the bucket is not configured for replicas).
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see \Couchbase\Bucket::mutateIn()
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     */
    public function prepend($ids, $value, $options = []) {}

    /**
     * Removes the document.
     *
     * @param string|array $ids one or more IDs
     * @param array $options options
     *   * "cas" last known document CAS, which serves for optimistic locking.
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     */
    public function remove($ids, $options = []) {}

    /**
     * Unlocks previously locked document
     *
     * @param string|array $ids one or more IDs
     * @param array $options options
     *   * "cas" last known document CAS, which has been returned by locking command.
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see \Couchbase\Bucket::get()
     * @see \Couchbase\Bucket::getAndLock()
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     */
    public function unlock($ids, $options = []) {}

    /**
     * Updates document's expiration time.
     *
     * @param string|array $ids one or more IDs
     * @param int $expiry time after which the document will not be accessible.
     *      If larger than 30 days (60*60*24*30), it will be interpreted by the
     *      server as absolute UNIX time (seconds from epoch 1970-01-01T00:00:00).
     * @param array $options options
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     */
    public function touch($ids, $expiry, $options = []) {}

    /**
     * Increments or decrements a key (based on $delta)
     *
     * @param string|array $ids one or more IDs
     * @param int $delta the number whih determines the sign (positive/negative) and the value of the increment
     * @param array $options options
     *   * "initial" initial value of the counter if it does not exist
     *   * "expiry" time after which the document will not be accessible.
     *      If larger than 30 days (60*60*24*30), it will be interpreted by the
     *      server as absolute UNIX time (seconds from epoch 1970-01-01T00:00:00).
     *   * "groupid" override value for hashing (not recommended to use)
     * @return \Couchbase\Document|array document or list of the documents
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/core-operations.html
     *   Overview of K/V operations
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/document-operations.html
     *   More details about K/V operations for PHP SDK
     */
    public function counter($ids, $delta = 1, $options = []) {}

    /**
     * Returns a builder for reading subdocument API.
     *
     * @param string $id The ID of the JSON document
     * @return LookupInBuilder
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function lookupIn($id) {}

    /**
     * Retrieves specified paths in JSON document
     *
     * This is essentially a shortcut for `lookupIn($id)->get($paths)->execute()`.
     *
     * @param string $id The ID of the JSON document
     * @param string ...$paths List of the paths inside JSON documents (see "Path syntax" section of the
     *   "Sub-Document Operations" documentation).
     * @return \Couchbase\DocumentFragment
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function retrieveIn($id, ...$paths) {}

    /**
     * Returns a builder for writing subdocument API.
     *
     * @param string $id The ID of the JSON document
     * @param string $cas Last known document CAS value for optimisti locking
     * @return MutateInBuilder
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function mutateIn($id, $cas) {}

    /**
     * Performs a query to Couchbase Server
     *
     * @param N1qlQuery|ViewQuery|SpatialViewQuery|SearchQuery|AnalyticsQuery $query
     * @param bool $jsonAsArray if true, the values in the result rows (or hits) will be represented as
     *    PHP arrays, otherwise they will be instances of the `stdClass`
     * @return object Query-specific result object.
     *
     * @see \Couchbase\N1qlQuery
     * @see \Couchbase\SearchQuery
     * @see \Couchbase\ViewQuery
     * @see \Couchbase\SpatialViewQuery
     */
    public function query($query, $jsonAsArray = false) {}

    /**
     * Returns size of the map
     *
     * @param string $id ID of the document
     * @return int number of the key-value pairs
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function mapSize($id) {}

    /**
     * Add key to the map
     *
     * @param string $id ID of the document
     * @param string $key key
     * @param mixed $value value
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function mapAdd($id, $key, $value) {}

    /**
     * Removes key from the map
     *
     * @param string $id ID of the document
     * @param string $key key
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function mapRemove($id, $key) {}

    /**
     * Get an item from a map
     *
     * @param string $id ID of the document
     * @param string $key key
     * @return mixed value associated with the key
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function mapGet($id, $key) {}

    /**
     * Returns size of the set
     *
     * @param string $id ID of the document
     * @return int number of the elements
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function setSize($id) {}

    /**
     * Add value to the set
     *
     * Note, that currently only primitive values could be stored in the set (strings, integers and booleans).
     *
     * @param string $id ID of the document
     * @param string|int|float|bool $value new value
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function setAdd($id, $value) {}

    /**
     * Check if the value exists in the set
     *
     * @param string $id ID of the document
     * @param string|int|float|bool $value value to check
     * @return bool true if the value exists in the set
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function setExists($id, $value) {}

    /**
     * Remove value from the set
     *
     * @param string $id ID of the document
     * @param string|int|float|bool $value value to remove
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function setRemove($id, $value) {}

    /**
     * Returns size of the list
     *
     * @param string $id ID of the document
     * @return int number of the elements
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function listSize($id) {}

    /**
     * Add an element to the end of the list
     *
     * @param string $id ID of the document
     * @param mixed $value new value
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function listPush($id, $value) {}

    /**
     * Add an element to the beginning of the list
     *
     * @param string $id ID of the document
     * @param mixed $value new value
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function listShift($id, $value) {}

    /**
     * Remove an element at the given position
     *
     * @param string $id ID of the document
     * @param int $index index of the element to be removed
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function listRemove($id, $index) {}

    /**
     * Get an element at the given position
     *
     * @param string $id ID of the document
     * @param int $index index of the element
     * @return mixed the value
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function listGet($id, $index) {}

    /**
     * Set an element at the given position
     *
     * @param string $id ID of the document
     * @param int $index index of the element
     * @param mixed $value new value
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function listSet($id, $index, $value) {}

    /**
     * Check if the list contains specified value
     *
     * @param string $id ID of the document
     * @param mixed $value value to look for
     * @return bool true if the list contains the value
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function listExists($id, $value) {}

    /**
     * Returns size of the queue
     *
     * @param string $id ID of the document
     * @return int number of the elements in the queue
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function queueSize($id) {}

    /**
     * Checks if the queue contains specified value
     *
     * @param string $id ID of the document
     * @param mixed $value value to look for
     * @return bool true if the queue contains the value
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function queueExists($id, $value) {}

    /**
     * Add an element to the beginning of the queue
     *
     * @param string $id ID of the document
     * @param mixed $value new value
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function queueAdd($id, $value) {}

    /**
     * Remove the element at the end of the queue and return it
     *
     * @param string $id ID of the document
     * @return mixed removed value
     *
     * @see https://developer.couchbase.com/documentation/server/current/sdk/php/datastructures.html
     *   More details on Data Structures
     * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
     *   Overview of Sub-Document Operations
     */
    public function queueRemove($id) {}

    /**
     * Try to reach specified services, and measure network latency.
     *
     * @param int $services bitwise mask of required services (and all services when zero)
     * @param string $reportId custom identifier, which will be appended to "id" property in report
     * @return array the report object
     *
     * @see \Couchbase\Bucket::PINGSVC_KV
     * @see \Couchbase\Bucket::PINGSVC_N1QL
     * @see \Couchbase\Bucket::PINGSVC_VIEWS
     * @see \Couchbase\Bucket::PINGSVC_FTS
     *
     * @see https://github.com/couchbaselabs/sdk-rfcs/blob/master/rfc/0034-health-check.md
     *   SDK RFC #34, which describes the feature and report layout.
     */
    public function ping($services = 0, $reportId = null) {}

    /**
     * Collect and return information about state of internal network connections.
     *
     * @param string $reportId custom identifier, which will be appended to "id" property in report
     * @return array the report object
     *
     * @see https://github.com/couchbaselabs/sdk-rfcs/blob/master/rfc/0034-health-check.md
     *   SDK RFC #34, which describes the feature and report layout.
     */
    public function diag($reportId = null) {}

    /**
     * Encrypt fields inside specified document.
     *
     * @param array $document document structure
     * @param array $fieldOptions specification for fields needed to be encrypted. Where 'alg' contains
     *   a string with alias of the registed crypto provider, and 'name' contains the name of the field.
     * @param string $prefix optional prefix for modified field (when null, the library will use "__crypt")
     *
     * @return array where the fields encrypted
     *
     * @see https://github.com/couchbase/php-couchbase-encryption
     */
    public function encryptFields($document, $fieldOptions, $prefix = null) {}

    /**
     * Decrypt fields inside specified document.
     *
     * @param array $document document structure
     * @param array $fieldOptions specification for fields needed to be decrypted. Where 'alg' contains
     *   a string with alias of the registed crypto provider, and 'name' contains the name of the field.
     * @param string $prefix optional prefix for modified field (when null, the library will use "__crypt")
     *
     * @return array where the fields decrypted
     *
     * @see https://github.com/couchbase/php-couchbase-encryption
     */
    public function decryptFields($document, $fieldOptions, $prefix = null) {}
}

/**
 * Provides management capabilities for the Couchbase Bucket
 */
class BucketManager
{
    final private function __construct() {}

    /**
     * Returns information about the bucket
     *
     * Returns an associative array of status information as seen by the cluster for
     * this bucket. The exact structure of the returned data can be seen in the Couchbase
     * Manual by looking at the bucket /info endpoint.
     *
     * @return array
     *
     * @see https://developer.couchbase.com/documentation/server/current/rest-api/rest-bucket-info.html
     *   Getting Single Bucket Information
     */
    public function info() {}

    /**
     * Flushes the bucket (clears all data)
     */
    public function flush() {}

    /**
     * Returns all design documents of the bucket.
     *
     * @return array
     */
    public function listDesignDocuments() {}

    /**
     * Get design document by its name
     *
     * @param string $name name of the design document (without _design/ prefix)
     * @return array
     */
    public function getDesignDocument($name) {}

    /**
     * Removes design document by its name
     *
     * @param string $name name of the design document (without _design/ prefix)
     */
    public function removeDesignDocument($name) {}

    /**
     * Creates or replaces design document.
     *
     * @param string $name name of the design document (without _design/ prefix)
     * @param array $document
     */
    public function upsertDesignDocument($name, $document) {}

    /**
     * Inserts design document and fails if it is exist already.
     *
     * @param string $name name of the design document (without _design/ prefix)
     * @param array $document
     */
    public function insertDesignDocument($name, $document) {}

    /**
     * List all N1QL indexes that are registered for the current bucket.
     *
     * @return array
     */
    public function listN1qlIndexes() {}

    /**
     * Create a primary N1QL index.
     *
     * @param string $customName the custom name for the primary index.
     * @param bool $ignoreIfExist if a primary index already exists, an exception
     *   will be thrown unless this is set to true.
     * @param bool $defer true to defer index building.
     */
    public function createN1qlPrimaryIndex($customName = '', $ignoreIfExist = false, $defer = false) {}

    /**
     * Create secondary N1QL index.
     *
     * @param string $name name of the index
     * @param array $fields list of JSON fields to index
     * @param string $whereClause the WHERE clause of the index.
     * @param bool $ignoreIfExist if a secondary index already exists, an exception
     *   will be thrown unless this is set to true.
     * @param bool $defer true to defer index building.
     */
    public function createN1qlIndex($name, $fields, $whereClause = '', $ignoreIfExist = false, $defer = false) {}

    /**
     * Drop the given primary index
     *
     * @param string $customName the custom name for the primary index
     * @param bool $ignoreIfNotExist if a primary index does not exist, an exception
     *   will be thrown unless this is set to true.
     */
    public function dropN1qlPrimaryIndex($customName = '', $ignoreIfNotExist = false) {}

    /**
     * Drop the given secondary index
     *
     * @param string $name the index name
     * @param bool $ignoreIfNotExist if a secondary index does not exist, an exception
     *   will be thrown unless this is set to true.
     */
    public function dropN1qlIndex($name, $ignoreIfNotExist = false) {}
}

/**
 * Interface of authentication containers.
 *
 * @see \Couchbase\Cluster::authenticate()
 * @see \Couchbase\ClassicAuthenticator
 * @see \Couchbase\PasswordAuthenticator
 */
interface Authenticator {}

/**
 * Authenticator based on login/password credentials.
 *
 * This authenticator uses separate credentials for Cluster management interface
 * as well as for each bucket.
 *
 *
 *
 * @see \Couchbase\Cluster::authenticate()
 * @see \Couchbase\Authenticator
 */
class ClassicAuthenticator implements Authenticator
{
    /**
     * Registers cluster management credentials in the container
     *
     * @param string $username admin username
     * @param string $password admin password
     */
    public function cluster($username, $password) {}

    /**
     * Registers bucket credentials in the container
     *
     * @param string $name bucket name
     * @param string $password bucket password
     */
    public function bucket($name, $password) {}
}

/**
 * Authenticator based on RBAC feature of Couchbase Server 5+.
 *
 * This authenticator uses single credentials for all operations (data and management).
 *
 * @see \Couchbase\Cluster::authenticate()
 * @see \Couchbase\Authenticator
 */
class PasswordAuthenticator implements Authenticator
{
    /**
     * Sets username
     *
     * @param string $username username
     * @return \Couchbase\PasswordAuthenticator
     */
    public function username($username) {}

    /**
     * Sets password
     *
     * @param string $password password
     * @return \Couchbase\PasswordAuthenticator
     */
    public function password($password) {}
}

/**
 * An object which contains meta information of the document needed to enforce query consistency.
 */
class MutationToken
{
    final private function __construct() {}

    /**
     * Creates new mutation token
     *
     * @param string $bucketName name of the bucket
     * @param int $vbucketId partition number
     * @param string $vbucketUuid UUID of the partition
     * @param string $sequenceNumber sequence number inside partition
     */
    public static function from($bucketName, $vbucketId, $vbucketUuid, $sequenceNumber) {}

    /**
     * Returns bucket name
     *
     * @return string
     */
    public function bucketName() {}

    /**
     * Returns partition number
     *
     * @return int
     */
    public function vbucketId() {}

    /**
     * Returns UUID of the partition
     *
     * @return string
     */
    public function vbucketUuid() {}

    /**
     * Returns the sequence number inside partition
     *
     * @return string
     */
    public function sequenceNumber() {}
}

/**
 * Container for mutation tokens.
 */
class MutationState
{
    final private function __construct() {}

    /**
     * Create container from the given mutation token holders.
     *
     * @param array|Document|DocumentFragment $source anything that can have attached MutationToken
     * @return MutationState
     *
     * @see \Couchbase\MutationToken
     */
    public static function from($source) {}

    /**
     * Update container with the given mutation token holders.
     *
     * @param array|Document|DocumentFragment $source anything that can have attached MutationToken
     *
     * @see \Couchbase\MutationToken
     */
    public function add($source) {}
}

/**
 * Common interface for all View queries
 *
 * @see \Couchbase\ViewQuery
 * @see \Couchbase\SpatialViewQuery
 */
interface ViewQueryEncodable
{
    /**
     * Returns associative array, representing the View query.
     *
     * @return array object which is ready to be serialized.
     */
    public function encode();
}

/**
 * Represents regular Couchbase Map/Reduce View query
 *
 * @see \Couchbase\Bucket::query()
 * @see \Couchbase\SpatialViewQuery
 * @see https://developer.couchbase.com/documentation/server/current/sdk/php/view-queries-with-sdk.html
 *   MapReduce Views
 * @see https://developer.couchbase.com/documentation/server/current/architecture/querying-data-with-views.html
 *   Querying Data with Views
 * @see https://developer.couchbase.com/documentation/server/current/rest-api/rest-views-get.html
 *   Getting Views Information
 */
class ViewQuery implements ViewQueryEncodable
{
    /** Force a view update before returning data */
    public const UPDATE_BEFORE = 1;

    /** Allow stale views */
    public const UPDATE_NONE = 2;

    /** Allow stale view, update view after it has been accessed. */
    public const UPDATE_AFTER = 3;
    public const ORDER_ASCENDING = 1;
    public const ORDER_DESCENDING = 2;

    final private function __construct() {}

    /**
     * Creates a new Couchbase ViewQuery instance for performing a view query.
     *
     * @param string $designDocumentName the name of the design document to query
     * @param string $viewName the name of the view to query
     * @return ViewQuery
     */
    public static function from($designDocumentName, $viewName) {}

    /**
     * Creates a new Couchbase ViewQuery instance for performing a spatial query.
     * @param string $designDocumentName the name of the design document to query
     * @param string $viewName the name of the view to query
     * @return SpatialViewQuery
     */
    public static function fromSpatial($designDocumentName, $viewName) {}

    /**
     * Returns associative array, representing the View query.
     *
     * @return array object which is ready to be serialized.
     */
    public function encode() {}

    /**
     * Limits the result set to a specified number rows.
     *
     * @param int $limit maximum number of records in the response
     * @return ViewQuery
     */
    public function limit($limit) {}

    /**
     * Skips a number o records rom the beginning of the result set
     *
     * @param int $skip number of records to skip
     * @return ViewQuery
     */
    public function skip($skip) {}

    /**
     * Specifies the mode of updating to perorm before and after executing the query
     *
     * @param int $consistency use constants UPDATE_BEFORE, UPDATE_NONE, UPDATE_AFTER
     * @return ViewQuery
     *
     * @see \Couchbase\ViewQuery::UPDATE_BEFORE
     * @see \Couchbase\ViewQuery::UPDATE_NONE
     * @see \Couchbase\ViewQuery::UPDATE_AFTER
     */
    public function consistency($consistency) {}

    /**
     * Orders the results by key as specified
     *
     * @param int $order use contstants ORDER_ASCENDING, ORDER_DESCENDING
     * @return ViewQuery
     */
    public function order($order) {}

    /**
     * Specifies whether the reduction function should be applied to results of the query.
     *
     * @param bool $reduce
     * @return ViewQuery
     */
    public function reduce($reduce) {}

    /**
     * Group the results using the reduce function to a group or single row.
     *
     * Important: this setter and groupLevel should not be used together in the
     * same ViewQuery. It is sufficient to only set the grouping level only and
     * use this setter in cases where you always want the highest group level
     * implictly.
     *
     * @param bool $group
     * @return ViewQuery
     *
     * @see \Couchbase\ViewQuery::groupLevel
     */
    public function group($group) {}

    /**
     * Specify the group level to be used.
     *
     * Important: group() and this setter should not be used together in the
     * same ViewQuery. It is sufficient to only use this setter and use group()
     * in cases where you always want the highest group level implictly.
     *
     * @param int $groupLevel the number of elements in the keys to use
     * @return ViewQuery
     *
     * @see \Couchbase\ViewQuery::group
     */
    public function groupLevel($groupLevel) {}

    /**
     * Restict results of the query to the specified key
     *
     * @param mixed $key key
     * @return ViewQuery
     */
    public function key($key) {}

    /**
     * Restict results of the query to the specified set of keys
     *
     * @param array $keys set of keys
     * @return ViewQuery
     */
    public function keys($keys) {}

    /**
     * Specifies a range of the keys to return from the index.
     *
     * @param mixed $startKey
     * @param mixed $endKey
     * @param bool $inclusiveEnd
     * @return ViewQuery
     */
    public function range($startKey, $endKey, $inclusiveEnd = false) {}

    /**
     * Specifies start and end document IDs in addition to range limits.
     *
     * This might be needed for more precise pagination with a lot of documents
     * with the same key selected into the same page.
     *
     * @param string $startKeyDocumentId document ID
     * @param string $endKeyDocumentId document ID
     * @return ViewQuery
     */
    public function idRange($startKeyDocumentId, $endKeyDocumentId) {}

    /**
     * Specifies custom options to pass to the server.
     *
     * Note that these options are expected to be already encoded.
     *
     * @param array $customParameters parameters
     * @return ViewQuery
     *
     * @see https://developer.couchbase.com/documentation/server/current/rest-api/rest-views-get.html
     *   Getting Views Information
     */
    public function custom($customParameters) {}
}

/**
 * Represents spatial Couchbase Map/Reduce View query
 *
 * @see \Couchbase\Bucket::query()
 * @see \Couchbase\ViewQuery
 * @see https://developer.couchbase.com/documentation/server/current/architecture/querying-geo-data-spatial-views.html
 *   Querying Geographic Data with Spatial Views
 * @see https://developer.couchbase.com/documentation/server/current/rest-api/rest-views-get.html
 *   Getting Views Information
 * @see https://developer.couchbase.com/documentation/server/current/views/sv-query-parameters.html
 *   Querying spatial views
 */
class SpatialViewQuery implements ViewQueryEncodable
{
    final private function __construct() {}

    /**
     * Returns associative array, representing the View query.
     *
     * @return array object which is ready to be serialized.
     */
    public function encode() {}

    /**
     * Limits the result set to a specified number rows.
     *
     * @param int $limit maximum number of records in the response
     * @return SpatialViewQuery
     */
    public function limit($limit) {}

    /**
     * Skips a number o records rom the beginning of the result set
     *
     * @param int $skip number of records to skip
     * @return SpatialViewQuery
     */
    public function skip($skip) {}

    /**
     * Specifies the mode of updating to perorm before and after executing the query
     *
     * @param int $consistency use constants UPDATE_BEFORE, UPDATE_NONE, UPDATE_AFTER
     * @return SpatialViewQuery
     *
     * @see \Couchbase\ViewQuery::UPDATE_BEFORE
     * @see \Couchbase\ViewQuery::UPDATE_NONE
     * @see \Couchbase\ViewQuery::UPDATE_AFTER
     */
    public function consistency($consistency) {}

    /**
     * Orders the results by key as specified
     *
     * @param int $order use contstants ORDER_ASCENDING, ORDER_DESCENDING
     * @return SpatialViewQuery
     */
    public function order($order) {}

    /**
     * Specifies the bounding box to search within.
     *
     * Note, using bbox() is discouraged, startRange/endRange is more flexible and should be preferred.
     *
     * @param array $bbox bounding box coordinates expressed as a list of numeric values
     * @return SpatialViewQuery
     *
     * @see \Couchbase\SpatialViewQuery::startRange()
     * @see \Couchbase\SpatialViewQuery::endRange()
     */
    public function bbox($bbox) {}

    /**
     * Specify start range for query
     *
     * @param array $range
     * @return SpatialViewQuery
     *
     * @see https://developer.couchbase.com/documentation/server/current/views/sv-query-parameters.html
     *   Querying spatial views
     */
    public function startRange($range) {}

    /**
     * Specify end range for query
     *
     * @param array $range
     * @return SpatialViewQuery
     *
     * @see https://developer.couchbase.com/documentation/server/current/views/sv-query-parameters.html
     *   Querying spatial views
     */
    public function endRange($range) {}

    /**
     * Specifies custom options to pass to the server.
     *
     * Note that these options are expected to be already encoded.
     *
     * @param array $customParameters parameters
     *
     * @see https://developer.couchbase.com/documentation/server/current/rest-api/rest-views-get.html
     *   Getting Views Information
     * @see https://developer.couchbase.com/documentation/server/current/views/sv-query-parameters.html
     *   Querying spatial views
     */
    public function custom($customParameters) {}
}

/**
 * Represents a N1QL query
 *
 * @see https://developer.couchbase.com/documentation/server/current/sdk/n1ql-query.html
 *   Querying with N1QL
 * @see https://developer.couchbase.com/documentation/server/current/sdk/php/n1ql-queries-with-sdk.html
 *   N1QL from the SDKs
 * @see https://developer.couchbase.com/documentation/server/current/n1ql/n1ql-rest-api/index.html
 *   N1QL REST API
 * @see https://developer.couchbase.com/documentation/server/current/performance/index-scans.html
 *   Understanding Index Scans
 * @see https://developer.couchbase.com/documentation/server/current/performance/indexing-and-query-perf.html
 *   Indexing JSON Documents and Query Performance
 */
class N1qlQuery
{
    /**
     * This is the default (for single-statement requests).
     * No timestamp vector is used in the index scan.
     * This is also the fastest mode, because we avoid the cost of obtaining the vector,
     * and we also avoid any wait for the index to catch up to the vector.
     */
    public const NOT_BOUNDED = 1;

    /**
     * This implements strong consistency per request.
     * Before processing the request, a current vector is obtained.
     * The vector is used as a lower bound for the statements in the request.
     * If there are DML statements in the request, RYOW is also applied within the request.
     */
    public const REQUEST_PLUS = 2;

    /**
     * This implements strong consistency per statement.
     * Before processing each statement, a current vector is obtained
     * and used as a lower bound for that statement.
     */
    public const STATEMENT_PLUS = 3;

    /**
     * Disables profiling. This is the default
     */
    public const PROFILE_NONE = 'off';

    /**
     * Enables phase profiling.
     */
    public const PROFILE_PHASES = 'phases';

    /**
     * Enables general timing profiling.
     */
    public const PROFILE_TIMINGS = 'timings';

    final private function __construct() {}

    /**
     * Creates new N1qlQuery instance directly from the N1QL string.
     *
     * @param string $statement N1QL string
     * @return N1qlQuery
     */
    public static function fromString($statement) {}

    /**
     * Allows to specify if this query is adhoc or not.
     *
     * If it is not adhoc (so performed often), the client will try to perform optimizations
     * transparently based on the server capabilities, like preparing the statement and
     * then executing a query plan instead of the raw query.
     *
     * @param bool $adhoc if query is adhoc, default is true (plain execution)
     * @return N1qlQuery
     */
    public function adhoc($adhoc) {}

    /**
     * Allows to pull credentials from the Authenticator
     *
     * @param bool $crossBucket if query includes joins for multiple buckets (default is false)
     * @return N1qlQuery
     *
     *
     * @see \Couchbase\Authenticator
     * @see \Couchbase\ClassicAuthenticator
     */
    public function crossBucket($crossBucket) {}

    /**
     * Specify array of positional parameters
     *
     * Previously specified positional parameters will be replaced.
     * Note: carefully choose type of quotes for the query string, because PHP also uses `$`
     * (dollar sign) for variable interpolation. If you are using double quotes, make sure
     * that N1QL parameters properly escaped.
     *
     * @param array $params
     * @return N1qlQuery
     */
    public function positionalParams($params) {}

    /**
     * Specify associative array of named parameters
     *
     * The supplied array of key/value pairs will be merged with already existing named parameters.
     * Note: carefully choose type of quotes for the query string, because PHP also uses `$`
     * (dollar sign) for variable interpolation. If you are using double quotes, make sure
     * that N1QL parameters properly escaped.
     *
     * @param array $params
     * @return N1qlQuery
     */
    public function namedParams($params) {}

    /**
     * Specifies the consistency level for this query
     *
     * @param int $consistency consistency level
     * @return N1qlQuery
     *
     * @see \Couchbase\N1qlQuery::NOT_BOUNDED
     * @see \Couchbase\N1qlQuery::REQUEST_PLUS
     * @see \Couchbase\N1qlQuery::STATEMENT_PLUS
     * @see \Couchbase\N1qlQuery::consistentWith()
     */
    public function consistency($consistency) {}

    /**
     * Controls the profiling mode used during query execution
     *
     * @param string $profileType
     * @return N1qlQuery
     * @see \Couchbase\N1qlQuery::PROFILE_NONE
     * @see \Couchbase\N1qlQuery::PROFILE_PHASES
     * @see \Couchbase\N1qlQuery::PROFILE_TIMINGS
     */
    public function profile($profileType) {}

    /**
     * Sets mutation state the query should be consistent with
     *
     * @param MutationState $state the container of mutation tokens
     * @return N1qlQuery
     *
     * @see \Couchbase\MutationState
     */
    public function consistentWith($state) {}

    /**
     * If set to true, it will signal the query engine on the server that only non-data modifying requests
     * are allowed. Note that this rule is enforced on the server and not the SDK side.
     *
     * Controls whether a query can change a resulting record set.
     *
     * If readonly is true, then the following statements are not allowed:
     *  - CREATE INDEX
     *  - DROP INDEX
     *  - INSERT
     *  - MERGE
     *  - UPDATE
     *  - UPSERT
     *  - DELETE
     *
     * @param bool $readonly true if readonly should be forced, false is the default and will use the server side default.
     * @return N1qlQuery
     */
    public function readonly($readonly) {}

    /**
     * Advanced: Maximum buffered channel size between the indexer client and the query service for index scans.
     *
     * This parameter controls when to use scan backfill. Use 0 or a negative number to disable.
     *
     * @param int $scanCap the scan_cap param, use 0 or negative number to disable.
     * @return N1qlQuery
     */
    public function scanCap($scanCap) {}

    /**
     * Advanced: Controls the number of items execution operators can batch for Fetch from the KV.
     *
     * @param int $pipelineBatch the pipeline_batch param.
     * @return N1qlQuery
     */
    public function pipelineBatch($pipelineBatch) {}

    /**
     * Advanced: Maximum number of items each execution operator can buffer between various operators.
     *
     * @param int $pipelineCap the pipeline_cap param.
     * @return N1qlQuery
     */
    public function pipelineCap($pipelineCap) {}

    /**
     * Allows to override the default maximum parallelism for the query execution on the server side.
     *
     * @param int $maxParallelism the maximum parallelism for this query, 0 or negative values disable it.
     * @return N1qlQuery
     */
    public function maxParallelism($maxParallelism) {}
}

/**
 * Represents N1QL index definition
 *
 * @see https://developer.couchbase.com/documentation/server/current/performance/indexing-and-query-perf.html
 *   Indexing JSON Documents and Query Performance
 */
class N1qlIndex
{
    public const UNSPECIFIED = 0;
    public const GSI = 1;
    public const VIEW = 2;

    final private function __construct() {}

    /**
     * Name of the index
     *
     * @var string
     */
    public $name;

    /**
     * Is it primary index
     *
     * @var bool
     */
    public $isPrimary;

    /**
     * Type of the index
     *
     * @var int
     *
     * @see \Couchbase\N1qlIndex::UNSPECIFIED
     * @see \Couchbase\N1qlIndex::GSI
     * @see \Couchbase\N1qlIndex::VIEW
     */
    public $type;

    /**
     * The descriptive state of the index
     *
     * @var string
     */
    public $state;

    /**
     * The keyspace for the index, typically the bucket name
     * @var string
     */
    public $keyspace;

    /**
     * The namespace for the index. A namespace is a resource pool that contains multiple keyspaces
     * @var string
     */
    public $namespace;

    /**
     * The fields covered by index
     * @var array
     */
    public $fields;

    /**
     * Return the string representation of the index's condition (the WHERE clause
     * of the index), or an empty String if no condition was set.
     *
     * Note that the query service can present the condition in a slightly different
     * manner from when you declared the index: for instance it will wrap expressions
     * with parentheses and show the fields in an escaped format (surrounded by backticks).
     *
     * @var string
     */
    public $condition;
}

/**
 * A builder for subdocument lookups. In order to perform the final set of operations, use the
 * execute() method.
 *
 * Instances of this builder should be obtained through \Couchbase\Bucket->lookupIn()
 *
 * @see \Couchbase\Bucket::lookupIn
 * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
 *   Sub-Document Operations
 */
class LookupInBuilder
{
    final private function __construct() {}

    /**
     * Get a value inside the JSON document.
     *
     * @param string $path the path inside the document where to get the value from.
     * @param array $options the array with command modificators. Supported values are
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return LookupInBuilder
     */
    public function get($path, $options = []) {}

    /**
     * Get a count of values inside the JSON document.
     *
     * This method is only available with Couchbase Server 5.0 and later.
     *
     * @param string $path the path inside the document where to get the count from.
     * @param array $options the array with command modificators. Supported values are
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return LookupInBuilder
     */
    public function getCount($path, $options = []) {}

    /**
     * Check if a value exists inside the document.
     *
     * This doesn't transmit the value on the wire if it exists, saving the corresponding byte overhead.
     *
     * @param string $path the path inside the document to check for existence
     * @param array $options the array with command modificators. Supported values are
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return LookupInBuilder
     */
    public function exists($path, $options = []) {}

    /**
     * Perform several lookup operations inside a single existing JSON document, using a specific timeout
     * @return DocumentFragment
     */
    public function execute() {}
}

/**
 * A builder for subdocument mutations. In order to perform the final set of operations, use the
 * execute() method.
 *
 * Instances of this builder should be obtained through \Couchbase\Bucket->mutateIn()
 *
 * @see \Couchbase\Bucket::mutateIn
 * @see https://developer.couchbase.com/documentation/server/current/sdk/subdocument-operations.html
 *   Sub-Document Operations
 */
class MutateInBuilder
{
    public const FULLDOC_REPLACE = 0;
    public const FULLDOC_UPSERT = 1;
    public const FULLDOC_INSERT = 2;

    final private function __construct() {}

    /**
     * Insert a fragment provided the last element of the path doesn't exists.
     *
     * @param string $path the path where to insert a new dictionary value.
     * @param mixed $value the new dictionary value to insert.
     * @param array|bool $options the array with command modificators.
     *   The boolean value, controls "createPath" option. Supported values are:
     *   * "createPath" (default: false) true to create missing intermediary nodes.
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return MutateInBuilder
     */
    public function insert($path, $value, $options = []) {}

    /**
     * Select mode for new full-document operations.
     *
     * It defines behaviour of MutateInBuilder#upsert() method. The $mode
     * could take one of three modes:
     *  * FULLDOC_REPLACE: complain when document does not exist
     *  * FULLDOC_INSERT: complain when document does exist
     *  * FULLDOC_UPSERT: unconditionally set value for the document
     *
     * @param int $mode operation mode
     */
    public function modeDocument($mode) {}

    /**
     * Insert a fragment, replacing the old value if the path exists.
     *
     * When only one argument supplied, the library will handle it as full-document
     * upsert, and treat this argument as value. See MutateInBuilder#modeDocument()
     *
     * @param string $path the path where to insert (or replace) a dictionary value
     * @param mixed $value the new dictionary value to be applied.
     * @param array|bool $options the array with command modificators.
     *   The boolean value, controls "createPath" option. Supported values are:
     *   * "createPath" (default: false) true to create missing intermediary nodes.
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return MutateInBuilder
     */
    public function upsert($path, $value, $options = []) {}

    /**
     * Replace an existing value by the given fragment
     *
     * @param string $path the path where the value to replace is
     * @param mixed $value the new value
     * @param array $options the array with command modificators. Supported values are:
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return MutateInBuilder
     */
    public function replace($path, $value, $options = []) {}

    /**
     * Remove an entry in a JSON document.
     *
     * Scalar, array element, dictionary entry, whole array or dictionary, depending on the path.
     *
     * @param string $path the path to remove
     * @param array $options the array with command modificators. Supported values are:
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return MutateInBuilder
     */
    public function remove($path, $options = []) {}

    /**
     * Prepend to an existing array, pushing the value to the front/first position in the array.
     *
     * @param string $path the path of the array
     * @param mixed $value the value to insert at the front of the array
     * @param array|bool $options the array with command modificators.
     *   The boolean value, controls "createPath" option. Supported values are:
     *   * "createPath" (default: false) true to create missing intermediary nodes.
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return MutateInBuilder
     */
    public function arrayPrepend($path, $value, $options = []) {}

    /**
     * Prepend multiple values at once in an existing array.
     *
     * Push all values in the collection's iteration order to the front/start of the array.
     * For example given an array [A, B, C], prepending the values X and Y yields [X, Y, A, B, C]
     * and not [[X, Y], A, B, C].
     *
     * @param string $path the path of the array
     * @param array $values the values to insert at the front of the array as individual elements
     * @param array|bool $options the array with command modificators.
     *   The boolean value, controls "createPath" option. Supported values are:
     *   * "createPath" (default: false) true to create missing intermediary nodes.
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return MutateInBuilder
     */
    public function arrayPrependAll($path, $values, $options = []) {}

    /**
     * Append to an existing array, pushing the value to the back/last position in the array.
     *
     * @param string $path the path of the array
     * @param mixed $value the value to insert at the back of the array
     * @param array|bool $options the array with command modificators.
     *   The boolean value, controls "createPath" option. Supported values are:
     *   * "createPath" (default: false) true to create missing intermediary nodes.
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return MutateInBuilder
     */
    public function arrayAppend($path, $value, $options = []) {}

    /**
     * Append multiple values at once in an existing array.
     *
     * Push all values in the collection's iteration order to the back/end of the array.
     * For example given an array [A, B, C], appending the values X and Y yields [A, B, C, X, Y]
     * and not [A, B, C, [X, Y]].
     *
     * @param string $path the path of the array
     * @param array $values the values to individually insert at the back of the array
     * @param array|bool $options the array with command modificators.
     *   The boolean value, controls "createPath" option. Supported values are:
     *   * "createPath" (default: false) true to create missing intermediary nodes.
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return MutateInBuilder
     */
    public function arrayAppendAll($path, $values, $options = []) {}

    /**
     * Insert into an existing array at a specific position
     *
     * Position denoted in the path, eg. "sub.array[2]".
     *
     * @param string $path the path (including array position) where to insert the value
     * @param mixed $value the value to insert in the array
     * @param array $options the array with command modificators. Supported values are:
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return MutateInBuilder
     */
    public function arrayInsert($path, $value, $options = []) {}

    /**
     * Insert multiple values at once in an existing array at a specified position.
     *
     * Position denoted in the path, eg. "sub.array[2]"), inserting all values in the collection's iteration order
     * at the given position and shifting existing values beyond the position by the number of elements in the
     * collection.
     *
     * For example given an array [A, B, C], inserting the values X and Y at position 1 yields [A, B, X, Y, C]
     * and not [A, B, [X, Y], C].
     * @param string $path the path of the array
     * @param array $values the values to insert at the specified position of the array, each value becoming
     *   an entry at or after the insert position.
     * @param array $options the array with command modificators. Supported values are:
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return MutateInBuilder
     */
    public function arrayInsertAll($path, $values, $options = []) {}

    /**
     * Insert a value in an existing array only if the value
     * isn't already contained in the array (by way of string comparison).
     *
     * @param string $path the path to mutate in the JSON
     * @param mixed $value the value to insert
     * @param array|bool $options the array with command modificators.
     *   The boolean value, controls "createPath" option. Supported values are:
     *   * "createPath" (default: false) true to create missing intermediary nodes.
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return MutateInBuilder
     */
    public function arrayAddUnique($path, $value, $options = []) {}

    /**
     * Increment/decrement a numerical fragment in a JSON document.
     *
     * If the value (last element of the path) doesn't exist the counter
     * is created and takes the value of the delta.
     *
     * @param string $path the path to the counter (must be containing a number).
     * @param int $delta the value to increment or decrement the counter by
     * @param array|bool $options the array with command modificators.
     *   The boolean value, controls "createPath" option. Supported values are:
     *   * "createPath" (default: false) true to create missing intermediary nodes.
     *   * "xattr" (default: false) if true, the path refers to a location
     *     within the document's extended attributes, not the document body.
     * @return MutateInBuilder
     */
    public function counter($path, $delta, $options = []) {}

    /**
     * Change the expiry of the enclosing document as part of the mutation.
     *
     * @param mixed $expiry the new expiry to apply (or 0 to avoid changing the expiry)
     * @return MutateInBuilder
     */
    public function withExpiry($expiry) {}

    /**
     * Perform several mutation operations inside a single existing JSON document.
     * @return DocumentFragment
     */
    public function execute() {}
}

/**
 * Represents full text search query
 *
 * @see https://developer.couchbase.com/documentation/server/4.6/sdk/php/full-text-searching-with-sdk.html
 *   Searching from the SDK
 */
class SearchQuery implements \JsonSerializable
{
    public const HIGHLIGHT_HTML = 'html';
    public const HIGHLIGHT_ANSI = 'ansi';
    public const HIGHLIGHT_SIMPLE = 'simple';

    /**
     * Prepare boolean search query
     *
     * @return BooleanSearchQuery
     */
    public static function boolean() {}

    /**
     * Prepare date range search query
     *
     * @return DateRangeSearchQuery
     */
    public static function dateRange() {}

    /**
     * Prepare numeric range search query
     *
     * @return NumericRangeSearchQuery
     */
    public static function numericRange() {}

    /**
     * Prepare term range search query
     *
     * @return TermRangeSearchQuery
     */
    public static function termRange() {}

    /**
     * Prepare boolean field search query
     *
     * @param bool $value
     * @return BooleanFieldSearchQuery
     */
    public static function booleanField($value) {}

    /**
     * Prepare compound conjunction search query
     *
     * @param SearchQueryPart ...$queries list of inner query parts
     * @return ConjunctionSearchQuery
     */
    public static function conjuncts(...$queries) {}

    /**
     * Prepare compound disjunction search query
     *
     * @param SearchQueryPart ...$queries list of inner query parts
     * @return DisjunctionSearchQuery
     */
    public static function disjuncts(...$queries) {}

    /**
     * Prepare document ID search query
     *
     * @param string ...$documentIds
     * @return DocIdSearchQuery
     */
    public static function docId(...$documentIds) {}

    /**
     * Prepare match search query
     *
     * @param string $match
     * @return MatchSearchQuery
     */
    public static function match($match) {}

    /**
     * Prepare match all search query
     *
     * @return MatchAllSearchQuery
     */
    public static function matchAll() {}

    /**
     * Prepare match non search query
     *
     * @return MatchNoneSearchQuery
     */
    public static function matchNone() {}

    /**
     * Prepare phrase search query
     *
     * @param string ...$terms
     * @return MatchPhraseSearchQuery
     */
    public static function matchPhrase(...$terms) {}

    /**
     * Prepare prefix search query
     *
     * @param string $prefix
     * @return PrefixSearchQuery
     */
    public static function prefix($prefix) {}

    /**
     * Prepare query string search query
     *
     * @param string $queryString
     * @return QueryStringSearchQuery
     */
    public static function queryString($queryString) {}

    /**
     * Prepare regexp search query
     *
     * @param string $regexp
     * @return RegexpSearchQuery
     */
    public static function regexp($regexp) {}

    /**
     * Prepare term search query
     *
     * @param string $term
     * @return TermSearchQuery
     */
    public static function term($term) {}

    /**
     * Prepare wildcard search query
     *
     * @param string $wildcard
     * @return WildcardSearchQuery
     */
    public static function wildcard($wildcard) {}

    /**
     * Prepare geo distance search query
     *
     * @param float $longitude
     * @param float $latitude
     * @param string $distance e.g. "10mi"
     * @return GeoDistanceSearchQuery
     */
    public static function geoDistance($longitude, $latitude, $distance) {}

    /**
     * Prepare geo bounding box search query
     *
     * @param float $topLeftLongitude
     * @param float $topLeftLatitude
     * @param float $bottomRightLongitude
     * @param float $bottomRightLatitude
     * @return GeoBoundingBoxSearchQuery
     */
    public static function geoBoundingBox($topLeftLongitude, $topLeftLatitude, $bottomRightLongitude, $bottomRightLatitude) {}

    /**
     * Prepare term search facet
     *
     * @param string $field
     * @param int $limit
     * @return TermSearchFacet
     */
    public static function termFacet($field, $limit) {}

    /**
     * Prepare date range search facet
     *
     * @param string $field
     * @param int $limit
     * @return DateRangeSearchFacet
     */
    public static function dateRangeFacet($field, $limit) {}

    /**
     * Prepare numeric range search facet
     *
     * @param string $field
     * @param int $limit
     * @return NumericRangeSearchFacet
     */
    public static function numericRangeFacet($field, $limit) {}

    /**
     * Prepare an FTS SearchQuery on an index.
     *
     * Top level query parameters can be set after that by using the fluent API.
     *
     * @param string $indexName the FTS index to search in
     * @param SearchQueryPart $queryPart the body of the FTS query (e.g. a match phrase query)
     */
    public function __construct($indexName, $queryPart) {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * Add a limit to the query on the number of hits it can return
     *
     * @param int $limit the maximum number of hits to return
     * @return SearchQuery
     */
    public function limit($limit) {}

    /**
     * Set the number of hits to skip (eg. for pagination).
     *
     * @param int $skip the number of results to skip
     * @return SearchQuery
     */
    public function skip($skip) {}

    /**
     * Activates the explanation of each result hit in the response
     *
     * @param bool $explain
     * @return SearchQuery
     */
    public function explain($explain) {}

    /**
     * Sets the server side timeout in milliseconds
     *
     * @param int $serverSideTimeout the server side timeout to apply
     * @return SearchQuery
     */
    public function serverSideTimeout($serverSideTimeout) {}

    /**
     * Sets the consistency to consider for this FTS query to AT_PLUS and
     * uses the MutationState to parameterize the consistency.
     *
     * This replaces any consistency tuning previously set.
     *
     * @param MutationState $state the mutation state information to work with
     * @return SearchQuery
     */
    public function consistentWith($state) {}

    /**
     * Configures the list of fields for which the whole value should be included in the response.
     *
     * If empty, no field values are included. This drives the inclusion of the fields in each hit.
     * Note that to be highlighted, the fields must be stored in the FTS index.
     *
     * @param string ...$fields
     * @return SearchQuery
     */
    public function fields(...$fields) {}

    /**
     * Configures the highlighting of matches in the response
     *
     * @param string $style highlight style to apply. Use constants HIGHLIGHT_HTML,
     *   HIGHLIGHT_ANSI, HIGHLIGHT_SIMPLE.
     * @param string ...$fields the optional fields on which to highlight.
     *   If none, all fields where there is a match are highlighted.
     * @return SearchQuery
     *
     * @see \Couchbase\SearchQuery::HIGHLIGHT_HTML
     * @see \Couchbase\SearchQuery::HIGHLIGHT_ANSI
     * @see \Couchbase\SearchQuery::HIGHLIGHT_SIMPLE
     */
    public function highlight($style, ...$fields) {}

    /**
     * Configures the list of fields (including special fields) which are used for sorting purposes.
     * If empty, the default sorting (descending by score) is used by the server.
     *
     * The list of sort fields can include actual fields (like "firstname" but then they must be stored in the
     * index, configured in the server side mapping). Fields provided first are considered first and in a "tie" case
     * the next sort field is considered. So sorting by "firstname" and then "lastname" will first sort ascending by
     * the firstname and if the names are equal then sort ascending by lastname. Special fields like "_id" and
     * "_score" can also be used. If prefixed with "-" the sort order is set to descending.
     *
     * If no sort is provided, it is equal to sort("-_score"), since the server will sort it by score in descending
     * order.
     *
     * @param mixed $sort the fields that should take part in the sorting.
     * @return SearchQuery
     */
    public function sort(...$sort) {}

    /**
     * Adds one SearchFacet to the query
     *
     * This is an additive operation (the given facets are added to any facet previously requested),
     * but if an existing facet has the same name it will be replaced.
     *
     * Note that to be faceted, a field's value must be stored in the FTS index.
     *
     * @param string $name
     * @param SearchFacet $facet
     * @return SearchQuery
     *
     * @see \Couchbase\SearchFacet
     * @see \Couchbase\TermSearchFacet
     * @see \Couchbase\NumericRangeSearchFacet
     * @see \Couchbase\DateRangeSearchFacet
     */
    public function addFacet($name, $facet) {}
}

/**
 * Common interface for all classes, which could be used as a body of SearchQuery
 *
 * @see \Couchbase\SearchQuery::__construct()
 */
interface SearchQueryPart {}

/**
 * A FTS query that queries fields explicitly indexed as boolean.
 */
class BooleanFieldSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return BooleanFieldSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return BooleanFieldSearchQuery
     */
    public function field($field) {}
}

/**
 * A compound FTS query that allows various combinations of sub-queries.
 */
class BooleanSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return BooleanSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param SearchQueryPart ...$queries
     * @return BooleanSearchQuery
     */
    public function must(...$queries) {}

    /**
     * @param SearchQueryPart ...$queries
     * @return BooleanSearchQuery
     */
    public function mustNot(...$queries) {}

    /**
     * @param SearchQueryPart ...$queries
     * @return BooleanSearchQuery
     */
    public function should(...$queries) {}
}

/**
 * A compound FTS query that performs a logical AND between all its sub-queries (conjunction).
 */
class ConjunctionSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return ConjunctionSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param SearchQueryPart ...$queries
     * @return ConjunctionSearchQuery
     */
    public function every(...$queries) {}
}

/**
 * A compound FTS query that performs a logical OR between all its sub-queries (disjunction). It requires that a
 * minimum of the queries match. The minimum is configurable (default 1).
 */
class DisjunctionSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return DisjunctionSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param SearchQueryPart ...$queries
     * @return DisjunctionSearchQuery
     */
    public function either(...$queries) {}

    /**
     * @param int $min
     * @return DisjunctionSearchQuery
     */
    public function min($min) {}
}

/**
 * A FTS query that matches documents on a range of values. At least one bound is required, and the
 * inclusiveness of each bound can be configured.
 */
class DateRangeSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return DateRangeSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return DateRangeSearchQuery
     */
    public function field($field) {}

    /**
     * @param int|string $start The strings will be taken verbatim and supposed to be formatted with custom date
     *      time formatter (see dateTimeParser). Integers interpreted as unix timestamps and represented as RFC3339
     *      strings.
     * @param bool $inclusive
     * @return DateRangeSearchQuery
     */
    public function start($start, $inclusive = true) {}

    /**
     * @param int|string $end The strings will be taken verbatim and supposed to be formatted with custom date
     *      time formatter (see dateTimeParser). Integers interpreted as unix timestamps and represented as RFC3339
     *      strings.
     * @param bool $inclusive
     * @return DateRangeSearchQuery
     */
    public function end($end, $inclusive = false) {}

    /**
     * @param string $dateTimeParser
     * @return DateRangeSearchQuery
     */
    public function dateTimeParser($dateTimeParser) {}
}

/**
 * A FTS query that matches documents on a range of values. At least one bound is required, and the
 * inclusiveness of each bound can be configured.
 */
class NumericRangeSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return NumericRangeSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return NumericRangeSearchQuery
     */
    public function field($field) {}

    /**
     * @param float $min
     * @param bool $inclusive
     * @return NumericRangeSearchQuery
     */
    public function min($min, $inclusive = true) {}

    /**
     * @param float $max
     * @param bool $inclusive
     * @return NumericRangeSearchQuery
     */
    public function max($max, $inclusive = false) {}
}

/**
 * A FTS query that matches on Couchbase document IDs. Useful to restrict the search space to a list of keys (by using
 * this in a compound query).
 */
class DocIdSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return DocIdSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return DocIdSearchQuery
     */
    public function field($field) {}

    /**
     * @param string ...$documentIds
     * @return DocIdSearchQuery
     */
    public function docIds(...$documentIds) {}
}

/**
 * A FTS query that matches all indexed documents (usually for debugging purposes).
 */
class MatchAllSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return MatchAllSearchQuery
     */
    public function boost($boost) {}
}

/**
 * A FTS query that matches 0 document (usually for debugging purposes).
 */
class MatchNoneSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return MatchNoneSearchQuery
     */
    public function boost($boost) {}
}

/**
 * A FTS query that matches several given terms (a "phrase"), applying further processing
 * like analyzers to them.
 */
class MatchPhraseSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return MatchPhraseSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return MatchPhraseSearchQuery
     */
    public function field($field) {}

    /**
     * @param string $analyzer
     * @return MatchPhraseSearchQuery
     */
    public function analyzer($analyzer) {}
}

/**
 * A FTS query that matches a given term, applying further processing to it
 * like analyzers, stemming and even #fuzziness(int).
 */
class MatchSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return MatchSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return MatchSearchQuery
     */
    public function field($field) {}

    /**
     * @param string $analyzer
     * @return MatchSearchQuery
     */
    public function analyzer($analyzer) {}

    /**
     * @param int $prefixLength
     * @return MatchSearchQuery
     */
    public function prefixLength($prefixLength) {}

    /**
     * @param int $fuzziness
     * @return MatchSearchQuery
     */
    public function fuzziness($fuzziness) {}
}

/**
 * A FTS query that matches several terms (a "phrase") as is. The order of the terms mater and no further processing is
 * applied to them, so they must appear in the index exactly as provided.  Usually for debugging purposes, prefer
 * MatchPhraseQuery.
 */
class PhraseSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return PhraseSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return PhraseSearchQuery
     */
    public function field($field) {}
}

/**
 * A FTS query that allows for simple matching of regular expressions.
 */
class RegexpSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return RegexpSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return RegexpSearchQuery
     */
    public function field($field) {}
}

/**
 * A FTS query that allows for simple matching using wildcard characters (* and ?).
 */
class WildcardSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return WildcardSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return WildcardSearchQuery
     */
    public function field($field) {}
}

/**
 * A FTS query that allows for simple matching on a given prefix.
 */
class PrefixSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return PrefixSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return PrefixSearchQuery
     */
    public function field($field) {}
}

/**
 * A FTS query that performs a search according to the "string query" syntax.
 */
class QueryStringSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return QueryStringSearchQuery
     */
    public function boost($boost) {}
}

/**
 * A facet that gives the number of occurrences of the most recurring terms in all hits.
 */
class TermSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return TermSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return TermSearchQuery
     */
    public function field($field) {}

    /**
     * @param int $prefixLength
     * @return TermSearchQuery
     */
    public function prefixLength($prefixLength) {}

    /**
     * @param int $fuzziness
     * @return TermSearchQuery
     */
    public function fuzziness($fuzziness) {}
}

/**
 * A FTS query that matches documents on a range of values. At least one bound is required, and the
 * inclusiveness of each bound can be configured.
 */
class TermRangeSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return TermRangeSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return TermRangeSearchQuery
     */
    public function field($field) {}

    /**
     * @param string $min
     * @param bool $inclusive
     * @return TermRangeSearchQuery
     */
    public function min($min, $inclusive = true) {}

    /**
     * @param string $max
     * @param bool $inclusive
     * @return TermRangeSearchQuery
     */
    public function max($max, $inclusive = false) {}
}

/**
 * A FTS query that finds all matches from a given location (point) within the given distance.
 *
 * Both the point and the distance are required.
 */
class GeoDistanceSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return GeoDistanceSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return GeoDistanceSearchQuery
     */
    public function field($field) {}
}

/**
 * A FTS query which allows to match geo bounding boxes.
 */
class GeoBoundingBoxSearchQuery implements \JsonSerializable, SearchQueryPart
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param float $boost
     * @return GeoBoundingBoxSearchQuery
     */
    public function boost($boost) {}

    /**
     * @param string $field
     * @return GeoBoundingBoxSearchQuery
     */
    public function field($field) {}
}

/**
 * Common interface for all search facets
 *
 * @see \Couchbase\SearchQuery::addFacet()
 * @see \Couchbase\TermSearchFacet
 * @see \Couchbase\DateRangeSearchFacet
 * @see \Couchbase\NumericRangeSearchFacet
 */
interface SearchFacet {}

/**
 * A facet that gives the number of occurrences of the most recurring terms in all hits.
 */
class TermSearchFacet implements \JsonSerializable, SearchFacet
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}
}

/**
 * A facet that categorizes hits inside date ranges (or buckets) provided by the user.
 */
class DateRangeSearchFacet implements \JsonSerializable, SearchFacet
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param string $name
     * @param int|string $start
     * @param int|string $end
     * @return DateSearchFacet
     */
    public function addRange($name, $start, $end) {}
}

/**
 * A facet that categorizes hits into numerical ranges (or buckets) provided by the user.
 */
class NumericRangeSearchFacet implements \JsonSerializable, SearchFacet
{
    final private function __construct() {}

    /**
     * @return array
     */
    public function jsonSerialize() {}

    /**
     * @param string $name
     * @param float $min
     * @param float $max
     * @return NumericSearchFacet
     */
    public function addRange($name, $min, $max) {}
}

/**
 * Base class for all FTS sort options in querying.
 */
class SearchSort
{
    private function __construct() {}

    /**
     * Sort by the document identifier.
     *
     * @return SearchSortId
     */
    public static function id() {}

    /**
     * Sort by the hit score.
     *
     * @return SearchSortScore
     */
    public static function score() {}

    /**
     * Sort by a field in the hits.
     *
     * @param string $field the field name
     *
     * @return SearchSortField
     */
    public static function field($field) {}

    /**
     * Sort by geo location.
     *
     * @param string $field the field name
     * @param float $longitude the longitude of the location
     * @param float $latitude the latitude of the location
     *
     * @return SearchSortGeoDistance
     */
    public static function geoDistance($field, $longitude, $latitude) {}
}

/**
 * Sort by the document identifier.
 */
class SearchSortId extends SearchSort implements \JsonSerializable
{
    private function __construct() {}

    /**
     * Direction of the sort
     *
     * @param bool $descending
     *
     * @return SearchSortId
     */
    public function descending($descending) {}
}

/**
 * Sort by the hit score.
 */
class SearchSortScore extends SearchSort implements \JsonSerializable
{
    private function __construct() {}

    /**
     * Direction of the sort
     *
     * @param bool $descending
     *
     * @return SearchSortScore
     */
    public function descending($descending) {}
}

/**
 * Sort by a field in the hits.
 */
class SearchSortField extends SearchSort implements \JsonSerializable
{
    public const TYPE_AUTO = "auto";
    public const TYPE_STRING = "string";
    public const TYPE_NUMBER = "number";
    public const TYPE_DATE = "date";
    public const MODE_DEFAULT = "default";
    public const MODE_MIN = "min";
    public const MODE_MAX = "max";
    public const MISSING_FIRST = "first";
    public const MISSING_LAST = "last";

    private function __construct() {}

    /**
     * Direction of the sort
     *
     * @param bool $descending
     *
     * @return SearchSortField
     */
    public function descending($descending) {}

    /**
     * Set type of the field
     *
     * @param string $type the type
     *
     * @see SearchSortField::TYPE_AUTO
     * @see SearchSortField::TYPE_STRING
     * @see SearchSortField::TYPE_NUMBER
     * @see SearchSortField::TYPE_DATE
     */
    public function type($type) {}

    /**
     * Set mode of the sort
     *
     * @param string $mode the mode
     *
     * @see SearchSortField::MODE_MIN
     * @see SearchSortField::MODE_MAX
     */
    public function mode($mode) {}

    /**
     * Set where the hits with missing field will be inserted
     *
     * @param string $missing strategy for hits with missing fields
     *
     * @see SearchSortField::MISSING_FIRST
     * @see SearchSortField::MISSING_LAST
     */
    public function missing($missing) {}
}

/**
 * Sort by a location and unit in the hits.
 */
class SearchSortGeoDistance extends SearchSort implements \JsonSerializable
{
    private function __construct() {}

    /**
     * Direction of the sort
     *
     * @param bool $descending
     *
     * @return SearchSortGeoDistance
     */
    public function descending($descending) {}

    /**
     * Name of the units
     *
     * @param string $unit
     *
     * @return SearchSortGeoDistance
     */
    public function unit($unit) {}
}

/**
 * Represents a Analytics query (currently experimental support).
 *
 * @see https://developer.couchbase.com/documentation/server/4.5/analytics/quick-start.html
 *   Analytics quick start
 */
class AnalyticsQuery
{
    final private function __construct() {}

    /**
     * Creates new AnalyticsQuery instance directly from the string.
     *
     * @param string $statement statement string
     * @return AnalyticsQuery
     */
    public static function fromString($statement) {}
}
