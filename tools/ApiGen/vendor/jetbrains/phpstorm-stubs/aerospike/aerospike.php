<?php
/**
 * Copyright 2013-2017 Aerospike, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category   Database
 * @package    Aerospike
 * @author     Robert Marks <robert@aerospike.com>
 * @copyright  Copyright 2013-2018 Aerospike, Inc.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2
 * @link       https://www.aerospike.com/docs/client/php/
 * @filesource
 */

use JetBrains\PhpStorm\Deprecated;

/**
 * The Aerospike client class
 *
 * The Aerospike config options for `php.ini`:
 * ```php
 * // The connection timeout in milliseconds.
 * aerospike.connect_timeout = 1000;
 * // The read operation timeout in milliseconds.
 * aerospike.read_timeout = 1000;
 * // The write operation timeout in milliseconds.
 * aerospike.write_timeout = 1000;
 * // Whether to send and store the record's (ns,set,key) data along with
 * // its (unique identifier) digest. 0: digest, 1: send
 * aerospike.key_policy = 0; // only digest
 * // The unsupported type handler. 0: none, 1: PHP, 2: user-defined
 * aerospike.serializer = 1; // php serializer

 * // Path to the user-defined Lua function modules.
 * aerospike.udf.lua_user_path = /usr/local/aerospike/usr-lua;
 * // Indicates if shared memory should be used for cluster tending.
 * // Recommended for multi-process cases such as FPM. { true, false }
 * aerospike.shm.use = false;
 * // Explicitly sets the shm key for this client to store shared-memory
 * // cluster tending results in.
 * aerospike.shm.key = 0xA8000000; // integer value
 * // Shared memory maximum number of server nodes allowed. Leave a cushion so
 * // new nodes can be added without needing a client restart.
 * aerospike.shm.max_nodes = 16;
 * // Shared memory maximum number of namespaces allowed. Leave a cushion for
 * // new namespaces.
 * aerospike.shm.max_namespaces = 8;
 * // Take over shared memory cluster tending if the cluster hasn't been tended
 * // by this threshold in seconds.
 * aerospike.shm.takeover_threshold_sec = 30;
 * // Control the batch protocol. 0: batch-index, 1: batch-direct
 * aerospike.use_batch_direct = 0;
 * // The client will compress records larger than this value in bytes for transport.
 * aerospike.compression_threshold = 0;
 * // Max size of the synchronous connection pool for each server node
 * aerospike.max_threads = 300;
 * // Number of threads stored in underlying thread pool that is used in
 * // batch/scan/query commands. In ZTS builds, this is always 0.
 * aerospike.thread_pool_size = 16;
 * // When turning on the optional logging in the client, this is the path to the log file.
 * aerospike.log_path = NULL;
 * aerospike.log_level = NULL;
 * aerospike.nesting_depth = 3;
 *
 * // session handler
 * session.save_handler = aerospike; // to use the Aerospike session handler
 * session.gc_maxlifetime = 1440; // the TTL of the record used to store the session in seconds
 * session.save_path = NULL; // should follow the format ns|set|addr:port[,addr:port[,...]]. Ex: "test|sess|127.0.0.1:3000". The host info of just one cluster node is necessary
 * ```
 * @author Robert Marks <robert@aerospike.com>
 */
class Aerospike
{
    // Lifecycle and Connection Methods

    /**
     * Construct an Aerospike client object, and connect to the cluster defined
     * in $config.
     *
     * Aerospike::isConnected() can be used to test whether the connection has
     * succeeded. If a config or connection error has occured, Aerospike::error()
     * and Aerospike::errorno() can be used to inspect it.
     *
     * ```php
     * $config = [
     *   "hosts" => [
     *     ["addr" => "localhost", "port" => 3000]
     *   ],
     *   "shm" => []
     * ];
     * // Set a default policy for write and read operations
     * $writeOpts = [Aerospike::OPT_POLICY_KEY => Aerospike::POLICY_KEY_SEND];
     * $readOpts = [Aerospike::OPT_TOTAL_TIMEOUT => 150];
     * $opts = [Aerospike::OPT_WRITE_DEFAULT_POL => $writeOpts, Aerospike::OPT_READ_DEFAULT_POL => $readOpts];
     * $client = new Aerospike($config, true, $opts);
     * if (!$client->isConnected()) {
     *   echo "Aerospike failed to connect[{$client->errorno()}]: {$client->error()}\n";
     *   exit(1);
     * }
     * ```
     * @see Aerospike php.ini config parameters
     * @link https://github.com/aerospike/aerospike-client-php/blob/master/doc/README.md#configuration-in-a-web-server-context Configuration in a Web Server Context
     * @param array $config holds cluster connection and client config information
     * * _hosts_ a **required** array of host pairs. One node or more (for failover)
     *        may be defined. Once a connection is established to the
     *        "seed" node, the client will retrieve the full list of nodes in
     *        the cluster, and manage its connections to them.
     * * _addr_ hostname or IP of the node
     * * _port_ the port of the node
     * * _user_ **required** for the Enterprise Edition
     * * _pass_ **required** for the Enterprise Edition
     * * _shm_ optional. Shared-memory cluster tending is enabled if an array
     *     (even an empty one) is provided. Disabled by default.
     * * _shm\_key_ explicitly sets the shm key for the cluster. It is
     *       otherwise implicitly evaluated per unique hostname, and can be
     *       inspected with shmKey(). (default: 0xA8000000)
     * * _shm\_max\_nodes_ maximum number of nodes allowed. Pad so new nodes
     *       can be added without configuration changes (default: 16)
     * * _shm\_max\_namespaces_ maximum number of namespaces allowed (default: 8)
     * * _shm\_takeover\_threshold\_sec_ take over tending if the cluster
     *       hasn't been checked for this many seconds (default: 30)
     * * _max\_threads_ (default: 300)
     * * _thread\_pool\_size_ should be at least the number of nodes in the cluster (default: 16) In ZTS builds this is set to 0
     * * _compression\_threshold_ client will compress records larger than this value for transport (default: 0)
     * * _tender\_interval_ polling interval in milliseconds for cluster tender (default: 1000)
     * * _cluster\_name_ if specified, only server nodes matching this name will be used when determining the cluster
     * * _rack\_aware_ Boolean: Track server rack data.  This field is useful when directing read commands to
     * the server node that contains the key and exists on the same rack as the client.
     * This serves to lower cloud provider costs when nodes are distributed across different
     * racks/data centers.
     * POLICY_REPLICA_PREFER_RACK must be set as the replica policy for reads and _rack\_id_ must be set toenable this functionality.
     * (Default: false)
     * * _rack\_id_ Integer. Rack where this client instance resides.
     *
     * _rack\_aware_, POLICY_REPLICA_PREFER_RACK and server rack configuration must also be
     * set to enable this functionality.
     *
     * Default: 0
     * * Aerospike::OPT_TLS_CONFIG an array of TLS setup parameters whose keys include
     * * * Aerospike::OPT_TLS_ENABLE boolean Whether or not to enable TLS.
     * * * Aerospike::OPT_OPT_TLS_CAFILE
     * * * Aerospike::OPT_TLS_CAPATH
     * * * Aerospike::OPT_TLS_PROTOCOLS
     * * * Aerospike::OPT_TLS_CIPHER_SUITE
     * * * Aerospike::OPT_TLS_CRL_CHECK
     * * * Aerospike::OPT_TLS_CRL_CHECK_ALL
     * * * Aerospike::OPT_TLS_CERT_BLACKLIST
     * * * Aerospike::OPT_TLS_LOG_SESSION_INFO
     * * * Aerospike::OPT_TLS_KEYFILE
     * * * Aerospike::OPT_TLS_CERTFILE
     * @param bool $persistent_connection In a multiprocess context, such as a
     *        web server, the client should be configured to use
     *        persistent connections. This allows for reduced overhead,
     *        saving on discovery of the cluster topology, fetching its partition
     *        map, and on opening connections to the nodes.
     * @param array $options An optional client config array whose keys include
     * * Aerospike::OPT_CONNECT_TIMEOUT
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_EXISTS
     * * Aerospike::OPT_SERIALIZER
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_POLICY_REPLICA
     * * Aerospike::OPT_POLICY_READ_MODE_AP
     * * Aerospike::OPT_POLICY_READ_MODE_SC
     * * Aerospike::OPT_READ_DEFAULT_POL An array of default policies for read operations.
     * * Aerospike::OPT_WRITE_DEFAULT_POL An array of default policies for write operations.
     * * AEROSPIKE::OPT_REMOVE_DEFAULT_POL An array of default policies for remove operations.
     * * Aerospike::OPT_BATCH_DEFAULT_POL An array of default policies for batch operations.
     * * Aerospike::OPT_OPERATE_DEFAULT_POL An array of default policies for operate operations.
     * * Aerospike::OPT_QUERY_DEFAULT_POL An array of default policies for query operations.
     * * Aerospike::OPT_SCAN_DEFAULT_POL An array of default policies for scan operations.
     * * Aerospike::OPT_APPLY_DEFAULT_POL An array of default policies for apply operations.
     * @see Aerospike::OPT_CONNECT_TIMEOUT Aerospike::OPT_CONNECT_TIMEOUT options
     * @see Aerospike::OPT_READ_TIMEOUT Aerospike::OPT_READ_TIMEOUT options
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_EXISTS Aerospike::OPT_POLICY_EXISTS options
     * @see Aerospike::OPT_SERIALIZER Aerospike::OPT_SERIALIZER options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_POLICY_REPLICA Aerospike::OPT_POLICY_REPLICA options
     * @see Aerospike::OPT_POLICY_READ_MODE_AP Aerospike::OPT_POLICY_READ_MODE_AP options
     * @see Aerospike::OPT_POLICY_READ_MODE_SC Aerospike::OPT_POLICY_READ_MODE_SC options
     * @see Aerospike::isConnected() isConnected()
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     */
    public function __construct(array $config, bool $persistent_connection = true, array $options = []) {}

    /**
     * Disconnect from the Aerospike cluster and clean up resources.
     *
     * No need to ever call this method explicilty.
     * @return void
     */
    public function __destruct() {}

    /**
     * Test whether the client is connected to the cluster.
     *
     * If a connection error has occured, Aerospike::error() and Aerospike::errorno()
     * can be used to inspect it.
     * ```php
     * if (!$client->isConnected()) {
     *   echo "Aerospike failed to connect[{$client->errorno()}]: {$client->error()}\n";
     *   exit(1);
     * }
     * ```
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return bool
     */
    public function isConnected() {}

    /**
     * Disconnect the client from all the cluster nodes.
     *
     * This method should be explicitly called when using non-persistent connections.
     * @see Aerospike::isConnected()
     * @see Aerospike::reconnect()
     * @return void
     */
    public function close() {}

    /**
     * Reconnect the client to the cluster nodes.
     *
     * Aerospike::isConnected() can be used to test whether the re-connection
     * succeded. If a connection error occured Aerospike::error() and
     * Aerospike::errorno() can be used to inspect it.
     * ```php
     * $client = new Aerospike($config, false);
     * $client->close();
     * $client->reconnect();
     * if (!$client->isConnected()) {
     *   echo "Aerospike failed to connect[{$client->errorno()}]: {$client->error()}\n";
     *   exit(1);
     * }
     * ```
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return void
     */
    public function reconnect() {}

    /**
     * Expose the shared memory key used by shared-memory cluster tending
     *
     * If shm cluster tending is enabled, Aerospike::shmKey will return the
     * value of the shm key being used by the client. If it was set explicitly
     * under the client's shm config parameter, or through the global
     * `aerospike.shm.key` we expect to see that value. Otherwise the implicit
     * value generated by the client will be returned
     * @return int|null null if not enabled
     */
    public function shmKey() {}

    /**
     * Return the error message associated with the last operation.
     *
     * If the operation was successful the return value should be an empty string.
     * ```php
     * $client = new Aerospike($config, false);
     * if (!$client->isConnected()) {
     *   echo "{$client->error()} [{$client->errorno()}]";
     *   exit(1);
     * }
     * ```
     * On connection error would show:
     * ```
     * Unable to connect to server [-1]
     * ```
     * @see Aerospike::OK Error Codes
     * @return string
     */
    public function error() {}

    /**
     * Return the error code associated with the last operation.
     * If the operation was successful the return value should be 0 (Aerospike::OK)
     * @see Aerospike::OK Error Codes
     * @return int
     */
    public function errorno() {}

    // Key-Value Methods.

    /**
     * Return an array that represents the record's key.
     *
     * This value can be passed as the $key arguement required by other
     * key-value methods.
     *
     * In Aerospike, a record is identified by the tuple (namespace, set,
     * primary key), or by the digest which results from hashing this tuple
     * through RIPEMD-160.
     *
     * ** Initializing a key **
     *
     * ```php
     * $key = $client->initKey("test", "users", 1234);
     * var_dump($key);
     * ```
     *
     * ```bash
     *array(3) {
     *  ["ns"]=>
     *  string(4) "test"
     *  ["set"]=>
     *  string(5) "users"
     *  ["key"]=>
     *  int(1234)
     *}
     * ```
     *
     * ** Setting a digest **
     *
     * ```php
     * $base64_encoded_digest = '7EV9CpdMSNVoWn76A9E33Iu95+M=';
     * $digest = base64_decode($base64_encoded_digest);
     * $key = $client->initKey("test", "users", $digest, true);
     * var_dump($key);
     * ```
     *
     * ```bash
     *array(3) {
     *  ["ns"]=>
     *  string(4) "test"
     *  ["set"]=>
     *  string(5) "users"
     *  ["digest"]=>
     *  string(20) "?E}
     *?LH?hZ~??7Ü‹???"
     *}
     * ```
     *
     * @link https://github.com/aerospike/aerospike-client-php/blob/master/doc/README.md#configuration-in-a-web-server-context Configuration in a Web Server Context
     * @param string $ns the namespace
     * @param string $set the set within the given namespace
     * @param int|string $pk The primary key in the application, or the RIPEMD-160 digest of the (namespce, set, primary-key) tuple
     * @param bool $is_digest True if the *$pk* argument is a digest
     * @return array
     * @see Aerospike::getKeyDigest() getKeyDigest()
     */
    public function initKey(string $ns, string $set, $pk, bool $is_digest = false) {}

    /**
     * Return the digest of hashing the (namespace, set, primary-key) tuple
     * with RIPEMD-160.
     *
     * The digest uniquely identifies the record in the cluster, and is used to
     * calculate a partition ID. Using the partition ID, the client can identify
     * the node holding the record's master partition or replica partition(s) by
     * looking it up against the cluster's partition map.
     *
     * ```php
     * $digest = $client->getKeyDigest("test", "users", 1);
     * $key = $client->initKey("test", "users", $digest, true);
     * var_dump($digest, $key);
     * ```
     *
     * ```bash
     * string(20) "9!?@%??;???Wp?'??Ag"
     * array(3) {
     *   ["ns"]=>
     *   string(4) "test"
     *   ["set"]=>
     *   string(5) "users"
     *   ["digest"]=>
     *   string(20) "9!?@%??;???Wp?'??Ag"
     * }
     * ```
     *
     * @link https://github.com/aerospike/aerospike-client-php/blob/master/doc/README.md#configuration-in-a-web-server-context Configuration in a Web Server Context
     * @param string $ns the namespace
     * @param string $set the set within the given namespace
     * @param int|string $pk The primary key in the application
     * @return string
     * @see Aerospike::initKey() initKey()
     */
    public function getKeyDigest(string $ns, string $set, $pk) {}

    /**
     * Write a record identified by the $key with $bins, an array of bin-name => bin-value pairs.
     *
     * By default Aerospike::put() behaves in a set-and-replace mode, similar to
     * how new keys are added to an array, or the value of existing ones is overwritten.
     * This behavior can be modified using the *$options* parameter.
     *
     * **Note:** a binary-string which includes a null-byte will get truncated
     * at the position of the **\0** character if it is not wrapped. For more
     * information and the workaround see 'Handling Unsupported Types'.
     *
     * **Example #1 Aerospike::put() default behavior example**
     * ```php
     * $key = $client->initKey("test", "users", 1234);
     * $bins = ["email" => "hey@example.com", "name" => "Hey There"];
     * // will ensure a record exists at the given key with the specified bins
     * $status = $client->put($key, $bins);
     * if ($status == Aerospike::OK) {
     *     echo "Record written.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     *
     * // Updating the record
     * $bins = ["name" => "You There", "age" => 33];
     * // will update the name bin, and create a new 'age' bin
     * $status = $client->put($key, $bins);
     * if ($status == Aerospike::OK) {
     *     echo "Record updated.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * Record written.
     * Record updated.
     * ```
     *
     * **Example #2 Fail unless the put explicitly creates a new record**
     * ```php
     *
     * // This time we expect an error, due to the record already existing (assuming we
     * // ran Example #1)
     * $status = $client->put($key, $bins, 0, [Aerospike::OPT_POLICY_EXISTS => Aerospike::POLICY_EXISTS_CREATE]);
     *
     * if ($status == Aerospike::OK) {
     *     echo "Record written.\n";
     * } elseif ($status == Aerospike::ERR_RECORD_EXISTS) {
     *     echo "The Aerospike server already has a record with the given key.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * The Aerospike cluster already has a record with the given key.
     * ```
     *
     * **Example #3 Fail if the record has been written since it was last read
     * (CAS)**
     * ```php
     * // Get the record metadata and note its generation
     * $client->exists($key, $metadata);
     * $gen = $metadata['generation'];
     * $gen_policy = [Aerospike::POLICY_GEN_EQ, $gen];
     * $res = $client->put($key, $bins, 0, [Aerospike::OPT_POLICY_GEN => $gen_policy]);
     *
     * if ($res == Aerospike::OK) {
     *     echo "Record written.\n";
     * } elseif ($res == Aerospike::ERR_RECORD_GENERATION) {
     *     echo "The record has been written since we last read it.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ?>
     * ```
     * ```
     * The record has been written since we last read it.
     * ```
     *
     * **Example #4 Handling binary strings**
     * ```php
     * $str = 'Glagnar\'s Human Rinds, "It\'s a bunch\'a munch\'a crunch\'a human!';
     * $deflated = new \Aerospike\Bytes(gzdeflate($str));
     * $wrapped = new \Aerospike\Bytes("trunc\0ated");
     *
     * $key = $client->initKey('test', 'demo', 'wrapped-bytes');
     * $status = $client->put($key, ['unwrapped'=>"trunc\0ated", 'wrapped'=> $wrapped, 'deflated' => $deflated]);
     * if ($status !== Aerospike::OK) {
     *     die($client->error());
     * }
     * $client->get($key, $record);
     * $wrapped = \Aerospike\Bytes::unwrap($record['bins']['wrapped']);
     * $deflated = $record['bins']['deflated'];
     * $inflated = gzinflate($deflated->s);
     * echo "$inflated\n";
     * echo "wrapped binary-string: ";
     * var_dump($wrapped);
     * $unwrapped = $record['bins']['unwrapped'];
     * echo "The binary-string that was given to put() without a wrapper: $unwrapped\n";
     * ```
     * ```
     * Glagnar's Human Rinds, "It's a bunch'a munch'a crunch'a human!
     * wrapped binary-string: string(10) "truncated"
     * The binary-string that was given to put() without a wrapper: trunc
     * ```
     * @link https://www.aerospike.com/docs/architecture/data-model.html Aerospike Data Model
     * @link https://www.aerospike.com/docs/guide/kvs.html Key-Value Store
     * @link https://github.com/aerospike/aerospike-client-php/blob/master/doc/README.md#handling-unsupported-types Handling Unsupported Types
     * @link https://www.aerospike.com/docs/client/c/usage/kvs/write.html#change-record-time-to-live-ttl Time-to-live
     * @link https://www.aerospike.com/docs/guide/glossary.html Glossary
     * @param array $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param array $bins The array of bin names and values to write. **Bin names cannot be longer than 14 characters.** Binary data containing the null byte (**\0**) may get truncated. See 'Handling Unsupported Types' for more details and a workaround
     * @param int $ttl The record's time-to-live in seconds
     * @param array $options an optional array of write policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_SERIALIZER
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_EXISTS
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::COMPRESSION_THRESHOLD
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_SERIALIZER Aerospike::OPT_SERIALIZER options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_EXISTS Aerospike::OPT_POLICY_EXISTS options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::COMPRESSION_THRESHOLD
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function put(array $key, array $bins, int $ttl = 0, array $options = []) {}

    /**
     * Read a record with a given key, and store it in $record
     *
     * The bins returned in *$record* can be filtered by passing a *$select*
     * array of bin names. Non-existent bins will appear in the *$record* with a `NULL` value.
     *
     * **Example #1 Aerospike::get() default behavior example**
     * ```php
     * $key = $client->initKey("test", "users", 1234);
     * $status = $client->get($key, $record);
     * if ($status == Aerospike::OK) {
     *     var_dump($record);
     * } elseif ($status == Aerospike::ERR_RECORD_NOT_FOUND) {
     *     echo "A user with key ". $key['key']. " does not exist in the database\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * array(3) {
     *   ["key"]=>
     *   array(4) {
     *     ["digest"]=>
     *     string(40) "436a3b9fcafb96d12844ab1377c0ff0d7a0b70cc"
     *     ["namespace"]=>
     *     NULL
     *     ["set"]=>
     *     NULL
     *     ["key"]=>
     *     NULL
     *   }
     *   ["metadata"]=>
     *   array(2) {
     *     ["generation"]=>
     *     int(3)
     *     ["ttl"]=>
     *     int(12345)
     *   }
     *   ["bins"]=>
     *   array(3) {
     *     ["email"]=>
     *     string(9) "hey@example.com"
     *     ["name"]=>
     *     string(9) "You There"
     *     ["age"]=>
     *     int(33)
     *   }
     * }
     * ```
     * **Example #2 get the record with filtered bins**
     * ```php
     * // assuming this follows Example #1, getting a filtered record
     * $filter = ["email", "manager"];
     * unset($record);
     * $status = $client->get($key, $record, $filter);
     * if ($status == Aerospike::OK) {
     *     var_dump($record);
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * array(3) {
     *   ["key"]=>
     *   array(4) {
     *     ["digest"]=>
     *     string(40) "436a3b9fcafb96d12844ab1377c0ff0d7a0b70cc"
     *     ["namespace"]=>
     *     NULL
     *     ["set"]=>
     *     NULL
     *     ["key"]=>
     *     NULL
     *   }
     *   ["metadata"]=>
     *   array(2) {
     *     ["generation"]=>
     *     int(3)
     *     ["ttl"]=>
     *     int(12344)
     *   }
     *   ["bins"]=>
     *   array(2) {
     *     ["email"]=>
     *     string(15) "hey@example.com"
     *     ["manager"]=>
     *     NULL
     *   }
     * }
     * ```
     * @link https://www.aerospike.com/docs/architecture/data-model.html Aerospike Data Model
     * @link https://www.aerospike.com/docs/guide/kvs.html Key-Value Store
     * @link https://www.aerospike.com/docs/guide/glossary.html Glossary
     * @param array $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param array &$record a reference to a variable which will contain the retrieved record of `['key', metadata', 'bins]` with the structure:
     * ```
     * Array:
     *   key => Array
     *     ns => namespace
     *     set => set name
     *     key => primary-key, present if written with POLICY_KEY_SEND
     *     digest => the record's RIPEMD-160 digest, always present
     *   metadata => Array
     *     ttl => time in seconds until the record expires
     *     generation => the number of times the record has been written
     *   bins => Array of bin-name => bin-value pairs
     * ```
     * @param null|array $select only these bins out of the record (optional)
     * @param array $options an optional array of read policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_DESERIALIZE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * * Aerospike::OPT_POLICY_REPLICA
     * * Aerospike::OPT_POLICY_READ_MODE_AP
     * * Aerospike::OPT_POLICY_READ_MODE_SC
     * @see Aerospike::OPT_READ_TIMEOUT Aerospike::OPT_READ_TIMEOUT options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_DESERIALIZE Aerospike::OPT_DESERIALIZE option
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OPT_POLICY_REPLICA Aerospike::OPT_POLICY_REPLICA options
     * @see Aerospike::OPT_POLICY_READ_MODE_AP Aerospike::OPT_POLICY_READ_MODE_AP options
     * @see Aerospike::OPT_POLICY_READ_MODE_SC Aerospike::OPT_POLICY_READ_MODE_SC options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function get(array $key, &$record, ?array $select = null, array $options = []) {}

    /**
     * Get the metadata of a record with a given key, and store it in $metadata
     *
     * ```php
     * $key = $client->initKey("test", "users", 1234);
     * $status = $client->exists($key, $metadata);
     * if ($status == Aerospike::OK) {
     *     var_dump($metadata);
     * } elseif ($status == Aerospike::ERR_RECORD_NOT_FOUND) {
     *     echo "A user with key ". $key['key']. " does not exist in the database\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * array(2) {
     *   ["generation"]=>
     *   int(4)
     *   ["ttl"]=>
     *   int(1337)
     * }
     * ```
     * **or**
     * ```
     * A user with key 1234 does not exist in the database.
     * ```
     * @link https://www.aerospike.com/docs/guide/glossary.html Glossary
     * @param array $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param array &$metadata a reference to a variable which will be filled with an array of `['ttl', 'generation']` values
     * @param array $options an optional array of read policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::OPT_DESERIALIZE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_REPLICA
     * * Aerospike::OPT_POLICY_READ_MODE_AP
     * * Aerospike::OPT_POLICY_READ_MODE_SC
     * @see Aerospike::OPT_READ_TIMEOUT Aerospike::OPT_READ_TIMEOUT options
     * @see Aerospike::OPT_DESERIALIZE Aerospike::OPT_DESERIALIZE option
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_REPLICA Aerospike::OPT_POLICY_REPLICA options
     * @see Aerospike::OPT_POLICY_READ_MODE_AP Aerospike::OPT_POLICY_READ_MODE_AP options
     * @see Aerospike::OPT_POLICY_READ_MODE_SC Aerospike::OPT_POLICY_READ_MODE_SC options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function exists(array $key, &$metadata, array $options = []) {}

    /**
     * Touch the record identified by the $key, resetting its time-to-live.
     *
     * ```php
     * $key = $client->initKey("test", "users", 1234);
     * $status = $client->touch($key, 120);
     * if ($status == Aerospike::OK) {
     *     echo "Added 120 seconds to the record's expiration.\n"
     * } elseif ($status == Aerospike::ERR_RECORD_NOT_FOUND) {
     *     echo "A user with key ". $key['key']. " does not exist in the database\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * Added 120 seconds to the record's expiration.
     * ```
     * **or**
     * ```
     * A user with key 1234 does not exist in the database.
     * ```
     * @link https://www.aerospike.com/docs/client/c/usage/kvs/write.html#change-record-time-to-live-ttl Time-to-live
     * @link https://www.aerospike.com/docs/guide/FAQ.html FAQ
     * @link https://discuss.aerospike.com/t/records-ttl-and-evictions/737 Record TTL and Evictions
     * @param array $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param int $ttl The record's time-to-live in seconds
     * @param array $options an optional array of write policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_DESERIALIZE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_DESERIALIZE Aerospike::OPT_DESERIALIZE option
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function touch(array $key, int $ttl = 0, array $options = []) {}

    /**
     * Remove the record identified by the $key.
     *
     * ```php
     * $key = $client->initKey("test", "users", 1234);
     * $status = $client->remove($key);
     * if ($status == Aerospike::OK) {
     *     echo "Record removed.\n";
     * } elseif ($status == Aerospike::ERR_RECORD_NOT_FOUND) {
     *     echo "A user with key ". $key['key']. " does not exist in the database\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * Record removed.
     * ```
     * @param array $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param array $options an optional array of write policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_POLICY_DURABLE_DELETE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_POLICY_DURABLE_DELETE Aerospike::OPT_POLICY_DURABLE_DELETE options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function remove(array $key, array $options = []) {}

    /**
     * Remove $bins from the record identified by the $key.
     *
     * ```php
     * $key = ["ns" => "test", "set" => "users", "key" => 1234];
     * $options = array(Aerospike::OPT_TTL => 3600);
     * $status = $client->removeBin($key, ["age"], $options);
     * if ($status == Aerospike::OK) {
     *     echo "Removed bin 'age' from the record.\n";
     * } elseif ($status == Aerospike::ERR_RECORD_NOT_FOUND) {
     *     echo "The database has no record with the given key.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * @param array $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param array $bins A list of bin names to remove
     * @param array $options an optional array of write policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::COMPRESSION_THRESHOLD
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::COMPRESSION_THRESHOLD
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function removeBin(array $key, array $bins, array $options = []) {}

    /**
     * Remove all the records from a namespace or set
     *
     * Remove records in a specified namespace/set efficiently. This method is
     * many orders of magnitude faster than deleting records one at a time.
     * **Note:** works with Aerospike Server versions >= 3.12
     * See {@link https://www.aerospike.com/docs/reference/info#truncate Truncate command information}
     *
     * This asynchronous server call may return before the truncation is complete.
     * The user can still write new records after the server returns because new
     * records will have last update times greater than the truncate cutoff
     * (set at the time of truncate call).
     *
     * The truncate command does not durably delete records in the Community Edition.
     * The Enterprise Edition provides durability through the truncate command.
     *
     * ```php
     * $secondsInDay = 24 * 60 * 60;
     *
     * // Multiply by 10 ^ 9 to get nanoseconds
     * $yesterday = 1000000000 * (time() - $secondsInDay);
     *
     * // Remove all records in test/truncateSet updated before 24 hours ago
     * $status = $client->truncate("test", "demoSet", $yesterday);
     *
     * // Truncate all records in test, regardless of update time
     * $status = $client->truncate("test", null, 0);
     * ```
     * @version 3.12 Requires server >= 3.12
     * @param string $ns the namespace
     * @param string $set the set within the given namespace
     * @param int    $nanos cutoff threshold indicating that records
     * last updated before the threshold will be removed. Units are in
     * nanoseconds since unix epoch (1970-01-01 00:00:00). A value of 0
     * indicates that all records in the set should be truncated
     * regardless of update time. The value must not be in the future.
     * @param array $options an optional array of write policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function truncate(string $ns, string $set, int $nanos, array $options = []) {}

    /**
     * Increment the value of $bin in the record identified by the $key by an
     * $offset.
     *
     * ```php
     * $key = $client->initKey("test", "users", 1234);
     * $options = [Aerospike::OPT_TTL => 7200];
     * $status = $client->increment($key, 'pto', -4, $options);
     * if ($status == Aerospike::OK) {
     *     echo "Decremented four vacation days from the user's PTO balance.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * @param array $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin The name of the bin to increment
     * @param int|float $offset The value by which to increment the bin
     * @param array $options an optional array of write policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function increment(array $key, string $bin, $offset, array $options = []) {}

    /**
     * Append a string $value to the one already in $bin, in the record identified by the $key.
     *
     * ```php
     * $key = $client->initKey("test", "users", 1234);
     * $options = [Aerospike::OPT_TTL => 3600];
     * $status = $client->append($key, 'name', ' Ph.D.', $options);
     * if ($status == Aerospike::OK) {
     *     echo "Added the Ph.D. suffix to the user.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * @param array $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin The name of the bin
     * @param string $value The string value to append to the bin
     * @param array $options an optional array of write policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_DESERIALIZE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_DESERIALIZE Aerospike::OPT_DESERIALIZE option
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function append(array $key, string $bin, string $value, array $options = []) {}

    /**
     * Prepend a string $value to the one already in $bin, in the record identified by the $key.
     *
     * ```php
     * $key = $client->initKey("test", "users", 1234);
     * $options = [Aerospike::OPT_TTL => 3600];
     * $status = $client->prepend($key, 'name', '*', $options);
     * if ($status == Aerospike::OK) {
     *     echo "Starred the user.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * @param array $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin The name of the bin
     * @param string $value The string value to prepend to the bin
     * @param array $options an optional array of write policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_DESERIALIZE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_DESERIALIZE Aerospike::OPT_DESERIALIZE option
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function prepend(array $key, string $bin, string $value, array $options = []) {}

    /**
     *  Perform multiple bin operations on a record with a given key, with write operations happening before read ones.
     *
     *  Non-existent bins being read will have a `NULL` value.
     *
     * Currently a call to operate() can include only one write operation per-bin.
     * For example, you cannot both append and prepend to the same bin, in the same call.
     *
     * Like other bin operations, operate() only works on existing records (i.e. ones that were previously created with a put()).
     *
     * **Example #1 Combining several write operations into one multi-op call**
     *
     * ```
     * [
     *   ["op" => Aerospike::OPERATOR_APPEND, "bin" => "name", "val" => " Ph.D."],
     *   ["op" => Aerospike::OPERATOR_INCR, "bin" => "age", "val" => 1],
     *   ["op" => Aerospike::OPERATOR_READ, "bin" => "age"]
     * ]
     * ```
     *
     * ```php
     * $config = ["hosts" => [["addr"=>"localhost", "port"=>3000]], "shm"=>[]];
     * $client = new Aerospike($config, true);
     * if (!$client->isConnected()) {
     *    echo "Aerospike failed to connect[{$client->errorno()}]: {$client->error()}\n";
     *    exit(1);
     * }
     *
     * $key = $client->initKey("test", "users", 1234);
     * $operations = [
     *   ["op" => Aerospike::OPERATOR_APPEND, "bin" => "name", "val" => " Ph.D."],
     *   ["op" => Aerospike::OPERATOR_INCR, "bin" => "age", "val" => 1],
     *   ["op" => Aerospike::OPERATOR_READ, "bin" => "age"],
     * ];
     * $options = [Aerospike::OPT_TTL => 600];
     * $status = $client->operate($key, $operations, $returned, $options);
     * if ($status == Aerospike::OK) {
     *     var_dump($returned);
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * array(1) {
     *   ["age"]=>
     *   int(34)
     * }
     * ```
     *
     * **Example #2 Implementing an LRU by reading a bin and touching a record in the same operation**
     *
     * ```
     * [
     *   ["op" => Aerospike::OPERATOR_READ, "bin" => "age"],
     *   ["op" => Aerospike::OPERATOR_TOUCH, "ttl" => 20]
     * ]
     * ```
     * @link https://www.aerospike.com/docs/guide/kvs.html Key-Value Store
     * @link https://github.com/aerospike/aerospike-client-php/blob/master/doc/README.md#handling-unsupported-types Handling Unsupported Types
     * @link https://www.aerospike.com/docs/client/c/usage/kvs/write.html#change-record-time-to-live-ttl Time-to-live
     * @link https://www.aerospike.com/docs/guide/glossary.html Glossary
     * @param array $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param array $operations The array of of one or more per-bin operations conforming to the following structure:
     * ```
     * Write Operation:
     *   op => Aerospike::OPERATOR_WRITE
     *   bin => bin name (cannot be longer than 14 characters)
     *   val => the value to store in the bin
     *
     * Increment Operation:
     *   op => Aerospike::OPERATOR_INCR
     *   bin => bin name
     *   val => the integer by which to increment the value in the bin
     *
     * Prepend Operation:
     *   op => Aerospike::OPERATOR_PREPEND
     *   bin => bin name
     *   val => the string to prepend the string value in the bin
     *
     * Append Operation:
     *   op => Aerospike::OPERATOR_APPEND
     *   bin => bin name
     *   val => the string to append the string value in the bin
     *
     * Read Operation:
     *   op => Aerospike::OPERATOR_READ
     *   bin => name of the bin we want to read after any write operations
     *
     * Touch Operation: reset the time-to-live of the record and increment its generation
     *                  (only combines with read operations)
     *   op => Aerospike::OPERATOR_TOUCH
     *   ttl => a positive integer value to set as time-to-live for the record
     *
     * Delete Operation:
     *   op => Aerospike::OPERATOR_DELETE
     *
     * List Append Operation:
     *   op => Aerospike::OP_LIST_APPEND,
     *   bin =>  "events",
     *   val =>  1234
     *
     * List Merge Operation:
     *   op => Aerospike::OP_LIST_MERGE,
     *   bin =>  "events",
     *   val =>  [ 123, 456 ]
     *
     * List Insert Operation:
     *   op => Aerospike::OP_LIST_INSERT,
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  1234
     *
     * List Insert Items Operation:
     *   op => Aerospike::OP_LIST_INSERT_ITEMS,
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  [ 123, 456 ]
     *
     * List Pop Operation:
     *   op => Aerospike::OP_LIST_POP, # returns a value
     *   bin =>  "events",
     *   index =>  2
     *
     * List Pop Range Operation:
     *   op => Aerospike::OP_LIST_POP_RANGE, # returns a value
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  3 # remove 3 elements starting at index 2
     *
     * List Remove Operation:
     *   op => Aerospike::OP_LIST_REMOVE,
     *   bin =>  "events",
     *   index =>  2
     *
     * List Remove Range Operation:
     *   op => Aerospike::OP_LIST_REMOVE_RANGE,
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  3 # remove 3 elements starting at index 2
     *
     * List Clear Operation:
     *   op => Aerospike::OP_LIST_CLEAR,
     *   bin =>  "events"
     *
     * List Set Operation:
     *   op => Aerospike::OP_LIST_SET,
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  "latest event at index 2" # set this value at index 2
     *
     * List Get Operation:
     *   op => Aerospike::OP_LIST_GET, # returns a value
     *   bin =>  "events",
     *   index =>  2 # similar to Aerospike::OPERATOR_READ but only returns the value
     *                 at index 2 of the list, not the whole bin
     *
     * List Get Range Operation:
     *   op => Aerospike::OP_LIST_GET_RANGE, # returns a value
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  3 # get 3 elements starting at index 2
     *
     * List Trim Operation:
     *   op => Aerospike::OP_LIST_TRIM,
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  3 # remove all elements not in the range between index 2 and index 2 + 3
     *
     * List Size Operation:
     *   op => Aerospike::OP_LIST_SIZE, # returns a value
     *   bin =>  "events" # gets the size of a list contained in the bin
     *
     *
     * Map operations
     *
     * Map Policies:
     * Many of the following operations require a map policy, the policy is an array
     * containing any of the keys AEROSPIKE::OPT_MAP_ORDER, AEROSPIKE::OPT_MAP_WRITE_MODE
     *
     * the value for AEROSPIKE::OPT_MAP_ORDER should be one of AEROSPIKE::AS_MAP_UNORDERED , AEROSPIKE::AS_MAP_KEY_ORDERED , AEROSPIKE::AS_MAP_KEY_VALUE_ORDERED
     * the default value is currently AEROSPIKE::AS_MAP_UNORDERED
     *
     * the value for AEROSPIKE::OPT_MAP_WRITE_MODE should be one of: AEROSPIKE::AS_MAP_UPDATE, AEROSPIKE::AS_MAP_UPDATE_ONLY , AEROSPIKE::AS_MAP_CREATE_ONLY
     * the default value is currently AEROSPIKE::AS_MAP_UPDATE
     *
     * the value for AEROSPIKE::OPT_MAP_WRITE_FLAGS should be one of: AEROSPIKE::AS_MAP_WRITE_DEFAULT, AEROSPIKE::AS_MAP_WRITE_CREATE_ONLY, AEROSPIKE::AS_MAP_WRITE_UPDATE_ONLY, AEROSPIKE::AS_MAP_WRITE_NO_FAIL, AEROSPIKE::AS_MAP_WRITE_PARTIAL
     * the default value is currently AEROSPIKE::AS_MAP_WRITE_DEFAULT
     *
     * Map return types:
     * many of the map operations require a return_type entry.
     * this specifies the format in which the response should be returned. The options are:
     * AEROSPIKE::AS_MAP_RETURN_NONE # Do not return a result.
     * AEROSPIKE::AS_MAP_RETURN_INDEX # Return key index order.
     * AEROSPIKE::AS_MAP_RETURN_REVERSE_INDEX # Return reverse key order.
     * AEROSPIKE::AS_MAP_RETURN_RANK # Return value order.
     * AEROSPIKE::AS_MAP_RETURN_REVERSE_RANK # Return reserve value order.
     * AEROSPIKE::AS_MAP_RETURN_COUNT # Return count of items selected.
     * AEROSPIKE::AS_MAP_RETURN_KEY # Return key for single key read and key list for range read.
     * AEROSPIKE::AS_MAP_RETURN_VALUE # Return value for single key read and value list for range read.
     * AEROSPIKE::AS_MAP_RETURN_KEY_VALUE # Return key/value items. Will be of the form ['key1', 'val1', 'key2', 'val2', 'key3', 'val3]
     *
     * Map policy Operation:
     *   op => Aerospike::OP_MAP_SET_POLICY,
     *   bin =>  "map",
     *   map_policy =>  [ AEROSPIKE::OPT_MAP_ORDER => AEROSPIKE::AS_MAP_KEY_ORDERED]
     *
     * Map clear operation: (Remove all items from a map)
     *   op => AEROSPIKE::OP_MAP_CLEAR,
     *   bin => "bin_name"
     *
     *
     * Map Size Operation: Return the number of items in a map
     *   op => AEROSPIKE::OP_MAP_SIZE,
     *   bin => "bin_name"
     *
     * Map Get by Key operation
     *   op => AEROSPIKE::OP_MAP_GET_BY_KEY ,
     *   bin => "bin_name",
     *   key => "my_key",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get By Key Range operation:
     *   op => AEROSPIKE::OP_MAP_GET_BY_KEY_RANGE ,
     *   bin => "bin_name",
     *   key => "aaa",
     *   range_end => "bbb"
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get By Value operation:
     *   op => AEROSPIKE::OP_MAP_GET_BY_VALUE ,
     *   bin => "bin_name",
     *   value => "my_val"
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get by Value Range operation:
     *   op => AEROSPIKE::OP_MAP_GET_BY_VALUE_RANGE ,
     *   bin => "bin_name",
     *   value => "value_a",
     *   range_end => "value_z",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get By Index operation
     *   op => AEROSPIKE::OP_MAP_GET_BY_INDEX ,
     *   bin => "bin_name",
     *   index => 2,
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get by Index Range operation
     *   op => AEROSPIKE::OP_MAP_GET_BY_INDEX_RANGE,
     *   bin => "bin_name",
     *   index => 2,
     *   count => 2,
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get By Rank operation
     *   op => AEROSPIKE::OP_MAP_GET_BY_RANK ,
     *   bin => "bin_name",
     *   rank => -1, # get the item with the largest value
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get by Rank Range operation
     *   op => AEROSPIKE::OP_MAP_GET_BY_RANK_RANGE ,
     *   rank => -2 ,
     *   count => 2 ,
     *   bin => "bin_name",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Put operation
     *   op => AEROSPIKE::OP_MAP_PUT ,
     *   bin => "bin_name",
     *   key => "aero",
     *   val => "spike",
     *   map_policy => [ AEROSPIKE::OPT_MAP_ORDER => AEROSPIKE::AS_MAP_KEY_ORDERED]
     *
     * Map Put Items operations
     *  op => AEROSPIKE::OP_MAP_PUT_ITEMS ,
     *  bin => "bin_name",
     *  val => [1, "a", 1.5],
     *  map_policy => [ AEROSPIKE::OPT_MAP_ORDER => AEROSPIKE::AS_MAP_KEY_ORDERED]
     *
     * Map Increment operation
     *   op => AEROSPIKE::OP_MAP_INCREMENT ,
     *   bin => "bin_name",
     *   val => 5, #increment the value by 5
     *   key => "key_to_increment",
     *   map_policy => [ AEROSPIKE::OPT_MAP_ORDER => AEROSPIKE::AS_MAP_KEY_ORDERED]
     *
     * Map Decrement operation
     *   op => AEROSPIKE::OP_MAP_DECREMENT ,
     *   bin => "bin_name",
     *   key => "key_to_decrement",
     *   val => 5, #decrement by 5
     *   map_policy => [ AEROSPIKE::OPT_MAP_ORDER => AEROSPIKE::AS_MAP_KEY_ORDERED]
     *
     * Map Remove by Key operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_KEY ,
     *   bin => "bin_name",
     *   key => "key_to_remove",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Remove by Key list operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_KEY_LIST ,
     *   bin => "bin_name",
     *   key => ["key1", 2, "key3"],
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map remove by Key Range operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_KEY_RANGE ,
     *   bin => "bin",
     *   key => "a",
     *   range_end => "d",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map remove by Value operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_VALUE ,
     *   bin => "bin_name",
     *   val => 5,
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map remove by value range operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_VALUE_RANGE ,
     *   bin => "bin_name",
     *   val => "a",
     *   range_end => "d"
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map remove by value list operation
     *  op => AEROSPIKE::OP_MAP_REMOVE_BY_VALUE_LIST ,
     *  bin => "bin_name",
     *  val => [1, 2, 3, 4],
     *  return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Remove by Index operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_INDEX ,
     *   index => 2,
     *   bin => "bin_name",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Remove By Index Range operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_INDEX_RANGE ,
     *   bin => "bin_name",
     *   index => 3 ,
     *   count => 3 ,
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Remove by Rank operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_RANK ,
     *   rank => -1 ,
     *   bin => "bin_name",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map remove by rank range
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_RANK_RANGE,
     *   bin => "bin_name",
     *   rank => -1,
     *   count => return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     *
     *
     * ```
     *
     * @param array &$returned a pass-by-reference array of bins retrieved by read operations. If multiple operations exist for a specific bin name, the last operation will be the one placed as the value
     * @param array $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_POLICY_REPLICA
     * * Aerospike::OPT_POLICY_READ_MODE_AP
     * * Aerospike::OPT_POLICY_READ_MODE_SC
     * * Aerospike::OPT_POLICY_DURABLE_DELETE
     * * Aerospike::OPT_DESERIALIZE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_POLICY_REPLICA Aerospike::OPT_POLICY_REPLICA options
     * @see Aerospike::OPT_POLICY_READ_MODE_AP Aerospike::OPT_POLICY_READ_MODE_AP options
     * @see Aerospike::OPT_POLICY_READ_MODE_SC Aerospike::OPT_POLICY_READ_MODE_SC options
     * @see Aerospike::OPT_POLICY_DURABLE_DELETE Aerospike::OPT_POLICY_DURABLE_DELETE options
     * @see Aerospike::OPT_DESERIALIZE Aerospike::OPT_DESERIALIZE option
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @see Aerospike::OPERATOR_WRITE Aerospike::OPERATOR_WRITE and other operators
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function operate(array $key, array $operations, &$returned, array $options = []) {}

    /**
     *  Perform multiple bin operations on a record with a given key, with write operations happening before read ones.
     *  The order of the resulting elements will correspond to the order of the operations in the parameters.
     *
     *  Non-existent bins being read will have a `NULL` value.
     *
     * Currently a call to operateOrdered() can include only one write operation per-bin.
     * For example, you cannot both append and prepend to the same bin, in the same call.
     *
     * Like other bin operations, operateOrdered() only works on existing records (i.e. ones that were previously created with a put()).
     *
     * **Example #1 Combining several write operations into one multi-op call**
     *
     * ```php
     * $config = ["hosts" => [["addr"=>"localhost", "port"=>3000]], "shm"=>[]];
     * $client = new Aerospike($config, true);
     * if (!$client->isConnected()) {
     *    echo "Aerospike failed to connect[{$client->errorno()}]: {$client->error()}\n";
     *    exit(1);
     * }
     *
     * $key = $client->initKey("test", "demo", "pk458");
     * $operations = [
     * array("op" => Aerospike::OP_LIST_APPEND, "bin" => "age", "val"=>49),
     * array("op" => Aerospike::OP_LIST_GET, "bin" => "age", "index"=>0),
     * array("op" => Aerospike::OP_LIST_POP, "bin" => "age", "index"=>0)
     * ];
     * $returned = "output value";
     * $status = $client->operateOrdered($key, $operations, $returned);
     *
     * if ($status == Aerospike::OK) {
     *     var_dump($returned);
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     *
     * @link https://www.aerospike.com/docs/guide/kvs.html Key-Value Store
     * @link https://github.com/aerospike/aerospike-client-php/blob/master/doc/README.md#handling-unsupported-types Handling Unsupported Types
     * @link https://www.aerospike.com/docs/client/c/usage/kvs/write.html#change-record-time-to-live-ttl Time-to-live
     * @link https://www.aerospike.com/docs/guide/glossary.html Glossary
     * @param array $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param array $operations The array of of one or more per-bin operations conforming to the following structure:
     * ```
     * Write Operation:
     *   op => Aerospike::OPERATOR_WRITE
     *   bin => bin name (cannot be longer than 14 characters)
     *   val => the value to store in the bin
     *
     * Increment Operation:
     *   op => Aerospike::OPERATOR_INCR
     *   bin => bin name
     *   val => the integer by which to increment the value in the bin
     *
     * Prepend Operation:
     *   op => Aerospike::OPERATOR_PREPEND
     *   bin => bin name
     *   val => the string to prepend the string value in the bin
     *
     * Append Operation:
     *   op => Aerospike::OPERATOR_APPEND
     *   bin => bin name
     *   val => the string to append the string value in the bin
     *
     * Read Operation:
     *   op => Aerospike::OPERATOR_READ
     *   bin => name of the bin we want to read after any write operations
     *
     * Touch Operation: reset the time-to-live of the record and increment its generation
     *                  (only combines with read operations)
     *   op => Aerospike::OPERATOR_TOUCH
     *   ttl => a positive integer value to set as time-to-live for the record
     *
     * Delete Operation:
     *   op => Aerospike::OPERATOR_DELETE
     *
     * List Append Operation:
     *   op => Aerospike::OP_LIST_APPEND,
     *   bin =>  "events",
     *   val =>  1234
     *
     * List Merge Operation:
     *   op => Aerospike::OP_LIST_MERGE,
     *   bin =>  "events",
     *   val =>  [ 123, 456 ]
     *
     * List Insert Operation:
     *   op => Aerospike::OP_LIST_INSERT,
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  1234
     *
     * List Insert Items Operation:
     *   op => Aerospike::OP_LIST_INSERT_ITEMS,
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  [ 123, 456 ]
     *
     * List Pop Operation:
     *   op => Aerospike::OP_LIST_POP, # returns a value
     *   bin =>  "events",
     *   index =>  2
     *
     * List Pop Range Operation:
     *   op => Aerospike::OP_LIST_POP_RANGE, # returns a value
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  3 # remove 3 elements starting at index 2
     *
     * List Remove Operation:
     *   op => Aerospike::OP_LIST_REMOVE,
     *   bin =>  "events",
     *   index =>  2
     *
     * List Remove Range Operation:
     *   op => Aerospike::OP_LIST_REMOVE_RANGE,
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  3 # remove 3 elements starting at index 2
     *
     * List Clear Operation:
     *   op => Aerospike::OP_LIST_CLEAR,
     *   bin =>  "events"
     *
     * List Set Operation:
     *   op => Aerospike::OP_LIST_SET,
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  "latest event at index 2" # set this value at index 2
     *
     * List Get Operation:
     *   op => Aerospike::OP_LIST_GET, # returns a value
     *   bin =>  "events",
     *   index =>  2 # similar to Aerospike::OPERATOR_READ but only returns the value
     *                 at index 2 of the list, not the whole bin
     *
     * List Get Range Operation:
     *   op => Aerospike::OP_LIST_GET_RANGE, # returns a value
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  3 # get 3 elements starting at index 2
     *
     * List Trim Operation:
     *   op => Aerospike::OP_LIST_TRIM,
     *   bin =>  "events",
     *   index =>  2,
     *   val =>  3 # remove all elements not in the range between index 2 and index 2 + 3
     *
     * List Size Operation:
     *   op => Aerospike::OP_LIST_SIZE, # returns a value
     *   bin =>  "events" # gets the size of a list contained in the bin
     *
     *
     * Map operations
     *
     * Map Policies:
     * Many of the following operations require a map policy, the policy is an array
     * containing any of the keys AEROSPIKE::OPT_MAP_ORDER, AEROSPIKE::OPT_MAP_WRITE_MODE
     *
     * the value for AEROSPIKE::OPT_MAP_ORDER should be one of AEROSPIKE::AS_MAP_UNORDERED , AEROSPIKE::AS_MAP_KEY_ORDERED , AEROSPIKE::AS_MAP_KEY_VALUE_ORDERED
     * the default value is currently AEROSPIKE::AS_MAP_UNORDERED
     *
     * the value for AEROSPIKE::OPT_MAP_WRITE_MODE should be one of: AEROSPIKE::AS_MAP_UPDATE, AEROSPIKE::AS_MAP_UPDATE_ONLY , AEROSPIKE::AS_MAP_CREATE_ONLY
     * the default value is currently AEROSPIKE::AS_MAP_UPDATE
     *
     * the value for AEROSPIKE::OPT_MAP_WRITE_FLAGS should be one of: AEROSPIKE::AS_MAP_WRITE_DEFAULT, AEROSPIKE::AS_MAP_WRITE_CREATE_ONLY, AEROSPIKE::AS_MAP_WRITE_UPDATE_ONLY, AEROSPIKE::AS_MAP_WRITE_NO_FAIL, AEROSPIKE::AS_MAP_WRITE_PARTIAL
     * the default value is currently AEROSPIKE::AS_MAP_WRITE_DEFAULT
     *
     * Map return types:
     * many of the map operations require a return_type entry.
     * this specifies the format in which the response should be returned. The options are:
     * AEROSPIKE::AS_MAP_RETURN_NONE # Do not return a result.
     * AEROSPIKE::AS_MAP_RETURN_INDEX # Return key index order.
     * AEROSPIKE::AS_MAP_RETURN_REVERSE_INDEX # Return reverse key order.
     * AEROSPIKE::AS_MAP_RETURN_RANK # Return value order.
     * AEROSPIKE::AS_MAP_RETURN_REVERSE_RANK # Return reserve value order.
     * AEROSPIKE::AS_MAP_RETURN_COUNT # Return count of items selected.
     * AEROSPIKE::AS_MAP_RETURN_KEY # Return key for single key read and key list for range read.
     * AEROSPIKE::AS_MAP_RETURN_VALUE # Return value for single key read and value list for range read.
     * AEROSPIKE::AS_MAP_RETURN_KEY_VALUE # Return key/value items. Will be of the form ['key1', 'val1', 'key2', 'val2', 'key3', 'val3]
     *
     * Map policy Operation:
     *   op => Aerospike::OP_MAP_SET_POLICY,
     *   bin =>  "map",
     *   map_policy =>  [ AEROSPIKE::OPT_MAP_ORDER => AEROSPIKE::AS_MAP_KEY_ORDERED]
     *
     * Map clear operation: (Remove all items from a map)
     *   op => AEROSPIKE::OP_MAP_CLEAR,
     *   bin => "bin_name"
     *
     *
     * Map Size Operation: Return the number of items in a map
     *   op => AEROSPIKE::OP_MAP_SIZE,
     *   bin => "bin_name"
     *
     * Map Get by Key operation
     *   op => AEROSPIKE::OP_MAP_GET_BY_KEY ,
     *   bin => "bin_name",
     *   key => "my_key",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get By Key Range operation:
     *   op => AEROSPIKE::OP_MAP_GET_BY_KEY_RANGE ,
     *   bin => "bin_name",
     *   key => "aaa",
     *   range_end => "bbb"
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get By Value operation:
     *   op => AEROSPIKE::OP_MAP_GET_BY_VALUE ,
     *   bin => "bin_name",
     *   value => "my_val"
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get by Value Range operation:
     *   op => AEROSPIKE::OP_MAP_GET_BY_VALUE_RANGE ,
     *   bin => "bin_name",
     *   value => "value_a",
     *   range_end => "value_z",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get By Index operation
     *   op => AEROSPIKE::OP_MAP_GET_BY_INDEX ,
     *   bin => "bin_name",
     *   index => 2,
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get by Index Range operation
     *   op => AEROSPIKE::OP_MAP_GET_BY_INDEX_RANGE,
     *   bin => "bin_name",
     *   index => 2,
     *   count => 2,
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get By Rank operation
     *   op => AEROSPIKE::OP_MAP_GET_BY_RANK ,
     *   bin => "bin_name",
     *   rank => -1, # get the item with the largest value
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Get by Rank Range operation
     *   op => AEROSPIKE::OP_MAP_GET_BY_RANK_RANGE ,
     *   rank => -2 ,
     *   count => 2 ,
     *   bin => "bin_name",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Put operation
     *   op => AEROSPIKE::OP_MAP_PUT ,
     *   bin => "bin_name",
     *   key => "aero",
     *   val => "spike",
     *   map_policy => [ AEROSPIKE::OPT_MAP_ORDER => AEROSPIKE::AS_MAP_KEY_ORDERED]
     *
     * Map Put Items operations
     *  op => AEROSPIKE::OP_MAP_PUT_ITEMS ,
     *  bin => "bin_name",
     *  val => [1, "a", 1.5],
     *  map_policy => [ AEROSPIKE::OPT_MAP_ORDER => AEROSPIKE::AS_MAP_KEY_ORDERED]
     *
     * Map Increment operation
     *   op => AEROSPIKE::OP_MAP_INCREMENT ,
     *   bin => "bin_name",
     *   val => 5, #increment the value by 5
     *   key => "key_to_increment",
     *   map_policy => [ AEROSPIKE::OPT_MAP_ORDER => AEROSPIKE::AS_MAP_KEY_ORDERED]
     *
     * Map Decrement operation
     *   op => AEROSPIKE::OP_MAP_DECREMENT ,
     *   bin => "bin_name",
     *   key => "key_to_decrement",
     *   val => 5, #decrement by 5
     *   map_policy => [ AEROSPIKE::OPT_MAP_ORDER => AEROSPIKE::AS_MAP_KEY_ORDERED]
     *
     * Map Remove by Key operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_KEY ,
     *   bin => "bin_name",
     *   key => "key_to_remove",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Remove by Key list operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_KEY_LIST ,
     *   bin => "bin_name",
     *   key => ["key1", 2, "key3"],
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map remove by Key Range operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_KEY_RANGE ,
     *   bin => "bin",
     *   key => "a",
     *   range_end => "d",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map remove by Value operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_VALUE ,
     *   bin => "bin_name",
     *   val => 5,
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map remove by value range operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_VALUE_RANGE ,
     *   bin => "bin_name",
     *   val => "a",
     *   range_end => "d"
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map remove by value list operation
     *  op => AEROSPIKE::OP_MAP_REMOVE_BY_VALUE_LIST ,
     *  bin => "bin_name",
     *  val => [1, 2, 3, 4],
     *  return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Remove by Index operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_INDEX ,
     *   index => 2,
     *   bin => "bin_name",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Remove By Index Range operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_INDEX_RANGE ,
     *   bin => "bin_name",
     *   index => 3 ,
     *   count => 3 ,
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map Remove by Rank operation
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_RANK ,
     *   rank => -1 ,
     *   bin => "bin_name",
     *   return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     * Map remove by rank range
     *   op => AEROSPIKE::OP_MAP_REMOVE_BY_RANK_RANGE,
     *   bin => "bin_name",
     *   rank => -1,
     *   count => return_type => AEROSPIKE::MAP_RETURN_KEY_VALUE
     *
     *
     *
     * ```
     *
     * @param array &$returned a pass-by-reference array of bins retrieved by read operations. If multiple operations exist for a specific bin name, the last operation will be the one placed as the value
     * @param array $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_POLICY_REPLICA
     * * Aerospike::OPT_POLICY_READ_MODE_AP
     * * Aerospike::OPT_POLICY_READ_MODE_SC
     * * Aerospike::OPT_POLICY_DURABLE_DELETE
     * * Aerospike::OPT_DESERIALIZE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_POLICY_REPLICA Aerospike::OPT_POLICY_REPLICA options
     * @see Aerospike::OPT_POLICY_READ_MODE_AP Aerospike::OPT_POLICY_READ_MODE_AP options
     * @see Aerospike::OPT_POLICY_READ_MODE_SC Aerospike::OPT_POLICY_READ_MODE_SC options
     * @see Aerospike::OPT_POLICY_DURABLE_DELETE Aerospike::OPT_POLICY_DURABLE_DELETE options
     * @see Aerospike::OPT_DESERIALIZE Aerospike::OPT_DESERIALIZE option
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @see Aerospike::OPERATOR_WRITE Aerospike::OPERATOR_WRITE and other operators
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function operateOrdered(array $key, array $operations, &$returned, array $options = []) {}

    /**
     * Count the number of elements in a list type bin
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param int    &$count pass-by-reference param
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_REPLICA
     * * Aerospike::OPT_POLICY_READ_MODE_AP
     * * Aerospike::OPT_POLICY_READ_MODE_SC
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_READ_TIMEOUT Aerospike::OPT_READ_TIMEOUT options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_REPLICA Aerospike::OPT_POLICY_REPLICA options
     * @see Aerospike::OPT_POLICY_READ_MODE_AP Aerospike::OPT_POLICY_READ_MODE_AP options
     * @see Aerospike::OPT_POLICY_READ_MODE_SC Aerospike::OPT_POLICY_READ_MODE_SC options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listSize(array $key, $bin, &$count, array $options = []) {}

    /**
     * Add a single value (of any type) to the end of a list type bin
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param mixed  $value
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listAppend(array $key, $bin, $value, array $options = []) {}

    /**
     * Add several items to the end of a list type bin
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param array  $items
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listMerge(array $key, $bin, array $items, array $options = []) {}

    /**
     * Insert a single element (of any type) at a specified index of a list type bin
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param int    $index
     * @param mixed  $value
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listInsert(array $key, $bin, $index, $value, array $options = []) {}

    /**
     * Insert several elements at a specified index of a list type bin
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param int    $index
     * @param array  $elements
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listInsertItems(array $key, $bin, $index, array $elements, array $options = []) {}

    /**
     * Remove and get back the element at a specified index of a list type bin
     * Index -1 is the last item in the list, -3 is the third from last, 0 is the first in the list.
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param int    $index
     * @param mixed  &$element pass-by-reference param
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_POLICY_DURABLE_DELETE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_DURABLE_DELETE Aerospike::OPT_POLICY_DURABLE_DELETE options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listPop(array $key, $bin, $index, &$element, array $options = []) {}

    /**
     * Remove and get back several elements at a specified index range of a list type bin
     * Index -1 is the last item in the list, -3 is the third from last, 0 is the first in the list.
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param int    $index
     * @param int    $count
     * @param array  &$elements pass-by-reference param. After the method call it will be an array holding the popped elements.
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_POLICY_DURABLE_DELETE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_POLICY_DURABLE_DELETE Aerospike::OPT_POLICY_DURABLE_DELETE options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listPopRange(array $key, $bin, $index, $count, &$elements, array $options = []) {}

    /**
     * Remove a list element at a specified index of a list type bin
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param int    $index
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_POLICY_DURABLE_DELETE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_POLICY_DURABLE_DELETE Aerospike::OPT_POLICY_DURABLE_DELETE options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listRemove(array $key, $bin, $index, array $options = []) {}

    /**
     * Remove several list elements at a specified index range of a list type bin
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param int    $index
     * @param int    $count
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_POLICY_DURABLE_DELETE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_POLICY_DURABLE_DELETE Aerospike::OPT_POLICY_DURABLE_DELETE options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listRemoveRange(array $key, $bin, $index, $count, array $options = []) {}

    /**
     * Trim the list, removing all elements not in the specified index range of a list type bin
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param int    $index
     * @param int    $count
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_POLICY_DURABLE_DELETE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_POLICY_DURABLE_DELETE Aerospike::OPT_POLICY_DURABLE_DELETE options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listTrim(array $key, $bin, $index, $count, array $options = []) {}

    /**
     * Remove all the elements from a list type bin
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_POLICY_DURABLE_DELETE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_POLICY_DURABLE_DELETE Aerospike::OPT_POLICY_DURABLE_DELETE options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listClear(array $key, $bin, array $options = []) {}

    /**
     * Set an element at a specified index of a list type bin
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param int    $index
     * @param mixed  $value
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_TTL
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_GEN
     * * Aerospike::OPT_POLICY_COMMIT_LEVEL
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_TTL Aerospike::OPT_TTL options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_GEN Aerospike::OPT_POLICY_GEN options
     * @see Aerospike::OPT_POLICY_COMMIT_LEVEL Aerospike::OPT_POLICY_COMMIT_LEVEL options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listSet(array $key, $bin, $index, $value, array $options = []) {}

    /**
     * Get an element from a specified index of a list type bin
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param int    $index
     * @param array  &$element pass-by-reference param which will hold the returned element.
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_REPLICA
     * * Aerospike::OPT_POLICY_READ_MODE_AP
     * * Aerospike::OPT_POLICY_READ_MODE_SC
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_READ_TIMEOUT Aerospike::OPT_READ_TIMEOUT options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_REPLICA Aerospike::OPT_POLICY_REPLICA options
     * @see Aerospike::OPT_POLICY_READ_MODE_AP Aerospike::OPT_POLICY_READ_MODE_AP options
     * @see Aerospike::OPT_POLICY_READ_MODE_SC Aerospike::OPT_POLICY_READ_MODE_SC options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listGet(array $key, $bin, $index, array &$element, array $options = []) {}

    /**
     * Get several elements starting at a specified index from a list type bin
     *
     * @version 3.7 Requires server >= 3.7
     * @param array  $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $bin
     * @param int    $index
     * @param int    $count
     * @param array  &$elements pass-by-reference param which will hold an array of returned elements from the specified list bin.
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_POLICY_REPLICA
     * * Aerospike::OPT_POLICY_READ_MODE_AP
     * * Aerospike::OPT_POLICY_READ_MODE_SC
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_READ_TIMEOUT Aerospike::OPT_READ_TIMEOUT options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_POLICY_REPLICA Aerospike::OPT_POLICY_REPLICA options
     * @see Aerospike::OPT_POLICY_READ_MODE_AP Aerospike::OPT_POLICY_READ_MODE_AP options
     * @see Aerospike::OPT_POLICY_READ_MODE_SC Aerospike::OPT_POLICY_READ_MODE_SC options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listGetRange(array $key, $bin, $index, $count, &$elements, array $options = []) {}

    // Batch Operation Methods

    /**
     * Read a batch of records from a list of given keys, and fill $records with the resulting indexed array
     *
     * Each record is an array consisting of *key*, *metadata* and *bins* (see: {@see Aerospike::get() get()}).
     * Non-existent records will have `NULL` for their *metadata* and *bins* fields.
     * The bins returned can be filtered by passing an array of bin names.
     *
     * **Note** that the protocol getMany() will use (batch-direct or batch-index)
     * is configurable through the config parameter `Aerospike::USE_BATCH_DIRECT`
     * or `php.ini` config parameter `aerospike.use_batch_direct`.
     * By default batch-index is used with servers that support it (version >= 3.6.0).
     *
     * **Example #1 Aerospike::getMany() default behavior example**
     * ```php
     * $config = ["hosts" => [["addr"=>"localhost", "port"=>3000]], "shm"=>[]];
     * $client = new Aerospike($config, true);
     * if (!$client->isConnected()) {
     *    echo "Aerospike failed to connect[{$client->errorno()}]: {$client->error()}\n";
     *    exit(1);
     * }
     *
     * $key1 = $client->initKey("test", "users", 1234);
     * $key2 = $client->initKey("test", "users", 1235); // this key does not exist
     * $key3 = $client->initKey("test", "users", 1236);
     * $keys = array($key1, $key2, $key3);
     * $status = $client->getMany($keys, $records);
     * if ($status == Aerospike::OK) {
     *     var_dump($records);
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * array(3) {
     *   [0]=>
     *   array(3) {
     *     ["key"]=>
     *     array(4) {
     *       ["ns"]=>
     *       string(4) "test"
     *       ["set"]=>
     *       string(5) "users"
     *       ["key"]=>
     *       int(1234)
     *       ["digest"]=>
     *       string(20) "M?v2Kp???
     *
     * ?[??4?v
     *     }
     *     ["metadata"]=>
     *     array(2) {
     *       ["ttl"]=>
     *       int(4294967295)
     *       ["generation"]=>
     *       int(1)
     *     }
     *     ["bins"]=>
     *     array(3) {
     *       ["email"]=>
     *       string(15) "hey@example.com"
     *       ["name"]=>
     *       string(9) "You There"
     *       ["age"]=>
     *       int(33)
     *     }
     *   }
     *   [1]=>
     *   array(3) {
     *     ["key"]=>
     *     array(4) {
     *       ["ns"]=>
     *       string(4) "test"
     *       ["set"]=>
     *       string(5) "users"
     *       ["key"]=>
     *       int(1235)
     *       ["digest"]=>
     *       string(20) "?C??[?vwS??ƨ?????"
     *     }
     *     ["metadata"]=>
     *     NULL
     *     ["bins"]=>
     *     NULL
     *   }
     *   [2]=>
     *   array(3) {
     *     ["key"]=>
     *     array(4) {
     *       ["ns"]=>
     *       string(4) "test"
     *       ["set"]=>
     *       string(5) "users"
     *       ["key"]=>
     *       int(1236)
     *       ["digest"]=>
     *       string(20) "'?9?
     *                       ??????
     * ?	?"
     *     }
     *     ["metadata"]=>
     *     array(2) {
     *       ["ttl"]=>
     *       int(4294967295)
     *       ["generation"]=>
     *       int(1)
     *     }
     *     ["bins"]=>
     *     array(3) {
     *       ["email"]=>
     *       string(19) "thisguy@example.com"
     *       ["name"]=>
     *       string(8) "This Guy"
     *       ["age"]=>
     *       int(42)
     *     }
     *   }
     * }
     * ```
     * **Example #2 getMany records with filtered bins**
     * ```php
     * // assuming this follows Example #1
     *
     * $filter = ["email"];
     * $keys = [$key1, $key3];
     * $status = $client->getMany($keys, $records, $filter);
     * if ($status == Aerospike::OK) {
     *     var_dump($records);
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * array(2) {
     *   [0]=>
     *   array(3) {
     *     ["key"]=>
     *     array(4) {
     *       ["ns"]=>
     *       string(4) "test"
     *       ["set"]=>
     *       string(5) "users"
     *       ["key"]=>
     *       int(1234)
     *       ["digest"]=>
     *       string(20) "M?v2Kp???
     *
     * ?[??4?v
     *     }
     *     ["metadata"]=>
     *     array(2) {
     *       ["ttl"]=>
     *       int(4294967295)
     *       ["generation"]=>
     *       int(4)
     *     }
     *     ["bins"]=>
     *     array(1) {
     *       ["email"]=>
     *       string(15) "hey@example.com"
     *     }
     *   }
     *   [1]=>
     *   array(3) {
     *     ["key"]=>
     *     array(4) {
     *       ["ns"]=>
     *       string(4) "test"
     *       ["set"]=>
     *       string(5) "users"
     *       ["key"]=>
     *       int(1236)
     *       ["digest"]=>
     *       string(20) "'?9?
     *                       ??????
     * ?	?"
     *     }
     *     ["metadata"]=>
     *     array(2) {
     *       ["ttl"]=>
     *       int(4294967295)
     *       ["generation"]=>
     *       int(4)
     *     }
     *     ["bins"]=>
     *     array(1) {
     *       ["email"]=>
     *       string(19) "thisguy@example.com"
     *     }
     *   }
     * }
     * ```
     * @param array $keys an array of initialized keys, each key an array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param array &$records a pass-by-reference variable which will hold an array of record values, each record an array of `['key', 'metadata', 'bins']`
     * @param array $select only these bins out of the record (optional)
     * @param array $options an optional array of read policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::USE_BATCH_DIRECT
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * * Aerospike::OPT_BATCH_CONCURRENT
     * * Aerospike::OPT_SEND_SET_NAME
     * * Aerospike::OPT_ALLOW_INLINE
     * @see Aerospike::USE_BATCH_DIRECT Aerospike::USE_BATCH_DIRECT options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @see Aerospike::get() get()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function getMany(array $keys, &$records, array $select = [], array $options = []) {}

    /**
     * Check if a batch of records exists in the database and fill $metdata with the results
     *
     * Checks for the existence a batch of given *keys* (see: {@see Aerospike::exists() exists()}),
     * and return an indexed array matching the order of the *keys*.
     * Non-existent records will have `NULL` for their *metadata*.
     *
     * **Note** that the protocol existsMany() will use (batch-direct or batch-index)
     * is configurable through the config parameter `Aerospike::USE_BATCH_DIRECT`
     * or `php.ini` config parameter `aerospike.use_batch_direct`.
     * By default batch-index is used with servers that support it (version >= 3.6.0).
     *
     * **Example #1 Aerospike::existsMany() default behavior example**
     * ```php
     * $config = ["hosts" => [["addr"=>"localhost", "port"=>3000]], "shm"=>[]];
     * $client = new Aerospike($config, true);
     * if (!$client->isConnected()) {
     *    echo "Aerospike failed to connect[{$client->errorno()}]: {$client->error()}\n";
     *    exit(1);
     * }
     *
     * $key1 = $client->initKey("test", "users", 1234);
     * $key2 = $client->initKey("test", "users", 1235); // this key does not exist
     * $key3 = $client->initKey("test", "users", 1236);
     * $keys = array($key1, $key2, $key3);
     * $status = $client->existsMany($keys, $metadata);
     * if ($status == Aerospike::OK) {
     *     var_dump($records);
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * array(3) {
     *   [0]=>
     *   array(3) {
     *     ["key"]=>
     *     array(4) {
     *       ["ns"]=>
     *       string(4) "test"
     *       ["set"]=>
     *       string(5) "users"
     *       ["key"]=>
     *       int(1234)
     *       ["digest"]=>
     *       string(20) "M?v2Kp???
     *
     * ?[??4?v
     *     }
     *     ["metadata"]=>
     *     array(2) {
     *       ["ttl"]=>
     *       int(4294967295)
     *       ["generation"]=>
     *       int(1)
     *     }
     *   }
     *   [1]=>
     *   array(3) {
     *     ["key"]=>
     *     array(4) {
     *       ["ns"]=>
     *       string(4) "test"
     *       ["set"]=>
     *       string(5) "users"
     *       ["key"]=>
     *       int(1235)
     *       ["digest"]=>
     *       string(20) "?C??[?vwS??ƨ?????"
     *     }
     *     ["metadata"]=>
     *     NULL
     *   }
     *   [2]=>
     *   array(3) {
     *     ["key"]=>
     *     array(4) {
     *       ["ns"]=>
     *       string(4) "test"
     *       ["set"]=>
     *       string(5) "users"
     *       ["key"]=>
     *       int(1236)
     *       ["digest"]=>
     *       string(20) "'?9?
     *                       ??????
     * ?	?"
     *     }
     *     ["metadata"]=>
     *     array(2) {
     *       ["ttl"]=>
     *       int(4294967295)
     *       ["generation"]=>
     *       int(1)
     *     }
     *   }
     * }
     * ```
     * @param array $keys an array of initialized keys, each key an array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param array &$metadata a pass-by-reference array of metadata values, each an array of `['key', 'metadata']`
     * @param array $options an optional array of read policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::USE_BATCH_DIRECT
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * * Aerospike::OPT_BATCH_CONCURRENT
     * * Aerospike::OPT_SEND_SET_NAME
     * * Aerospike::OPT_ALLOW_INLINE
     * @see Aerospike::USE_BATCH_DIRECT Aerospike::USE_BATCH_DIRECT options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @see Aerospike::error() error()
     * @see Aerospike::errorno() errorno()
     * @see Aerospike::exists() exists()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function existsMany(array $keys, array &$metadata, array $options = []) {}

    // Scan and Query

    /**
     * Scan a namespace or set
     *
     * Scan a _ns.set_, and invoke a callback function *record_cb* on each
     * record streaming back from the cluster.
     *
     * Optionally select the bins to be returned. Non-existent bins in this list will appear in the
     * record with a NULL value.
     *
     * ```php
     * $options = [Aerospike::OPT_SCAN_PRIORITY => Aerospike::SCAN_PRIORITY_MEDIUM];
     * $processed = 0;
     * $status = $client->scan('test', 'users', function ($record) use (&$processed) {
     *     if (!is_null($record['bins']['email'])) echo $record['bins']['email']."\n";
     *     if ($processed++ > 19) return false; // halt the stream by returning a false
     * }, ['email'], $options);
     *
     * var_dump($status, $processed);
     * ```
     * ```
     * foo@example.com
     * :
     * bar@example.com
     * I think a sample of 20 records is enough
     * ```
     * @link https://www.aerospike.com/docs/architecture/data-model.html Aerospike Data Model
     * @link https://www.aerospike.com/docs/guide/scan.html Scans
     * @link https://www.aerospike.com/docs/operations/manage/scans/ Managing Scans
     * @param string   $ns the namespace
     * @param string   $set the set within the given namespace
     * @param callable $record_cb A callback function invoked for each record streaming back from the cluster
     * @param array    $select An array of bin names which are the subset to be returned
     * @param array    $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::OPT_SOCKET_TIMEOUT maximum socket idle time in milliseconds (0 means do not apply a socket idle timeout)
     * * Aerospike::OPT_SCAN_PRIORITY
     * * Aerospike::OPT_SCAN_PERCENTAGE of the records in the set to return
     * * Aerospike::OPT_SCAN_CONCURRENTLY whether to run the scan in parallel
     * * Aerospike::OPT_SCAN_NOBINS whether to not retrieve bins for the records
     * * Aerospike::OPT_SCAN_RPS_LIMIT limit the scan to process OPT_SCAN_RPS_LIMIT per second.
     *
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function scan(string $ns, string $set, callable $record_cb, array $select = [], array $options = []) {}

    /**
     * Query a secondary index on a namespace or set
     *
     * Query a _ns.set_ with a specified predicate, and invoke a callback function *record_cb* on each
     * record matched by the query and streaming back from the cluster.
     *
     * Optionally select the bins to be returned. Non-existent bins in this list will appear in the
     * record with a NULL value.
     *
     * ```php
     * $result = [];
     * $where = Aerospike::predicateBetween("age", 30, 39);
     * $status = $client->query("test", "users", $where, function ($record) use (&$result) {
     *     $result[] = $record['bins'];
     * });
     * if ($status !== Aerospike::OK) {
     *     echo "An error occured while querying[{$client->errorno()}] {$client->error()}\n";
     * } else {
     *     echo "The query returned ".count($result)." records\n";
     * }
     * ```
     * ```
     * foo@example.com
     * :
     * bar@example.com
     * I think a sample of 20 records is enough
     * ```
     * @link https://www.aerospike.com/docs/architecture/data-model.html Aerospike Data Model
     * @link https://www.aerospike.com/docs/guide/query.html Query
     * @link https://www.aerospike.com/docs/operations/manage/queries/index.html Managing Queries
     * @param string   $ns the namespace
     * @param string   $set the set within the given namespace
     * @param array $where the predicate for the query, usually created by the
     * predicate helper methods. The arrays conform to one of the following:
     * ```
     * Array:
     *   bin => bin name
     *   op => one of Aerospike::OP_EQ, Aerospike::OP_BETWEEN, Aerospike::OP_CONTAINS, Aerospike::OP_RANGE, etc
     *   val => scalar integer/string for OP_EQ and OP_CONTAINS or [$min, $max] for OP_BETWEEN and OP_RANGE
     *
     * or an empty array() for no predicate
     * ```
     * examples
     * ```
     * ["bin"=>"name", "op"=>Aerospike::OP_EQ, "val"=>"foo"]
     * ["bin"=>"age", "op"=>Aerospike::OP_BETWEEN, "val"=>[35,50]]
     * ["bin"=>"movies", "op"=>Aerospike::OP_CONTAINS, "val"=>"12 Monkeys"]
     * ["bin"=>"movies", "op"=>Aerospike::OP_RANGE, "val"=>[10,1000]]
     * [] // no predicate
     * ```
     * @param callable $record_cb A callback function invoked for each record streaming back from the cluster
     * @param array    $select An array of bin names which are the subset to be returned
     * @param array    $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * * Aerospike::OPT_QUERY_NOBINS
     * @see Aerospike::predicateEquals()
     * @see Aerospike::predicateBetween()
     * @see Aerospike::predicateContains()
     * @see Aerospike::predicateRange()
     * @see Aerospike::predicateGeoContainsGeoJSONPoint()
     * @see Aerospike::predicateGeoWithinGeoJSONRegion()
     * @see Aerospike::predicateGeoContainsPoint()
     * @see Aerospike::predicateGeoWithinRadius()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function query(string $ns, string $set, array $where, callable $record_cb, array $select = [], array $options = []) {}

    /**
     * Helper method for creating an EQUALS predicate
     * @param string     $bin name
     * @param int|string $val
     * @see Aerospike::query()
     * @see Aerospike::queryApply()
     * @see Aerospike::aggregate()
     * @return array expressing the predicate, to be used by query(), queryApply() or aggregate()
     * ```
     * Associative Array:
     *   bin => bin name
     *   op => Aerospike::OP_EQ
     *   val => scalar integer/string value
     * ```
     */
    public static function predicateEquals(string $bin, $val) {}

    /**
     * Helper method for creating a BETWEEN predicate
     * @param string $bin name
     * @param int    $min
     * @param int    $max
     * @see Aerospike::query()
     * @see Aerospike::queryApply()
     * @see Aerospike::aggregate()
     * @return array expressing the predicate, to be used by query(), queryApply() or aggregate()
     * ```
     * Associative Array:
     *   bin => bin name
     *   op  => Aerospike::OP_BETWEEN
     *   val => [min, max]
     * ```
     */
    public static function predicateBetween(string $bin, int $min, int $max) {}

    /**
     * Helper method for creating an CONTAINS predicate
     *
     * Similar to predicateEquals(), predicateContains() looks for an exact
     * match of a value inside a complex type - a list containing the value
     * (if the index type is *INDEX_TYPE_LIST*), the value contained in the keys
     * of a map (if the index type is *INDEX_TYPE_MAPKEYS*), or a record with the
     * given value contained in the values of a map (if the index type was
     * *INDEX_TYPE_MAPVALUES*).
     * @param string     $bin name
     * @param int        $index_type one of Aerospike::INDEX_TYPE_*
     * @param int|string $val
     * @see Aerospike::query()
     * @see Aerospike::queryApply()
     * @see Aerospike::aggregate()
     * @return array expressing the predicate, to be used by query(), queryApply() or aggregate()
     * ```
     * Associative Array:
     *   bin => bin name
     *   index_type => Aerospike::INDEX_TYPE_*
     *   op => Aerospike::OP_CONTAINS
     *   val => scalar integer/string value
     * ```
     */
    public static function predicateContains(string $bin, int $index_type, $val) {}

    /**
     * Helper method for creating a RANGE predicate
     *
     * Similar to predicateBetween(), predicateRange() looks for records with a
     * range of values inside a complex type - a list containing the values
     * (if the index type is *INDEX_TYPE_LIST*), the values contained in the keys
     * of a map (if the index type is *INDEX_TYPE_MAPKEYS*), or a record with the
     * given values contained in the values of a map (if the index type was
     * *INDEX_TYPE_MAPVALUES*)
     * @param string $bin name
     * @param int    $index_type one of Aerospike::INDEX_TYPE_*
     * @param int    $min
     * @param int    $max
     * @see Aerospike::query()
     * @see Aerospike::queryApply()
     * @see Aerospike::aggregate()
     * @return array expressing the predicate, to be used by query(), queryApply() or aggregate()
     * ```
     * Associative Array:
     *   bin => bin name
     *   index_type => Aerospike::INDEX_TYPE_*
     *   op  => Aerospike::OP_BETWEEN
     *   val => [min, max]
     * ```
     */
    public static function predicateRange(string $bin, int $index_type, int $min, int $max) {}

    /**
     * Helper method for creating a GEOCONTAINS point predicate
     * @param string $bin name
     * @param string $point GeoJSON string describing a point
     * @see Aerospike::query()
     * @see Aerospike::queryApply()
     * @see Aerospike::aggregate()
     * @return array expressing the predicate, to be used by query(), queryApply() or aggregate()
     * ```
     * Associative Array:
     *   bin => bin name
     *   op => Aerospike::OP_GEOCONTAINSPOINT
     *   val => GeoJSON string
     * ```
     */
    public static function predicateGeoContainsGeoJSONPoint(string $bin, string $point) {}

    /**
     * Helper method for creating a GEOCONTAINS point predicate
     * @param string $bin name
     * @param float  $long longitude of the point
     * @param float  $lat  latitude of the point
     * @see Aerospike::query()
     * @see Aerospike::queryApply()
     * @see Aerospike::aggregate()
     * @return array expressing the predicate, to be used by query(), queryApply() or aggregate()
     * ```
     * Associative Array:
     *   bin => bin name
     *   op => Aerospike::OP_GEOCONTAINSPOINT
     *   val => GeoJSON string produced from $long and $lat
     * ```
     */
    public static function predicateGeoContainsPoint(string $bin, float $long, float $lat) {}

    /**
     * Helper method for creating a GEOWITHIN region predicate
     * @param string $bin name
     * @param string $region GeoJSON string describing the region (polygon)
     * @see Aerospike::query()
     * @see Aerospike::queryApply()
     * @see Aerospike::aggregate()
     * @return array expressing the predicate, to be used by query(), queryApply() or aggregate()
     * ```
     * Associative Array:
     *   bin => bin name
     *   op => Aerospike::OP_GEOWITHINREGION
     *   val => GeoJSON string
     * ```
     */
    public static function predicateGeoWithinGeoJSONRegion(string $bin, string $region) {}

    /**
     * Helper method for creating a GEOWITHIN circle region predicate
     * @param string $bin name
     * @param float  $long longitude of the point
     * @param float  $lat  latitude of the point
     * @param float  $radiusMeter radius of the circle in meters
     * @see Aerospike::query()
     * @see Aerospike::queryApply()
     * @see Aerospike::aggregate()
     * @return array expressing the predicate, to be used by query(), queryApply() or aggregate()
     * ```
     * Associative Array:
     *   bin => bin name
     *   op => Aerospike::OP_GEOWITHINREGION
     *   val => GeoJSON string produced from $long, $lat and $radius
     * ```
     */
    public static function predicateGeoWithinRadius(string $bin, float $long, float $lat, float $radiusMeter) {}

    /**
     * Get the status of a background job triggered by Aerospike::scanApply or Aerospike::queryApply
     *
     * ```php
     * // after a queryApply() where $job_id was set:
     * do {
     *     time_nanosleep(0, 30000000); // pause 30ms
     *     $status = $client->jobInfo($job_id, Aerospike::JOB_QUERY, $job_info);
     *     var_dump($job_info);
     * } while($job_info['status'] != Aerospike::JOB_STATUS_COMPLETED);
     * ```
     *
     * @param int   $job_id  The Job ID
     * @param int   $job_type The type of the job, either Aerospike::JOB_QUERY, or Aerospike::JOB_SCAN
     * @param array &$info    The status of the background job filled (by reference) as an array of
     * ```
     * [
     *   'progress_pct' => progress percentage for the job
     *   'records_read' => number of records read by the job
     *   'status'       => one of Aerospike::STATUS_*
     * ]
     * ```
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * @see Aerospike::scanApply()
     * @see Aerospike::queryApply()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function jobInfo(int $job_id, $job_type, array &$info, array $options = []) {}

    // UDF Methods

    /**
     * Register a UDF module with the cluster
     *
     * Note that modules containing stream UDFs need to also be copied to the
     * path described in `aerospike.udf.lua_user_path`, as the last reduce
     * iteration is run locally on the client (after reducing on all the nodes
     * of the cluster).
     *
     * Currently the only UDF language supported is Lua.
     * ```php
     * $status = $client->register('/path/to/my_udf.lua', 'my_udf.lua');
     * if ($status == Aerospike::OK) {
     *     echo "UDF module at $path is registered as my_udf on the Aerospike DB.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * @link https://www.aerospike.com/docs/udf/udf_guide.html UDF Development Guide
     * @param string $path the path to the Lua file on the client-side machine
     * @param string $module the name of the UDF module to register with the cluster
     * @param int    $language
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function register($path, $module, $language = Aerospike::UDF_TYPE_LUA, $options = []) {}

    /**
     * Remove a UDF module from the cluster
     *
     * ```php
     * $status = $client->deregister('my_udf');
     * if ($status == Aerospike::OK) {
     *     echo "UDF module my_udf was removed from the Aerospike DB.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * @param string $module the name of the UDF module registered with the cluster
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT
     * @see Aerospike::ERR_UDF_NOT_FOUND UDF error status codes
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function deregister($module, $options = []) {}

    /**
     * List the UDF modules registered with the cluster
     *
     * The modules array has the following structure:
     * ```
     * Array of:
     *   name => module name
     *   type => Aerospike::UDF_TYPE_*
     * ```
     * **Example**
     * ```php
     * $status = $client->listRegistered($modules);
     * if ($status == Aerospike::OK) {
     *     var_dump($modules);
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * array(2) {
     *   [0]=>
     *   array(2) {
     *     ["name"]=>
     *     string(13) "my_record_udf"
     *     ["type"]=>
     *     int(0)
     *   }
     *   [1]=>
     *   array(2) {
     *     ["name"]=>
     *     string(13) "my_stream_udf"
     *     ["type"]=>
     *     int(0)
     *   }
     * }
     * ```
     * @param array &$modules pass-by-reference param
     * @param int   $language
     * @param array $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * @see Aerospike::OPT_READ_TIMEOUT Aerospike::OPT_READ_TIMEOUT
     * @see Aerospike::OK Aerospike::OK and error status codes
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function listRegistered(&$modules, $language = Aerospike::UDF_TYPE_LUA, $options = []) {}

    /**
     * Get the code for a UDF module registered with the cluster
     *
     * Populates _code_ with the content of the matching UDF _module_ that was
     * previously registered with the server.
     *
     * **Example**
     * ```php
     * $status = $client->getRegistered('my_udf', $code);
     * if ($status == Aerospike::OK) {
     *     var_dump($code);
     * } elseif ($status == Aerospike::ERR_LUA_FILE_NOT_FOUND) {
     *     echo "The UDF module my_udf was not found to be registered with the server.\n";
     * }
     * ```
     * ```
     * string(351) "function startswith(rec, bin_name, prefix)
     *   if not aerospike:exists(rec) then
     *     return false
     *   end
     *   if not prefix then
     *     return true
     *   end
     *   if not rec[bin_name] then
     *     return false
     *   end
     *   local bin_val = rec[bin_name]
     *   l = prefix:len()
     *   if l > bin_val:len() then
     *     return false
     *   end
     *   ret = bin_val:sub(1, l) == prefix
     *   return ret
     * end
     * "
     * ```
     * @param string $module the name of the UDF module registered with the cluster
     * @param string &$code pass-by-reference param
     * @param string $language
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * @see Aerospike::OPT_READ_TIMEOUT Aerospike::OPT_READ_TIMEOUT
     * @see Aerospike::ERR_LUA_FILE_NOT_FOUND UDF error status codes
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function getRegistered($module, &$code, $language = Aerospike::UDF_TYPE_LUA, $options = []) {}

    /**
     * Apply a UDF to a record
     *
     * Applies the UDF _module.function_ to a record with a given _key_.
     * Arguments can be passed to the UDF and any returned value optionally captured.
     *
     * Currently the only UDF language supported is Lua.
     * ```php
     * $key = ["ns" => "test", "set" => "users", "key" => "1234"];
     * $status = $client->apply($key, 'my_udf', 'startswith', ['email', 'hey@'], $returned);
     * if ($status == Aerospike::OK) {
     *     if ($returned) {
     *         echo "The email of the user with key {$key['key']} starts with 'hey@'.\n";
     *     } else {
     *         echo "The email of the user with key {$key['key']} does not start with 'hey@'.\n";
     *     }
     * } elseif ($status == Aerospike::ERR_UDF_NOT_FOUND) {
     *     echo "The UDF module my_udf.lua was not registered with the Aerospike DB.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * The email of the user with key 1234 starts with 'hey@'.
     * ```
     * @link https://www.aerospike.com/docs/udf/udf_guide.html UDF Development Guide
     * @link https://www.aerospike.com/docs/udf/developing_record_udfs.html Developing Record UDFs
     * @link https://www.aerospike.com/docs/udf/api_reference.html Lua UDF - API Reference
     * @param array $key The key identifying the record. An array with keys `['ns','set','key']` or `['ns','set','digest']`
     * @param string $module the name of the UDF module registered with the cluster
     * @param string $function the name of the UDF
     * @param array  $args optional arguments for the UDF
     * @param mixed  &$returned pass-by-reference param
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_POLICY_KEY
     * * Aerospike::OPT_SERIALIZER
     * * Aerospike::OPT_POLICY_DURABLE_DELETE
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_POLICY_KEY Aerospike::OPT_POLICY_KEY options
     * @see Aerospike::OPT_SERIALIZER Aerospike::OPT_SERIALIZER options
     * @see Aerospike::OPT_POLICY_DURABLE_DELETE Aerospike::OPT_POLICY_DURABLE_DELETE options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::ERR_LUA UDF error status codes
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function apply(array $key, string $module, string $function, array $args = [], &$returned = null, $options = []) {}

    /**
     * Apply a UDF to each record in a scan
     *
     * Scan the *ns.set* and apply a UDF _module.function_ to each of its records.
     * Arguments can be passed to the UDF and any returned value optionally captured.
     *
     * Currently the only UDF language supported is Lua.
     * ```php
     * $status = $client->scanApply("test", "users", "my_udf", "mytransform", array(20), $job_id);
     * if ($status === Aerospike::OK) {
     *     var_dump("Job ID is $job_id");
     * } else if ($status === Aerospike::ERR_CLIENT) {
     *     echo "An error occured while initiating the BACKGROUND SCAN [{$client->errorno()}] ".$client->error();
     * } else {
     *     echo "An error occured while running the BACKGROUND SCAN [{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * string(12) "Job ID is 1"
     * ```
     * @link https://www.aerospike.com/docs/udf/udf_guide.html UDF Development Guide
     * @link https://www.aerospike.com/docs/udf/developing_record_udfs.html Developing Record UDFs
     * @link https://www.aerospike.com/docs/udf/api_reference.html Lua UDF - API Reference
     * @param string $ns the namespace
     * @param string $set the set within the given namespace
     * @param string $module the name of the UDF module registered with the cluster
     * @param string $function the name of the UDF
     * @param array  $args optional arguments for the UDF
     * @param int    &$job_id pass-by-reference filled by the job ID of the scan
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_POLICY_DURABLE_DELETE
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * * Aerospike::OPT_FAIL_ON_CLUSTER_CHANGE
     * * Aerospike::OPT_SCAN_RPS_LIMIT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_POLICY_DURABLE_DELETE Aerospike::OPT_POLICY_DURABLE_DELETE options
     * @see Aerospike::ERR_LUA UDF error status codes
     * @see Aerospike::jobInfo()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function scanApply(string $ns, string $set, string $module, string $function, array $args, int &$job_id, array $options = []) {}

    /**
     * Apply a UDF to each record in a query
     *
     * Query the *ns.set* with a predicate, and apply a UDF _module.function_
     * to each of matched records.
     * Arguments can be passed to the UDF and any returned value optionally captured.
     *
     * Currently the only UDF language supported is Lua.
     * ```php
     * $where = Aerospike::predicateBetween("age", 30, 39);
     * $status = $client->queryApply("test", "users", "my_udf", "mytransform", [20], $job_id);
     * if ($status === Aerospike::OK) {
     *     var_dump("Job ID is $job_id");
     * } else if ($status === Aerospike::ERR_CLIENT) {
     *     echo "An error occured while initiating the BACKGROUND SCAN [{$client->errorno()}] ".$client->error();
     * } else {
     *     echo "An error occured while running the BACKGROUND SCAN [{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * string(12) "Job ID is 1"
     * ```
     * @link https://www.aerospike.com/docs/udf/udf_guide.html UDF Development Guide
     * @link https://www.aerospike.com/docs/udf/developing_record_udfs.html Developing Record UDFs
     * @link https://www.aerospike.com/docs/udf/api_reference.html Lua UDF - API Reference
     * @param string $ns the namespace
     * @param string $set the set within the given namespace
     * @param array $where the predicate for the query, usually created by the
     * predicate methods. The arrays conform to one of the following:
     * ```
     * Array:
     *   bin => bin name
     *   op => one of Aerospike::OP_EQ, Aerospike::OP_BETWEEN, Aerospike::OP_CONTAINS, Aerospike::OP_RANGE, etc
     *   val => scalar integer/string for OP_EQ and OP_CONTAINS or [$min, $max] for OP_BETWEEN and OP_RANGE
     *
     * or an empty array() for no predicate
     * ```
     * examples
     * ```
     * ["bin"=>"name", "op"=>Aerospike::OP_EQ, "val"=>"foo"]
     * ["bin"=>"age", "op"=>Aerospike::OP_BETWEEN, "val"=>[35,50]]
     * ["bin"=>"movies", "op"=>Aerospike::OP_CONTAINS, "val"=>"12 Monkeys"]
     * ["bin"=>"movies", "op"=>Aerospike::OP_RANGE, "val"=>[10,1000]]
     * [] // no predicate
     * ```
     * @param string $module the name of the UDF module registered with the cluster
     * @param string $function the name of the UDF
     * @param array  $args optional arguments for the UDF
     * @param int    &$job_id pass-by-reference filled by the job ID of the scan
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * * Aerospike::OPT_POLICY_DURABLE_DELETE
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_WRITE_TIMEOUT Aerospike::OPT_WRITE_TIMEOUT options
     * @see Aerospike::OPT_POLICY_DURABLE_DELETE Aerospike::OPT_POLICY_DURABLE_DELETE options
     * @see Aerospike::OPT_SLEEP_BETWEEN_RETRIES Aerospike::OPT_SLEEP_BETWEEN_RETRIES options
     * @see Aerospike::OPT_TOTAL_TIMEOUT Aerospike::OPT_TOTAL_TIMEOUT options
     * @see Aerospike::OPT_SOCKET_TIMEOUT Aerospike::OPT_SOCKET_TIMEOUT options
     * @see Aerospike::MAX_RETRIES Aerospike::MAX_RETRIES options
     * @see Aerospike::ERR_LUA UDF error status codes
     * @see Aerospike::jobInfo()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function queryApply(string $ns, string $set, array $where, string $module, string $function, array $args, int &$job_id, array $options = []) {}

    /**
     * Apply a stream UDF to a scan or secondary index query
     *
     * Apply the UDF _module.function_ to the result of running a secondary
     * index query on _ns.set_. The aggregated _returned_ variable is then
     * filled, with its type depending on the UDF. It may be a string, integer
     * or array, and potentially an array of arrays, such as in the case the
     * UDF does not specify a reducer and there are multiple nodes in the
     * cluster, each sending back the result of its own aggregation.
     *
     * As with query(), if an empty array is given as the _where_ predicate a
     * 'scan aggregation' is initiated instead of a query, which means the
     * stream UDF is applied to all the records returned by the scan.
     *
     * **Note** that modules containing stream UDFs need to also be copied to the
     * path described in `aerospike.udf.lua_user_path`, as the last reduce
     * iteration is run locally on the client, after reducing on all the nodes
     * of the cluster.
     *
     * **Note** aggregate is currently unsupported in PHP built with ZTS enabled.
     * Attempting to use it in that environment will fail.
     *
     * Currently the only UDF language supported is Lua.
     *
     * **Example Stream UDF**
     *
     * Module registered as stream_udf.lua
     * ```
     * local function having_ge_threshold(bin_having, ge_threshold)
     *     debug("group_count::thresh_filter: %s >  %s ?", tostring(rec[bin_having]), tostring(ge_threshold))
     *     return function(rec)
     *         if rec[bin_having] < ge_threshold then
     *             return false
     *         end
     *         return true
     *     end
     * end
     *
     * local function count(group_by_bin)
     *   return function(group, rec)
     *     if rec[group_by_bin] then
     *       local bin_name = rec[group_by_bin]
     *       group[bin_name] = (group[bin_name] or 0) + 1
     *     end
     *     return group
     *   end
     * end
     *
     * local function add_values(val1, val2)
     *   return val1 + val2
     * end
     *
     * local function reduce_groups(a, b)
     *   return map.merge(a, b, add_values)
     * end
     *
     * function group_count(stream, group_by_bin, bin_having, ge_threshold)
     *   if bin_having and ge_threshold then
     *     local myfilter = having_ge_threshold(bin_having, ge_threshold)
     *     return stream : filter(myfilter) : aggregate(map{}, count(group_by_bin)) : reduce(reduce_groups)
     *   else
     *     return stream : aggregate(map{}, count(group_by_bin)) : reduce(reduce_groups)
     *   end
     * end
     * ```
     * **Example of aggregating a stream UDF to the result of a secondary index query**
     * ```php
     * // assuming test.users has a bin first_name, show the first name distribution
     * // for users in their twenties
     * $where = Aerospike::predicateBetween("age", 20, 29);
     * $status = $client->aggregate("test", "users", $where, "stream_udf", "group_count", ["first_name"], $names);
     * if ($status == Aerospike::OK) {
     *     var_dump($names);
     * } else {
     *     echo "An error occured while running the AGGREGATE [{$client->errorno()}] ".$client->error();
     * }
     * ```
     * ```
     * array(5) {
     *   ["Claudio"]=>
     *   int(1)
     *   ["Michael"]=>
     *   int(3)
     *   ["Jennifer"]=>
     *   int(2)
     *   ["Jessica"]=>
     *   int(3)
     *   ["Jonathan"]=>
     *   int(3)
     * }
     * ```
     * @link https://www.aerospike.com/docs/udf/udf_guide.html UDF Development Guide
     * @link https://www.aerospike.com/docs/udf/developing_stream_udfs.html Developing Stream UDFs
     * @link https://www.aerospike.com/docs/guide/aggregation.html Aggregation
     * @param string $ns the namespace
     * @param string $set the set within the given namespace
     * @param array $where the predicate for the query, usually created by the
     * predicate methods. The arrays conform to one of the following:
     * ```
     * Array:
     *   bin => bin name
     *   op => one of Aerospike::OP_EQ, Aerospike::OP_BETWEEN, Aerospike::OP_CONTAINS, Aerospike::OP_RANGE
     *   val => scalar integer/string for OP_EQ and OP_CONTAINS or [$min, $max] for OP_BETWEEN and OP_RANGE
     *
     * or an empty array() for no predicate
     * ```
     * examples
     * ```
     * ["bin"=>"name", "op"=>Aerospike::OP_EQ, "val"=>"foo"]
     * ["bin"=>"age", "op"=>Aerospike::OP_BETWEEN, "val"=>[35,50]]
     * ["bin"=>"movies", "op"=>Aerospike::OP_CONTAINS, "val"=>"12 Monkeys"]
     * ["bin"=>"movies", "op"=>Aerospike::OP_RANGE, "val"=>[10,1000]]
     * [] // no predicate
     * ```
     * @param string $module the name of the UDF module registered with the cluster
     * @param string $function the name of the UDF
     * @param array  $args optional arguments for the UDF
     * @param mixed  &$returned pass-by-reference param
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::OPT_READ_TIMEOUT
     * * Aerospike::OPT_SLEEP_BETWEEN_RETRIES
     * * Aerospike::OPT_TOTAL_TIMEOUT
     * * Aerospike::OPT_MAX_RETRIES
     * * Aerospike::OPT_SOCKET_TIMEOUT
     * @see Aerospike::OPT_READ_TIMEOUT Aerospike::OPT_READ_TIMEOUT options
     * @see Aerospike::ERR_LUA UDF error status codes
     * @see Aerospike::predicateEquals()
     * @see Aerospike::predicateBetween()
     * @see Aerospike::predicateContains()
     * @see Aerospike::predicateRange()
     * @see Aerospike::predicateGeoContainsGeoJSONPoint()
     * @see Aerospike::predicateGeoWithinGeoJSONRegion()
     * @see Aerospike::predicateGeoContainsPoint()
     * @see Aerospike::predicateGeoWithinRadius()
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function aggregate(string $ns, string $set, array $where, string $module, string $function, array $args, &$returned, array $options = []) {}

    // Admin methods

    /**
     * Create a secondary index on a bin of a specified set
     *
     * Create a secondary index of a given *index_type* on a namespace *ns*, *set* and *bin* with a specified *name*
     * ```php
     * $status = $client->addIndex("test", "user", "email", "user_email_idx", Aerospike::INDEX_TYPE_DEFAULT, Aerospike::INDEX_STRING);
     * if ($status == Aerospike::OK) {
     *     echo "Index user_email_idx created on test.user.email\n";
     * } else if ($status == Aerospike::ERR_INDEX_FOUND) {
     *     echo "This index has already been created.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     *
     * $client->addIndex("test", "user", "movies", "user_movie_titles_idx", Aerospike::INDEX_TYPE_MAPKEYS, Aerospike::INDEX_STRING);
     * $client->addIndex("test", "user", "movies", "user_movie_views_idx", Aerospike::INDEX_TYPE_MAPVALUES, Aerospike::INDEX_NUMERIC);
     * $client->addIndex("test", "user", "aliases", "user_aliases_idx", Aerospike::INDEX_TYPE_LIST, Aerospike::INDEX_STRING);
     *
     * $client->info("sindex", $res);
     * echo($res);
     * ```
     * @param string $ns the namespace
     * @param string $set the set within the given namespace
     * @param string $bin the bin on which the secondary index is to be created
     * @param string $name the name of the index
     * @param int    $indexType one of *Aerospike::INDEX\_TYPE\_\**
     * @param int    $dataType one of *Aerospike::INDEX_NUMERIC* and *Aerospike::INDEX_STRING*
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * @see Aerospike::INDEX_TYPE_DEFAULT
     * @see Aerospike::INDEX_STRING
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function addIndex(string $ns, string $set, string $bin, string $name, int $indexType, int $dataType, array $options = []) {}

    /**
     * Drop a secondary index
     *
     * ```php
     * $status = $client->dropIndex("test", "user_email_idx");
     * if ($status == Aerospike::OK) {
     *     echo "Index user_email_idx was dropped from namespace 'test'\n";
     * } else if ($status == Aerospike::ERR_INDEX_NOT_FOUND) {
     *     echo "No such index exists.\n";
     * } else {
     *     echo "[{$client->errorno()}] ".$client->error();
     * }
     * ```
     * @param string $ns the namespace
     * @param string $name the name of the index
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_WRITE_TIMEOUT
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function dropIndex(string $ns, string $name, array $options = []) {}

    // Info Methods

    /**
     * Send an info request to a single cluster node
     *
     * Interface with the cluster's command and control functions.
     * A formatted request string is sent to a cluster node, and a formatted
     * response returned.
     *
     * A specific host can be optionally set, otherwise the request command is
     * sent to the host definded for client constructor.
     *
     * ```php
     * $client->info('bins/test', $response);
     * var_dump($response);
     * ```
     * ```
     * string(53) "bins/test	num-bin-names=2,bin-names-quota=32768,demo,characters"
     * ```
     * @link https://www.aerospike.com/docs/reference/info Info Command Reference
     * @param string $request  a formatted info command
     * @param string &$response a formatted response from the server, filled by reference
     * @param null|array $host an array holding the cluster node connection information cluster
     *                         and manage its connections to them. ```[ 'addr' => $addr , 'port' =>  $port ]```
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * @return int The status code of the operation. Compare to the Aerospike class status constants.
     */
    public function info(string $request, string &$response, ?array $host = null, array $options = []) {}

    /**
     * Send an info request to a single cluster node
     *
     * Interface with the cluster's command and control functions.
     * A formatted request string is sent to a cluster node, and a formatted
     * response returned.
     *
     * A specific host can be optionally set, otherwise the request command is
     * sent to the host definded for client constructor.
     *
     * ```php
     * $response = $client->infoMany('build');
     * var_dump($response);
     * ```
     * ```
     * array(3) {
     *   ["BB936F106CA0568"]=>
     *   string(6) "build  3.3.19"
     *   ["AE712F245BB9876"]=>
     *   string(6) "build  3.3.19"
     *   ["DCBA9AA34EE12FA"]=>
     *   string(6) "build  3.3.19"
     * }
     * ```
     * @link https://www.aerospike.com/docs/reference/info Info Command Reference
     * @param string $request  a formatted info command
     * @param null|array $host an array of _host_ arrays, each with ```[ 'addr' => $addr , 'port' =>  $port ]```
     * @param array  $options an optional array of policy options, whose keys include
     * * Aerospike::OPT_READ_TIMEOUT
     * @return array results in the format
     * ```
     * Array:
     *  NODE-ID => response string
     * ```
     */
    public function infoMany(string $request, ?array $host = null, array $options = []) {}

    /**
     * Get the addresses of the cluster nodes
     *
     * ```php
     * $nodes = $client->getNodes();
     * var_dump($nodes);
     * ```
     * ```
     * array(2) {
     *   [0]=>
     *   array(2) {
     *     ["addr"]=>
     *     string(15) "192.168.120.145"
     *     ["port"]=>
     *     string(4) "3000"
     *   }
     *   [1]=>
     *   array(2) {
     *     ["addr"]=>
     *     string(15) "192.168.120.144"
     *     ["port"]=>
     *     string(4) "3000"
     *   }
     * }
     * ```
     * @return array results in the format
     * ```
     * Array:
     *   Array:
     *     'addr' => the IP address of the node
     *     'port' => the port of the node
     * ```
     */
    public function getNodes() {}

    // Logging Methods

    /**
     * Set the logging threshold of the Aerospike object
     *
     * @param int $log_level one of `Aerospike::LOG_LEVEL_*` values
     * * Aerospike::LOG_LEVEL_OFF
     * * Aerospike::LOG_LEVEL_ERROR
     * * Aerospike::LOG_LEVEL_WARN
     * * Aerospike::LOG_LEVEL_INFO
     * * Aerospike::LOG_LEVEL_DEBUG
     * * Aerospike::LOG_LEVEL_TRACE
     * @see Aerospike::LOG_LEVEL_OFF Aerospike::LOG_LEVEL_* constants
     */
    public function setLogLevel(int $log_level) {}

    /**
     * Set a handler for log events
     *
     * Registers a callback method that will be triggered whenever a logging event above the declared log threshold occurs.
     *
     * ```php
     * $config = ["hosts" => [["addr"=>"localhost", "port"=>3000]], "shm"=>[]];
     * $client = new Aerospike($config, true);
     * if (!$client->isConnected()) {
     *   echo "Aerospike failed to connect[{$client->errorno()}]: {$client->error()}\n";
     *   exit(1);
     * }
     * $client->setLogLevel(Aerospike::LOG_LEVEL_DEBUG);
     * $client->setLogHandler(function ($level, $file, $function, $line) {
     *   switch ($level) {
     *     case Aerospike::LOG_LEVEL_ERROR:
     *       $lvl_str = 'ERROR';
     *       break;
     *     case Aerospike::LOG_LEVEL_WARN:
     *       $lvl_str = 'WARN';
     *       break;
     *     case Aerospike::LOG_LEVEL_INFO:
     *       $lvl_str = 'INFO';
     *       break;
     *     case Aerospike::LOG_LEVEL_DEBUG:
     *       $lvl_str = 'DEBUG';
     *       break;
     *     case Aerospike::LOG_LEVEL_TRACE:
     *       $lvl_str = 'TRACE';
     *       break;
     *     default:
     *       $lvl_str = '???';
     *   }
     *   error_log("[$lvl_str] in $function at $file:$line");
     * });
     * ```
     *
     * @see Aerospike::LOG_LEVEL_OFF Aerospike::LOG_LEVEL_* constants
     * @param callable $log_handler a callback function with the signature
     * ```php
     * function log_handler ( int $level, string $file, string $function, int $line ) : void
     * ```
     */
    public function setLogHandler(callable $log_handler) {}

    // Unsupported Type Handler Methods

    /**
     * Set a serialization handler for unsupported types
     *
     * Registers a callback method that will be triggered whenever a write method handles a value whose type is unsupported.
     * This is a static method and the *serialize_cb* handler is global across all instances of the Aerospike class.
     *
     * ```php
     * Aerospike::setSerializer(function ($val) {
     *   return gzcompress(json_encode($val));
     * });
     * ```
     *
     * @link https://github.com/aerospike/aerospike-client-php/tree/master/doc#handling-unsupported-types Handling Unsupported Types
     * @param callable $serialize_cb a callback invoked for each value of an unsupported type, when writing to the cluster. The function must follow the signature
     * ```php
     * function aerospike_serialize ( mixed $value ) : string
     * ```
     * @see Aerospike::OPT_SERIALIZER Aerospike::OPT_SERIALIZER options
     */
    public function setSerializer(callable $serialize_cb) {}

    /**
     * Set a deserialization handler for unsupported types
     *
     * Registers a callback method that will be triggered whenever a read method handles a value whose type is unsupported.
     * This is a static method and the *unserialize_cb* handler is global across all instances of the Aerospike class.
     *
     * ```php
     * Aerospike::setDeserializer(function ($val) {
     *   return json_decode(gzuncompress($val));
     * });
     * ```
     *
     * @link https://github.com/aerospike/aerospike-client-php/tree/master/doc#handling-unsupported-types Handling Unsupported Types
     * @param callable $unserialize_cb a callback invoked for each value of an unsupported type, when reading from the cluster. The function must follow the signature
     * ```php
     * // $value is binary data of type AS_BYTES_BLOB
     * function aerospike_deserialize ( string $value )
     * ```
     * @see Aerospike::OPT_SERIALIZER Aerospike::OPT_SERIALIZER options
     */
    public function setDeserializer(callable $unserialize_cb) {}

    /*
     * Options can be assigned values that modify default behavior
     * Used by the constructor, read, write, scan, query, apply, and info
     * operations.
     */

    /* Key used to specify an array of read policy defaults used in the constructor.
       See https://github.com/aerospike/aerospike-client-php/blob/master/doc/policies.md
    */
    public const OPT_READ_DEFAULT_POL = "OPT_READ_DEFAULT_POL";

    /* Key used to specify an array of write policy defaults used in the constructor.
       See https://github.com/aerospike/aerospike-client-php/blob/master/doc/policies.md
    */
    public const OPT_WRITE_DEFAULT_POL = "OPT_WRITE_DEFAULT_POL";

    /* Key used to specify an array of remove policy defaults used in the constructor.
       See https://github.com/aerospike/aerospike-client-php/blob/master/doc/policies.md
    */
    public const OPT_REMOVE_DEFAULT_POL = "OPT_REMOVE_DEFAULT_POL";

    /* Key used to specify an array of batch policy defaults used in the constructor.
       See https://github.com/aerospike/aerospike-client-php/blob/master/doc/policies.md
    */
    public const OPT_BATCH_DEFAULT_POL = "OPT_BATCH_DEFAULT_POL";

    /* Key used to specify an array of operate policy defaults used in the constructor.
       See https://github.com/aerospike/aerospike-client-php/blob/master/doc/policies.md
    */
    public const OPT_OPERATE_DEFAULT_POL = "OPT_OPERATE_DEFAULT_POL";

    /* Key used to specify an array of query policy defaults used in the constructor.
       See https://github.com/aerospike/aerospike-client-php/blob/master/doc/policies.md
    */
    public const OPT_QUERY_DEFAULT_POL = "OPT_QUERY_DEFAULT_POL";

    /* Key used to specify an array of scan policy defaults used in the constructor.
       See https://github.com/aerospike/aerospike-client-php/blob/master/doc/policies.md
    */
    public const OPT_SCAN_DEFAULT_POL = "OPT_SCAN_DEFAULT_POL";

    /* Key used to specify an array of apply policy defaults used in the constructor.
       See https://github.com/aerospike/aerospike-client-php/blob/master/doc/policies.md
    */
    public const OPT_APPLY_DEFAULT_POL = "OPT_APPLY_DEFAULT_POL";

    /*
    Key used in the options argument of the constructor used to point to an array of TLS
    configuration parameters. Use of TLS requires an enterprise version of the Aerospike Server.
    */
    public const OPT_TLS_CONFIG = "OPT_TLS_CONFIG";

    /* Key used in the OPT_TLS boolean Whether or not to enable TLS.
    */
    public const OPT_TLS_ENABLE = "OPT_TLS_ENABLE";

    /*
    Key used to specify a string path to a trusted CA certificate file. By default TLS will use system standard trusted CA certificates
    */
    public const OPT_OPT_TLS_CAFILE = "OPT_OPT_TLS_CAFILE";

    /*
    Key used to specify a Path to a directory of trusted certificates. See the OpenSSL SSL_CTX_load_verify_locations manual page for more information about the format of the directory.
    */
    public const OPT_TLS_CAPATH = "OPT_TLS_CAPATH";

    /*Key used to specify a string representation of allowed protocols. Specifies enabled protocols. This format is the same as Apache's SSLProtocol documented at https://httpd.apache.org/docs/current/mod/mod_ssl.html#sslprotocol . If not specified the client will use "-all +TLSv1.2".
    */
    public const OPT_TLS_PROTOCOLS = "OPT_TLS_PROTOCOLS";

    /*
    Key used to specify a string. Specifies enabled cipher suites. The format is the same as OpenSSL's Cipher List Format documented at https://www.openssl.org/docs/manmaster/apps/ciphers.html .If not specified the OpenSSL default cipher suite described in the ciphers documentation will be used. If you are not sure what cipher suite to select this option is best left unspecified
    */
    public const OPT_TLS_CIPHER_SUITE = "OPT_TLS_CIPHER_SUITE";

    /*
    Key used to specify a boolean. Enable CRL checking for the certificate chain leaf certificate. An error occurs if a suitable CRL cannot be found. By default CRL checking is disabled.
    */
    public const OPT_TLS_CRL_CHECK = "OPT_TLS_CRL_CHECK";

    /*
    Key used to specify a bolean. Enable CRL checking for the entire certificate chain. An error occurs if a suitable CRL cannot be found. By default CRL checking is disabled.
    */
    public const OPT_TLS_CRL_CHECK_ALL = "OPT_TLS_CRL_CHECK_ALL";

    /* Key used to specify a path to a certificate blacklist file. The file should contain one line for each blacklisted certificate. Each line starts with the certificate serial number expressed in hex. Each entry may optionally specify the issuer name of the certificate (serial numbers are only required to be unique per issuer). Example records: 867EC87482B2 /C=US/ST=CA/O=Acme/OU=Engineering/CN=Test Chain CA E2D4B0E570F9EF8E885C065899886461 */
    public const OPT_TLS_CERT_BLACKLIST = "OPT_TLS_CERT_BLACKLIST";

    /* Boolean: Log session information for each connection. */
    public const OPT_TLS_LOG_SESSION_INFO = "OPT_TLS_LOG_SESSION_INFO";

    /*
    Path to the client's key for mutual authentication. By default mutual authentication is disabled.
    */
    public const OPT_TLS_KEYFILE = "OPT_TLS_KEYFILE";

    /* Path to the client's certificate chain file for mutual authentication. By default mutual authentication is disabled.
    */
    public const OPT_TLS_CERTFILE = "OPT_TLS_CERTFILE";

    /**
     * Defines the length of time (in milliseconds) the client waits on establishing a connection.
     * @const OPT_CONNECT_TIMEOUT value in milliseconds (default: 1000)
     */
    public const OPT_CONNECT_TIMEOUT = "OPT_CONNECT_TIMEOUT";

    /**
     * Defines the length of time (in milliseconds) the client waits on a read
     * operation.
     * @const OPT_READ_TIMEOUT value in milliseconds (default: 1000)
     */
    public const OPT_READ_TIMEOUT = "OPT_READ_TIMEOUT";

    /**
     * Defines the length of time (in milliseconds) the client waits on a write
     * operation.
     * @const OPT_WRITE_TIMEOUT value in milliseconds (default: 1000)
     */
    public const OPT_WRITE_TIMEOUT = "OPT_WRITE_TIMEOUT";

    /**
     * Sets the TTL of the record along with a write operation.
     *
     * * TTL > 0 sets the number of seconds into the future in which to expire the record.
     * * TTL = 0 uses the default TTL defined for the namespace.
     * * TTL = -1 means the record should never expire.
     * * TTL = -2 means the record's TTL should not be modified.
     * @const OPT_TTL value in seconds, or the special values 0, -1 or -2 (default: 0)
     */
    public const OPT_TTL = "OPT_TTL";

    /**
     * Accepts one of the POLICY_KEY_* values.
     *
     * {@link https://www.aerospike.com/docs/client/php/usage/kvs/record-structure.html Records}
     * are uniquely identified by their digest, and can optionally store the value of their primary key
     * (their unique ID in the application).
     * @const OPT_POLICY_KEY Key storage policy option (digest-only or send key)
     */
    public const OPT_POLICY_KEY = "OPT_POLICY_KEY";

    /**
     * Do not store the primary key with the record (default)
     * @const POLICY_KEY_DIGEST digest only
     */
    public const POLICY_KEY_DIGEST = 0;

    /**
     * Store the primary key with the record
     * @const POLICY_KEY_SEND store the primary key with the record
     */
    public const POLICY_KEY_SEND = 1;

    /**
     * Accepts one of the POLICY_EXISTS_* values.
     *
     * By default writes will try to create a record or update its bins, which
     * is a behavior similar to how arrays work in PHP. Setting a write with a
     * different POLICY\_EXISTS\_* value can simulate a more DML-like behavior,
     * similar to an RDBMS.
     * @const OPT_POLICY_EXISTS existence policy option
     */
    public const OPT_POLICY_EXISTS = "OPT_POLICY_EXISTS";

    /**
     * "CREATE_OR_UPDATE" behavior. Create the record if it does not exist,
     * or update its bins if it does. (default)
     * @const POLICY_EXISTS_IGNORE create or update behavior
     */
    public const POLICY_EXISTS_IGNORE = 0;

    /**
     * Create a record ONLY if it DOES NOT exist.
     * @const POLICY_EXISTS_CREATE create only behavior (fail otherwise)
     */
    public const POLICY_EXISTS_CREATE = 1;

    /**
     * Update a record ONLY if it exists.
     * @const POLICY_EXISTS_UPDATE update only behavior (fail otherwise)
     */
    public const POLICY_EXISTS_UPDATE = 2;

    /**
     * Replace a record ONLY if it exists.
     * @const POLICY_EXISTS_REPLACE replace only behavior (fail otherwise)
     */
    public const POLICY_EXISTS_REPLACE = 3;

    /**
     * Create the record if it does not exist, or replace its bins if it does.
     * @const POLICY_EXISTS_CREATE_OR_REPLACE create or replace behavior
     */
    public const POLICY_EXISTS_CREATE_OR_REPLACE = 4;

    /**
     * Set to an array( Aerospike::POLICY_GEN_* [, (int) $gen_value ] )
     *
     * Specifies the behavior of write opertions with regards to the record's
     * generation. Used to implement a check-and-set (CAS) pattern.
     * @const OPT_POLICY_GEN generation policy option
     */
    public const OPT_POLICY_GEN = "OPT_POLICY_GEN";

    /**
     * Do not consider generation for the write operation.
     * @const POLICY_GEN_IGNORE write a record, regardless of generation (default)
     */
    public const POLICY_GEN_IGNORE = 0;

    /**
     * Only write if the record was not modified since a given generation value.
     * @const POLICY_GEN_EQ write a record, ONLY if generations are equal
     */
    public const POLICY_GEN_EQ = 1;

    /**
     * @const POLICY_GEN_GT write a record, ONLY if local generation is greater-than remote generation
     */
    public const POLICY_GEN_GT = 2;

    /**
     * Set to one of the SERIALIZER_* values.
     *
     * Supported types, such as string, integer, and array get directly cast to
     * the matching Aerospike types, such as as_string, as_integer, and as_map.
     * Unsupported types, such as boolean, need a serializer to handle them.
     * @const OPT_SERIALIZER determines a handler for unsupported data types
     */
    public const OPT_SERIALIZER = "OPT_SERIALIZER";

    /**
     * Throw an exception instead of serializing unsupported types.
     * @const SERIALIZER_NONE throw an error when serialization is required
     */
    public const SERIALIZER_NONE = 0;

    /**
     * Use the built-in PHP serializer for any unsupported types.
     * @const SERIALIZER_PHP use the PHP serialize/unserialize functions (default)
     */
    public const SERIALIZER_PHP = 1;

    /**
     * Use a user-defined serializer for any unsupported types.
     * @const SERIALIZER_USER use a pair of functions written in PHP for serialization
     */
    public const SERIALIZER_USER = 2;

    /**
     * Accepts one of the POLICY_COMMIT_LEVEL_* values.
     *
     * One of the {@link https://www.aerospike.com/docs/architecture/consistency.html per-transaction consistency levels}.
     * Specifies the number of replicas required to be successfully committed
     * before returning success in a write operation to provide the desired
     * consistency level.
     * @const OPT_POLICY_COMMIT_LEVEL commit level policy option
     */
    public const OPT_POLICY_COMMIT_LEVEL = "OPT_POLICY_COMMIT_LEVEL";

    /**
     * Return succcess only after successfully committing all replicas.
     * @const POLICY_COMMIT_LEVEL_ALL write to the master and all replicas (default)
     */
    public const POLICY_COMMIT_LEVEL_ALL = 0;

    /**
     * Return succcess after successfully committing the master replica.
     * @const POLICY_COMMIT_LEVEL_MASTER master will asynchronously write to replicas
     */
    public const POLICY_COMMIT_LEVEL_MASTER = 1;

    /**
     * Accepts one of the POLICY_REPLICA_* values.
     *
     * One of the {@link https://www.aerospike.com/docs/architecture/consistency.html per-transaction consistency levels}.
     * Specifies which partition replica to read from.
     * @const OPT_POLICY_REPLICA replica policy option
     */
    public const OPT_POLICY_REPLICA = "OPT_POLICY_REPLICA";

    /**
     * Read from the partition master replica node.
     * @const POLICY_REPLICA_MASTER read from master
     */
    public const POLICY_REPLICA_MASTER = 0;

    /**
     * Read from an unspecified replica node.
     * @const POLICY_REPLICA_ANY read from any replica node
     */
    public const POLICY_REPLICA_ANY = 1;

    /**
     *   Always try node containing master partition first. If connection fails and
     *   `retry_on_timeout` is true, try node containing replica partition.
     *   Currently restricted to master and one replica. (default)
     * @const POLICY_REPLICA_SEQUENCE attempt to read from master first, then try the node containing replica partition if connection failed. (default)
     */
    public const POLICY_REPLICA_SEQUENCE = 2;

    /**
     * Try node on the same rack as the client first.  If there are no nodes on the
     * same rack, use POLICY_REPLICA_SEQUENCE instead.
     *
     * "rack_aware" must be set to true in the client constructor, and "rack_id" must match the server rack configuration
     * to enable this functionality.
     * @const POLICY_REPLICA_PREFER_RACK attemp to read from master first, then try the node containing replica partition if connection failed. (default)
     */
    public const POLICY_REPLICA_PREFER_RACK = 3;

    /**
     * Accepts one of the POLICY_READ_MODE_AP_* values.
     *
     * One of the {@link https://www.aerospike.com/docs/architecture/consistency.html per-transaction consistency levels}.
     * Specifies the number of replicas to be consulted in a read operation to
     * provide the desired consistency level in availability mode.
     * @const OPT_POLICY_READ_MODE_AP policy read option for availability namespaces
     */
    public const OPT_POLICY_READ_MODE_AP = "OPT_POLICY_READ_MODE_AP";

    /**
     * Involve a single replica in the operation.
     * @const POLICY_READ_MODE_AP_ONE (default)
     */
    public const POLICY_READ_MODE_AP_ONE = 0;

    /**
     * Involve all replicas in the operation.
     * @const AS_POLICY_READ_MODE_AP_ALL
     */
    public const AS_POLICY_READ_MODE_AP_ALL = 1;

    /**
     * Accepts one of the POLICY_READ_MODE_SC_* values.
     *
     * One of the {@link https://www.aerospike.com/docs/architecture/consistency.html per-transaction consistency levels}.
     * Specifies the number of replicas to be consulted in a read operation to
     * provide the desired consistency level.
     * @const OPT_POLICY_READ_MODE_SC policy read option for consistency namespaces
     */
    public const OPT_POLICY_READ_MODE_SC = "OPT_POLICY_READ_MODE_SC";

    /**
     * Always read from master. Record versions are local to session.
     * @const POLICY_READ_MODE_SC_SESSION (default)
     */
    public const POLICY_READ_MODE_SC_SESSION = 0;

    /**
     * Always read from master. Record versions are global and thus serialized.
     * @const POLICY_READ_MODE_SC_LINEARIZE
     */
    public const POLICY_READ_MODE_SC_LINEARIZE = 1;

    /**
     * Read from master or fully migrated replica. Record versions may not always increase.
     * @const POLICY_READ_MODE_SC_ALLOW_REPLICA
     */
    public const POLICY_READ_MODE_SC_ALLOW_REPLICA = 2;

    /**
     * Read from master or fully migrated replica. Unavailable partitions are allowed. Record versions may not always increase.
     * @const POLICY_READ_MODE_SC_ALLOW_UNAVAILABLE
     */
    public const POLICY_READ_MODE_SC_ALLOW_UNAVAILABLE = 3;

    /*
      * Should raw bytes representing a list or map be deserialized to an array.
      * Set to false for backup programs that just need access to raw bytes.
      * Default: true
      @const OPT_DESERIALIZE
    */
    public const OPT_DESERIALIZE = "deserialize";

    /**
     * Milliseconds to sleep between retries.  Enter zero to skip sleep.
     * const OPT_SLEEP_BETWEEN_RETRIES
     */
    public const OPT_SLEEP_BETWEEN_RETRIES = "sleep_between_retries";

    /**
     * Maximum number of retries before aborting the current transaction.
     * The initial attempt is not counted as a retry.
     * If OPT_MAX_RETRIES is exceeded, the transaction will return error ERR_TIMEOUT.
     * WARNING: Database writes that are not idempotent (such as "add")
     * should not be retried because the write operation may be performed
     * multiple times if the client timed out previous transaction attempts.
     * It's important to use a distinct write policy for non-idempotent
     * writes which sets OPT_MAX_RETRIES = 0;
     **/
    public const OPT_MAX_RETRIES = "OPT_MAX_RETRIES";

    /**
     * Total transaction timeout in milliseconds.
     * The OPT_TOTAL_TIMEOUT is tracked on the client and sent to the server along with
     * the transaction in the wire protocol.  The client will most likely timeout
     * first, but the server also has the capability to timeout the transaction.

     * If OPT_TOTAL_TIMEOUT is not zero and OPT_TOTAL_TIMEOUT is reached before the transaction
     * completes, the transaction will return error ERR_TIMEOUT.
     * If OPT_TOTAL_TIMEOUT is zero, there will be no total time limit.
     */
    public const OPT_TOTAL_TIMEOUT = "OPT_TOTAL_TIMEOUT";

    /**
     * Socket idle timeout in milliseconds when processing a database command.

     * If OPT_SOCKET_TIMEOUT is not zero and the socket has been idle for at least OPT_SOCKET_TIMEOUT,
     * both OPT_MAX_RETRIES and OPT_TOTAL_TIMEOUT are checked.  If OPT_MAX_RETRIES and OPT_TOTAL_TIMEOUT are not
     * exceeded, the transaction is retried.

     * If both OPT_SOCKET_TIMEOUT and OPT_TOTAL_TIMEOUT are non-zero and OPT_SOCKET_TIMEOUT > OPT_TOTAL_TIMEOUT,
     * then OPT_SOCKET_TIMEOUT will be set to OPT_TOTAL_TIMEOUT.  If OPT_SOCKET_TIMEOUT is zero, there will be
     * no socket idle limit.
     */
    public const OPT_SOCKET_TIMEOUT = "OPT_SOCKET_TIMEOUT";

    /**
     * Determine if batch commands to each server are run in parallel threads.
     */
    public const OPT_BATCH_CONCURRENT = "OPT_BATCH_CONCURRENT";

    /**
     * Allow batch to be processed immediately in the server's receiving thread when the server
     * deems it to be appropriate.  If false, the batch will always be processed in separate
     * transaction threads.  This field is only relevant for the new batch index protocol.
     *
     * For batch exists or batch reads of smaller sized records (<= 1K per record), inline
     * processing will be significantly faster on "in memory" namespaces.  The server disables
     * inline processing on disk based namespaces regardless of this policy field.
     *
     * Inline processing can introduce the possibility of unfairness because the server
     * can process the entire batch before moving onto the next command.
     * Default: true
     */
    public const OPT_ALLOW_INLINE = "OPT_ALLOW_INLINE";

    /**
     * Send set name field to server for every key in the batch for batch index protocol.
     * This is only necessary when authentication is enabled and security roles are defined
     * on a per set basis.
     * Default: false
     */
    public const OPT_SEND_SET_NAME = "OPT_SEND_SET_NAME";

    /**
     * Abort the scan if the cluster is not in a stable state. Default false
     */
    public const OPT_FAIL_ON_CLUSTER_CHANGE = "OPT_FAIL_ON_CLUSTER_CHANGE";

    /**
     * Accepts one of the SCAN_PRIORITY_* values.
     *
     * @const OPT_SCAN_PRIORITY The priority of the scan
     */
    public const OPT_SCAN_PRIORITY = "OPT_SCAN_PRIORITY";

    /**
     * The cluster will auto-adjust the priority of the scan.
     * @const SCAN_PRIORITY_AUTO auto-adjust the scan priority (default)
     */
    public const SCAN_PRIORITY_AUTO = "SCAN_PRIORITY_AUTO";

    /**
     * Set the scan as having low priority.
     * @const SCAN_PRIORITY_LOW low priority scan
     */
    public const SCAN_PRIORITY_LOW = "SCAN_PRIORITY_LOW";

    /**
     * Set the scan as having medium priority.
     * @const SCAN_PRIORITY_MEDIUM medium priority scan
     */
    public const SCAN_PRIORITY_MEDIUM = "SCAN_PRIORITY_MEDIUM";

    /**
     * Set the scan as having high priority.
     * @const SCAN_PRIORITY_HIGH high priority scan
     */
    public const SCAN_PRIORITY_HIGH = "SCAN_PRIORITY_HIGH";

    /**
     * Do not return the bins of the records matched by the scan.
     *
     * @const OPT_SCAN_NOBINS boolean value (default: false)
     */
    public const OPT_SCAN_NOBINS = "OPT_SCAN_NOBINS";

    /**
     * Set the scan to run over a given percentage of the possible records.
     *
     * @const OPT_SCAN_PERCENTAGE integer value from 1-100 (default: 100)
     */
    public const OPT_SCAN_PERCENTAGE = "OPT_SCAN_PERCENTAGE";

    /**
     * Scan all the nodes in the cluster concurrently.
     *
     * @const OPT_SCAN_CONCURRENTLY boolean value (default: false)
     */
    public const OPT_SCAN_CONCURRENTLY = "OPT_SCAN_CONCURRENTLY";

    /**
     * Do not return the bins of the records matched by the query.
     *
     * @const OPT_QUERY_NOBINS boolean value (default: false)
     */
    public const OPT_QUERY_NOBINS = "OPT_QUERY_NOBINS";

    /**
     * Revert to the older batch-direct protocol, instead of batch-index.
     *
     * @const USE_BATCH_DIRECT boolean value (default: false)
     */
    public const USE_BATCH_DIRECT = "USE_BATCH_DIRECT";

    /**
     * Set to true to enable durable delete for the operation.
     * Durable deletes are an Enterprise Edition feature
     *
     * @const OPT_POLICY_DURABLE_DELETE boolean value (default: false)
     */
    public const OPT_POLICY_DURABLE_DELETE = "OPT_POLICY_DURABLE_DELETE";

    /**
     * Map policy declaring the ordering of an Aerospike map type
     *
     * @see Aerospike::AS_MAP_UNORDERED
     * @see Aerospike::AS_MAP_KEY_ORDERED
     * @see Aerospike::AS_MAP_KEY_VALUE_ORDERED
     * @const OPT_MAP_ORDER
     */
    public const OPT_MAP_ORDER = "OPT_MAP_ORDER";

    /**
     * The Aerospike map is unordered
     * @const AS_MAP_UNORDERED (default)
     */
    public const AS_MAP_UNORDERED = "AS_MAP_UNORDERED";

    /**
     * The Aerospike map is ordered by key
     * @const AS_MAP_KEY_ORDERED
     */
    public const AS_MAP_KEY_ORDERED = "AS_MAP_KEY_ORDERED";

    /**
     * The Aerospike map is ordered by key and value
     * @const AS_MAP_KEY_VALUE_ORDERED
     */
    public const AS_MAP_KEY_VALUE_ORDERED = "AS_MAP_KEY_VALUE_ORDERED";

    /**
     * Map policy declaring the behavior of map write operations
     * @see Aerospike::AS_MAP_UPDATE
     * @see Aerospike::AS_MAP_UPDATE_ONLY
     * @see Aerospike::AS_MAP_CREATE_ONLY
     * @const OPT_MAP_WRITE_MODE
     */
    public const OPT_MAP_WRITE_MODE = "OPT_MAP_WRITE_MODE";

    /**
     * @const AS_MAP_UPDATE (default)
     */
    public const AS_MAP_UPDATE = "AS_MAP_UPDATE";

    /**
     * @const AS_MAP_UPDATE_ONLY
     */
    public const AS_MAP_UPDATE_ONLY = "AS_MAP_UPDATE_ONLY";

    /**
     * @const AS_MAP_CREATE_ONLY
     */
    public const AS_MAP_CREATE_ONLY = "AS_MAP_CREATE_ONLY";

    /**
     * Map policy flags declaring the behavior of map write operations
     * @see Aerospike::AS_MAP_WRITE_DEFAULT
     * @see Aerospike::AS_MAP_WRITE_CREATE_ONLY
     * @see Aerospike::AS_MAP_WRITE_UPDATE_ONLY
     * @see Aerospike::AS_MAP_WRITE_NO_FAIL
     * @see Aerospike::AS_MAP_WRITE_PARTIAL
     * @const OPT_MAP_WRITE_FLAGS
     */
    public const OPT_MAP_WRITE_FLAGS = "OPT_MAP_WRITE_FLAGS";

    /**
     * Default. Allow create or update.
     * @const AS_MAP_WRITE_DEFAULT (default)
     */
    public const AS_MAP_WRITE_DEFAULT = "AS_MAP_WRITE_DEFAULT";

    /**
     * If the key already exists, the item will be denied. If the key does not exist, a new item will be created.
     * @const AS_MAP_WRITE_CREATE_ONLY
     */
    public const AS_MAP_WRITE_CREATE_ONLY = "AS_MAP_WRITE_CREATE_ONLY";

    /**
     * If the key already exists, the item will be overwritten. If the key does not exist, the item will be denied.
     * @const AS_MAP_WRITE_UPDATE_ONLY
     */
    public const AS_MAP_WRITE_UPDATE_ONLY = "AS_MAP_WRITE_UPDATE_ONLY";

    /**
     * Do not raise error if a map item is denied due to write flag constraints (always succeed).
     * @const AS_MAP_WRITE_NO_FAIL
     */
    public const AS_MAP_WRITE_NO_FAIL = "AS_MAP_WRITE_NO_FAIL";

    /**
     * Allow other valid map items to be committed if a map item is denied due to write flag constraints.
     * @const AS_MAP_WRITE_PARTIAL
     */
    public const AS_MAP_WRITE_PARTIAL = "AS_MAP_WRITE_PARTIAL";

    /**
     * Do not return a result for the map operation (get and remove operations)
     * @link https://www.aerospike.com/docs/guide/cdt-map.html#map-apis Map Result Types
     * @const MAP_RETURN_NONE
     */
    public const MAP_RETURN_NONE = "AS_MAP_RETURN_NONE";

    /**
     * Return in key index order
     * @link https://www.aerospike.com/docs/guide/cdt-map.html#map-apis Map Result Types
     * @const AS_MAP_RETURN_INDEX
     */
    public const MAP_RETURN_INDEX = "AS_MAP_RETURN_INDEX";

    /**
     * Return in reverse key order
     * @link https://www.aerospike.com/docs/guide/cdt-map.html#map-apis Map Result Types
     * @const MAP_RETURN_REVERSE_INDEX
     */
    public const MAP_RETURN_REVERSE_INDEX = "AS_MAP_RETURN_REVERSE_INDEX";

    /**
     * Return in value order
     * @link https://www.aerospike.com/docs/guide/cdt-map.html#map-apis Map Result Types
     * @const MAP_RETURN_RANK
     */
    public const MAP_RETURN_RANK = "AS_MAP_RETURN_RANK";

    /**
     * Return in reverse value order
     * @link https://www.aerospike.com/docs/guide/cdt-map.html#map-apis Map Result Types
     * @const MAP_RETURN_REVERSE_RANK
     */
    public const MAP_RETURN_REVERSE_RANK = "AS_MAP_RETURN_REVERSE_RANK";

    /**
     * Return count of items selected
     * @link https://www.aerospike.com/docs/guide/cdt-map.html#map-apis Map Result Types
     * @const MAP_RETURN_COUNT
     */
    public const MAP_RETURN_COUNT = "AS_MAP_RETURN_COUNT";

    /**
     * Return key for single key read and key list for range read
     * @link https://www.aerospike.com/docs/guide/cdt-map.html#map-apis Map Result Types
     * @const MAP_RETURN_KEY
     */
    public const MAP_RETURN_KEY = "AS_MAP_RETURN_KEY";

    /**
     * Return value for single key read and value list for range read
     * @link https://www.aerospike.com/docs/guide/cdt-map.html#map-apis Map Result Types
     * @const MAP_RETURN_VALUE
     */
    public const MAP_RETURN_VALUE = "AS_MAP_RETURN_VALUE";

    /**
     * Return key/value items
     * Will be of the form ['key1', 'val1', 'key2', 'val2', 'key3', 'val3]
     * @link https://www.aerospike.com/docs/guide/cdt-map.html#map-apis Map Result Types
     * @const MAP_RETURN_KEY_VALUE
     */
    public const MAP_RETURN_KEY_VALUE = "AS_MAP_RETURN_KEY_VALUE";

    /**
     * @const LOG_LEVEL_OFF
     */
    public const LOG_LEVEL_OFF = "LOG_LEVEL_OFF";

    /**
     * @const LOG_LEVEL_ERROR
     */
    public const LOG_LEVEL_ERROR = "LOG_LEVEL_ERROR";

    /**
     * @const LOG_LEVEL_WARN
     */
    public const LOG_LEVEL_WARN = "LOG_LEVEL_WARN";

    /**
     * @const LOG_LEVEL_INFO
     */
    public const LOG_LEVEL_INFO = "LOG_LEVEL_INFO";

    /**
     * @const LOG_LEVEL_DEBUG
     */
    public const LOG_LEVEL_DEBUG = "LOG_LEVEL_DEBUG";

    /**
     * @const LOG_LEVEL_TRACE
     */
    public const LOG_LEVEL_TRACE = "LOG_LEVEL_TRACE";

    /**
     * Aerospike Status Codes
     *
     * Each Aerospike API method invocation returns a status code from the
     * server.
     *
     * The status codes map to the
     * {@link https://github.com/aerospike/aerospike-client-c/blob/master/src/include/aerospike/as_status.h status codes}
     * of the C client.
     *
     * @const OK Success
     */
    public const OK = "AEROSPIKE_OK";

    // -10 - -1 - Client Errors

    /**
     * Synchronous connection error
     * @const ERR_CONNECTION
     */
    public const ERR_CONNECTION = "AEROSPIKE_ERR_CONNECTION";

    /**
     * Node invalid or could not be found
     * @const ERR_TLS_ERROR
     */
    public const ERR_TLS_ERROR = "AEROSPIKE_ERR_TLS";

    /**
     * Node invalid or could not be found
     * @const ERR_INVALID_NODE
     */
    public const ERR_INVALID_NODE = "AEROSPIKE_ERR_INVALID_NODE";

    /**
     * Client hit the max asynchronous connections
     * @const ERR_NO_MORE_CONNECTIONS
     */
    public const ERR_NO_MORE_CONNECTIONS = "AEROSPIKE_ERR_NO_MORE_CONNECTIONS";

    /**
     * Asynchronous connection error
     * @const ERR_ASYNC_CONNECTION
     */
    public const ERR_ASYNC_CONNECTION = "AEROSPIKE_ERR_ASYNC_CONNECTION";

    /**
     * Query or scan was aborted in user's callback
     * @const ERR_CLIENT_ABORT
     */
    public const ERR_CLIENT_ABORT = "AEROSPIKE_ERR_CLIENT_ABORT";

    /**
     * Host name could not be found in DNS lookup
     * @const ERR_INVALID_HOST
     */
    public const ERR_INVALID_HOST = "AEROSPIKE_ERR_INVALID_HOST";

    /**
     * Invalid client API parameter
     * @const ERR_PARAM
     */
    public const ERR_PARAM = "AEROSPIKE_ERR_PARAM";

    /**
     * Generic client API usage error
     * @const ERR_CLIENT
     */
    public const ERR_CLIENT = "AEROSPIKE_ERR_CLIENT";

    // 1-49 - Basic Server Errors

    /**
     * Generic error returned by server
     * @const ERR_SERVER
     */
    public const ERR_SERVER = "AEROSPIKE_ERR_SERVER";

    /**
     * No record is found with the specified namespace/set/key combination.
     * May be returned by a read, or a write with OPT_POLICY_EXISTS
     * set to POLICY_EXISTS_UPDATE
     * @const ERR_RECORD_NOT_FOUND
     */
    public const ERR_RECORD_NOT_FOUND = "AEROSPIKE_ERR_RECORD_NOT_FOUND";

    /**
     * Generation of record does not satisfy the OPT_POLICY_GEN write policy
     * @const ERR_RECORD_GENERATION
     */
    public const ERR_RECORD_GENERATION = "AEROSPIKE_ERR_RECORD_GENERATION";

    /**
     * Illegal parameter sent from client. Check client parameters and verify
     * each is supported by current server version
     * @const ERR_REQUEST_INVALID
     */
    public const ERR_REQUEST_INVALID = "AEROSPIKE_ERR_REQUEST_INVALID";

    /**
     * The operation cannot be applied to the current bin on the server
     * @const ERR_OP_NOT_APPLICABLE
     */
    public const ERR_OP_NOT_APPLICABLE = "AEROSPIKE_ERR_OP_NOT_APPLICABLE";

    /**
     * Record already exists. May be returned by a write with the
     * OPT_POLICY_EXISTS write policy set to POLICY_EXISTS_CREATE
     * @const ERR_RECORD_EXISTS
     */
    public const ERR_RECORD_EXISTS = "AEROSPIKE_ERR_RECORD_EXISTS";

    /**
     * (future) For future write requests which specify 'BIN_CREATE_ONLY',
     * request failed because one of the bins in the write already exists
     * @const ERR_BIN_EXISTS
     */
    public const ERR_BIN_EXISTS = "AEROSPIKE_ERR_BIN_EXISTS";

    /**
     * On scan requests, the scan terminates because cluster is in migration.
     * Only occur when client requested 'fail_on_cluster_change' policy on scan
     * @const ERR_CLUSTER_CHANGE
     */
    public const ERR_CLUSTER_CHANGE = "AEROSPIKE_ERR_CLUSTER_CHANGE";

    /**
     * Occurs when stop_writes is true (either memory - stop-writes-pct -
     * or disk - min-avail-pct). Can also occur if memory cannot be allocated
     * anymore (but stop_writes should in general hit first). Namespace will no
     * longer be able to accept write requests
     * @const ERR_SERVER_FULL
     */
    public const ERR_SERVER_FULL = "AEROSPIKE_ERR_SERVER_FULL";

    /**
     * Request was not completed during the allocated time, thus aborted
     * @const ERR_TIMEOUT
     */
    public const ERR_TIMEOUT = "AEROSPIKE_ERR_TIMEOUT";

    /**
     * Write request is rejected because XDR is not running.
     * Only occur when XDR configuration xdr-stop-writes-noxdr is on
     * @const ERR_NO_XDR
     */
    #[Deprecated("Will be reused as ERR_ALWAYS_FORBIDDEN")]
    public const ERR_ALWAYS_FORBIDDEN = "AEROSPIKE_ERR_ALWAYS_FORBIDDEN";

    /**
     * Server is not accepting requests.
     * Occur during single node on a quick restart to join existing cluster
     * @const ERR_CLUSTER
     */
    public const ERR_CLUSTER = "AEROSPIKE_ERR_CLUSTER";

    /**
     * Operation is not allowed due to data type or namespace configuration incompatibility.
     * For example, append to a float data type, or insert a non-integer when
     * namespace is configured as data-in-index
     * @const ERR_BIN_INCOMPATIBLE_TYPE
     */
    public const ERR_BIN_INCOMPATIBLE_TYPE = "AEROSPIKE_ERR_BIN_INCOMPATIBLE_TYPE";

    /**
     * Attempt to write a record whose size is bigger than the configured write-block-size
     * @const ERR_RECORD_TOO_BIG
     */
    public const ERR_RECORD_TOO_BIG = "AEROSPIKE_ERR_RECORD_TOO_BIG";

    /**
     * Too many concurrent operations (> transaction-pending-limit) on the same record.
     * A "hot-key" situation
     * @const ERR_RECORD_BUSY
     */
    public const ERR_RECORD_BUSY = "AEROSPIKE_ERR_RECORD_BUSY";

    /**
     * Scan aborted by user on server
     * @const ERR_SCAN_ABORTED
     */
    public const ERR_SCAN_ABORTED = "AEROSPIKE_ERR_SCAN_ABORTED";

    /**
     * The client is trying to use a feature that does not yet exist in the
     * version of the server node it is talking to
     * @const ERR_UNSUPPORTED_FEATURE
     */
    public const ERR_UNSUPPORTED_FEATURE = "AEROSPIKE_ERR_UNSUPPORTED_FEATURE";

    /**
     * (future) For future write requests which specify 'REPLACE_ONLY',
     * request fail because specified bin name does not exist in record
     * @const ERR_BIN_NOT_FOUND
     */
    public const ERR_BIN_NOT_FOUND = "AEROSPIKE_ERR_BIN_NOT_FOUND";

    /**
     * Write request is rejected because one or more storage devices of the node are not keeping up
     * @const ERR_DEVICE_OVERLOAD
     */
    public const ERR_DEVICE_OVERLOAD = "AEROSPIKE_ERR_DEVICE_OVERLOAD";

    /**
     * For update request on records which has key stored, the incoming key does not match
     * the existing stored key. This indicates a RIPEMD160 key collision has happend (report as a bug)
     * @const ERR_RECORD_KEY_MISMATCH
     */
    public const ERR_RECORD_KEY_MISMATCH = "AEROSPIKE_ERR_RECORD_KEY_MISMATCH";

    /**
     * Namespace in request not found on server
     * @const ERR_NAMESPACE_NOT_FOUND
     */
    public const ERR_NAMESPACE_NOT_FOUND = "AEROSPIKE_ERR_NAMESPACE_NOT_FOUND";

    /**
     * Bin name length greater than 14 characters, or maximum number of unique bin names are exceeded
     * @const ERR_BIN_NAME
     */
    public const ERR_BIN_NAME = "AEROSPIKE_ERR_BIN_NAME";

    /**
     * Operation not allowed at this time.
     * For writes, the set is in the middle of being deleted, or the set's stop-write is reached;
     * For scan, too many concurrent scan jobs (> scan-max-active);
     * For XDR-ed cluster, fail writes which are not replicated from another datacenter
     * @const ERR_FAIL_FORBIDDEN
     */
    public const ERR_FAIL_FORBIDDEN = "AEROSPIKE_ERR_FORBIDDEN";

    /**
     * Target was not found for operations that requires a target to be found
     * @const ERR_FAIL_ELEMENT_NOT_FOUND
     */
    public const ERR_FAIL_ELEMENT_NOT_FOUND = "AEROSPIKE_ERR_FAIL_NOT_FOUND";

    /**
     * Target already exist for operations that requires the target to not exist
     * @const ERR_FAIL_ELEMENT_EXISTS
     */
    public const ERR_FAIL_ELEMENT_EXISTS = "AEROSPIKE_ERR_FAIL_ELEMENT_EXISTS";

    // 50-89 - Security Specific Errors

    /**
     * Security functionality not supported by connected server
     * @const ERR_SECURITY_NOT_SUPPORTED
     */
    public const ERR_SECURITY_NOT_SUPPORTED = "AEROSPIKE_ERR_SECURITY_NOT_SUPPORTED";

    /**
     * Security functionality not enabled by connected server
     * @const ERR_SECURITY_NOT_ENABLED
     */
    public const ERR_SECURITY_NOT_ENABLED = "AEROSPIKE_ERR_SECURITY_NOT_ENABLED";

    /**
     * Security scheme not supported
     * @const ERR_SECURITY_SCHEME_NOT_SUPPORTED
     */
    public const ERR_SECURITY_SCHEME_NOT_SUPPORTED = "AEROSPIKE_ERR_SECURITY_SCHEME_NOT_SUPPORTED";

    /**
     * Unrecognized security command
     * @const ERR_INVALID_COMMAND
     */
    public const ERR_INVALID_COMMAND = "AEROSPIKE_ERR_INVALID_COMMAND";

    /**
     * Field is not valid
     * @const ERR_INVALID_FIELD
     */
    public const ERR_INVALID_FIELD = "AEROSPIKE_ERR_INVALID_FIELD";

    /**
     * Security protocol not followed
     * @const ERR_ILLEGAL_STATE
     */
    public const ERR_ILLEGAL_STATE = "AEROSPIKE_ERR_ILLEGAL_STATE";

    /**
     * No user supplied or unknown user
     * @const ERR_INVALID_USER
     */
    public const ERR_INVALID_USER = "AEROSPIKE_ERR_INVALID_USER";

    /**
     * User already exists
     * @const ERR_USER_ALREADY_EXISTS
     */
    public const ERR_USER_ALREADY_EXISTS = "AEROSPIKE_ERR_USER_ALREADY_EXISTS";

    /**
     * Password does not exists or not recognized
     * @const ERR_INVALID_PASSWORD
     */
    public const ERR_INVALID_PASSWORD = "AEROSPIKE_ERR_INVALID_PASSWORD";

    /**
     * Expired password
     * @const ERR_EXPIRED_PASSWORD
     */
    public const ERR_EXPIRED_PASSWORD = "AEROSPIKE_ERR_EXPIRED_PASSWORD";

    /**
     * Forbidden password (e.g. recently used)
     * @const ERR_FORBIDDEN_PASSWORD
     */
    public const ERR_FORBIDDEN_PASSWORD = "AEROSPIKE_ERR_FORBIDDEN_PASSWORD";

    /**
     * Invalid credential or credential does not exist
     * @const ERR_INVALID_CREDENTIAL
     */
    public const ERR_INVALID_CREDENTIAL = "AEROSPIKE_ERR_INVALID_CREDENTIAL";

    /**
     * No role(s) or unknown role(s)
     * @const ERR_INVALID_ROLE
     */
    public const ERR_INVALID_ROLE = "AEROSPIKE_ERR_INVALID_ROLE";

    /**
     * Privilege is invalid
     * @const ERR_INVALID_PRIVILEGE
     */
    public const ERR_INVALID_PRIVILEGE = "AEROSPIKE_ERR_INVALID_PRIVILEGE";

    /**
     * User must be authenticated before performing database operations
     * @const ERR_NOT_AUTHENTICATED
     */
    public const ERR_NOT_AUTHENTICATED = "AEROSPIKE_ERR_NOT_AUTHENTICATED";

    /**
     * User does not possess the required role to perform the database operation
     * @const ERR_ROLE_VIOLATION
     */
    public const ERR_ROLE_VIOLATION = "AEROSPIKE_ERR_ROLE_VIOLATION";

    /**
     * Role already exists
     * @const ERR_ROLE_ALREADY_EXISTS
     */
    public const ERR_ROLE_ALREADY_EXISTS = "AEROSPIKE_ERR_ROLE_ALREADY_EXISTS";

    // 100-109 - UDF Specific Errors
    //
    /**
     * A user defined function failed to execute
     * @const ERR_UDF
     */
    public const ERR_UDF = "AEROSPIKE_ERR_UDF";

    /**
     * The UDF does not exist
     * @const ERR_UDF_NOT_FOUND
     */
    public const ERR_UDF_NOT_FOUND = "AEROSPIKE_ERR_UDF_NOT_FOUND";

    /**
     * The LUA file does not exist
     * @const ERR_LUA_FILE_NOT_FOUND
     */
    public const ERR_LUA_FILE_NOT_FOUND = "AEROSPIKE_ERR_LUA_FILE_NOT_FOUND";

    // 150-159 - Batch Specific Errors

    /**
     * Batch functionality has been disabled by configuring the batch-index-thread=0
     * @const ERR_BATCH_DISABLED
     */
    public const ERR_BATCH_DISABLED = "AEROSPIKE_ERR_BATCH_DISABLED";

    /**
     * Batch max requests has been exceeded
     * @const ERR_BATCH_MAX_REQUESTS_EXCEEDED
     */
    public const ERR_BATCH_MAX_REQUESTS_EXCEEDED = "AEROSPIKE_ERR_BATCH_MAX_REQUESTS_EXCEEDED";

    /**
     * All batch queues are full
     * @const ERR_BATCH_QUEUES_FULL
     */
    public const ERR_BATCH_QUEUES_FULL = "AEROSPIKE_ERR_BATCH_QUEUES_FULL";

    // 160-169 - Geo Specific Errors

    /**
     * GeoJSON is malformed or not supported
     * @const ERR_GEO_INVALID_GEOJSON
     */
    public const ERR_GEO_INVALID_GEOJSON = "AEROSPIKE_ERR_GEO_INVALID_GEOJSON";

    // 200-219 - Secondary Index Specific Errors

    /**
     * Secondary index already exists
     * @const ERR_INDEX_FOUND
     * Accepts one of the POLICY_KEY_* values.
     *
     * {@link https://www.aerospike.com/docs/client/php/usage/kvs/record-structure.html Records}
     * are uniquely identified by their digest, and can optionally store the value of their primary key
     * (their unique ID in the application).
     * @const OPT_POLICY_KEY Key storage policy option (digest-only or send key)
     */
    public const ERR_INDEX_FOUND = "AEROSPIKE_ERR_INDEX_FOUND";

    /**
     * Secondary index does not exist
     * @const ERR_INDEX_NOT_FOUND
     */
    public const ERR_INDEX_NOT_FOUND = "AEROSPIKE_ERR_INDEX_NOT_FOUND";

    /**
     * Secondary index memory space exceeded
     * @const ERR_INDEX_OOM
     */
    public const ERR_INDEX_OOM = "AEROSPIKE_ERR_INDEX_OOM";

    /**
     * Secondary index not available for query. Occurs when indexing creation has not finished
     * @const ERR_INDEX_NOT_READABLE
     */
    public const ERR_INDEX_NOT_READABLE = "AEROSPIKE_ERR_INDEX_NOT_READABLE";

    /**
     * Generic secondary index error
     * @const ERR_INDEX
     */
    public const ERR_INDEX = "AEROSPIKE_ERR_INDEX";

    /**
     * Index name maximun length exceeded
     * @const ERR_INDEX_NAME_MAXLEN
     */
    public const ERR_INDEX_NAME_MAXLEN = "AEROSPIKE_ERR_INDEX_NAME_MAXLEN";

    /**
     * Maximum number of indicies exceeded
     * @const ERR_INDEX_MAXCOUNT
     */
    public const ERR_INDEX_MAXCOUNT = "AEROSPIKE_ERR_INDEX_MAXCOUNT";

    /**
     * Secondary index query aborted
     * @const ERR_QUERY_ABORTED
     */
    public const ERR_QUERY_ABORTED = "AEROSPIKE_ERR_QUERY_ABORTED";

    /**
     * Secondary index queue full
     * @const ERR_QUERY_QUEUE_FULL
     */
    public const ERR_QUERY_QUEUE_FULL = "AEROSPIKE_ERR_QUERY_QUEUE_FULL";

    /**
     * Secondary index query timed out on server
     * @const ERR_QUERY_TIMEOUT
     */
    public const ERR_QUERY_TIMEOUT = "AEROSPIKE_ERR_QUERY_TIMEOUT";

    /**
     * Generic query error
     * @const ERR_QUERY
     */
    public const ERR_QUERY = "AEROSPIKE_ERR_QUERY";

    /**
     * write operator for the operate() method
     * @const OPERATOR_WRITE
     */
    public const OPERATOR_WRITE = "OPERATOR_WRITE";

    /**
     * read operator for the operate() method
     * @const OPERATOR_READ
     */
    public const OPERATOR_READ = "OPERATOR_READ";

    /**
     * increment operator for the operate() method
     * @const OPERATOR_INCR
     */
    public const OPERATOR_INCR = "OPERATOR_INCR";

    /**
     * prepend operator for the operate() method
     * @const OPERATOR_PREPEND
     */
    public const OPERATOR_PREPEND = "OPERATOR_PREPEND";

    /**
     * append operator for the operate() method
     * @const OPERATOR_APPEND
     */
    public const OPERATOR_APPEND = "OPERATOR_APPEND";

    /**
     * touch operator for the operate() method
     * @const OPERATOR_TOUCH
     */
    public const OPERATOR_TOUCH = "OPERATOR_TOUCH";

    /**
     * delete operator for the operate() method
     * @const OPERATOR_DELETE
     */
    public const OPERATOR_DELETE = "OPERATOR_DELETE";

    // List operation constants

    /**
     * list-append operator for the operate() method
     * @const OP_LIST_APPEND
     */
    public const OP_LIST_APPEND = "OP_LIST_APPEND";

    /**
     * list-merge operator for the operate() method
     * @const OP_LIST_MERGE
     */
    public const OP_LIST_MERGE = "OP_LIST_MERGE";

    /**
     * list-insert operator for the operate() method
     * @const OP_LIST_INSERT
     */
    public const OP_LIST_INSERT = "OP_LIST_INSERT";

    /**
     * list-insert-items operator for the operate() method
     * @const OP_LIST_INSERT_ITEMS
     */
    public const OP_LIST_INSERT_ITEMS = "OP_LIST_INSERT_ITEMS";

    /**
     * list-pop operator for the operate() method
     * @const OP_LIST_POP
     */
    public const OP_LIST_POP = "OP_LIST_POP";

    /**
     * list-pop-range operator for the operate() method
     * @const OP_LIST_POP_RANGE
     */
    public const OP_LIST_POP_RANGE = "OP_LIST_POP_RANGE";

    /**
     * list-remove operator for the operate() method
     * @const OP_LIST_REMOVE
     */
    public const OP_LIST_REMOVE = "OP_LIST_REMOVE";

    /**
     * list-remove-range operator for the operate() method
     * @const OP_LIST_REMOVE_RANGE
     */
    public const OP_LIST_REMOVE_RANGE = "OP_LIST_REMOVE_RANGE";

    /**
     * list-clear operator for the operate() method
     * @const OP_LIST_CLEAR
     */
    public const OP_LIST_CLEAR = "OP_LIST_CLEAR";

    /**
     * list-set operator for the operate() method
     * @const OP_LIST_SET
     */
    public const OP_LIST_SET = "OP_LIST_SET";

    /**
     * list-get operator for the operate() method
     * @const OP_LIST_GET
     */
    public const OP_LIST_GET = "OP_LIST_GET";

    /**
     * list-get-range operator for the operate() method
     * @const OP_LIST_GET_RANGE
     */
    public const OP_LIST_GET_RANGE = "OP_LIST_GET_RANGE";

    /**
     * list-trim operator for the operate() method
     * @const OP_LIST_TRIM
     */
    public const OP_LIST_TRIM = "OP_LIST_TRIM";

    /**
     * list-size operator for the operate() method
     * @const OP_LIST_SIZE
     */
    public const OP_LIST_SIZE = "OP_LIST_SIZE";

    // Map operation constants

    /**
     * map-size operator for the operate() method
     * @const OP_MAP_SIZE
     */
    public const OP_MAP_SIZE = "OP_MAP_SIZE";

    /**
     * map-size operator for the operate() method
     * @const OP_MAP_CLEAR
     */
    public const OP_MAP_CLEAR = "OP_MAP_CLEAR";

    /**
     * map-set-policy operator for the operate() method
     * @const OP_MAP_SET_POLICY
     */
    public const OP_MAP_SET_POLICY = "OP_MAP_SET_POLICY";

    /**
     * map-get-by-key operator for the operate() method
     * @const OP_MAP_GET_BY_KEY
     */
    public const OP_MAP_GET_BY_KEY = "OP_MAP_GET_BY_KEY";

    /**
     * map-get-by-key-range operator for the operate() method
     * @const OP_MAP_GET_BY_KEY_RANGE
     */
    public const OP_MAP_GET_BY_KEY_RANGE = "OP_MAP_GET_BY_KEY_RANGE";

    /**
     * map-get-by-value operator for the operate() method
     * @const OP_MAP_GET_BY_VALUE
     */
    public const OP_MAP_GET_BY_VALUE = "OP_MAP_GET_BY_VALUE";

    /**
     * map-get-by-value-range operator for the operate() method
     * @const OP_MAP_GET_BY_VALUE_RANGE
     */
    public const OP_MAP_GET_BY_VALUE_RANGE = "OP_MAP_GET_BY_VALUE_RANGE";

    /**
     * map-get-by-index operator for the operate() method
     * @const OP_MAP_GET_BY_INDEX
     */
    public const OP_MAP_GET_BY_INDEX = "OP_MAP_GET_BY_INDEX";

    /**
     * map-get-by-index-range operator for the operate() method
     * @const OP_MAP_GET_BY_INDEX_RANGE
     */
    public const OP_MAP_GET_BY_INDEX_RANGE = "OP_MAP_GET_BY_INDEX_RANGE";

    /**
     * map-get-by-rank operator for the operate() method
     * @const OP_MAP_GET_BY_RANK
     */
    public const OP_MAP_GET_BY_RANK = "OP_MAP_GET_BY_RANK";

    /**
     * map-get-by-rank-range operator for the operate() method
     * @const OP_MAP_GET_BY_RANK_RANGE
     */
    public const OP_MAP_GET_BY_RANK_RANGE = "OP_MAP_GET_BY_RANK_RANGE";

    /**
     * map-put  operator for the operate() method
     * @const OP_MAP_PUT
     */
    public const OP_MAP_PUT = "OP_MAP_PUT";

    /**
     * map-put-items operator for the operate() method
     * @const OP_MAP_PUT_ITEMS
     */
    public const OP_MAP_PUT_ITEMS = "OP_MAP_PUT_ITEMS";

    /**
     * map-increment operator for the operate() method
     * @const OP_MAP_INCREMENT
     */
    public const OP_MAP_INCREMENT = "OP_MAP_INCREMENT";

    /**
     * map-decrement operator for the operate() method
     * @const OP_MAP_DECREMENT
     */
    public const OP_MAP_DECREMENT = "OP_MAP_DECREMENT";

    /**
     * map-remove-by-key operator for the operate() method
     * @const OP_MAP_REMOVE_BY_KEY
     */
    public const OP_MAP_REMOVE_BY_KEY = "OP_MAP_REMOVE_BY_KEY";

    /**
     * map-remove-by-key-list operator for the operate() method
     * @const OP_MAP_REMOVE_BY_KEY_LIST
     */
    public const OP_MAP_REMOVE_BY_KEY_LIST = "OP_MAP_REMOVE_BY_KEY_LIST";

    /**
     * map-remove-by-key-range key operator for the operate() method
     * @const OP_MAP_REMOVE_BY_KEY_RANGE
     */
    public const OP_MAP_REMOVE_BY_KEY_RANGE = "OP_MAP_REMOVE_BY_KEY_RANGE";

    /**
     * map-remove-by-value operator for the operate() method
     * @const OP_MAP_REMOVE_BY_VALUE
     */
    public const OP_MAP_REMOVE_BY_VALUE = "OP_MAP_REMOVE_BY_VALUE";

    /**
     * map-remove-by-value operator for the operate() method
     * @const OP_MAP_REMOVE_BY_VALUE_RANGE
     */
    public const OP_MAP_REMOVE_BY_VALUE_RANGE = "OP_MAP_REMOVE_BY_VALUE_RANGE";

    /**
     * map-remove-by-value-list operator for the operate() method
     * @const OP_MAP_REMOVE_BY_VALUE_LIST
     */
    public const OP_MAP_REMOVE_BY_VALUE_LIST = "OP_MAP_REMOVE_BY_VALUE_LIST";

    /**
     * map-remove-by-index operator for the operate() method
     * @const OP_MAP_REMOVE_BY_INDEX
     */
    public const OP_MAP_REMOVE_BY_INDEX = "OP_MAP_REMOVE_BY_INDEX";

    /**
     * map-remove-by-index-range operator for the operate() method
     * @const OP_MAP_REMOVE_BY_INDEX_RANGE
     */
    public const OP_MAP_REMOVE_BY_INDEX_RANGE = "OP_MAP_REMOVE_BY_INDEX_RANGE";

    /**
     * map-remove-by-rank operator for the operate() method
     * @const OP_MAP_REMOVE_BY_RANK
     */
    public const OP_MAP_REMOVE_BY_RANK = "OP_MAP_REMOVE_BY_RANK";

    /**
     * map-remove-by-rank-range operator for the operate() method
     * @const OP_MAP_REMOVE_BY_RANK_RANGE
     */
    public const OP_MAP_REMOVE_BY_RANK_RANGE = "OP_MAP_REMOVE_BY_RANK_RANGE";

    // Query Predicate Operators

    /**
     * predicate operator for equality check of scalar integer or string value
     * @const OP_EQ
     * @see Aerospike::predicateEquals
     */
    public const OP_EQ = "=";

    /**
     * predicate operator matching whether an integer falls between a range of integer values
     * @const OP_BETWEEN
     * @see Aerospike::predicateBetween
     */
    public const OP_BETWEEN = "BETWEEN";

    /**
     * predicate operator for a whether a specific value is in an indexed list, mapkeys, or mapvalues
     * @const OP_CONTAINS
     * @see Aerospike::predicateContains
     */
    public const OP_CONTAINS = "CONTAINS";

    /**
     * predicate operator for whether an indexed list, mapkeys, or mapvalues has an integer value within a specified range
     * @const OP_RANGE
     * @see Aerospike::predicateRange
     */
    public const OP_RANGE = "RANGE";

    /**
     * geospatial predicate operator for points within a specified region
     * @const OP_GEOWITHINREGION
     */
    public const OP_GEOWITHINREGION = "GEOWITHIN";

    /**
     * geospatial predicate operator for regons containing a sepcified point
     * @const OP_GEOWITHINREGION
     */
    public const OP_GEOCONTAINSPOINT = "GEOCONTAINS";

    /**
     * Scan status is undefined
     */
    #[Deprecated('use JOB_STATUS_UNDEF along with jobInfo()')]
    public const SCAN_STATUS_UNDEF = "SCAN_STATUS_UNDEF";

    /**
     * Scan is currently running
     */
    #[Deprecated('use JOB_STATUS_INPROGRESS along with jobInfo()')]
    public const SCAN_STATUS_INPROGRESS = "SCAN_STATUS_INPROGRESS";

    /**
     * Scan completed successfully
     */
    #[Deprecated]
    public const SCAN_STATUS_ABORTED = "SCAN_STATUS_ABORTED";

    /**
     * Scan was aborted due to failure or the user
     */
    #[Deprecated('use JOB_STATUS_COMPLETED along with jobInfo()')]
    public const SCAN_STATUS_COMPLETED = "SCAN_STATUS_COMPLETED";

    // Status values returned by jobInfo()

    /**
     * Job status is undefined
     */
    public const JOB_STATUS_UNDEF = "JOB_STATUS_UNDEF";

    /**
     * Job is currently running
     */
    public const JOB_STATUS_INPROGRESS = "JOB_STATUS_INPROGRESS";

    /**
     * Job completed successfully
     */
    public const JOB_STATUS_COMPLETED = "JOB_STATUS_COMPLETED";

    // Index (container) types
    /**
     * The bin being indexed should contain scalar values such as string or integer
     * @const INDEX_TYPE_DEFAULT
     */
    public const INDEX_TYPE_DEFAULT = "INDEX_TYPE_DEFAULT";

    /**
     * The bin being indexed should contain a list
     * @const INDEX_TYPE_LIST
     */
    public const INDEX_TYPE_LIST = "INDEX_TYPE_LIST";

    /**
     * The bin being indexed should contain a map. The map keys will be indexed
     * @const INDEX_TYPE_MAPKEYS
     */
    public const INDEX_TYPE_MAPKEYS = "INDEX_TYPE_MAPKEYS";

    /**
     * The bin being indexed should contain a map. The map values will be indexed
     * @const INDEX_TYPE_MAPKEYS
     */
    public const INDEX_TYPE_MAPVALUES = "INDEX_TYPE_MAPVALUES";

    // Data type
    /**
     * If and only if the container type matches, the value should be of type string
     * @const INDEX_STRING
     */
    public const INDEX_STRING = "INDEX_STRING";

    /**
     * If and only if the container type matches, the value should be of type integer
     * @const INDEX_NUMERIC
     */
    public const INDEX_NUMERIC = "INDEX_NUMERIC";

    /**
     * If and only if the container type matches, the value should be GeoJSON
     * @const INDEX_GEO2DSPHERE
     */
    public const INDEX_GEO2DSPHERE = "INDEX_GEO2DSPHERE";

    /**
     * Declare the UDF module's language to be Lua
     * @const UDF_TYPE_LUA
     */
    public const UDF_TYPE_LUA = "UDF_TYPE_LUA";

    // Security role privileges

    /**
     * Privilege to read data
     * @const PRIV_READ
     * @link https://www.aerospike.com/docs/guide/security/access-control.html Access Control
     */
    public const PRIV_READ = "PRIV_READ";

    /**
     * Privilege to read and write data
     * @const PRIV_READ_WRITE
     * @link https://www.aerospike.com/docs/guide/security/access-control.html Access Control
     */
    public const PRIV_READ_WRITE = "PRIV_READ_WRITE";

    /**
     * Privilege to read, write and execute user-defined functions
     * @const PRIV_READ_WRITE_UDF
     * @link https://www.aerospike.com/docs/guide/security/access-control.html Access Control
     */
    public const PRIV_READ_WRITE_UDF = "PRIV_READ_WRITE_UDF";

    /**
     * Privilege to create and assign roles to users
     * @const PRIV_USER_ADMIN
     * @link https://www.aerospike.com/docs/guide/security/access-control.html Access Control
     */
    public const PRIV_USER_ADMIN = "PRIV_USER_ADMIN";

    /**
     * Privilege to manage indexes and UDFs, monitor and abort scan/query jobs, get server config
     * @const PRIV_DATA_ADMIN
     * @link https://www.aerospike.com/docs/guide/security/access-control.html Access Control
     */
    public const PRIV_DATA_ADMIN = "PRIV_DATA_ADMIN"; // can perform data admin functions that do not involve user admin

    /** Privilege to modify dynamic server configs, get config and stats, and all data admin privileges
     * @const PRIV_SYS_ADMIN
     * @link https://www.aerospike.com/docs/guide/security/access-control.html Access Control
     */
    public const PRIV_SYS_ADMIN = "PRIV_SYS_ADMIN"; // can perform sysadmin functions that do not involve user admin

    /*
        // TODO:
        // security methods
        public int createRole ( string $role, array $privileges [, array $options ] )
        public int grantPrivileges ( string $role, array $privileges [, array $options ] )
        public int revokePrivileges ( string $role, array $privileges [, array $options ] )
        public int queryRole ( string $role, array &$privileges [, array $options ] )
        public int queryRoles ( array &$roles [, array $options ] )
        public int dropRole ( string $role [, array $options ] )
        public int createUser ( string $user, string $password, array $roles [, array $options ] )
        public int setPassword ( string $user, string $password [, array $options ] )
        public int changePassword ( string $user, string $password [, array $options ] )
        public int grantRoles ( string $user, array $roles [, array $options ] )
        public int revokeRoles ( string $user, array $roles [, array $options ] )
        public int queryUser ( string $user, array &$roles [, array $options ] )
        public int queryUsers ( array &$roles [, array $options ] )
        public int dropUser ( string $user [, array $options ] )

     */
}
