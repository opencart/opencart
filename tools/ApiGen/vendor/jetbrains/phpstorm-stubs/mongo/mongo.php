<?php
/*
 * Mongo extension stubs
 * Gathered from https://secure.php.net/manual/en/book.mongo.php
 * Maintainer: Alexander Makarov, sam@rmcreative.ru, max@upgradeyour.com
 *
 * MongoClient: https://github.com/djsipe/PHP-Stubs
 */

use JetBrains\PhpStorm\Deprecated;

/**
 * A connection between PHP and MongoDB. This class is used to create and manage connections
 * See MongoClient::__construct() and the section on connecting for more information about creating connections.
 * @link https://secure.php.net/manual/en/class.mongoclient.php
 */
class MongoClient
{
    public const VERSION = '3.x';
    public const DEFAULT_HOST = "localhost";
    public const DEFAULT_PORT = 27017;
    public const RP_PRIMARY = "primary";
    public const RP_PRIMARY_PREFERRED = "primaryPreferred";
    public const RP_SECONDARY = "secondary";
    public const RP_SECONDARY_PREFERRED = "secondaryPreferred";
    public const RP_NEAREST = "nearest";

    /* Properties */
    public $connected = false;
    public $status = null;
    protected $server = null;
    protected $persistent = null;

    /* Methods */
    /**
     * Creates a new database connection object
     * @link https://php.net/manual/en/mongo.construct.php
     * @param string $server [optional] The server name.
     * @param array $options [optional] An array of options for the connection. Currently
     *        available options include: "connect" If the constructor should connect before
     *        returning. Default is true. "timeout" For how long the driver should try to
     *        connect to the database (in milliseconds). "replicaSet" The name of the replica
     *        set to connect to. If this is given, the master will be determined by using the
     *        ismaster database command on the seeds, so the driver may end up connecting to a
     *        server that was not even listed. See the replica set example below for details.
     *        "username" The username can be specified here, instead of including it in the
     *        host list. This is especially useful if a username has a ":" in it. This
     *        overrides a username set in the host list. "password" The password can be
     *        specified here, instead of including it in the host list. This is especially
     *        useful if a password has a "@" in it. This overrides a password set in the host
     *        list. "db" The database to authenticate against can be specified here, instead
     *        of including it in the host list. This overrides a database given in the host
     *        list  "fsync" When "fsync" is set, all write operations will block until the database has flushed the changes to disk. This makes the write operations slower, but it guarantees that writes have succeeded and that the operations can be recovered in case of total system failure.
     *        If the MongoDB server has journaling enabled, this option is identical to "journal". If journaling is not enabled, this option ensures that write operations will be synced to database files on disk.
     *        "journal"
     *        When "journal" is set, all write operations will block until the database has flushed the changes to the journal on disk. This makes the write operations slower, but it guarantees that writes have succeeded and that the operations can be recovered in case of total system failure.
     *        Note: If this option is used and journaling is disabled, MongoDB 2.6+ will raise an error and the write will fail; older server versions will simply ignore the option.
     *        "gssapiServiceName"
     *        Sets the » Kerberos service principal. Only applicable when authMechanism=GSSAPI. Defaults to "mongodb".
     *        "password"
     *        The password can be specified here, instead of including it in the host list. This is especially useful if a password has a "@" in it. This overrides a password set in the host list.
     *        "readPreference"
     *        Specifies the read preference type. Read preferences provide you with control from which secondaries data can be read from.
     *        Allowed values are: MongoClient::RP_PRIMARY, MongoClient::RP_PRIMARY_PREFERRED, MongoClient::RP_SECONDARY, MongoClient::RP_SECONDARY_PREFERRED and MongoClient::RP_NEAREST.
     *        See the documentation on read preferences for more information.
     *        "readPreferenceTags"
     *        Specifies the read preference tags as an array of strings. Tags can be used in combination with the readPreference option to further control which secondaries data might be read from.
     *        See the documentation on read preferences for more information.
     *        "replicaSet"
     *        The name of the replica set to connect to. If this is given, the primary will be automatically be determined. This means that the driver may end up connecting to a server that was not even listed. See the replica set example below for details.
     *        "secondaryAcceptableLatencyMS"
     *        When reading from a secondary (using ReadPreferences), do not read from secondaries known to be more then secondaryAcceptableLatencyMS away from us. Defaults to 15
     *        "socketTimeoutMS"
     *        How long a socket operation (read or write) can take before timing out in milliseconds. Defaults to 30000 (30 seconds).
     *        If -1 is specified, socket operations may block indefinitely. This option may also be set on a per-operation basis using MongoCursor::timeout() for queries or the "socketTimeoutMS" option for write methods.
     *        Note: This is a client-side timeout. If a write operation times out, there is no way to know if the server actually handled the write or not, as a MongoCursorTimeoutException will be thrown in lieu of returning a write result.
     *        "ssl"
     *        A boolean to specify whether you want to enable SSL for the connections to MongoDB. Extra options such as certificates can be set with SSL context options.
     *        "username"
     *        The username can be specified here, instead of including it in the host list. This is especially useful if a username has a ":" in it. This overrides a username set in the host list.
     *        "w"
     *        The w option specifies the Write Concern for the driver, which determines how long the driver blocks when writing. The default value is 1.
     *        This option is applicable when connecting to both single servers and replica sets. A positive value controls how many nodes must acknowledge the write instruction before the driver continues. A value of 1 would require the single server or primary (in a replica set) to acknowledge the write operation. A value of 3 would cause the driver to block until the write has been applied to the primary as well as two secondary servers (in a replica set).
     *        A string value is used to control which tag sets are taken into account for write concerns. "majority" is special and ensures that the write operation has been applied to the majority (more than 50%) of the participating nodes.
     *        "wTimeoutMS" This option specifies the time limit, in milliseconds, for write concern acknowledgement. It is only applicable for write operations where "w" is greater than 1, as the timeout pertains to replication. If the write concern is not satisfied within the time limit, a MongoCursorException will be thrown. A value of 0 may be specified to block indefinitely. The default value is 10000 (ten seconds).
     * @param array $driver_options [optional] <p>
     *         An array of options for the MongoDB driver. Options include setting
     *         connection {@link https://php.net/manual/en/mongo.connecting.ssl.php#mongo.connecting.context.ssl context options for SSL}
     *         or {@link https://php.net/manual/en/context.mongodb.php logging callbacks}.
     *         </p><ul>
     *         <li>
     *         <p>
     *         <em>"context"</em>
     *         </p>
     *         <p>
     *         The Stream Context to attach to all new connections. This allows you
     *         for example to configure SSL certificates and are described at
     *         {@link https://php.net/manual/en/context.ssl.php SSL context options}. See the
     *         {@link https://php.net/manual/en/mongo.connecting.ssl.php#mongo.connecting.context.ssl Connecting over SSL} tutorial.
     *         </p>
     *         </li>
     *         </ul>
     * @throws MongoConnectionException
     */
    public function __construct($server = "mongodb://localhost:27017", array $options = ["connect" => true], $driver_options) {}

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * Closes this database connection
     * This method does not need to be called, except in unusual circumstances.
     * The driver will cleanly close the database connection when the Mongo object goes out of scope.
     * @link https://secure.php.net/manual/en/mongoclient.close.php
     * @param bool|string $connection [optional] <p>
     * If connection is not given, or <b>FALSE</b> then connection that would be selected for writes would be closed. In a single-node configuration, that is then the whole connection, but if you are connected to a replica set, close() will only close the connection to the primary server.
     * If connection is <b>TRUE</b> then all connections as known by the connection manager will be closed. This can include connections that are not referenced in the connection string used to create the object that you are calling close on.
     * If connection is a string argument, then it will only close the connection identified by this hash. Hashes are identifiers for a connection and can be obtained by calling {@see MongoClient::getConnections()}.
     * </p>
     * @return bool If the connection was successfully closed.
     */
    public function close($connection) {}

    /**
     * Connects to a database server
     *
     * @link https://secure.php.net/manual/en/mongoclient.connect.php
     *
     * @throws MongoConnectionException
     * @return bool If the connection was successful.
     */
    public function connect() {}

    /**
     * Drops a database
     *
     * @link https://secure.php.net/manual/en/mongoclient.dropdb.php
     * @param mixed $db The database to drop. Can be a MongoDB object or the name of the database.
     * @return array The database response.
     */
    #[Deprecated(replacement: "%class%->drop()")]
    public function dropDB($db) {}

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * Gets a database
     * @link https://php.net/manual/en/mongoclient.get.php
     * @param string $dbname The database name.
     * @return MongoDB The database name.
     */
    public function __get($dbname) {}

    /**
     * Get connections
     * Returns an array of all open connections, and information about each of the servers
     * @return array
     */
    public static function getConnections() {}

    /**
     * Get hosts
     * This method is only useful with a connection to a replica set. It returns the status of all of the hosts in the
     * set. Without a replica set, it will just return an array with one element containing the host that you are
     * connected to.
     * @return array
     */
    public function getHosts() {}

    /**
     * Get read preference
     * Get the read preference for this connection
     * @return array
     */
    public function getReadPreference() {}

    /**
     * (PECL mongo &gt;= 1.5.0)<br/>
     * Get the write concern for this connection
     * @return array <p>This function returns an array describing the write concern.
     * The array contains the values w for an integer acknowledgement level or string mode,
     * and wtimeout denoting the maximum number of milliseconds to wait for the server to satisfy the write concern.</p>
     */
    public function getWriteConcern() {}

    /**
     * Kills a specific cursor on the server
     * @link https://secure.php.net/manual/en/mongoclient.killcursor.php
     * @param string $server_hash <p>
     * The server hash that has the cursor. This can be obtained through
     * {@link https://secure.php.net/manual/en/mongocursor.info.php MongoCursor::info()}.
     * </p>
     * @param int|MongoInt64 $id
     * <p>
     * The ID of the cursor to kill. You can either supply an {@link https://secure.php.net/manual/en/language.types.integer.php int}
     * containing the 64 bit cursor ID, or an object of the
     * {@link https://secure.php.net/manual/en/class.mongoint64.php MongoInt64} class. The latter is necessary on 32
     * bit platforms (and Windows).
     * </p>
     */
    public function killCursor($server_hash, $id) {}

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * Lists all of the databases available
     * @link https://php.net/manual/en/mongoclient.listdbs.php
     * @return array Returns an associative array containing three fields. The first field is databases, which in turn contains an array. Each element of the array is an associative array corresponding to a database, giving the database's name, size, and if it's empty. The other two fields are totalSize (in bytes) and ok, which is 1 if this method ran successfully.
     */
    public function listDBs() {}

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * Gets a database collection
     * @link https://secure.php.net/manual/en/mongoclient.selectcollection.php
     * @param string $db The database name.
     * @param string $collection The collection name.
     * @throws Exception Throws Exception if the database or collection name is invalid.
     * @return MongoCollection Returns a new collection object.
     */
    public function selectCollection($db, $collection) {}

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * Gets a database
     * @link https://secure.php.net/manual/en/mongo.selectdb.php
     * @param string $name The database name.
     * @throws InvalidArgumentException
     * @return MongoDB Returns a new db object.
     */
    public function selectDB($name) {}

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * Set read preference
     * @param string $readPreference
     * @param array $tags
     * @return bool
     */
    public function setReadPreference($readPreference, $tags = null) {}

    /**
     * (PECL mongo &gt;= 1.1.0)<br/>
     * Choose a new secondary for slaveOkay reads
     * @link https://secure.php.net/manual/en/mongo.switchslave.php
     * @return string The address of the secondary this connection is using for reads. This may be the same as the previous address as addresses are randomly chosen. It may return only one address if only one secondary (or only the primary) is available.
     * For example, if we had a three member replica set with a primary, secondary, and arbiter this method would always return the address of the secondary. If the secondary became unavailable, this method would always return the address of the primary. If the primary also became unavailable, this method would throw an exception, as an arbiter cannot handle reads.
     * @throws MongoException (error code 15) if it is called on a non-replica-set connection. It will also throw MongoExceptions if it cannot find anyone (primary or secondary) to read from (error code 16).
     */
    public function switchSlave() {}

    /**
     * String representation of this connection
     * @link https://secure.php.net/manual/en/mongoclient.tostring.php
     * @return string Returns hostname and port for this connection.
     */
    public function __toString() {}
}

/**
 * The connection point between MongoDB and PHP.
 * This class is used to initiate a connection and for database server commands.
 * @link https://secure.php.net/manual/en/class.mongo.php
 * Relying on this feature is highly discouraged. Please use MongoClient instead.
 * @see MongoClient
 */
#[Deprecated("This class has been DEPRECATED as of version 1.3.0.")]
class Mongo extends MongoClient
{
    /**
     * (PECL mongo &gt;= 1.2.0)<br/>
     * Get pool size for connection pools
     * @link https://php.net/manual/en/mongo.getpoolsize.php
     * @return int Returns the current pool size.
     * @see MongoPool::getSize()
     */
    #[Deprecated('This feature has been DEPRECATED as of version 1.2.3. Relying on this feature is highly discouraged. Please use MongoPool::getSize() instead.')]
    public function getPoolSize() {}

    /**
     * (PECL mongo &gt;= 1.1.0)<br/>
     * Returns the address being used by this for slaveOkay reads
     * @link https://php.net/manual/en/mongo.getslave.php
     * @return bool <p>The address of the secondary this connection is using for reads.
     * </p>
     * <p>
     * This returns <b>NULL</b> if this is not connected to a replica set or not yet
     * initialized.
     * </p>
     */
    public function getSlave() {}

    /**
     * (PECL mongo &gt;= 1.1.0)<br/>
     * Get slaveOkay setting for this connection
     * @link https://php.net/manual/en/mongo.getslaveokay.php
     * @return bool Returns the value of slaveOkay for this instance.
     */
    public function getSlaveOkay() {}

    /**
     * Connects to paired database server
     * @link https://secure.php.net/manual/en/mongo.pairconnect.php
     * @throws MongoConnectionException
     * @return bool
     */
    #[Deprecated('Pass a string of the form "mongodb://server1,server2" to the constructor instead of using this method.')]
    public function pairConnect() {}

    /**
     * (PECL mongo &gt;= 1.2.0)<br/>
     * Returns information about all connection pools.
     * @link https://php.net/manual/en/mongo.pooldebug.php
     * @return array  Each connection pool has an identifier, which starts with the host. For each pool, this function shows the following fields:
     * <p><b>in use</b></p>
     * <p>The number of connections currently being used by MongoClient instances.
     * in pool
     * The number of connections currently in the pool (not being used).</p>
     * <p><b>remaining</b></p>
     *
     * <p>The number of connections that could be created by this pool. For example, suppose a pool had 5 connections remaining and 3 connections in the pool. We could create 8 new instances of MongoClient before we exhausted this pool (assuming no instances of MongoClient went out of scope, returning their connections to the pool).
     *
     * A negative number means that this pool will spawn unlimited connections.
     *
     * Before a pool is created, you can change the max number of connections by calling Mongo::setPoolSize(). Once a pool is showing up in the output of this function, its size cannot be changed.</p>
     * <p><b>timeout</b></p>
     *
     * <p>The socket timeout for connections in this pool. This is how long connections in this pool will attempt to connect to a server before giving up.</p>
     * @see MongoPool::info()
     */
    #[Deprecated('@deprecated This feature has been DEPRECATED as of version 1.2.3. Relying on this feature is highly discouraged. Please use MongoPool::info() instead.')]
    public function poolDebug() {}

    /**
     * (PECL mongo &gt;= 1.1.0)<br/>
     * Change slaveOkay setting for this connection
     * @link https://php.net/manual/en/mongo.setslaveokay.php
     * @param bool $ok [optional] <p class="para">
     * If reads should be sent to secondary members of a replica set for all
     * possible queries using this {@see MongoClient} instance.
     * </p>
     * @return bool returns the former value of slaveOkay for this instance.
     */
    public function setSlaveOkay($ok) {}

    /**
     *(PECL mongo &gt;= 1.2.0)<br/>
     * Set the size for future connection pools.
     * @link https://php.net/manual/en/mongo.setpoolsize.php
     * @param int $size <p>The max number of connections future pools will be able to create. Negative numbers mean that the pool will spawn an infinite number of connections.</p>
     * @return bool Returns the former value of pool size.
     * @see MongoPool::setSize()
     */
    #[Deprecated('Relying on this feature is highly discouraged. Please use MongoPool::setSize() instead.')]
    public function setPoolSize($size) {}

    /**
     * Creates a persistent connection with a database server
     * @link https://secure.php.net/manual/en/mongo.persistconnect.php
     * @param string $username A username used to identify the connection.
     * @param string $password A password used to identify the connection.
     * @throws MongoConnectionException
     * @return bool If the connection was successful.
     */
    #[Deprecated('Pass array("persist" => $id) to the constructor instead of using this method.')]
    public function persistConnect($username = "", $password = "") {}

    /**
     * Creates a persistent connection with paired database servers
     * @link https://secure.php.net/manual/en/mongo.pairpersistconnect.php
     * @param string $username A username used to identify the connection.
     * @param string $password A password used to identify the connection.
     * @throws MongoConnectionException
     * @return bool If the connection was successful.
     */
    #[Deprecated('Pass "mongodb://server1,server2" and array("persist" => $id) to the constructor instead of using this method.')]
    public function pairPersistConnect($username = "", $password = "") {}

    /**
     * Connects with a database server
     *
     * @link https://secure.php.net/manual/en/mongo.connectutil.php
     * @throws MongoConnectionException
     * @return bool If the connection was successful.
     */
    protected function connectUtil() {}

   /**
    * Check if there was an error on the most recent db operation performed
    * @link https://secure.php.net/manual/en/mongo.lasterror.php
    * @return array|null Returns the error, if there was one, or NULL.
    * @see MongoDB::lastError()
    */
   #[Deprecated('Use MongoDB::lastError() instead.')]
    public function lastError() {}

   /**
    * Checks for the last error thrown during a database operation
    * @link https://secure.php.net/manual/en/mongo.preverror.php
    * @return array Returns the error and the number of operations ago it occurred.
    * @see MongoDB::prevError()
    */
   #[Deprecated('Use MongoDB::prevError() instead.')]
    public function prevError() {}

    /**
     * Clears any flagged errors on the connection
     * @link https://secure.php.net/manual/en/mongo.reseterror.php
     * @return array Returns the database response.
     * @see MongoDB::resetError()
     */
    #[Deprecated('Use MongoDB::resetError() instead.')]
    public function resetError() {}

    /**
     * Creates a database error on the database.
     * @link https://secure.php.net/manual/en/mongo.forceerror.php
     * @return bool The database response.
     * @see MongoDB::forceError()
     */
    #[Deprecated('Use MongoDB::forceError() instead.')]
    public function forceError() {}
}

/**
 * Instances of this class are used to interact with a database.
 * @link https://secure.php.net/manual/en/class.mongodb.php
 */
class MongoDB
{
    /**
     * Profiling is off.
     * @link https://php.net/manual/en/class.mongodb.php#mongodb.constants.profiling-off
     */
    public const PROFILING_OFF = 0;

    /**
     * Profiling is on for slow operations (>100 ms).
     * @link https://php.net/manual/en/class.mongodb.php#mongodb.constants.profiling-slow
     */
    public const PROFILING_SLOW = 1;

    /**
     * Profiling is on for all operations.
     * @link https://php.net/manual/en/class.mongodb.php#mongodb.constants.profiling-on
     */
    public const PROFILING_ON = 2;

    /**
     * @var int
     * <p>
     * The number of servers to replicate a change to before returning success.
     * Inherited by instances of {@link https://php.net/manual/en/class.mongocollection.php MongoCollection} derived
     * from this.  <em>w</em> functionality is only available in
     * version 1.5.1+ of the MongoDB server and 1.0.8+ of the driver.
     * </p>
     * <p>
     * <em>w</em> is used whenever you need to adjust the
     * acknowledgement level
     * ( {@link https://php.net/manual/en/mongocollection.insert.php MongoCollection::insert()},
     * {@link https://php.net/manual/en/mongocollection.update.php MongoCollection::update()},
     * {@link https://php.net/manual/en/mongocollection.remove.php MongoCollection::remove()},
     * {@link https://php.net/manual/en/mongocollection.save.php MongoCollection::save()}, and
     * {@link https://php.net/manual/en/mongocollection.ensureindex.php MongoCollection::ensureIndex()} all support this
     * option). With the default value (1), an acknowledged operation will return once
     * the database server has the operation. If the server goes down before
     * the operation has been replicated to a secondary, it is possible to lose
     * the operation forever. Thus, you can specify <em>w</em> to be
     * higher than one and guarantee that at least one secondary has the
     * operation before it is considered successful.
     * </p>
     * <p>
     * For example, if <em>w</em> is 2, the primary and one secondary
     * must have a record of the operation or the driver will throw a
     * {@link https://php.net/manual/en/class.mongocursorexception.php MongoCursorException}. It is tempting to set
     * <em>w</em> to the total number of secondaries + primary, but
     * then if one secondary is down the operation will fail and an exception
     * will be thrown, so usually <em>w=2</em> is safest (primary and
     * one secondary).
     * </p>
     */
    public $w = 1;

    /**
     * @var int <p>
     * T he number of milliseconds to wait for <em>MongoDB::$w</em>
     * replications to take place.  Inherited by instances of
     * {@link https://secure.php.net/manual/en/class.mongocollection.php MongoCollection} derived from this.
     * <em>w</em> functionality is only available in version 1.5.1+ of
     * the MongoDB server and 1.0.8+ of the driver.
     * </p>
     * <p>
     * Unless <em>wtimeout</em> is set, the server waits forever for
     * replicating to <em>w</em> servers to finish.  The driver
     * defaults to waiting for 10 seconds, you can change this value to alter
     * its behavior.
     * </p>
     */
    public $wtimeout = 10000;

    /**
     * (PECL mongo &gt;= 0.9.0)<br/>
     * Creates a new database
     * This method is not meant to be called directly. The preferred way to create an instance of MongoDB is through {@see Mongo::__get()} or {@see Mongo::selectDB()}.
     * @link https://secure.php.net/manual/en/mongodb.construct.php
     * @param MongoClient $conn Database connection.
     * @param string $name Database name.
     * @throws Exception
     */
    public function __construct($conn, $name) {}

    /**
     * The name of this database
     * @link https://secure.php.net/manual/en/mongodb.--tostring.php
     * @return string Returns this database's name.
     */
    public function __toString() {}

    /**
     * (PECL mongo &gt;= 1.0.2)<br/>
     * Gets a collection
     * @link https://secure.php.net/manual/en/mongodb.get.php
     * @param string $name The name of the collection.
     * @return MongoCollection
     */
    public function __get($name) {}

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * @link https://secure.php.net/manual/en/mongodb.getcollectionnames.php
     * Get all collections from this database
     * @param bool $includeSystemCollections [optional] Include system collections.
     * @return array Returns the names of the all the collections in the database as an
     * {@link https://secure.php.net/manual/en/language.types.array.php array}.
     */
    public function getCollectionNames($includeSystemCollections = false) {}

    /**
     * (PECL mongo &gt;= 0.9.0)<br/>
     * Fetches toolkit for dealing with files stored in this database
     * @link https://secure.php.net/manual/en/mongodb.getgridfs.php
     * @param string $prefix [optional] The prefix for the files and chunks collections.
     * @return MongoGridFS Returns a new gridfs object for this database.
     */
    public function getGridFS($prefix = "fs") {}

    /**
     * (PECL mongo &gt;= 0.9.0)<br/>
     * Gets this database's profiling level
     * @link https://secure.php.net/manual/en/mongodb.getprofilinglevel.php
     * @return int Returns the profiling level.
     */
    public function getProfilingLevel() {}

    /**
     * (PECL mongo &gt;= 1.1.0)<br/>
     * Get slaveOkay setting for this database
     * @link https://secure.php.net/manual/en/mongodb.getslaveokay.php
     * @return bool Returns the value of slaveOkay for this instance.
     */
    public function getSlaveOkay() {}

    /**
     * (PECL mongo &gt;= 0.9.0)<br/>
     * Sets this database's profiling level
     * @link https://secure.php.net/manual/en/mongodb.setprofilinglevel.php
     * @param int $level Profiling level.
     * @return int Returns the previous profiling level.
     */
    public function setProfilingLevel($level) {}

    /**
     * (PECL mongo &gt;= 0.9.0)<br/>
     * Drops this database
     * @link https://secure.php.net/manual/en/mongodb.drop.php
     * @return array Returns the database response.
     */
    public function drop() {}

    /**
     * Repairs and compacts this database
     * @link https://secure.php.net/manual/en/mongodb.repair.php
     * @param bool $preserve_cloned_files [optional] <p>If cloned files should be kept if the repair fails.</p>
     * @param bool $backup_original_files [optional] <p>If original files should be backed up.</p>
     * @return array <p>Returns db response.</p>
     */
    public function repair($preserve_cloned_files = false, $backup_original_files = false) {}

    /**
     * (PECL mongo &gt;= 0.9.0)<br/>
     * Gets a collection
     * @link https://secure.php.net/manual/en/mongodb.selectcollection.php
     * @param string $name <b>The collection name.</b>
     * @throws Exception if the collection name is invalid.
     * @return MongoCollection <p>
     * Returns a new collection object.
     * </p>
     */
    public function selectCollection($name) {}

    /**
     * (PECL mongo &gt;= 1.1.0)<br/>
     * Change slaveOkay setting for this database
     * @link https://php.net/manual/en/mongodb.setslaveokay.php
     * @param bool $ok [optional] <p>
     * If reads should be sent to secondary members of a replica set for all
     * possible queries using this {@link https://secure.php.net/manual/en/class.mongodb.php MongoDB} instance.
     * </p>
     * @return bool Returns the former value of slaveOkay for this instance.
     */
    public function setSlaveOkay($ok = true) {}

    /**
     * Creates a collection
     * @link https://secure.php.net/manual/en/mongodb.createcollection.php
     * @param string $name The name of the collection.
     * @param array $options [optional] <p>
     * <p>
     * An array containing options for the collections. Each option is its own
     * element in the options array, with the option name listed below being
     * the key of the element. The supported options depend on the MongoDB
     * server version. At the moment, the following options are supported:
     * </p>
     * <p>
     * <b>capped</b>
     * <p>
     * If the collection should be a fixed size.
     * </p>
     * </p>
     * <p>
     * <b>size</b>
     * <p>
     * If the collection is fixed size, its size in bytes.</p></p>
     * <p><b>max</b>
     * <p>If the collection is fixed size, the maximum number of elements to store in the collection.</p></p>
     * <i>autoIndexId</i>
     *
     * <p>
     * If capped is <b>TRUE</b> you can specify <b>FALSE</b> to disable the
     * automatic index created on the <em>_id</em> field.
     * Before MongoDB 2.2, the default value for
     * <em>autoIndexId</em> was <b>FALSE</b>.
     * </p>
     * </p>
     * @return MongoCollection <p>Returns a collection object representing the new collection.</p>
     */
    public function createCollection($name, $options) {}

    /**
     * (PECL mongo &gt;= 0.9.0)<br/>
     * Drops a collection
     * @link https://secure.php.net/manual/en/mongodb.dropcollection.php
     * @param MongoCollection|string $coll MongoCollection or name of collection to drop.
     * @return array Returns the database response.
     * @see MongoCollection::drop()
     */
    #[Deprecated('Use MongoCollection::drop() instead.')]
    public function dropCollection($coll) {}

    /**
     * (PECL mongo &gt;= 0.9.0)<br/>
     * Get a list of collections in this database
     * @link https://secure.php.net/manual/en/mongodb.listcollections.php
     * @param bool $includeSystemCollections [optional] <p>Include system collections.</p>
     * @return array Returns a list of MongoCollections.
     */
    public function listCollections($includeSystemCollections = false) {}

    /**
     * (PECL mongo &gt;= 0.9.0)<br/>
     * Creates a database reference
     * @link https://secure.php.net/manual/en/mongodb.createdbref.php
     * @param string $collection The collection to which the database reference will point.
     * @param mixed $document_or_id <p>
     * If an array or object is given, its <em>_id</em> field will be
     * used as the reference ID. If a {@see MongoId} or scalar
     * is given, it will be used as the reference ID.
     * </p>
     * @return array <p>Returns a database reference array.</p>
     * <p>
     * If an array without an <em>_id</em> field was provided as the
     * <em>document_or_id</em> parameter, <b>NULL</b> will be returned.
     * </p>
     */
    public function createDBRef($collection, $document_or_id) {}

    /**
     * (PECL mongo &gt;= 0.9.0)<br/>
     * Fetches the document pointed to by a database reference
     * @link https://secure.php.net/manual/en/mongodb.getdbref.php
     * @param array $ref A database reference.
     * @return array Returns the document pointed to by the reference.
     */
    public function getDBRef(array $ref) {}

    /**
     * (PECL mongo &gt;= 1.5.0)<br/>
     * Get the write concern for this database
     * @link https://php.net/manual/en/mongodb.getwriteconcern.php
     * @return array <p>This function returns an array describing the write concern.
     * The array contains the values w for an integer acknowledgement level or string mode,
     * and wtimeout denoting the maximum number of milliseconds to wait for the server to satisfy the write concern.</p>
     */
    public function getWriteConcern() {}

    /**
     * (PECL mongo &gt;= 0.9.3)<br/>
     * Runs JavaScript code on the database server.
     * @link https://secure.php.net/manual/en/mongodb.execute.php
     * @param MongoCode|string $code Code to execute.
     * @param array $args [optional] Arguments to be passed to code.
     * @return array Returns the result of the evaluation.
     */
    public function execute($code, array $args = []) {}

    /**
     * Execute a database command
     * @link https://secure.php.net/manual/en/mongodb.command.php
     * @param array $data The query to send.
     * @param array $options [optional] <p>
     * This parameter is an associative array of the form
     * <em>array("optionname" =&gt; &lt;boolean&gt;, ...)</em>. Currently
     * supported options are:
     * </p><ul>
     * <li><p><em>"timeout"</em></p><p>Deprecated alias for <em>"socketTimeoutMS"</em>.</p></li>
     * </ul>
     * @return array Returns database response.
     * Every database response is always maximum one document,
     * which means that the result of a database command can never exceed 16MB.
     * The resulting document's structure depends on the command,
     * but most results will have the ok field to indicate success or failure and results containing an array of each of the resulting documents.
     */
    public function command(array $data, $options) {}

    /**
     * (PECL mongo &gt;= 0.9.5)<br/>
     * Check if there was an error on the most recent db operation performed
     * @link https://secure.php.net/manual/en/mongodb.lasterror.php
     * @return array Returns the error, if there was one.
     */
    public function lastError() {}

    /**
     * (PECL mongo &gt;= 0.9.5)<br/>
     * Checks for the last error thrown during a database operation
     * @link https://secure.php.net/manual/en/mongodb.preverror.php
     * @return array Returns the error and the number of operations ago it occurred.
     */
    public function prevError() {}

    /**
     * (PECL mongo &gt;= 0.9.5)<br/>
     * Clears any flagged errors on the database
     * @link https://secure.php.net/manual/en/mongodb.reseterror.php
     * @return array Returns the database response.
     */
    public function resetError() {}

    /**
     * (PECL mongo &gt;= 0.9.5)<br/>
     * Creates a database error
     * @link https://secure.php.net/manual/en/mongodb.forceerror.php
     * @return bool Returns the database response.
     */
    public function forceError() {}

    /**
     * (PECL mongo &gt;= 1.0.1)<br/>
     * Log in to this database
     *
     * @link https://secure.php.net/manual/en/mongodb.authenticate.php
     *
     * @param string $username The username.
     * @param string $password The password (in plaintext).
     *
     * @return array <p>Returns database response. If the login was successful, it will return 1.</p>
     * <p>
     * <span style="color: #0000BB">&lt;?php<br></span>
     * <span style="color: #007700">array(</span>
     * <span style="color: #DD0000">"ok"&nbsp;</span>
     * <span style="color: #007700">=&gt;&nbsp;</span>
     * <span style="color: #0000BB">1</span>
     * <span style="color: #007700">);<br></span>
     * <span style="color: #0000BB">?&gt;</span>
     * </p>
     * <p> If something went wrong, it will return </p>
     * <p>
     * <span style="color: #0000BB">&lt;?php<br></span>
     * <span style="color: #007700">array(</span>
     * <span style="color: #DD0000">"ok"&nbsp;</span>
     * <span style="color: #007700">=&gt;&nbsp;</span>
     * <span style="color: #0000BB">0</span>
     * <span style="color: #007700">,&nbsp;</span>
     * <span style="color: #DD0000">"errmsg"&nbsp;</span>
     * <span style="color: #007700">=&gt;&nbsp;</span>
     * <span style="color: #DD0000">"auth&nbsp;fails"</span>
     * <span style="color: #007700">);<br></span>
     * <span style="color: #0000BB">?&gt;</span>
     * </p>
     * <p>("auth fails" could be another message, depending on database version and
     * what went wrong)</p>
     */
    public function authenticate($username, $password) {}

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * Get the read preference for this database
     * @link https://secure.php.net/manual/en/mongodb.getreadpreference.php
     * @return array This function returns an array describing the read preference. The array contains the values type for the string read preference mode (corresponding to the MongoClient constants), and tagsets containing a list of all tag set criteria. If no tag sets were specified, tagsets will not be present in the array.
     */
    public function getReadPreference() {}

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * Set the read preference for this database
     * @link https://secure.php.net/manual/en/mongodb.setreadpreference.php
     * @param string $read_preference <p>The read preference mode: <b>MongoClient::RP_PRIMARY</b>, <b>MongoClient::RP_PRIMARY_PREFERRED</b>, <b>MongoClient::RP_SECONDARY</b>, <b>MongoClient::RP_SECONDARY_PREFERRED</b>, or <b>MongoClient::RP_NEAREST</b>.</p>
     * @param array $tags [optional] <p>An array of zero or more tag sets, where each tag set is itself an array of criteria used to match tags on replica set members.</p>
     * @return bool Returns <b>TRUE</b> on success, or <b>FALSE</b> otherwise.
     */
    public function setReadPreference($read_preference, array $tags) {}

    /**
     * (PECL mongo &gt;= 1.5.0)<br/>
     * @link https://php.net/manual/en/mongodb.setwriteconcern.php
     * Set the write concern for this database
     * @param mixed $w <p>The write concern. This may be an integer denoting the number of servers required to acknowledge the write, or a string mode (e.g. "majority").</p>
     * @param int $wtimeout [optional] <p>The maximum number of milliseconds to wait for the server to satisfy the write concern.</p>
     * @return bool Returns <b>TRUE</b> on success, or <b>FALSE</b> otherwise.
     */
    public function setWriteConcern($w, $wtimeout) {}
}

/**
 * Represents a database collection.
 * @link https://secure.php.net/manual/en/class.mongocollection.php
 */
class MongoCollection
{
    /**
     * @link https://php.net/manual/en/class.mongocollection.php#mongocollection.constants.ascending
     */
    public const ASCENDING = 1;

    /**
     * @link https://php.net/manual/en/class.mongocollection.php#mongocollection.constants.descending
     */
    public const DESCENDING = -1;

    /**
     * @var MongoDB
     */
    public $db = null;

    /**
     * @var int <p>
     * The number of servers to replicate a change to before returning success.
     * Value is inherited from the parent database. The
     * {@link https://secure.php.net/manual/en/class.mongodb.php MongoDB} class has a more detailed description of
     * how <em>w</em> works.
     * </p>
     */
    public $w;

    /**
     * @var int <p>
     * The number of milliseconds to wait for <em>$this-&gt;w</em>
     * replications to take place.  Value is inherited from the parent database.
     * The {@link https://secure.php.net/manual/en/class.mongodb.php MongoDB} class has a more detailed description
     * of how <em>wtimeout</em> works.
     * </p>
     */
    public $wtimeout;

    /**
     * Creates a new collection
     * @link https://secure.php.net/manual/en/mongocollection.construct.php
     * @param MongoDB $db Parent database.
     * @param string $name Name for this collection.
     * @throws Exception
     */
    public function __construct(MongoDB $db, $name) {}

    /**
     * String representation of this collection
     * @link https://secure.php.net/manual/en/mongocollection.--tostring.php
     * @return string Returns the full name of this collection.
     */
    public function __toString() {}

    /**
     * Gets a collection
     * @link https://secure.php.net/manual/en/mongocollection.get.php
     * @param string $name The next string in the collection name.
     * @return MongoCollection
     */
    public function __get($name) {}

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * <p>
     * The MongoDB
     * {@link https://docs.mongodb.org/manual/applications/aggregation/ aggregation framework}
     * provides a means to calculate aggregated values without having to use
     * MapReduce. While MapReduce is powerful, it is often more difficult than
     * necessary for many simple aggregation tasks, such as totaling or averaging
     * field values.
     * </p>
     * <p>
     * This method accepts either a variable amount of pipeline operators, or a
     * single array of operators constituting the pipeline.
     * </p>
     * @link https://secure.php.net/manual/en/mongocollection.aggregate.php
     * @param array $pipeline <p> An array of pipeline operators, or just the first operator. </p>
     * @param array $op [optional] <p> The second pipeline operator.</p>
     * @param array $pipelineOperators [optional] <p> Additional pipeline operators. </p>
     * @return array The result of the aggregation as an array. The ok will be set to 1 on success, 0 on failure.
     */
    public function aggregate(array $pipeline, array $op, array $pipelineOperators) {}

    /**
     * (PECL mongo &gt;= 1.5.0)<br/>
     *
     * <p>
     * With this method you can execute Aggregation Framework pipelines and retrieve the results
     * through a cursor, instead of getting just one document back as you would with
     * {@link https://php.net/manual/en/mongocollection.aggregate.php MongoCollection::aggregate()}.
     * This method returns a {@link https://php.net/manual/en/class.mongocommandcursor.php MongoCommandCursor} object.
     * This cursor object implements the {@link https://php.net/manual/en/class.iterator.php Iterator} interface
     * just like the {@link https://php.net/manual/en/class.mongocursor.php MongoCursor} objects that are returned
     * by the {@link https://php.net/manual/en/mongocollection.find.php MongoCollection::find()} method
     * </p>
     *
     * @link https://php.net/manual/en/mongocollection.aggregatecursor.php
     *
     * @param array $pipeline          <p> The Aggregation Framework pipeline to execute. </p>
     * @param array $options            [optional] <p> Options for the aggregation command </p>
     *
     * @return MongoCommandCursor Returns a {@link https://php.net/manual/en/class.mongocommandcursor.php MongoCommandCursor} object
     */
    public function aggregateCursor(array $pipeline, array $options) {}

    /**
     * Returns this collection's name
     * @link https://secure.php.net/manual/en/mongocollection.getname.php
     * @return string
     */
    public function getName() {}

    /**
     * (PECL mongo &gt;= 1.1.0)<br/>
     * <p>
     * See {@link https://secure.php.net/manual/en/mongo.queries.php the query section} of this manual for
     * information on distributing reads to secondaries.
     * </p>
     * @link https://secure.php.net/manual/en/mongocollection.getslaveokay.php
     * @return bool Returns the value of slaveOkay for this instance.
     */
    public function getSlaveOkay() {}

    /**
     * (PECL mongo &gt;= 1.1.0)<br/>
     * <p>
     * See {@link https://secure.php.net/manual/en/mongo.queries.php the query section} of this manual for
     * information on distributing reads to secondaries.
     * </p>
     * @link https://secure.php.net/manual/en/mongocollection.setslaveokay.php
     * @param bool $ok [optional] <p>
     * If reads should be sent to secondary members of a replica set for all
     * possible queries using this {@link https://secure.php.net/manual/en/class.mongocollection.php MongoCollection}
     * instance.
     * @return bool Returns the former value of slaveOkay for this instance.
     * </p>
     */
    public function setSlaveOkay($ok = true) {}

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * @link https://secure.php.net/manual/en/mongocollection.getreadpreference.php
     * @return array This function returns an array describing the read preference. The array contains the values <em>type</em> for the string read preference mode
     * (corresponding to the {@link https://secure.php.net/manual/en/class.mongoclient.php MongoClient} constants), and <em>tagsets</em> containing a list of all tag set criteria. If no tag sets were specified, <em>tagsets</em> will not be present in the array.
     */
    public function getReadPreference() {}

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * @param string $read_preference <p>The read preference mode: <b>MongoClient::RP_PRIMARY</b>, <b>MongoClient::RP_PRIMARY_PREFERRED</b>, <b>MongoClient::RP_SECONDARY</b>, <b>MongoClient::RP_SECONDARY_PREFERRED</b>, or <b>MongoClient::RP_NEAREST</b>.</p>
     * @param array $tags [optional] <p>An array of zero or more tag sets, where each tag set is itself an array of criteria used to match tags on replica set members.<p>
     * @return bool Returns <b>TRUE</b> on success, or <b>FALSE</b> otherwise.
     */
    public function setReadPreference($read_preference, array $tags) {}

    /**
     * Drops this collection
     * @link https://secure.php.net/manual/en/mongocollection.drop.php
     * @return array Returns the database response.
     */
    public function drop() {}

    /**
     * Validates this collection
     * @link https://secure.php.net/manual/en/mongocollection.validate.php
     * @param bool $scan_data Only validate indices, not the base collection.
     * @return array Returns the database's evaluation of this object.
     */
    public function validate($scan_data = false) {}

    /**
     * Inserts an array into the collection
     * @link https://secure.php.net/manual/en/mongocollection.insert.php
     * @param array|object $a An array or object. If an object is used, it may not have protected or private properties.
     * Note: If the parameter does not have an _id key or property, a new MongoId instance will be created and assigned to it.
     * This special behavior does not mean that the parameter is passed by reference.
     * @param array $options Options for the insert.
     * <dl>
     * <dt>"w"</dt>
     * <dd>See WriteConcerns. The default value for MongoClient is 1.</dd>
     * <dt>"fsync"</dt>
     * <dd>Boolean, defaults to FALSE. Forces the insert to be synced to disk before returning success. If TRUE, an acknowledged insert is implied and will override setting w to 0.</dd>
     * <dt>"timeout"</dt>
     * <dd>Integer, defaults to MongoCursor::$timeout. If "safe" is set, this sets how long (in milliseconds) for the client to wait for a database response. If the database does not respond within the timeout period, a MongoCursorTimeoutException will be thrown.</dd>
     * <dt>"safe"</dt>
     * <dd>Deprecated. Please use the WriteConcern w option.</dd>
     * </dl>
     * @throws MongoException if the inserted document is empty or if it contains zero-length keys. Attempting to insert an object with protected and private properties will cause a zero-length key error.
     * @throws MongoCursorException if the "w" option is set and the write fails.
     * @throws MongoCursorTimeoutException if the "w" option is set to a value greater than one and the operation takes longer than MongoCursor::$timeout milliseconds to complete. This does not kill the operation on the server, it is a client-side timeout. The operation in MongoCollection::$wtimeout is milliseconds.
     * @return bool|array Returns an array containing the status of the insertion if the "w" option is set.
     * Otherwise, returns TRUE if the inserted array is not empty (a MongoException will be thrown if the inserted array is empty).
     * If an array is returned, the following keys may be present:
     * <dl>
     * <dt>ok</dt>
     * <dd>This should almost be 1 (unless last_error itself failed).</dd>
     * <dt>err</dt>
     * <dd>If this field is non-null, an error occurred on the previous operation. If this field is set, it will be a string describing the error that occurred.</dd>
     * <dt>code</dt>
     * <dd>If a database error occurred, the relevant error code will be passed back to the client.</dd>
     * <dt>errmsg</dt>
     * <dd>This field is set if something goes wrong with a database command. It is coupled with ok being 0. For example, if w is set and times out, errmsg will be set to "timed out waiting for slaves" and ok will be 0. If this field is set, it will be a string describing the error that occurred.</dd>
     * <dt>n</dt>
     * <dd>If the last operation was an update, upsert, or a remove, the number of documents affected will be returned. For insert operations, this value is always 0.</dd>
     * <dt>wtimeout</dt>
     * <dd>If the previous option timed out waiting for replication.</dd>
     * <dt>waited</dt>
     * <dd>How long the operation waited before timing out.</dd>
     * <dt>wtime</dt>
     * <dd>If w was set and the operation succeeded, how long it took to replicate to w servers.</dd>
     * <dt>upserted</dt>
     * <dd>If an upsert occurred, this field will contain the new record's _id field. For upserts, either this field or updatedExisting will be present (unless an error occurred).</dd>
     * <dt>updatedExisting</dt>
     * <dd>If an upsert updated an existing element, this field will be true. For upserts, either this field or upserted will be present (unless an error occurred).</dd>
     * </dl>
     */
    public function insert($a, array $options = []) {}

    /**
     * Inserts multiple documents into this collection
     * @link https://secure.php.net/manual/en/mongocollection.batchinsert.php
     * @param array $a An array of arrays.
     * @param array $options Options for the inserts.
     * @throws MongoCursorException
     * @return array|bool if "safe" is set, returns an associative array with the status of the inserts ("ok") and any error that may have occurred ("err"). Otherwise, returns TRUE if the batch insert was successfully sent, FALSE otherwise.
     */
    public function batchInsert(array $a, array $options = []) {}

    /**
     * Update records based on a given criteria
     * @link https://secure.php.net/manual/en/mongocollection.update.php
     * @param array $criteria Description of the objects to update.
     * @param array $newobj The object with which to update the matching records.
     * @param array $options This parameter is an associative array of the form
     *        array("optionname" => boolean, ...).
     *
     *        Currently supported options are:
     *          "upsert": If no document matches $$criteria, a new document will be created from $$criteria and $$new_object (see upsert example).
     *
     *          "multiple": All documents matching $criteria will be updated. MongoCollection::update has exactly the opposite behavior of MongoCollection::remove- it updates one document by
     *          default, not all matching documents. It is recommended that you always specify whether you want to update multiple documents or a single document, as the
     *          database may change its default behavior at some point in the future.
     *
     *          "safe" Can be a boolean or integer, defaults to false. If false, the program continues executing without waiting for a database response. If true, the program will wait for
     *          the database response and throw a MongoCursorException if the update did not succeed. If you are using replication and the master has changed, using "safe" will make the driver
     *          disconnect from the master, throw and exception, and attempt to find a new master on the next operation (your application must decide whether or not to retry the operation on the new master).
     *          If you do not use "safe" with a replica set and the master changes, there will be no way for the driver to know about the change so it will continuously and silently fail to write.
     *          If safe is an integer, will replicate the update to that many machines before returning success (or throw an exception if the replication times out, see wtimeout).
     *          This overrides the w variable set on the collection.
     *
     *         "fsync": Boolean, defaults to false. Forces the update to be synced to disk before returning success. If true, a safe update is implied and will override setting safe to false.
     *
     *         "timeout" Integer, defaults to MongoCursor::$timeout. If "safe" is set, this sets how long (in milliseconds) for the client to wait for a database response. If the database does
     *         not respond within the timeout period, a MongoCursorTimeoutException will be thrown
     * @throws MongoCursorException
     * @return bool
     */
    public function update(array $criteria, array $newobj, array $options = []) {}

    /**
     * (PECL mongo &gt;= 0.9.0)<br/>
     * Remove records from this collection
     * @link https://secure.php.net/manual/en/mongocollection.remove.php
     * @param array $criteria [optional] <p>Query criteria for the documents to delete.</p>
     * @param array $options [optional] <p>An array of options for the remove operation. Currently available options
     * include:
     * </p><ul>
     * <li><p><em>"w"</em></p><p>See {@link https://secure.php.net/manual/en/mongo.writeconcerns.php Write Concerns}. The default value for <b>MongoClient</b> is <em>1</em>.</p></li>
     * <li>
     * <p>
     * <em>"justOne"</em>
     * </p>
     * <p>
     * Specify <strong><code>TRUE</code></strong> to limit deletion to just one document. If <strong><code>FALSE</code></strong> or
     * omitted, all documents matching the criteria will be deleted.
     * </p>
     * </li>
     * <li><p><em>"fsync"</em></p><p>Boolean, defaults to <b>FALSE</b>. If journaling is enabled, it works exactly like <em>"j"</em>. If journaling is not enabled, the write operation blocks until it is synced to database files on disk. If <strong><code>TRUE</code></strong>, an acknowledged insert is implied and this option will override setting <em>"w"</em> to <em>0</em>.</p><blockquote class="note"><p><strong class="note">Note</strong>: <span class="simpara">If journaling is enabled, users are strongly encouraged to use the <em>"j"</em> option instead of <em>"fsync"</em>. Do not use <em>"fsync"</em> and <em>"j"</em> simultaneously, as that will result in an error.</p></blockquote></li>
     * <li><p><em>"j"</em></p><p>Boolean, defaults to <b>FALSE</b>. Forces the write operation to block until it is synced to the journal on disk. If <strong><code>TRUE</code></strong>, an acknowledged write is implied and this option will override setting <em>"w"</em> to <em>0</em>.</p><blockquote class="note"><p><strong class="note">Note</strong>: <span class="simpara">If this option is used and journaling is disabled, MongoDB 2.6+ will raise an error and the write will fail; older server versions will simply ignore the option.</p></blockquote></li>
     * <li><p><em>"socketTimeoutMS"</em></p><p>This option specifies the time limit, in milliseconds, for socket communication. If the server does not respond within the timeout period, a <b>MongoCursorTimeoutException</b> will be thrown and there will be no way to determine if the server actually handled the write or not. A value of <em>-1</em> may be specified to block indefinitely. The default value for <b>MongoClient</b> is <em>30000</em> (30 seconds).</p></li>
     * <li><p><em>"w"</em></p><p>See {@link https://secure.php.net/manual/en/mongo.writeconcerns.php Write Concerns}. The default value for <b>MongoClient</b> is <em>1</em>.</p></li>
     * <li><p><em>"wTimeoutMS"</em></p><p>This option specifies the time limit, in milliseconds, for {@link https://secure.php.net/manual/en/mongo.writeconcerns.php write concern} acknowledgement. It is only applicable when <em>"w"</em> is greater than <em>1</em>, as the timeout pertains to replication. If the write concern is not satisfied within the time limit, a <a href="class.mongocursorexception.php" class="classname">MongoCursorException</a> will be thrown. A value of <em>0</em> may be specified to block indefinitely. The default value for {@link https://secure.php.net/manual/en/class.mongoclient.php MongoClient} is <em>10000</em> (ten seconds).</p></li>
     * </ul>
     *
     * <p>
     * The following options are deprecated and should no longer be used:
     * </p><ul>
     * <li><p><em>"safe"</em></p><p>Deprecated. Please use the {@link https://secure.php.net/manual/en/mongo.writeconcerns.php write concern} <em>"w"</em> option.</p></li>
     * <li><p><em>"timeout"</em></p><p>Deprecated alias for <em>"socketTimeoutMS"</em>.</p></li>
     * <li><p><b>"wtimeout"</b></p><p>Deprecated alias for <em>"wTimeoutMS"</em>.</p></li></ul>
     * @throws MongoCursorException
     * @throws MongoCursorTimeoutException
     * @return bool|array <p>Returns an array containing the status of the removal if the
     * <em>"w"</em> option is set. Otherwise, returns <b>TRUE</b>.
     * </p>
     * <p>
     * Fields in the status array are described in the documentation for
     * <b>MongoCollection::insert()</b>.
     * </p>
     */
    public function remove(array $criteria = [], array $options = []) {}

    /**
     * Querys this collection
     * @link https://secure.php.net/manual/en/mongocollection.find.php
     * @param array $query The fields for which to search.
     * @param array $fields Fields of the results to return.
     * @return MongoCursor
     */
    public function find(array $query = [], array $fields = []) {}

    /**
     * Retrieve a list of distinct values for the given key across a collection
     * @link https://secure.php.net/manual/en/mongocollection.distinct.php
     * @param string $key The key to use.
     * @param array $query An optional query parameters
     * @return array|false Returns an array of distinct values, or <b>FALSE</b> on failure
     */
    public function distinct($key, array $query = null) {}

    /**
     * Update a document and return it
     * @link https://secure.php.net/manual/en/mongocollection.findandmodify.php
     * @param array $query The query criteria to search for.
     * @param array $update The update criteria.
     * @param array $fields Optionally only return these fields.
     * @param array $options An array of options to apply, such as remove the match document from the DB and return it.
     * @return array Returns the original document, or the modified document when new is set.
     */
    public function findAndModify(array $query, array $update = null, array $fields = null, array $options = null) {}

    /**
     * Querys this collection, returning a single element
     * @link https://secure.php.net/manual/en/mongocollection.findone.php
     * @param array $query The fields for which to search.
     * @param array $fields Fields of the results to return.
     * @param array $options This parameter is an associative array of the form array("name" => `<value>`, ...).
     * @return array|null
     */
    public function findOne(array $query = [], array $fields = [], array $options = []) {}

    /**
     * Creates an index on the given field(s), or does nothing if the index already exists
     * @link https://secure.php.net/manual/en/mongocollection.createindex.php
     * @param array $keys Field or fields to use as index.
     * @param array $options [optional] This parameter is an associative array of the form array("optionname" => `<boolean>`, ...).
     * @return array Returns the database response.
     */
    public function createIndex(array $keys, array $options = []) {}

    /**
     * Creates an index on the given field(s), or does nothing if the index already exists
     * @link https://secure.php.net/manual/en/mongocollection.ensureindex.php
     * @param array $keys Field or fields to use as index.
     * @param array $options [optional] This parameter is an associative array of the form array("optionname" => `<boolean>`, ...).
     * @return true always true
     * @see MongoCollection::createIndex()
     */
    #[Deprecated('Use MongoCollection::createIndex() instead.')]
    public function ensureIndex(array $keys, array $options = []) {}

    /**
     * Deletes an index from this collection
     * @link https://secure.php.net/manual/en/mongocollection.deleteindex.php
     * @param string|array $keys Field or fields from which to delete the index.
     * @return array Returns the database response.
     */
    public function deleteIndex($keys) {}

    /**
     * Delete all indexes for this collection
     * @link https://secure.php.net/manual/en/mongocollection.deleteindexes.php
     * @return array Returns the database response.
     */
    public function deleteIndexes() {}

    /**
     * Returns an array of index names for this collection
     * @link https://secure.php.net/manual/en/mongocollection.getindexinfo.php
     * @return array Returns a list of index names.
     */
    public function getIndexInfo() {}

    /**
     * Counts the number of documents in this collection
     * @link https://secure.php.net/manual/en/mongocollection.count.php
     * @param array|stdClass $query
     * @return int Returns the number of documents matching the query.
     */
    public function count($query = []) {}

    /**
     * Saves an object to this collection
     * @link https://secure.php.net/manual/en/mongocollection.save.php
     * @param array|object $a Array to save. If an object is used, it may not have protected or private properties.
     * Note: If the parameter does not have an _id key or property, a new MongoId instance will be created and assigned to it.
     * See MongoCollection::insert() for additional information on this behavior.
     * @param array $options Options for the save.
     * <dl>
     * <dt>"w"
     * <dd>See WriteConcerns. The default value for MongoClient is 1.
     * <dt>"fsync"
     * <dd>Boolean, defaults to FALSE. Forces the insert to be synced to disk before returning success. If TRUE, an acknowledged insert is implied and will override setting w to 0.
     * <dt>"timeout"
     * <dd>Integer, defaults to MongoCursor::$timeout. If "safe" is set, this sets how long (in milliseconds) for the client to wait for a database response. If the database does not respond within the timeout period, a MongoCursorTimeoutException will be thrown.
     * <dt>"safe"
     * <dd>Deprecated. Please use the WriteConcern w option.
     * </dl>
     * @throws MongoException if the inserted document is empty or if it contains zero-length keys. Attempting to insert an object with protected and private properties will cause a zero-length key error.
     * @throws MongoCursorException if the "w" option is set and the write fails.
     * @throws MongoCursorTimeoutException if the "w" option is set to a value greater than one and the operation takes longer than MongoCursor::$timeout milliseconds to complete. This does not kill the operation on the server, it is a client-side timeout. The operation in MongoCollection::$wtimeout is milliseconds.
     * @return array|bool If w was set, returns an array containing the status of the save.
     * Otherwise, returns a boolean representing if the array was not empty (an empty array will not be inserted).
     */
    public function save($a, array $options = []) {}

    /**
     * Creates a database reference
     * @link https://secure.php.net/manual/en/mongocollection.createdbref.php
     * @param array $a Object to which to create a reference.
     * @return array Returns a database reference array.
     */
    public function createDBRef(array $a) {}

    /**
     * Fetches the document pointed to by a database reference
     * @link https://secure.php.net/manual/en/mongocollection.getdbref.php
     * @param array $ref A database reference.
     * @return array Returns the database document pointed to by the reference.
     */
    public function getDBRef(array $ref) {}

    /**
     * @param  mixed $keys
     * @return string
     */
    protected static function toIndexString($keys) {}

    /**
     * Performs an operation similar to SQL's GROUP BY command
     * @link https://secure.php.net/manual/en/mongocollection.group.php
     * @param mixed $keys Fields to group by. If an array or non-code object is passed, it will be the key used to group results.
     * @param array $initial Initial value of the aggregation counter object.
     * @param MongoCode $reduce A function that aggregates (reduces) the objects iterated.
     * @param array $condition An condition that must be true for a row to be considered.
     * @return array
     */
    public function group($keys, array $initial, MongoCode $reduce, array $condition = []) {}
}

/**
 * Result object for database query.
 * @link https://secure.php.net/manual/en/class.mongocursor.php
 */
class MongoCursor implements Iterator
{
    /**
     * @link https://php.net/manual/en/class.mongocursor.php#mongocursor.props.slaveokay
     * @var bool
     */
    public static $slaveOkay = false;

    /**
     * @var int <p>
     * Set timeout in milliseconds for all database responses. Use
     * <em>-1</em> to wait forever. Can be overridden with
     * {link https://secure.php.net/manual/en/mongocursor.timeout.php MongoCursor::timeout()}. This does not cause the
     * MongoDB server to cancel the operation; it only instructs the driver to
     * stop waiting for a response and throw a
     * {@link https://php.net/manual/en/class.mongocursortimeoutexception.php MongoCursorTimeoutException} after a set time.
     * </p>
     */
    public static $timeout = 30000;

    /**
     * Create a new cursor
     * @link https://secure.php.net/manual/en/mongocursor.construct.php
     * @param MongoClient $connection Database connection.
     * @param string $ns Full name of database and collection.
     * @param array $query Database query.
     * @param array $fields Fields to return.
     */
    public function __construct($connection, $ns, array $query = [], array $fields = []) {}

    /**
     * (PECL mongo &gt;= 1.2.11)<br/>
     * Sets whether this cursor will wait for a while for a tailable cursor to return more data
     * @param bool $wait [optional] <p>If the cursor should wait for more data to become available.</p>
     * @return MongoCursor Returns this cursor.
     */
    public function awaitData($wait = true) {}

    /**
     * Checks if there are any more elements in this cursor
     * @link https://secure.php.net/manual/en/mongocursor.hasnext.php
     * @throws MongoConnectionException
     * @throws MongoCursorTimeoutException
     * @return bool Returns true if there is another element
     */
    public function hasNext() {}

    /**
     * Return the next object to which this cursor points, and advance the cursor
     * @link https://secure.php.net/manual/en/mongocursor.getnext.php
     * @throws MongoConnectionException
     * @throws MongoCursorTimeoutException
     * @return array Returns the next object
     */
    public function getNext() {}

    /**
     * (PECL mongo &gt;= 1.3.3)<br/>
     * @link https://secure.php.net/manual/en/mongocursor.getreadpreference.php
     * @return array This function returns an array describing the read preference. The array contains the values <em>type</em> for the string
     * read preference mode (corresponding to the {@link https://secure.php.net/manual/en/class.mongoclient.php MongoClient} constants), and <em>tagsets</em> containing a list of all tag set criteria. If no tag sets were specified, <em>tagsets</em> will not be present in the array.
     */
    public function getReadPreference() {}

    /**
     * Limits the number of results returned
     * @link https://secure.php.net/manual/en/mongocursor.limit.php
     * @param int $num The number of results to return.
     * @throws MongoCursorException
     * @return MongoCursor Returns this cursor
     */
    public function limit($num) {}

    /**
     * (PECL mongo &gt;= 1.2.0)<br/>
     * @link https://secure.php.net/manual/en/mongocursor.partial.php
     * @param bool $okay [optional] <p>If receiving partial results is okay.</p>
     * @return MongoCursor Returns this cursor.
     */
    public function partial($okay = true) {}

    /**
     * (PECL mongo &gt;= 1.2.1)<br/>
     * @link https://secure.php.net/manual/en/mongocursor.setflag.php
     * @param int $flag <p>
     * Which flag to set. You can not set flag 6 (EXHAUST) as the driver does
     * not know how to handle them. You will get a warning if you try to use
     * this flag. For available flags, please refer to the wire protocol
     * {@link https://www.mongodb.org/display/DOCS/Mongo+Wire+Protocol#MongoWireProtocol-OPQUERY documentation}.
     * </p>
     * @param bool $set [optional] <p>Whether the flag should be set (<b>TRUE</b>) or unset (<b>FALSE</b>).</p>
     * @return MongoCursor
     */
    public function setFlag($flag, $set = true) {}

    /**
     * (PECL mongo &gt;= 1.3.3)<br/>
     * @link https://secure.php.net/manual/en/mongocursor.setreadpreference.php
     * @param string $read_preference <p>The read preference mode: MongoClient::RP_PRIMARY, MongoClient::RP_PRIMARY_PREFERRED, MongoClient::RP_SECONDARY, MongoClient::RP_SECONDARY_PREFERRED, or MongoClient::RP_NEAREST.</p>
     * @param array $tags [optional] <p>The read preference mode: MongoClient::RP_PRIMARY, MongoClient::RP_PRIMARY_PREFERRED, MongoClient::RP_SECONDARY, MongoClient::RP_SECONDARY_PREFERRED, or MongoClient::RP_NEAREST.</p>
     * @return MongoCursor Returns this cursor.
     */
    public function setReadPreference($read_preference, array $tags) {}

    /**
     * Skips a number of results
     * @link https://secure.php.net/manual/en/mongocursor.skip.php
     * @param int $num The number of results to skip.
     * @throws MongoCursorException
     * @return MongoCursor Returns this cursor
     */
    public function skip($num) {}

    /**
     * Sets whether this query can be done on a slave
     * This method will override the static class variable slaveOkay.
     * @link https://secure.php.net/manual/en/mongocursor.slaveOkay.php
     * @param bool $okay If it is okay to query the slave.
     * @throws MongoCursorException
     * @return MongoCursor Returns this cursor
     */
    public function slaveOkay($okay = true) {}

    /**
     * Sets whether this cursor will be left open after fetching the last results
     * @link https://secure.php.net/manual/en/mongocursor.tailable.php
     * @param bool $tail If the cursor should be tailable.
     * @return MongoCursor Returns this cursor
     */
    public function tailable($tail = true) {}

    /**
     * Sets whether this cursor will timeout
     * @link https://secure.php.net/manual/en/mongocursor.immortal.php
     * @param bool $liveForever If the cursor should be immortal.
     * @throws MongoCursorException
     * @return MongoCursor Returns this cursor
     */
    public function immortal($liveForever = true) {}

    /**
     * Sets a client-side timeout for this query
     * @link https://secure.php.net/manual/en/mongocursor.timeout.php
     * @param int $ms The number of milliseconds for the cursor to wait for a response. By default, the cursor will wait forever.
     * @throws MongoCursorTimeoutException
     * @return MongoCursor Returns this cursor
     */
    public function timeout($ms) {}

    /**
     * Checks if there are documents that have not been sent yet from the database for this cursor
     * @link https://secure.php.net/manual/en/mongocursor.dead.php
     * @return bool Returns if there are more results that have not been sent to the client, yet.
     */
    public function dead() {}

    /**
     * Use snapshot mode for the query
     * @link https://secure.php.net/manual/en/mongocursor.snapshot.php
     * @throws MongoCursorException
     * @return MongoCursor Returns this cursor
     */
    public function snapshot() {}

    /**
     * Sorts the results by given fields
     * @link https://secure.php.net/manual/en/mongocursor.sort.php
     * @param array $fields An array of fields by which to sort. Each element in the array has as key the field name, and as value either 1 for ascending sort, or -1 for descending sort
     * @throws MongoCursorException
     * @return MongoCursor Returns the same cursor that this method was called on
     */
    public function sort(array $fields) {}

    /**
     * Gives the database a hint about the query
     * @link https://secure.php.net/manual/en/mongocursor.hint.php
     * @param mixed $key_pattern Indexes to use for the query.
     * @throws MongoCursorException
     * @return MongoCursor Returns this cursor
     */
    public function hint($key_pattern) {}

    /**
     * Adds a top-level key/value pair to a query
     * @link https://secure.php.net/manual/en/mongocursor.addoption.php
     * @param string $key Fieldname to add.
     * @param mixed $value Value to add.
     * @throws MongoCursorException
     * @return MongoCursor Returns this cursor
     */
    public function addOption($key, $value) {}

    /**
     * Execute the query
     * @link https://secure.php.net/manual/en/mongocursor.doquery.php
     * @throws MongoConnectionException if it cannot reach the database.
     * @return void
     */
    protected function doQuery() {}

    /**
     * Returns the current element
     * @link https://secure.php.net/manual/en/mongocursor.current.php
     * @return array
     */
    public function current() {}

    /**
     * Returns the current result's _id
     * @link https://secure.php.net/manual/en/mongocursor.key.php
     * @return string The current result's _id as a string.
     */
    public function key() {}

    /**
     * Advances the cursor to the next result
     * @link https://secure.php.net/manual/en/mongocursor.next.php
     * @throws MongoConnectionException
     * @throws MongoCursorTimeoutException
     * @return void
     */
    public function next() {}

    /**
     * Returns the cursor to the beginning of the result set
     * @throws MongoConnectionException
     * @throws MongoCursorTimeoutException
     * @return void
     */
    public function rewind() {}

    /**
     * Checks if the cursor is reading a valid result.
     * @link https://secure.php.net/manual/en/mongocursor.valid.php
     * @return bool If the current result is not null.
     */
    public function valid() {}

    /**
     * Clears the cursor
     * @link https://secure.php.net/manual/en/mongocursor.reset.php
     * @return void
     */
    public function reset() {}

    /**
     * Return an explanation of the query, often useful for optimization and debugging
     * @link https://secure.php.net/manual/en/mongocursor.explain.php
     * @return array Returns an explanation of the query.
     */
    public function explain() {}

    /**
     * Counts the number of results for this query
     * @link https://secure.php.net/manual/en/mongocursor.count.php
     * @param bool $all Send cursor limit and skip information to the count function, if applicable.
     * @return int The number of documents returned by this cursor's query.
     */
    public function count($all = false) {}

    /**
     * Sets the fields for a query
     * @link https://secure.php.net/manual/en/mongocursor.fields.php
     * @param array $f Fields to return (or not return).
     * @throws MongoCursorException
     * @return MongoCursor
     */
    public function fields(array $f) {}

    /**
     * Gets the query, fields, limit, and skip for this cursor
     * @link https://secure.php.net/manual/en/mongocursor.info.php
     * @return array The query, fields, limit, and skip for this cursor as an associative array.
     */
    public function info() {}

    /**
     * PECL mongo >= 1.0.11
     * Limits the number of elements returned in one batch.
     * <p>A cursor typically fetches a batch of result objects and store them locally.
     * This method sets the batchSize value to configure the amount of documents retrieved from the server in one data packet.
     * However, it will never return more documents than fit in the max batch size limit (usually 4MB).
     * </p>
     *
     * @param int $batchSize The number of results to return per batch. Each batch requires a round-trip to the server.
     * <p>If batchSize is 2 or more, it represents the size of each batch of objects retrieved.
     * It can be adjusted to optimize performance and limit data transfer.
     * </p>
     *
     * <p>If batchSize is 1 or negative, it will limit of number returned documents to the absolute value of batchSize,
     * and the cursor will be closed. For example if batchSize is -10, then the server will return a maximum of 10
     * documents and as many as can fit in 4MB, then close the cursor.
     * </p>
     * <b>Warning</b>
     * <p>A batchSize of 1 is special, and means the same as -1, i.e. a value of 1 makes the cursor only capable of returning one document.
     * </p>
     * <p>Note that this feature is different from MongoCursor::limit() in that documents must fit within a maximum size,
     * and it removes the need to send a request to close the cursor server-side.
     * The batch size can be changed even after a cursor is iterated, in which case the setting will apply on the next batch retrieval.
     * </p>
     * <p>This cannot override MongoDB's limit on the amount of data it will return to the client (i.e.,
     * if you set batch size to 1,000,000,000, MongoDB will still only return 4-16MB of results per batch).
     * </p>
     * <p>To ensure consistent behavior, the rules of MongoCursor::batchSize() and MongoCursor::limit() behave a little complex
     * but work "as expected". The rules are: hard limits override soft limits with preference given to MongoCursor::limit() over
     * MongoCursor::batchSize(). After that, whichever is set and lower than the other will take precedence.
     * See below. section for some examples.
     * </p>
     *
     * @return MongoCursor Returns this cursor.
     * @link https://secure.php.net/manual/en/mongocursor.batchsize.php
     */
    public function batchSize($batchSize) {}

    /**
     * (PECL mongo >= 1.5.0)
     * Sets a server-side timeout for this query
     * @link https://php.net/manual/en/mongocursor.maxtimems.php
     * @param int $ms <p>
     * Specifies a cumulative time limit in milliseconds to be allowed by the
     * server for processing operations on the cursor.
     * </p>
     * @return MongoCursor This cursor.
     */
    public function maxTimeMS($ms) {}
}

class MongoCommandCursor implements MongoCursorInterface
{
    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current() {}

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next() {}

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key() {}

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid() {}

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind() {}

    public function batchSize(int $batchSize): MongoCursorInterface {}

    public function dead(): bool {}

    public function info(): array {}

    public function getReadPreference(): array {}

    public function setReadPreference(string $read_preference, array $tags = null): MongoCursorInterface {}

    public function timeout(int $ms): MongoCursorInterface {}
}

interface MongoCursorInterface extends Iterator
{
    public function batchSize(int $batchSize): MongoCursorInterface;

    public function dead(): bool;

    public function info(): array;

    public function getReadPreference(): array;

    public function setReadPreference(string $read_preference, array $tags = null): MongoCursorInterface;

    public function timeout(int $ms): MongoCursorInterface;
}

class MongoGridFS extends MongoCollection
{
    public const ASCENDING = 1;
    public const DESCENDING = -1;

    /**
     * @link https://php.net/manual/en/class.mongogridfs.php#mongogridfs.props.chunks
     * @var MongoCollection
     */
    public $chunks;

    /**
     * @link https://php.net/manual/en/class.mongogridfs.php#mongogridfs.props.filesname
     * @var string
     */
    protected $filesName;

    /**
     * @link https://php.net/manual/en/class.mongogridfs.php#mongogridfs.props.chunksname
     * @var string
     */
    protected $chunksName;

    /**
     * Files as stored across two collections, the first containing file meta
     * information, the second containing chunks of the actual file. By default,
     * fs.files and fs.chunks are the collection names used.
     *
     * @link https://php.net/manual/en/mongogridfs.construct.php
     * @param MongoDB $db Database
     * @param string $prefix [optional] <p>Optional collection name prefix.</p>
     * @param mixed $chunks  [optional]
     */
    public function __construct($db, $prefix = "fs", $chunks = "fs") {}

    /**
     * Drops the files and chunks collections
     * @link https://php.net/manual/en/mongogridfs.drop.php
     * @return array The database response
     */
    public function drop() {}

    /**
     * @link https://php.net/manual/en/mongogridfs.find.php
     * @param array $query The query
     * @param array $fields Fields to return
     * @return MongoGridFSCursor A MongoGridFSCursor
     */
    public function find(array $query = [], array $fields = []) {}

    /**
     * Stores a file in the database
     * @link https://php.net/manual/en/mongogridfs.storefile.php
     * @param string|resource $filename The name of the file
     * @param array $extra Other metadata to add to the file saved
     * @param array $options Options for the store. "safe": Check that this store succeeded
     * @return mixed Returns the _id of the saved object
     */
    public function storeFile($filename, $extra = [], $options = []) {}

    /**
     * Chunkifies and stores bytes in the database
     * @link https://php.net/manual/en/mongogridfs.storebytes.php
     * @param string $bytes A string of bytes to store
     * @param array $extra Other metadata to add to the file saved
     * @param array $options Options for the store. "safe": Check that this store succeeded
     * @return mixed The _id of the object saved
     */
    public function storeBytes($bytes, $extra = [], $options = []) {}

    /**
     * Returns a single file matching the criteria
     * @link https://secure.php.net/manual/en/mongogridfs.findone.php
     * @param array $query The fields for which to search.
     * @param array $fields Fields of the results to return.
     * @return MongoGridFSFile|null
     */
    public function findOne(array $query = [], array $fields = []) {}

    /**
     * Removes files from the collections
     * @link https://secure.php.net/manual/en/mongogridfs.remove.php
     * @param array $criteria Description of records to remove.
     * @param array $options Options for remove. Valid options are: "safe"- Check that the remove succeeded.
     * @throws MongoCursorException
     * @return bool
     */
    public function remove(array $criteria = [], array $options = []) {}

    /**
     * Delete a file from the database
     * @link https://php.net/manual/en/mongogridfs.delete.php
     * @param mixed $id _id of the file to remove
     * @return bool Returns true if the remove was successfully sent to the database.
     */
    public function delete($id) {}

    /**
     * Saves an uploaded file directly from a POST to the database
     * @link https://secure.php.net/manual/en/mongogridfs.storeupload.php
     * @param string $name The name attribute of the uploaded file, from <code>&#x3C;input type=&#x22;file&#x22; name=&#x22;something&#x22;/&#x3E;</code>
     * @param array $metadata An array of extra fields for the uploaded file.
     * @return mixed Returns the _id of the uploaded file.
     */
    public function storeUpload($name, array $metadata = []) {}

    /**
     * Retrieve a file from the database
     * @link https://secure.php.net/manual/en/mongogridfs.get.php
     * @param mixed $id _id of the file to find.
     * @return MongoGridFSFile|null Returns the file, if found, or NULL.
     */
    public function get($id) {}

    /**
     * Stores a file in the database
     * @link https://php.net/manual/en/mongogridfs.put.php
     * @param string $filename The name of the file
     * @param array $extra Other metadata to add to the file saved
     * @return mixed Returns the _id of the saved object
     */
    public function put($filename, array $extra = []) {}
}

class MongoGridFSFile
{
    /**
     * @link https://php.net/manual/en/class.mongogridfsfile.php#mongogridfsfile.props.file
     * @var array|null
     */
    public $file;

    /**
     * @link https://php.net/manual/en/class.mongogridfsfile.php#mongogridfsfile.props.gridfs
     * @var MongoGridFS|null
     */
    protected $gridfs;

    /**
     * @link https://php.net/manual/en/mongogridfsfile.construct.php
     * @param MongoGridFS $gridfs The parent MongoGridFS instance
     * @param array $file A file from the database
     */
    public function __construct($gridfs, array $file) {}

    /**
     * Returns this file's filename
     * @link https://php.net/manual/en/mongogridfsfile.getfilename.php
     * @return string Returns the filename
     */
    public function getFilename() {}

    /**
     * Returns this file's size
     * @link https://php.net/manual/en/mongogridfsfile.getsize.php
     * @return int Returns this file's size
     */
    public function getSize() {}

    /**
     * Writes this file to the filesystem
     * @link https://php.net/manual/en/mongogridfsfile.write.php
     * @param string $filename The location to which to write the file (path+filename+extension). If none is given, the stored filename will be used.
     * @return int Returns the number of bytes written
     */
    public function write($filename = null) {}

    /**
     * This will load the file into memory. If the file is bigger than your memory, this will cause problems!
     * @link https://php.net/manual/en/mongogridfsfile.getbytes.php
     * @return string Returns a string of the bytes in the file
     */
    public function getBytes() {}

    /**
     * This method returns a stream resource that can be used to read the stored file with all file functions in PHP.
     * The contents of the file are pulled out of MongoDB on the fly, so that the whole file does not have to be loaded into memory first.
     * At most two GridFSFile chunks will be loaded in memory.
     *
     * @link https://php.net/manual/en/mongogridfsfile.getresource.php
     * @return resource Returns a resource that can be used to read the file with
     */
    public function getResource() {}
}

class MongoGridFSCursor extends MongoCursor implements Traversable, Iterator
{
    /**
     * @var bool
     */
    public static $slaveOkay;

    /**
     * @link https://php.net/manual/en/class.mongogridfscursor.php#mongogridfscursor.props.gridfs
     * @var MongoGridFS|null
     */
    protected $gridfs;

    /**
     * Create a new cursor
     * @link https://php.net/manual/en/mongogridfscursor.construct.php
     * @param MongoGridFS $gridfs Related GridFS collection
     * @param resource $connection Database connection
     * @param string $ns Full name of database and collection
     * @param array $query Database query
     * @param array $fields Fields to return
     */
    public function __construct($gridfs, $connection, $ns, $query, $fields) {}

    /**
     * Return the next file to which this cursor points, and advance the cursor
     * @link https://php.net/manual/en/mongogridfscursor.getnext.php
     * @return MongoGridFSFile Returns the next file
     */
    public function getNext() {}

    /**
     * Returns the current file
     * @link https://php.net/manual/en/mongogridfscursor.current.php
     * @return MongoGridFSFile The current file
     */
    public function current() {}

    /**
     * Returns the current result's filename
     * @link https://php.net/manual/en/mongogridfscursor.key.php
     * @return string The current results filename
     */
    public function key() {}
}

/**
 * A unique identifier created for database objects.
 * @link https://secure.php.net/manual/en/class.mongoid.php
 */
class MongoId
{
     /**
      * @var string <p> Note: The property name begins with a $ character. It may be accessed using
      * {@link https://php.net/manual/en/language.types.string.php#language.types.string.parsing.complex complex variable parsed syntax} (e.g. $mongoId->{'$id'}).</p>
      */
     public $id = null;

    /**
     * (PECL mongo &gt;= 0.8.0)
     * Creates a new id
     * @link https://secure.php.net/manual/en/mongoid.construct.php
     * @param MongoId|string $id [optional] A string to use as the id. Must be 24 hexadecimal characters. If an invalid string is passed to this constructor, the constructor will ignore it and create a new id value.
     */
    public function __construct($id = null) {}

    /**
     * (PECL mongo &gt;= 0.8.0)
     * Check if a value is a valid ObjectId
     * @link https://php.net/manual/en/mongoid.isvalid.php
     * @param mixed $value The value to check for validity.
     * @return bool <p>
     * Returns <b>TRUE</b> if <i>value</i> is a
     * MongoId instance or a string consisting of exactly 24
     * hexadecimal characters; otherwise, <b>FALSE</b> is returned.
     * </p>
     */
    public static function isValid($value) {}

    /**
     * (PECL mongo &gt;= 0.8.0)
     * Returns a hexadecimal representation of this id
     * @link https://secure.php.net/manual/en/mongoid.tostring.php
     * @return string This id.
     */
    public function __toString() {}

    /**
     * (PECL mongo &gt;= 1.0.11)
     * Gets the incremented value to create this id
     * @link https://php.net/manual/en/mongoid.getinc.php
     * @return int Returns the incremented value used to create this MongoId.
     */
    public function getInc() {}

    /**
     * (PECL mongo &gt;= 1.0.11)
     * Gets the process ID
     * @link https://php.net/manual/en/mongoid.getpid.php
     * @return int Returns the PID of the MongoId.
     */
    public function getPID() {}

    /**
     * (PECL mongo &gt;= 1.0.1)
     * Gets the number of seconds since the epoch that this id was created
     * @link https://secure.php.net/manual/en/mongoid.gettimestamp.php
     * @return int
     */
    public function getTimestamp() {}

    /**
     * (PECL mongo &gt;= 1.0.8)
     * Gets the hostname being used for this machine's ids
     * @link https://secure.php.net/manual/en/mongoid.gethostname.php
     * @return string Returns the hostname.
     */
    public static function getHostname() {}

    /**
     * (PECL mongo &gt;= 1.0.8)
     * Create a dummy MongoId
     * @link https://php.net/manual/en/mongoid.set-state.php
     * @param array $props <p>Theoretically, an array of properties used to create the new id. However, as MongoId instances have no properties, this is not used.</p>
     * @return MongoId A new id with the value "000000000000000000000000".
     */
    public static function __set_state(array $props) {}
}

class MongoCode
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var array
     */
    public $scope;

    /**
     * .
     *
     * @link https://php.net/manual/en/mongocode.construct.php
     * @param string $code A string of code
     * @param array $scope The scope to use for the code
     */
    public function __construct($code, array $scope = []) {}

    /**
     * Returns this code as a string
     * @return string
     */
    public function __toString() {}
}

class MongoRegex
{
    /**
     * @link https://php.net/manual/en/class.mongoregex.php#mongoregex.props.regex
     * @var string
     */
    public $regex;

    /**
     * @link https://php.net/manual/en/class.mongoregex.php#mongoregex.props.flags
     * @var string
     */
    public $flags;

    /**
     * Creates a new regular expression.
     *
     * @link https://php.net/manual/en/mongoregex.construct.php
     * @param string $regex Regular expression string of the form /expr/flags
     */
    public function __construct($regex) {}

    /**
     * Returns a string representation of this regular expression.
     * @return string This regular expression in the form "/expr/flags".
     */
    public function __toString() {}
}

class MongoDate
{
    /**
     * @link https://php.net/manual/en/class.mongodate.php#mongodate.props.sec
     * @var int
     */
    public $sec;

    /**
     * @link https://php.net/manual/en/class.mongodate.php#mongodate.props.usec
     * @var int
     */
    public $usec;

    /**
     * Creates a new date. If no parameters are given, the current time is used.
     *
     * @link https://php.net/manual/en/mongodate.construct.php
     * @param int $sec Number of seconds since January 1st, 1970
     * @param int $usec Microseconds
     */
    public function __construct($sec = 0, $usec = 0) {}

    /**
     * Returns a DateTime object representing this date
     * @link https://php.net/manual/en/mongodate.todatetime.php
     * @return DateTime
     */
    public function toDateTime() {}

    /**
     * Returns a string representation of this date
     * @return string
     */
    public function __toString() {}
}

class MongoBinData
{
    /**
     * Generic binary data.
     * @link https://php.net/manual/en/class.mongobindata.php#mongobindata.constants.custom
     */
    public const GENERIC = 0x0;

    /**
     * Function
     * @link https://php.net/manual/en/class.mongobindata.php#mongobindata.constants.func
     */
    public const FUNC = 0x1;

    /**
     * Generic binary data (deprecated in favor of MongoBinData::GENERIC)
     * @link https://php.net/manual/en/class.mongobindata.php#mongobindata.constants.byte-array
     */
    public const BYTE_ARRAY = 0x2;

    /**
     * Universally unique identifier (deprecated in favor of MongoBinData::UUID_RFC4122)
     * @link https://php.net/manual/en/class.mongobindata.php#mongobindata.constants.uuid
     */
    public const UUID = 0x3;

    /**
     * Universally unique identifier (according to » RFC 4122)
     * @link https://php.net/manual/en/class.mongobindata.php#mongobindata.constants.custom
     */
    public const UUID_RFC4122 = 0x4;

    /**
     * MD5
     * @link https://php.net/manual/en/class.mongobindata.php#mongobindata.constants.md5
     */
    public const MD5 = 0x5;

    /**
     * User-defined type
     * @link https://php.net/manual/en/class.mongobindata.php#mongobindata.constants.custom
     */
    public const CUSTOM = 0x80;

    /**
     * @link https://php.net/manual/en/class.mongobindata.php#mongobindata.props.bin
     * @var string
     */
    public $bin;

    /**
     * @link https://php.net/manual/en/class.mongobindata.php#mongobindata.props.type
     * @var int
     */
    public $type;

    /**
     * Creates a new binary data object.
     *
     * @link https://php.net/manual/en/mongobindata.construct.php
     * @param string $data Binary data
     * @param int $type Data type
     */
    public function __construct($data, $type = 2) {}

    /**
     * Returns the string representation of this binary data object.
     * @return string
     */
    public function __toString() {}
}

class MongoDBRef
{
    /**
     * @var string
     */
    protected static $refKey = '$ref';

    /**
     * @var string
     */
    protected static $idKey = '$id';

    /**
     * If no database is given, the current database is used.
     *
     * @link https://php.net/manual/en/mongodbref.create.php
     * @param string $collection Collection name (without the database name)
     * @param mixed $id The _id field of the object to which to link
     * @param string $database Database name
     * @return array Returns the reference
     */
    public static function create($collection, $id, $database = null) {}

    /**
     * This not actually follow the reference, so it does not determine if it is broken or not.
     * It merely checks that $ref is in valid database reference format (in that it is an object or array with $ref and $id fields).
     *
     * @link https://php.net/manual/en/mongodbref.isref.php
     * @param mixed $ref Array or object to check
     * @return bool Returns true if $ref is a reference
     */
    public static function isRef($ref) {}

    /**
     * Fetches the object pointed to by a reference
     * @link https://php.net/manual/en/mongodbref.get.php
     * @param MongoDB $db Database to use
     * @param array $ref Reference to fetch
     * @return array|null Returns the document to which the reference refers or null if the document does not exist (the reference is broken)
     */
    public static function get($db, $ref) {}
}

class MongoWriteBatch
{
    public const COMMAND_INSERT = 1;
    public const COMMAND_UPDATE = 2;
    public const COMMAND_DELETE = 3;

    /**
     * <p>(PECL mongo &gt;= 1.5.0)</p>
     * MongoWriteBatch constructor.
     * @link https://php.net/manual/en/mongowritebatch.construct.php
     * @param MongoCollection $collection The {@see MongoCollection} to execute the batch on.
     * Its {@link https://php.net/manual/en/mongo.writeconcerns.php write concern}
     * will be copied and used as the default write concern if none is given as <code >$write_options</code> or during
     * {@see MongoWriteBatch::execute()}.
     * @param string $batch_type [optional] <p>
     * One of:
     * </p><ul>
     * <li class="member"><em>0</em> - make an MongoWriteBatch::COMMAND_INSERT batch</li>
     * <li class="member"><em>1</em> - make an MongoWriteBatch::COMMAND_UPDATE batch</li>
     * <li class="member"><em>2</em> - make a  MongoWriteBatch::COMMAND_DELETE batch</li>
     * </ul>
     * @param array $write_options [optional]
     * <p> An array of Write Options.</p><table><thead><tr><th>key</th><th>value meaning</th></tr>
     * </thead>
     * <tbody><tr><td>w (int|string)</td><td>{@link https://php.net/manual/en/mongo.writeconcerns.php Write concern} value</td></tr>
     * <tr><td>wtimeout (int)</td><td>{@link https://php.net/manual/en/mongo.writeconcerns.php Maximum time to wait for replication}</td></tr>
     * <tr><td>ordered</td><td>Determins if MongoDB must apply this batch in order (sequentally, one item at a time) or can rearrange it.
     * Defaults to <strong><code>TRUE</code></strong></td></tr>
     * <tr><td>j (bool)</td><td>Wait for journaling on the primary. This value is discouraged, use WriteConcern instead</td></tr>
     * <tr><td>fsync (bool)</td><td>Wait for fsync on the primary. This value is discouraged, use WriteConcern instead</td></tr>
     * </tbody></table>
     */
    protected function __construct($collection, $batch_type, $write_options) {}

    /**
     * <p>(PECL mongo &gt;= 1.5.0)</p>
     * Adds a write operation to a batch
     * @link https://php.net/manual/en/mongowritebatch.add.php
     * @param array $item <p>
     * An array that describes a write operation. The structure of this value
     * depends on the batch's operation type.
     * </p><table>
     * <thead>
     * <tr>
     * <th>Batch type</th>
     * <th>Argument expectation</th>
     * </tr>
     *
     * </thead>
     *
     * <tbody>
     * <tr>
     * <td><strong><code>MongoWriteBatch::COMMAND_INSERT</code></strong></td>
     * <td>
     * The document to add.
     * </td>
     * </tr>
     * <tr>
     * <td><strong><code>MongoWriteBatch::COMMAND_UPDATE</code></strong></td>
     * <td>
     * <p>Raw update operation.</p>
     * <p>Required keys are <em>"q"</em> and <em>"u"</em>, which correspond to the
     * <code>$criteria</code> and <code>$new_object</code> parameters of {@see MongoCollection::update()}, respectively.</p>
     * <p>Optional keys are <em>"multi"</em> and <em>"upsert"</em>, which correspond to the
     * <em>"multiple"</em> and <em>"upsert"</em> options for {@see MongoCollection::update()}, respectively.
     * If unspecified, both options default to <strong><code>FALSE</code></strong>.</p>
     * </td>
     * </tr>
     * <tr>
     * <td><strong><code>MongoWriteBatch::COMMAND_DELETE</code></strong></td>
     * <td>
     * <p class="para">Raw delete operation.</p>
     * <p>Required keys are: <em>"q"</em> and <em>"limit"</em>, which correspond to the <code>$criteria</code> parameter
     * and <em>"justOne"</em> option of {@see MongoCollection::remove()}, respectively.</p>
     * <p>The <em>"limit"</em> option is an integer; however, MongoDB only supports <em>0</em> (i.e. remove all matching
     * ocuments) and <em>1</em> (i.e. remove at most one matching document) at this time.</p>
     * </td>
     * </tr>
     * </tbody>
     * </table>
     * @return bool <b>Returns TRUE on success and throws an exception on failure.</b>
     */
    public function add(array $item) {}

    /**
     * <p>(PECL mongo &gt;= 1.5.0)</p>
     * Executes a batch of write operations
     * @link https://php.net/manual/en/mongowritebatch.execute.php
     * @param array $write_options See {@see MongoWriteBatch::__construct}
     * @return array Returns an array containing statistical information for the full batch.
     * If the batch had to be split into multiple batches, the return value will aggregate the values from individual batches and return only the totals.
     * If the batch was empty, an array containing only the 'ok' field is returned (as <b>TRUE</b>) although nothing will be shipped over the wire (NOOP).
     */
    final public function execute(array $write_options) {}
}

class MongoUpdateBatch extends MongoWriteBatch
{
    /**
     * <p>(PECL mongo &gt;= 1.5.0)</p>
     * MongoUpdateBatch constructor.
     * @link https://php.net/manual/en/mongoupdatebatch.construct.php
     * @param MongoCollection $collection <p>The MongoCollection to execute the batch on.
     * Its write concern will be copied and used as the default write concern
     * if none is given as $write_options or during {@see MongoWriteBatch::execute()}.</p>
     * @param array $write_options <p class="para">An array of Write Options.</p><table class="doctable informaltable"><thead><tr><th>key</th><th>value meaning</th></tr>
     * </thead>
     * <tbody class="tbody"><tr><td>w (int|string)</td><td>{@link https://php.net/manual/en/mongo.writeconcerns.php Write concern} value</td></tr>
     * <tr><td>wtimeout (int)</td><td>{@link https://php.net/manual/en/mongo.writeconcerns.php Maximum time to wait for replication}</td></tr>
     * <tr><td>ordered</td><td>Determins if MongoDB must apply this batch in order (sequentally, one item at a time) or can rearrange it. Defaults to <strong><code>TRUE</code></strong></td></tr>
     * <tr><td>j (bool)</td><td>Wait for journaling on the primary. This value is discouraged, use WriteConcern instead</td></tr>
     * <tr><td>fsync (bool)</td><td>Wait for fsync on the primary. This value is discouraged, use WriteConcern instead</td></tr>
     * </tbody></table>
     */
    public function __construct(MongoCollection $collection, array $write_options) {}
}

class MongoException extends Exception {}

class MongoCursorException extends MongoException {}

class MongoCursorTimeoutException extends MongoCursorException {}

class MongoConnectionException extends MongoException {}

class MongoGridFSException extends MongoException {}

/**
 * <p>(PECL mongo &gt;= 1.5.0)</p>
 * @link https://php.net/manual/en/class.mongowriteconcernexception.php#class.mongowriteconcernexception
 */
class MongoWriteConcernException extends MongoCursorException
{
    /**
     * Get the error document
     * @link https://php.net/manual/en/mongowriteconcernexception.getdocument.php
     * @return array <p>A MongoDB document, if available, as an array.</p>
     */
    public function getDocument() {}
}

/**
 * <p>(PECL mongo &gt;= 1.5.0)</p>
 * @link https://php.net/manual/en/class.mongoexecutiontimeoutexception.php
 */
class MongoExecutionTimeoutException extends MongoException {}

/**
 * <p>(PECL mongo &gt;= 1.5.0)</p>
 */
class MongoProtocolException extends MongoException {}

/**
 * <p>(PECL mongo &gt;= 1.5.0)</p>
 * @link https://php.net/manual/en/class.mongoduplicatekeyexception.php
 */
class MongoDuplicateKeyException extends MongoWriteConcernException {}

/**
 * <p>(PECL mongo &gt;= 1.3.0)</p>
 * @link https://php.net/manual/en/class.mongoresultexception.php#mongoresultexception.props.document
 */
class MongoResultException extends MongoException
{
    /**
     * <p>(PECL mongo &gt;= 1.3.0)</p>
     * Retrieve the full result document
     * https://secure.php.net/manual/en/mongoresultexception.getdocument.php
     * @return array <p>The full result document as an array, including partial data if available and additional keys.</p>
     */
    public function getDocument() {}
    public $document;
}

class MongoTimestamp
{
    /**
     * @link https://php.net/manual/en/class.mongotimestamp.php#mongotimestamp.props.sec
     * @var int
     */
    public $sec;

    /**
     * @link https://php.net/manual/en/class.mongotimestamp.php#mongotimestamp.props.inc
     * @var int
     */
    public $inc;

    /**
     * Creates a new timestamp. If no parameters are given, the current time is used
     * and the increment is automatically provided. The increment is set to 0 when the
     * module is loaded and is incremented every time this constructor is called
     * (without the $inc parameter passed in).
     *
     * @link https://php.net/manual/en/mongotimestamp.construct.php
     * @param int $sec [optional] Number of seconds since January 1st, 1970
     * @param int $inc [optional] Increment
     */
    public function __construct($sec = 0, $inc) {}

    /**
     * @return string
     */
    public function __toString() {}
}

class MongoInt32
{
    /**
     * @link https://php.net/manual/en/class.mongoint32.php#mongoint32.props.value
     * @var string
     */
    public $value;

    /**
     * Creates a new 32-bit number with the given value.
     *
     * @link https://php.net/manual/en/mongoint32.construct.php
     * @param string $value A number
     */
    public function __construct($value) {}

    /**
     * @return string
     */
    public function __toString() {}
}

class MongoInt64
{
    /**
     * @link https://php.net/manual/en/class.mongoint64.php#mongoint64.props.value
     * @var string
     */
    public $value;

    /**
     * Creates a new 64-bit number with the given value.
     *
     * @link https://php.net/manual/en/mongoint64.construct.php
     * @param string $value A number
     */
    public function __construct($value) {}

    /**
     * @return string
     */
    public function __toString() {}
}

class MongoLog
{
    /**
     * @link https://php.net/manual/en/class.mongolog.php#mongolog.constants.none
     */
    public const NONE = 0;

    /**
     * @link https://php.net/manual/en/class.mongolog.php#mongolog.constants.all
     */
    public const ALL = 0;

    /**
     * @link https://php.net/manual/en/class.mongolog.php#mongolog.constants.warning
     */
    public const WARNING = 0;

    /**
     * @link https://php.net/manual/en/class.mongolog.php#mongolog.constants.info
     */
    public const INFO = 0;

    /**
     * @link https://php.net/manual/en/class.mongolog.php#mongolog.constants.fine
     */
    public const FINE = 0;

    /**
     * @link https://php.net/manual/en/class.mongolog.php#mongolog.constants.rs
     */
    public const RS = 0;

    /**
     * @link https://php.net/manual/en/class.mongolog.php#mongolog.constants.pool
     */
    public const POOL = 0;

    /**
     * @link https://php.net/manual/en/class.mongolog.php#mongolog.constants.io
     */
    public const IO = 0;

    /**
     * @link https://php.net/manual/en/class.mongolog.php#mongolog.constants.server
     */
    public const SERVER = 0;

    /**
     * @link https://php.net/manual/en/class.mongolog.php#mongolog.constants.parse
     */
    public const PARSE = 0;
    public const CON = 2;

    /**
     * (PECL mongo &gt;= 1.3.0)<br/>
     * <p>
     * This function will set a callback function to be called for {@link https://secure.php.net/manual/en/class.mongolog.php MongoLog} events
     * instead of triggering warnings.
     * </p>
     * @link https://secure.php.net/manual/en/mongolog.setcallback.php
     * @param callable $log_function   <p>
     * The function to be called on events.
     * </p>
     * <p>
     * The function should have the following prototype
     * </p>
     *
     * <em>log_function</em> ( <em>int</em> <em>$module</em> , <em>int</em> <em>$level</em>, <em>string</em> <em>$message</em>)
     * <ul>
     * <li>
     * <b><i>module</i></b>
     *
     * <p>One of the {@link https://secure.php.net/manual/en/class.mongolog.php#mongolog.constants.module MongoLog module constants}.</p>
     * </li>
     * <li>
     * <b><i>level</i></b>
     *
     * <p>One of the {@link https://secure.php.net/manual/en/class.mongolog.php#mongolog.constants.level MongoLog level constants}.</p>
     * </li>
     * <li>
     * <b><i>message</i></b>
     *
     * <p>The log message itself.</p></li>
     * <ul>
     * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public static function setCallback(callable $log_function) {}

    /**
     * This function can be used to set how verbose logging should be and the types of
     * activities that should be logged. Use the constants described in the MongoLog
     * section with bitwise operators to specify levels.
     *
     * @link https://php.net/manual/en/mongolog.setlevel.php
     * @param int $level The levels you would like to log
     * @return void
     */
    public static function setLevel($level) {}

    /**
     * This can be used to see the log level. Use the constants described in the
     * MongoLog section with bitwise operators to check the level.
     *
     * @link https://php.net/manual/en/mongolog.getlevel.php
     * @return int Returns the current level
     */
    public static function getLevel() {}

    /**
     * This function can be used to set which parts of the driver's functionality
     * should be logged. Use the constants described in the MongoLog section with
     * bitwise operators to specify modules.
     *
     * @link https://php.net/manual/en/mongolog.setmodule.php
     * @param int $module The module(s) you would like to log
     * @return void
     */
    public static function setModule($module) {}

    /**
     * This function can be used to see which parts of the driver's functionality are
     * being logged. Use the constants described in the MongoLog section with bitwise
     * operators to check if specific modules are being logged.
     *
     * @link https://php.net/manual/en/mongolog.getmodule.php
     * @return int Returns the modules currently being logged
     */
    public static function getModule() {}
}

class MongoPool
{
    /**
     * Returns an array of information about all connection pools.
     *
     * @link https://php.net/manual/en/mongopool.info.php
     * @return array Each connection pool has an identifier, which starts with the host. For
     *         each pool, this function shows the following fields: $in use The number of
     *         connections currently being used by Mongo instances. $in pool The number of
     *         connections currently in the pool (not being used). $remaining The number of
     *         connections that could be created by this pool. For example, suppose a pool had
     *         5 connections remaining and 3 connections in the pool. We could create 8 new
     *         instances of Mongo before we exhausted this pool (assuming no instances of Mongo
     *         went out of scope, returning their connections to the pool). A negative number
     *         means that this pool will spawn unlimited connections. Before a pool is created,
     *         you can change the max number of connections by calling Mongo::setPoolSize. Once
     *         a pool is showing up in the output of this function, its size cannot be changed.
     *         $total The total number of connections allowed for this pool. This should be
     *         greater than or equal to "in use" + "in pool" (or -1). $timeout The socket
     *         timeout for connections in this pool. This is how long connections in this pool
     *         will attempt to connect to a server before giving up. $waiting If you have
     *         capped the pool size, workers requesting connections from the pool may block
     *         until other workers return their connections. This field shows how many
     *         milliseconds workers have blocked for connections to be released. If this number
     *         keeps increasing, you may want to use MongoPool::setSize to add more connections
     *         to your pool
     */
    public static function info() {}

    /**
     * Sets the max number of connections new pools will be able to create.
     *
     * @link https://php.net/manual/en/mongopool.setsize.php
     * @param int $size The max number of connections future pools will be able to
     *        create. Negative numbers mean that the pool will spawn an infinite number of
     *        connections
     * @return bool Returns the former value of pool size
     */
    public static function setSize($size) {}

    /**
     * .
     *
     * @link https://php.net/manual/en/mongopool.getsize.php
     * @return int Returns the current pool size
     */
    public static function getSize() {}
}

class MongoMaxKey {}

class MongoMinKey {}
