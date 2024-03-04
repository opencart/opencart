<?php

/**
 * Helper autocomplete for php cassandra extension.
 * Compiled using the https://github.com/datastax/php-driver/blob/master/ext/doc/generate_doc.sh
 *
 * @see https://github.com/datastax/php-driver/tree/master/ext/doc
 *
 * @author Vasyl Sovyak <soulshockers@gmail.com>
 * @link   https://github.com/soulshockers/cassandra-phpdoc
 */

/**
 * Copyright 2019 DataStax, Inc.
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
 */

namespace {
    /**
     * The main entry point to the PHP Driver for Apache Cassandra.
     *
     * Use Cassandra::cluster() to build a cluster instance.
     * Use Cassandra::ssl() to build SSL options instance.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/
     */
    final class Cassandra
    {
        /**
         * Consistency level ANY means the request is fulfilled as soon as the data
         * has been written on the Coordinator. Requests with this consistency level
         * are not guaranteed to make it to Replica nodes.
         *
         * @see \Cassandra\Session::execute()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-CONSISTENCY_ANY
         */
        public const CONSISTENCY_ANY = 0;

        /**
         * Consistency level ONE guarantees that data has been written to at least
         * one Replica node.
         *
         * @see \Cassandra\Session::execute()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-CONSISTENCY_ONE
         */
        public const CONSISTENCY_ONE = 1;

        /**
         * Consistency level TWO guarantees that data has been written to at least
         * two Replica nodes.
         *
         * @see \Cassandra\Session::execute()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-CONSISTENCY_TWO
         */
        public const CONSISTENCY_TWO = 2;

        /**
         * Consistency level THREE guarantees that data has been written to at least
         * three Replica nodes.
         *
         * @see \Cassandra\Session::execute()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-CONSISTENCY_THREE
         */
        public const CONSISTENCY_THREE = 3;

        /**
         * Consistency level QUORUM guarantees that data has been written to at least
         * the majority of Replica nodes. How many nodes exactly are a majority
         * depends on the replication factor of a given keyspace and is calculated
         * using the formula `ceil(RF / 2 + 1)`, where `ceil` is a mathematical
         * ceiling function and `RF` is the replication factor used. For example,
         * for a replication factor of `5`, the majority is `ceil(5 / 2 + 1) = 3`.
         *
         * @see \Cassandra\Session::execute()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-CONSISTENCY_QUORUM
         */
        public const CONSISTENCY_QUORUM = 4;

        /**
         * Consistency level ALL guarantees that data has been written to all
         * Replica nodes.
         *
         * @see \Cassandra\Session::execute()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-CONSISTENCY_ALL
         */
        public const CONSISTENCY_ALL = 5;

        /**
         * Same as `CONSISTENCY_QUORUM`, but confined to the local data center. This
         * consistency level works only with `NetworkTopologyStrategy` replication.
         *
         * @see \Cassandra\Session::execute()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-CONSISTENCY_LOCAL_QUORUM
         */
        public const CONSISTENCY_LOCAL_QUORUM = 6;

        /**
         * Consistency level EACH_QUORUM guarantees that data has been written to at
         * least a majority Replica nodes in all datacenters. This consistency level
         * works only with `NetworkTopologyStrategy` replication.
         *
         * @see \Cassandra\Session::execute()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-CONSISTENCY_EACH_QUORUM
         */
        public const CONSISTENCY_EACH_QUORUM = 7;

        /**
         * This is a serial consistency level, it is used in conditional updates,
         * e.g. (`CREATE|INSERT ... IF NOT EXISTS`), and should be specified as the
         * `serial_consistency` execution option when invoking `session.execute`
         * or `session.execute_async`.
         *
         * Consistency level SERIAL, when set, ensures that a Paxos commit fails if
         * any of the replicas is down.
         *
         * @see \Cassandra\Session::execute()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-CONSISTENCY_SERIAL
         */
        public const CONSISTENCY_SERIAL = 8;

        /**
         * Same as `CONSISTENCY_SERIAL`, but confined to the local data center. This
         * consistency level works only with `NetworkTopologyStrategy` replication.
         *
         * @see \Cassandra\Session::execute()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-CONSISTENCY_LOCAL_SERIAL
         */
        public const CONSISTENCY_LOCAL_SERIAL = 9;

        /**
         * Same as `CONSISTENCY_ONE`, but confined to the local data center. This
         * consistency level works only with `NetworkTopologyStrategy` replication.
         *
         * @see \Cassandra\Session::execute()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-CONSISTENCY_LOCAL_ONE
         */
        public const CONSISTENCY_LOCAL_ONE = 10;

        /**
         * Perform no verification of nodes when using SSL encryption.
         *
         * @see \Cassandra\SSLOptions\Builder::withVerifyFlags()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-VERIFY_NONE
         */
        public const VERIFY_NONE = 0;

        /**
         * Verify presence and validity of SSL certificates.
         *
         * @see \Cassandra\SSLOptions\Builder::withVerifyFlags()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-VERIFY_PEER_CERT
         */
        public const VERIFY_PEER_CERT = 1;

        /**
         * Verify that the IP address matches the SSL certificate’s common name or
         * one of its subject alternative names. This implies the certificate is
         * also present.
         *
         * @see \Cassandra\SSLOptions\Builder::withVerifyFlags()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-VERIFY_PEER_IDENTITY
         */
        public const VERIFY_PEER_IDENTITY = 2;

        /**
         * @see \Cassandra\BatchStatement::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-BATCH_LOGGED
         */
        public const BATCH_LOGGED = 0;

        /**
         * @see \Cassandra\BatchStatement::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-BATCH_UNLOGGED
         */
        public const BATCH_UNLOGGED = 1;

        /**
         * @see \Cassandra\BatchStatement::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-BATCH_COUNTER
         */
        public const BATCH_COUNTER = 2;

        /**
         * Used to disable logging.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-LOG_DISABLED
         */
        public const LOG_DISABLED = 0;

        /**
         * Allow critical level logging.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-LOG_CRITICAL
         */
        public const LOG_CRITICAL = 1;

        /**
         * Allow error level logging.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-LOG_ERROR
         */
        public const LOG_ERROR = 2;

        /**
         * Allow warning level logging.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-LOG_WARN
         */
        public const LOG_WARN = 3;

        /**
         * Allow info level logging.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-LOG_INFO
         */
        public const LOG_INFO = 4;

        /**
         * Allow debug level logging.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-LOG_DEBUG
         */
        public const LOG_DEBUG = 5;

        /**
         * Allow trace level logging.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-LOG_TRACE
         */
        public const LOG_TRACE = 6;

        /**
         * When using a map, collection or set of type text, all of its elements
         * must be strings.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_TEXT
         */
        public const TYPE_TEXT = 'text';

        /**
         * When using a map, collection or set of type ascii, all of its elements
         * must be strings.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_ASCII
         */
        public const TYPE_ASCII = 'ascii';

        /**
         * When using a map, collection or set of type varchar, all of its elements
         * must be strings.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_VARCHAR
         */
        public const TYPE_VARCHAR = 'varchar';

        /**
         * When using a map, collection or set of type bigint, all of its elements
         * must be instances of Bigint.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_BIGINT
         */
        public const TYPE_BIGINT = 'bigint';

        /**
         * When using a map, collection or set of type smallint, all of its elements
         * must be instances of Inet.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_SMALLINT
         */
        public const TYPE_SMALLINT = 'smallint';

        /**
         * When using a map, collection or set of type tinyint, all of its elements
         * must be instances of Inet.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_TINYINT
         */
        public const TYPE_TINYINT = 'tinyint';

        /**
         * When using a map, collection or set of type blob, all of its elements
         * must be instances of Blob.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_BLOB
         */
        public const TYPE_BLOB = 'blob';

        /**
         * When using a map, collection or set of type bool, all of its elements
         * must be boolean.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_BOOLEAN
         */
        public const TYPE_BOOLEAN = 'boolean';

        /**
         * When using a map, collection or set of type counter, all of its elements
         * must be instances of Bigint.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_COUNTER
         */
        public const TYPE_COUNTER = 'counter';

        /**
         * When using a map, collection or set of type decimal, all of its elements
         * must be instances of Decimal.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_DECIMAL
         */
        public const TYPE_DECIMAL = 'decimal';

        /**
         * When using a map, collection or set of type double, all of its elements
         * must be doubles.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_DOUBLE
         */
        public const TYPE_DOUBLE = 'double';

        /**
         * When using a map, collection or set of type float, all of its elements
         * must be instances of Float.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_FLOAT
         */
        public const TYPE_FLOAT = 'float';

        /**
         * When using a map, collection or set of type int, all of its elements
         * must be ints.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_INT
         */
        public const TYPE_INT = 'int';

        /**
         * When using a map, collection or set of type timestamp, all of its elements
         * must be instances of Timestamp.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_TIMESTAMP
         */
        public const TYPE_TIMESTAMP = 'timestamp';

        /**
         * When using a map, collection or set of type uuid, all of its elements
         * must be instances of Uuid.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_UUID
         */
        public const TYPE_UUID = 'uuid';

        /**
         * When using a map, collection or set of type varint, all of its elements
         * must be instances of Varint.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_VARINT
         */
        public const TYPE_VARINT = 'varint';

        /**
         * When using a map, collection or set of type timeuuid, all of its elements
         * must be instances of Timeuuid.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_TIMEUUID
         */
        public const TYPE_TIMEUUID = 'timeuuid';

        /**
         * When using a map, collection or set of type inet, all of its elements
         * must be instances of Inet.
         *
         * @see Set::__construct()
         * @see Collection::__construct()
         * @see Map::__construct()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-TYPE_INET
         */
        public const TYPE_INET = 'inet';

        /**
         * The current version of the extension.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-VERSION
         */
        public const VERSION = '1.3.2';

        /**
         * The version of the cpp-driver the extension is compiled against.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#constant-CPP_DRIVER_VERSION
         */
        public const CPP_DRIVER_VERSION = '2.13.0';

        /**
         * Creates a new cluster builder for constructing a Cluster object.
         *
         * @return \Cassandra\Cluster\Builder A cluster builder object with default settings
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#method-cluster
         */
        public static function cluster() {}

        /**
         * Creates a new ssl builder for constructing a SSLOptions object.
         *
         * @return \Cassandra\SSLOptions\Builder A SSL options builder with default settings
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/class.Cassandra/#method-ssl
         */
        public static function ssl() {}
    }
}

/**
 * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/
 */

namespace Cassandra {
    use JetBrains\PhpStorm\Deprecated;

    /**
     * A PHP representation of a column
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Column/
     */
    interface Column
    {
        /**
         * Returns the name of the column.
         *
         * @return string Name of the column or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Column/#method-name
         */
        public function name();

        /**
         * Returns the type of the column.
         *
         * @return \Cassandra\Type Type of the column
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Column/#method-type
         */
        public function type();

        /**
         * Returns whether the column is in descending or ascending order.
         *
         * @return bool Whether the column is stored in descending order.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Column/#method-isReversed
         */
        public function isReversed();

        /**
         * Returns true for static columns.
         *
         * @return bool Whether the column is static
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Column/#method-isStatic
         */
        public function isStatic();

        /**
         * Returns true for frozen columns.
         *
         * @return bool Whether the column is frozen
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Column/#method-isFrozen
         */
        public function isFrozen();

        /**
         * Returns name of the index if defined.
         *
         * @return string Name of the index if defined or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Column/#method-indexName
         */
        public function indexName();

        /**
         * Returns index options if present.
         *
         * @return string Index options if present or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Column/#method-indexOptions
         */
        public function indexOptions();
    }

    /**
     * A session is used to prepare and execute statements.
     *
     * @see \Cassandra\Cluster::connect()
     * @see \Cassandra\Cluster::connectAsync()
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Session/
     */
    interface Session
    {
        /**
         * Execute a query.
         *
         * Available execution options:
         * | Option Name        | Option **Type** | Option Details                                                                                           |
         * |--------------------|-----------------|----------------------------------------------------------------------------------------------------------|
         * | arguments          | array           | An array or positional or named arguments                                                                |
         * | consistency        | int             | A consistency constant e.g Dse::CONSISTENCY_ONE, Dse::CONSISTENCY_QUORUM, etc.                           |
         * | timeout            | int             | A number of rows to include in result for paging                                                         |
         * | paging_state_token | string          | A string token use to resume from the state of a previous result set                                     |
         * | retry_policy       | RetryPolicy     | A retry policy that is used to handle server-side failures for this request                              |
         * | serial_consistency | int             | Either Dse::CONSISTENCY_SERIAL or Dse::CONSISTENCY_LOCAL_SERIAL                                          |
         * | timestamp          | int\|string     | Either an integer or integer string timestamp that represents the number of microseconds since the epoch |
         * | execute_as         | string          | User to execute statement as                                                                             |
         *
         * @param string|\Cassandra\Statement $statement string or statement to be executed.
         * @param array|\Cassandra\ExecutionOptions|null $options Options to control execution of the query.
         *
         * @return \Cassandra\Rows A collection of rows.
         * @throws \Cassandra\Exception
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Session/#method-execute
         */
        public function execute($statement, $options);

        /**
         * Execute a query asynchronously. This method returns immediately, but
         * the query continues execution in the background.
         *
         * @param string|\Cassandra\Statement $statement string or statement to be executed.
         * @param array|\Cassandra\ExecutionOptions|null $options Options to control execution of the query.
         *
         * @return \Cassandra\FutureRows A future that can be used to retrieve the result.
         *
         * @see \Cassandra\Session::execute() for valid execution options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Session/#method-executeAsync
         */
        public function executeAsync($statement, $options);

        /**
         * Prepare a query for execution.
         *
         * @param string $cql The query to be prepared.
         * @param array|\Cassandra\ExecutionOptions|null $options Options to control preparing the query.
         *
         * @return \Cassandra\PreparedStatement A prepared statement that can be bound with parameters and executed.
         *
         * @throws \Cassandra\Exception
         *
         * @see \Cassandra\Session::execute() for valid execution options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Session/#method-prepare
         */
        public function prepare($cql, $options);

        /**
         * Asynchronously prepare a query for execution.
         *
         * @param string $cql The query to be prepared.
         * @param array|\Cassandra\ExecutionOptions|null $options Options to control preparing the query.
         *
         * @return \Cassandra\FuturePreparedStatement A future that can be used to retrieve the prepared statement.
         *
         * @see \Cassandra\Session::execute() for valid execution options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Session/#method-prepareAsync
         */
        public function prepareAsync($cql, $options);

        /**
         * Close the session and all its connections.
         *
         * @param float $timeout The amount of time in seconds to wait for the session to close.
         *
         * @return null Nothing.
         * @throws \Cassandra\Exception
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Session/#method-close
         */
        public function close($timeout);

        /**
         * Asynchronously close the session and all its connections.
         *
         * @return \Cassandra\FutureClose A future that can be waited on.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Session/#method-closeAsync
         */
        public function closeAsync();

        /**
         * Get performance and diagnostic metrics.
         *
         * @return array Performance/Diagnostic metrics.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Session/#method-metrics
         */
        public function metrics();

        /**
         * Get a snapshot of the cluster's current schema.
         *
         * @return \Cassandra\Schema A snapshot of the cluster's schema.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Session/#method-schema
         */
        public function schema();
    }

    /**
     * A PHP representation of a table
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/
     */
    interface Table
    {
        /**
         * Returns the name of this table
         *
         * @return string Name of the table
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-name
         */
        public function name();

        /**
         * Return a table's option by name
         *
         * @param string $name The name of the option
         *
         * @return \Cassandra\Value Value of an option by name
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-option
         */
        public function option($name);

        /**
         * Returns all the table's options
         *
         * @return array A dictionary of `string` and `Value` pairs of the table's options.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-options
         */
        public function options();

        /**
         * Description of the table, if any
         *
         * @return string Table description or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-comment
         */
        public function comment();

        /**
         * Returns read repair chance
         *
         * @return float Read repair chance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-readRepairChance
         */
        public function readRepairChance();

        /**
         * Returns local read repair chance
         *
         * @return float Local read repair chance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-localReadRepairChance
         */
        public function localReadRepairChance();

        /**
         * Returns GC grace seconds
         *
         * @return int GC grace seconds
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-gcGraceSeconds
         */
        public function gcGraceSeconds();

        /**
         * Returns caching options
         *
         * @return string Caching options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-caching
         */
        public function caching();

        /**
         * Returns bloom filter FP chance
         *
         * @return float Bloom filter FP chance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-bloomFilterFPChance
         */
        public function bloomFilterFPChance();

        /**
         * Returns memtable flush period in milliseconds
         *
         * @return int Memtable flush period in milliseconds
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-memtableFlushPeriodMs
         */
        public function memtableFlushPeriodMs();

        /**
         * Returns default TTL.
         *
         * @return int Default TTL.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-defaultTTL
         */
        public function defaultTTL();

        /**
         * Returns speculative retry.
         *
         * @return string Speculative retry.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-speculativeRetry
         */
        public function speculativeRetry();

        /**
         * Returns index interval
         *
         * @return int Index interval
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-indexInterval
         */
        public function indexInterval();

        /**
         * Returns compaction strategy class name
         *
         * @return string Compaction strategy class name
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-compactionStrategyClassName
         */
        public function compactionStrategyClassName();

        /**
         * Returns compaction strategy options
         *
         * @return \Cassandra\Map Compaction strategy options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-compactionStrategyOptions
         */
        public function compactionStrategyOptions();

        /**
         * Returns compression parameters
         *
         * @return \Cassandra\Map Compression parameters
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-compressionParameters
         */
        public function compressionParameters();

        /**
         * Returns whether or not the `populate_io_cache_on_flush` is true
         *
         * @return bool Value of `populate_io_cache_on_flush` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-populateIOCacheOnFlush
         */
        public function populateIOCacheOnFlush();

        /**
         * Returns whether or not the `replicate_on_write` is true
         *
         * @return bool Value of `replicate_on_write` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-replicateOnWrite
         */
        public function replicateOnWrite();

        /**
         * Returns the value of `max_index_interval`
         *
         * @return int Value of `max_index_interval` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-maxIndexInterval
         */
        public function maxIndexInterval();

        /**
         * Returns the value of `min_index_interval`
         *
         * @return int Value of `min_index_interval` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-minIndexInterval
         */
        public function minIndexInterval();

        /**
         * Returns column by name
         *
         * @param string $name Name of the column
         *
         * @return \Cassandra\Column Column instance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-column
         */
        public function column($name);

        /**
         * Returns all columns in this table
         *
         * @return array A list of Column instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-columns
         */
        public function columns();

        /**
         * Returns the partition key columns of the table
         *
         * @return array A list of of Column instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-partitionKey
         */
        public function partitionKey();

        /**
         * Returns both the partition and clustering key columns of the table
         *
         * @return array A list of of Column instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-primaryKey
         */
        public function primaryKey();

        /**
         * Returns the clustering key columns of the table
         *
         * @return array A list of of Column instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-clusteringKey
         */
        public function clusteringKey();

        /**
         * @return array A list of cluster column orders ('asc' and 'desc')
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Table/#method-clusteringOrder
         */
        public function clusteringOrder();
    }

    /**
     * Interface for retry policies.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.RetryPolicy/
     */
    interface RetryPolicy {}

    /**
     * Interface for timestamp generators.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.TimestampGenerator/
     */
    interface TimestampGenerator {}

    /**
     * An interface implemented by all exceptions thrown by the PHP Driver.
     * Makes it easy to catch all driver-related exceptions using
     * `catch (Exception $e)`.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Exception/
     */
    interface Exception {}

    /**
     * A PHP representation of a function
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Function/
     */
    interface Function_
    {
        /**
         * Returns the full name of the function
         *
         * @return string Full name of the function including name and types
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Function/#method-name
         */
        public function name();

        /**
         * Returns the simple name of the function
         *
         * @return string Simple name of the function
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Function/#method-simpleName
         */
        public function simpleName();

        /**
         * Returns the arguments of the function
         *
         * @return array Arguments of the function
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Function/#method-arguments
         */
        public function arguments();

        /**
         * Returns the return type of the function
         *
         * @return \Cassandra\Type Return type of the function
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Function/#method-returnType
         */
        public function returnType();

        /**
         * Returns the signature of the function
         *
         * @return string Signature of the function (same as name())
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Function/#method-signature
         */
        public function signature();

        /**
         * Returns the lanuage of the function
         *
         * @return string Language used by the function
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Function/#method-language
         */
        public function language();

        /**
         * Returns the body of the function
         *
         * @return string Body of the function
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Function/#method-body
         */
        public function body();

        /**
         * Determines if a function is called when the value is null.
         *
         * @return bool Returns whether the function is called when the input columns are null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Function/#method-isCalledOnNullInput
         */
        public function isCalledOnNullInput();
    }

    /**
     * A PHP representation of the CQL `uuid` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.UuidInterface/
     */
    interface UuidInterface
    {
        /**
         * Returns this uuid as string.
         *
         * @return string uuid as string
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.UuidInterface/#method-uuid
         */
        public function uuid();

        /**
         * Returns the version of this uuid.
         *
         * @return int version of this uuid
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.UuidInterface/#method-version
         */
        public function version();
    }

    /**
     * A PHP representation of an index
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Index/
     */
    interface Index
    {
        /**
         * Returns the name of the index
         *
         * @return string Name of the index
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Index/#method-name
         */
        public function name();

        /**
         * Returns the kind of index
         *
         * @return string Kind of the index
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Index/#method-kind
         */
        public function kind();

        /**
         * Returns the target column of the index
         *
         * @return string Target column name of the index
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Index/#method-target
         */
        public function target();

        /**
         * Return a column's option by name
         *
         * @param string $name The name of the option
         *
         * @return \Cassandra\Value Value of an option by name
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Index/#method-option
         */
        public function option($name);

        /**
         * Returns all the index's options
         *
         * @return array A dictionary of `string` and `Value` pairs of the index's options.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Index/#method-options
         */
        public function options();

        /**
         * Returns the class name of the index
         *
         * @return string Class name of a custom index
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Index/#method-className
         */
        public function className();

        /**
         * Determines if the index is a custom index.
         *
         * @return bool true if a custom index
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Index/#method-isCustom
         */
        public function isCustom();
    }

    /**
     * Cluster object is used to create Sessions.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Cluster/
     */
    interface Cluster
    {
        /**
         * Creates a new Session instance.
         *
         * @param string $keyspace Optional keyspace name
         *
         * @return \Cassandra\Session Session instance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Cluster/#method-connect
         */
        public function connect($keyspace);

        /**
         * Creates a new Session instance.
         *
         * @param string $keyspace Optional keyspace name
         *
         * @return \Cassandra\Future A Future Session instance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Cluster/#method-connectAsync
         */
        public function connectAsync($keyspace);
    }

    /**
     * Common interface implemented by all numeric types, providing basic
     * arithmetic functions.
     *
     * @see \Cassandra\Bigint
     * @see \Cassandra\Decimal
     * @see \Cassandra\Float_
     * @see \Cassandra\Varint
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Numeric/
     */
    interface Numeric
    {
        /**
         * @param \Cassandra\Numeric $num a number to add to this one
         *
         * @return \Cassandra\Numeric sum
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Numeric/#method-add
         */
        public function add($num);

        /**
         * @param \Cassandra\Numeric $num a number to subtract from this one
         *
         * @return \Cassandra\Numeric difference
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Numeric/#method-sub
         */
        public function sub($num);

        /**
         * @param \Cassandra\Numeric $num a number to multiply this one by
         *
         * @return \Cassandra\Numeric product
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Numeric/#method-mul
         */
        public function mul($num);

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric quotient
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Numeric/#method-div
         */
        public function div($num);

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric remainder
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Numeric/#method-mod
         */
        public function mod($num);

        /**
         * @return \Cassandra\Numeric absolute value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Numeric/#method-abs
         */
        public function abs();

        /**
         * @return \Cassandra\Numeric negative value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Numeric/#method-neg
         */
        public function neg();

        /**
         * @return \Cassandra\Numeric square root
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Numeric/#method-sqrt
         */
        public function sqrt();

        /**
         * @return int this number as int
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Numeric/#method-toInt
         */
        public function toInt();

        /**
         * @return float this number as float
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Numeric/#method-toDouble
         */
        public function toDouble();
    }

    /**
     * Futures are returns from asynchronous methods.
     *
     * @see \Cassandra\Cluster::connectAsync()
     * @see \Cassandra\Session::executeAsync()
     * @see \Cassandra\Session::prepareAsync()
     * @see \Cassandra\Session::closeAsync()
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Future/
     */
    interface Future
    {
        /**
         * Waits for a given future resource to resolve and throws errors if any.
         *
         * @param int|float|null $timeout A timeout in seconds
         *
         * @return mixed a value that the future has been resolved with
         * @throws \Cassandra\Exception\TimeoutException
         *
         * @throws \Cassandra\Exception\InvalidArgumentException
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Future/#method-get
         */
        public function get($timeout);
    }

    /**
     * A PHP representation of a keyspace
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/
     */
    interface Keyspace
    {
        /**
         * Returns keyspace name
         *
         * @return string Name
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-name
         */
        public function name();

        /**
         * Returns replication class name
         *
         * @return string Replication class
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-replicationClassName
         */
        public function replicationClassName();

        /**
         * Returns replication options
         *
         * @return \Cassandra\Map Replication options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-replicationOptions
         */
        public function replicationOptions();

        /**
         * Returns whether the keyspace has durable writes enabled
         *
         * @return string Whether durable writes are enabled
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-hasDurableWrites
         */
        public function hasDurableWrites();

        /**
         * Returns a table by name
         *
         * @param string $name Table name
         *
         * @return \Cassandra\Table|null Table instance or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-table
         */
        public function table($name);

        /**
         * Returns all tables defined in this keyspace
         *
         * @return array An array of `Table` instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-tables
         */
        public function tables();

        /**
         * Get user type by name
         *
         * @param string $name User type name
         *
         * @return \Cassandra\Type\UserType|null A user type or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-userType
         */
        public function userType($name);

        /**
         * Get all user types
         *
         * @return array An array of user types
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-userTypes
         */
        public function userTypes();

        /**
         * Get materialized view by name
         *
         * @param string $name Materialized view name
         *
         * @return \Cassandra\MaterizedView|null A materialized view or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-materializedView
         */
        public function materializedView($name);

        /**
         * Gets all materialized views
         *
         * @return array An array of materialized views
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-materializedViews
         */
        public function materializedViews();

        /**
         * Get a function by name and signature
         *
         * @param string $name Function name
         * @param string|\Cassandra\Type $params Function arguments
         *
         * @return \Cassandra\Function_|null A function or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-function
         */
        public function function_($name, ...$params);

        /**
         * Get all functions
         *
         * @return array An array of functions
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-functions
         */
        public function functions();

        /**
         * Get an aggregate by name and signature
         *
         * @param string $name Aggregate name
         * @param string|\Cassandra\Type $params Aggregate arguments
         *
         * @return \Cassandra\Aggregate|null An aggregate or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-aggregate
         */
        public function aggregate($name, ...$params);

        /**
         * Get all aggregates
         *
         * @return array An array of aggregates
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Keyspace/#method-aggregates
         */
        public function aggregates();
    }

    /**
     * Common interface implemented by all Cassandra value types.
     *
     * @see \Cassandra\Bigint
     * @see \Cassandra\Smallint
     * @see \Cassandra\Tinyint
     * @see \Cassandra\Blob
     * @see \Cassandra\Collection
     * @see \Cassandra\Float_
     * @see \Cassandra\Inet
     * @see \Cassandra\Map
     * @see \Cassandra\Set
     * @see \Cassandra\Timestamp
     * @see \Cassandra\Timeuuid
     * @see \Cassandra\Uuid
     * @see \Cassandra\Varint
     * @see \Cassandra\Date
     * @see \Cassandra\Time
     *
     * @see \Cassandra\Numeric
     * @see \Cassandra\UuidInterface
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Value/
     */
    interface Value
    {
        /**
         * The type of represented by the value.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Value/#method-type
         */
        public function type();
    }

    /**
     * A PHP representation of an aggregate
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Aggregate/
     */
    interface Aggregate
    {
        /**
         * Returns the full name of the aggregate
         *
         * @return string Full name of the aggregate including name and types
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Aggregate/#method-name
         */
        public function name();

        /**
         * Returns the simple name of the aggregate
         *
         * @return string Simple name of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Aggregate/#method-simpleName
         */
        public function simpleName();

        /**
         * Returns the argument types of the aggregate
         *
         * @return array Argument types of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Aggregate/#method-argumentTypes
         */
        public function argumentTypes();

        /**
         * Returns the final function of the aggregate
         *
         * @return \Cassandra\Function_ Final function of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Aggregate/#method-finalFunction
         */
        public function finalFunction();

        /**
         * Returns the state function of the aggregate
         *
         * @return \Cassandra\Function_ State function of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Aggregate/#method-stateFunction
         */
        public function stateFunction();

        /**
         * Returns the initial condition of the aggregate
         *
         * @return \Cassandra\Value Initial condition of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Aggregate/#method-initialCondition
         */
        public function initialCondition();

        /**
         * Returns the return type of the aggregate
         *
         * @return \Cassandra\Type Return type of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Aggregate/#method-returnType
         */
        public function returnType();

        /**
         * Returns the state type of the aggregate
         *
         * @return \Cassandra\Type State type of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Aggregate/#method-stateType
         */
        public function stateType();

        /**
         * Returns the signature of the aggregate
         *
         * @return string Signature of the aggregate (same as name())
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Aggregate/#method-signature
         */
        public function signature();
    }

    /**
     * All statements implement this common interface.
     *
     * @see \Cassandra\SimpleStatement
     * @see \Cassandra\PreparedStatement
     * @see \Cassandra\BatchStatement
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Statement/
     */
    interface Statement {}

    /**
     * A PHP representation of a schema
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Schema/
     */
    interface Schema
    {
        /**
         * Returns a Keyspace instance by name.
         *
         * @param string $name Name of the keyspace to get
         *
         * @return \Cassandra\Keyspace Keyspace instance or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Schema/#method-keyspace
         */
        public function keyspace($name);

        /**
         * Returns all keyspaces defined in the schema.
         *
         * @return array An array of Keyspace instances.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/interface.Schema/#method-keyspaces
         */
        public function keyspaces();
    }

    /**
     * Rows represent a result of statement execution.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/
     */
    final class Rows implements \Iterator, \ArrayAccess
    {
        /**
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-__construct
         */
        public function __construct() {}

        /**
         * Returns the number of rows.
         *
         * @return int number of rows
         *
         * @see \Countable::count()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-count
         */
        public function count() {}

        /**
         * Resets the rows iterator.
         *
         * @return void
         *
         * @see \Iterator::rewind()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-rewind
         */
        public function rewind() {}

        /**
         * Returns current row.
         *
         * @return array current row
         *
         * @see \Iterator::current()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-current
         */
        public function current() {}

        /**
         * Returns current index.
         *
         * @return int index
         *
         * @see \Iterator::key()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-key
         */
        public function key() {}

        /**
         * Advances the rows iterator by one.
         *
         * @return void
         *
         * @see \Iterator::next()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-next
         */
        public function next() {}

        /**
         * Returns existence of more rows being available.
         *
         * @return bool whether there are more rows available for iteration
         *
         * @see \Iterator::valid()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-valid
         */
        public function valid() {}

        /**
         * Returns existence of a given row.
         *
         * @param int $offset row index
         *
         * @return bool whether a row at a given index exists
         *
         * @see \ArrayAccess::offsetExists()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-offsetExists
         */
        public function offsetExists($offset) {}

        /**
         * Returns a row at given index.
         *
         * @param int $offset row index
         *
         * @return array|null row at a given index
         *
         * @see \ArrayAccess::offsetGet()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-offsetGet
         */
        public function offsetGet($offset) {}

        /**
         * Sets a row at given index.
         *
         * @param int $offset row index
         * @param array $value row value
         *
         * @return void
         *
         * @throws \Cassandra\Exception\DomainException
         *
         * @see \ArrayAccess::offsetSet()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-offsetSet
         */
        public function offsetSet($offset, $value) {}

        /**
         * Removes a row at given index.
         *
         * @param int $offset row index
         *
         * @return void
         *
         * @throws \Cassandra\Exception\DomainException
         *
         * @see \ArrayAccess::offsetUnset()
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-offsetUnset
         */
        public function offsetUnset($offset) {}

        /**
         * Check for the last page when paging.
         *
         * @return bool whether this is the last page or not
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-isLastPage
         */
        public function isLastPage() {}

        /**
         * Get the next page of results.
         *
         * @param float|null $timeout
         *
         * @return \Cassandra\Rows|null loads and returns next result page
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-nextPage
         */
        public function nextPage($timeout) {}

        /**
         * Get the next page of results asynchronously.
         *
         * @return \Cassandra\Future returns future of the next result page
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-nextPageAsync
         */
        public function nextPageAsync() {}

        /**
         * Returns the raw paging state token.
         *
         * @return string
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-pagingStateToken
         */
        public function pagingStateToken() {}

        /**
         * Get the first row.
         *
         * @return array|null returns first row if any
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Rows/#method-first
         */
        public function first() {}
    }

    /**
     * Default cluster implementation.
     *
     * @see \Cassandra\Cluster
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultCluster/
     */
    final class DefaultCluster implements Cluster
    {
        /**
         * Creates a new Session instance.
         *
         * @param string $keyspace Optional keyspace name
         * @param int $timeout Optional timeout
         *
         * @return \Cassandra\Session Session instance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultCluster/#method-connect
         */
        public function connect($keyspace, $timeout) {}

        /**
         * Creates a new Session instance.
         *
         * @param string $keyspace Optional keyspace name
         *
         * @return \Cassandra\Future A Future Session instance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultCluster/#method-connectAsync
         */
        public function connectAsync($keyspace) {}
    }

    /**
     * A PHP representation of a public function
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultFunction/
     */
    final class DefaultFunction implements Function_
    {
        /**
         * Returns the full name of the function
         *
         * @return string Full name of the function including name and types
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultFunction/#method-name
         */
        public function name() {}

        /**
         * Returns the simple name of the function
         *
         * @return string Simple name of the function
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultFunction/#method-simpleName
         */
        public function simpleName() {}

        /**
         * Returns the arguments of the function
         *
         * @return array Arguments of the function
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultFunction/#method-arguments
         */
        public function arguments() {}

        /**
         * Returns the return type of the function
         *
         * @return \Cassandra\Type Return type of the function
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultFunction/#method-returnType
         */
        public function returnType() {}

        /**
         * Returns the signature of the function
         *
         * @return string Signature of the function (same as name())
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultFunction/#method-signature
         */
        public function signature() {}

        /**
         * Returns the lanuage of the function
         *
         * @return string Language used by the function
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultFunction/#method-language
         */
        public function language() {}

        /**
         * Returns the body of the function
         *
         * @return string Body of the function
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultFunction/#method-body
         */
        public function body() {}

        /**
         * Determines if a function is called when the value is null.
         *
         * @return bool Returns whether the function is called when the input columns are null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultFunction/#method-isCalledOnNullInput
         */
        public function isCalledOnNullInput() {}
    }

    /**
     * Simple statements can be executed using a Session instance.
     * They are constructed with a CQL string that can contain positional
     * argument markers `?`.
     *
     * NOTE: Positional argument are only valid for native protocol v2+.
     *
     * @see \Cassandra\Session::execute()
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.SimpleStatement/
     */
    final class SimpleStatement implements Statement
    {
        /**
         * Creates a new simple statement with the provided CQL.
         *
         * @param string $cql CQL string for this simple statement
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.SimpleStatement/#method-__construct
         */
        public function __construct($cql) {}
    }

    /**
     * A PHP representation of the CQL `tuple` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tuple/
     */
    final class Tuple implements Value, \Countable, \Iterator
    {
        /**
         * Creates a new tuple with the given types.
         *
         * @param array $types Array of types
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tuple/#method-__construct
         */
        public function __construct($types) {}

        /**
         * The type of this tuple.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tuple/#method-type
         */
        public function type() {}

        /**
         * Array of values in this tuple.
         *
         * @return array values
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tuple/#method-values
         */
        public function values() {}

        /**
         * Sets the value at index in this tuple .
         *
         * @param mixed $value A value or null
         *
         * @return void
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tuple/#method-set
         */
        public function set($value) {}

        /**
         * Retrieves the value at a given index.
         *
         * @param int $index Index
         *
         * @return mixed A value or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tuple/#method-get
         */
        public function get($index) {}

        /**
         * Total number of elements in this tuple
         *
         * @return int count
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tuple/#method-count
         */
        public function count() {}

        /**
         * Current element for iteration
         *
         * @return mixed current element
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tuple/#method-current
         */
        public function current() {}

        /**
         * Current key for iteration
         *
         * @return int current key
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tuple/#method-key
         */
        public function key() {}

        /**
         * Move internal iterator forward
         *
         * @return void
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tuple/#method-next
         */
        public function next() {}

        /**
         * Check whether a current value exists
         *
         * @return bool
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tuple/#method-valid
         */
        public function valid() {}

        /**
         * Rewind internal iterator
         *
         * @return void
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tuple/#method-rewind
         */
        public function rewind() {}
    }

    /**
     * A PHP representation of the CQL `smallint` datatype.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/
     */
    final class Smallint implements Value, Numeric
    {
        /**
         * Creates a new 16-bit signed integer.
         *
         * @param int|float|string $value The value as an integer, double or string
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-__construct
         */
        public function __construct($value) {}

        /**
         * Minimum possible Smallint value
         *
         * @return \Cassandra\Smallint minimum value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-min
         */
        public static function min() {}

        /**
         * Maximum possible Smallint value
         *
         * @return \Cassandra\Smallint maximum value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-max
         */
        public static function max() {}

        /**
         * @return string
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-__toString
         */
        public function __toString() {}

        /**
         * The type of this value (smallint).
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-type
         */
        public function type() {}

        /**
         * Returns the integer value.
         *
         * @return int integer value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-value
         */
        public function value() {}

        /**
         * @param \Cassandra\Numeric $num a number to add to this one
         *
         * @return \Cassandra\Numeric sum
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-add
         */
        public function add($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to subtract from this one
         *
         * @return \Cassandra\Numeric difference
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-sub
         */
        public function sub($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to multiply this one by
         *
         * @return \Cassandra\Numeric product
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-mul
         */
        public function mul($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric quotient
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-div
         */
        public function div($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric remainder
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-mod
         */
        public function mod($num) {}

        /**
         * @return \Cassandra\Numeric absolute value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-abs
         */
        public function abs() {}

        /**
         * @return \Cassandra\Numeric negative value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-neg
         */
        public function neg() {}

        /**
         * @return \Cassandra\Numeric square root
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-sqrt
         */
        public function sqrt() {}

        /**
         * @return int this number as int
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-toInt
         */
        public function toInt() {}

        /**
         * @return float this number as float
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Smallint/#method-toDouble
         */
        public function toDouble() {}
    }

    /**
     * A future returned from `Session::prepareAsync()`
     * This future will resolve with a PreparedStatement or an exception.
     *
     * @see \Cassandra\Session::prepareAsync()
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.FuturePreparedStatement/
     */
    final class FuturePreparedStatement implements Future
    {
        /**
         * Waits for a given future resource to resolve and throws errors if any.
         *
         * @param int|float|null $timeout A timeout in seconds
         *
         * @return \Cassandra\PreparedStatement A prepared statement
         * @throws \Cassandra\Exception\TimeoutException
         *
         * @throws \Cassandra\Exception\InvalidArgumentException
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.FuturePreparedStatement/#method-get
         */
        public function get($timeout) {}
    }

    /**
     * A PHP representation of a schema
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSchema/
     */
    final class DefaultSchema implements Schema
    {
        /**
         * Returns a Keyspace instance by name.
         *
         * @param string $name Name of the keyspace to get
         *
         * @return \Cassandra\Keyspace Keyspace instance or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSchema/#method-keyspace
         */
        public function keyspace($name) {}

        /**
         * Returns all keyspaces defined in the schema.
         *
         * @return array An array of `Keyspace` instances.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSchema/#method-keyspaces
         */
        public function keyspaces() {}

        /**
         * Get the version of the schema snapshot
         *
         * @return int Version of the schema.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSchema/#method-version
         */
        public function version() {}
    }

    /**
     * Batch statements are used to execute a series of simple or prepared
     * statements.
     *
     * There are 3 types of batch statements:
     *  * `Cassandra::BATCH_LOGGED`   - this is the default batch type. This batch
     *    guarantees that either all or none of its statements will be executed.
     *    This behavior is achieved by writing a batch log on the coordinator,
     *    which slows down the execution somewhat.
     *  * `Cassandra::BATCH_UNLOGGED` - this batch will not be verified when
     *    executed, which makes it faster than a `LOGGED` batch, but means that
     *    some of its statements might fail, while others - succeed.
     *  * `Cassandra::BATCH_COUNTER`  - this batch is used for counter updates,
     *    which are, unlike other writes, not idempotent.
     *
     * @see Cassandra::BATCH_LOGGED
     * @see Cassandra::BATCH_UNLOGGED
     * @see Cassandra::BATCH_COUNTER
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.BatchStatement/
     */
    final class BatchStatement implements Statement
    {
        /**
         * Creates a new batch statement.
         *
         * @param int $type must be one of Cassandra::BATCH_* (default: Cassandra::BATCH_LOGGED).
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.BatchStatement/#method-__construct
         */
        public function __construct($type) {}

        /**
         * Adds a statement to this batch.
         *
         * @param string|\Cassandra\Statement $statement string or statement to add
         * @param array|null $arguments positional or named arguments (optional)
         *
         * @return \Cassandra\BatchStatement self
         * @throws \Cassandra\Exception\InvalidArgumentException
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.BatchStatement/#method-add
         */
        public function add($statement, $arguments) {}
    }

    /**
     * A PHP representation of the CQL `list` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/
     */
    final class Collection implements Value, \Countable, \Iterator
    {
        /**
         * Creates a new collection of a given type.
         *
         * @param \Cassandra\Type $type
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-__construct
         */
        public function __construct($type) {}

        /**
         * The type of this collection.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-type
         */
        public function type() {}

        /**
         * Array of values in this collection.
         *
         * @return array values
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-values
         */
        public function values() {}

        /**
         * Adds one or more values to this collection.
         *
         * @param mixed ...$value one or more values to add
         *
         * @return int total number of values in this collection
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-add
         */
        public function add(...$value) {}

        /**
         * Retrieves the value at a given index.
         *
         * @param int $index Index
         *
         * @return mixed Value or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-get
         */
        public function get($index) {}

        /**
         * Finds index of a value in this collection.
         *
         * @param mixed $value Value
         *
         * @return int Index or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-find
         */
        public function find($value) {}

        /**
         * Total number of elements in this collection
         *
         * @return int count
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-count
         */
        public function count() {}

        /**
         * Current element for iteration
         *
         * @return mixed current element
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-current
         */
        public function current() {}

        /**
         * Current key for iteration
         *
         * @return int current key
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-key
         */
        public function key() {}

        /**
         * Move internal iterator forward
         *
         * @return void
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-next
         */
        public function next() {}

        /**
         * Check whether a current value exists
         *
         * @return bool
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-valid
         */
        public function valid() {}

        /**
         * Rewind internal iterator
         *
         * @return void
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-rewind
         */
        public function rewind() {}

        /**
         * Deletes the value at a given index
         *
         * @param int $index Index
         *
         * @return bool Whether the value at a given index is correctly removed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Collection/#method-remove
         */
        public function remove($index) {}
    }

    /**
     * This future results is resolved with Rows.
     *
     * @see \Cassandra\Session::executeAsync()
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.FutureRows/
     */
    final class FutureRows implements Future
    {
        /**
         * Waits for a given future resource to resolve and throws errors if any.
         *
         * @param int|float|null $timeout A timeout in seconds
         *
         * @return \Cassandra\Rows|null The result set
         * @throws \Cassandra\Exception\TimeoutException
         *
         * @throws \Cassandra\Exception\InvalidArgumentException
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.FutureRows/#method-get
         */
        public function get($timeout) {}
    }

    /**
     * A PHP representation of a materialized view
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/
     */
    final class DefaultMaterializedView extends MaterializedView
    {
        /**
         * Returns the name of this view
         *
         * @return string Name of the view
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-name
         */
        public function name() {}

        /**
         * Return a view's option by name
         *
         * @param string $name The name of the option
         *
         * @return \Cassandra\Value Value of an option by name
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-option
         */
        public function option($name) {}

        /**
         * Returns all the view's options
         *
         * @return array A dictionary of string and Value pairs of the
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-options
         */
        public function options() {}

        /**
         * Description of the view, if any
         *
         * @return string Table description or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-comment
         */
        public function comment() {}

        /**
         * Returns read repair chance
         *
         * @return float Read repair chance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-readRepairChance
         */
        public function readRepairChance() {}

        /**
         * Returns local read repair chance
         *
         * @return float Local read repair chance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-localReadRepairChance
         */
        public function localReadRepairChance() {}

        /**
         * Returns GC grace seconds
         *
         * @return int GC grace seconds
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-gcGraceSeconds
         */
        public function gcGraceSeconds() {}

        /**
         * Returns caching options
         *
         * @return string Caching options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-caching
         */
        public function caching() {}

        /**
         * Returns bloom filter FP chance
         *
         * @return float Bloom filter FP chance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-bloomFilterFPChance
         */
        public function bloomFilterFPChance() {}

        /**
         * Returns memtable flush period in milliseconds
         *
         * @return int Memtable flush period in milliseconds
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-memtableFlushPeriodMs
         */
        public function memtableFlushPeriodMs() {}

        /**
         * Returns default TTL.
         *
         * @return int Default TTL.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-defaultTTL
         */
        public function defaultTTL() {}

        /**
         * Returns speculative retry.
         *
         * @return string Speculative retry.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-speculativeRetry
         */
        public function speculativeRetry() {}

        /**
         * Returns index interval
         *
         * @return int Index interval
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-indexInterval
         */
        public function indexInterval() {}

        /**
         * Returns compaction strategy class name
         *
         * @return string Compaction strategy class name
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-compactionStrategyClassName
         */
        public function compactionStrategyClassName() {}

        /**
         * Returns compaction strategy options
         *
         * @return \Cassandra\Map Compaction strategy options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-compactionStrategyOptions
         */
        public function compactionStrategyOptions() {}

        /**
         * Returns compression parameters
         *
         * @return \Cassandra\Map Compression parameters
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-compressionParameters
         */
        public function compressionParameters() {}

        /**
         * Returns whether or not the `populate_io_cache_on_flush` is true
         *
         * @return bool Value of `populate_io_cache_on_flush` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-populateIOCacheOnFlush
         */
        public function populateIOCacheOnFlush() {}

        /**
         * Returns whether or not the `replicate_on_write` is true
         *
         * @return bool Value of `replicate_on_write` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-replicateOnWrite
         */
        public function replicateOnWrite() {}

        /**
         * Returns the value of `max_index_interval`
         *
         * @return int Value of `max_index_interval` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-maxIndexInterval
         */
        public function maxIndexInterval() {}

        /**
         * Returns the value of `min_index_interval`
         *
         * @return int Value of `min_index_interval` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-minIndexInterval
         */
        public function minIndexInterval() {}

        /**
         * Returns column by name
         *
         * @param string $name Name of the column
         *
         * @return \Cassandra\Column Column instance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-column
         */
        public function column($name) {}

        /**
         * Returns all columns in this view
         *
         * @return array A list of Column instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-columns
         */
        public function columns() {}

        /**
         * Returns the partition key columns of the view
         *
         * @return array A list of of Column instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-partitionKey
         */
        public function partitionKey() {}

        /**
         * Returns both the partition and clustering key columns of the view
         *
         * @return array A list of of Column instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-primaryKey
         */
        public function primaryKey() {}

        /**
         * Returns the clustering key columns of the view
         *
         * @return array A list of of Column instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-clusteringKey
         */
        public function clusteringKey() {}

        /**
         * @return array A list of cluster column orders ('asc' and 'desc')
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-clusteringOrder
         */
        public function clusteringOrder() {}

        /**
         * Returns the base table of the view
         *
         * @return \Cassandra\Table Base table of the view
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultMaterializedView/#method-baseTable
         */
        public function baseTable() {}
    }

    /**
     * SSL options for Cluster.
     *
     * @see \Cassandra\SSLOptions\Builder
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.SSLOptions/
     */
    final class SSLOptions {}

    /**
     * A PHP representation of the CQL `bigint` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/
     */
    final class Bigint implements Value, Numeric
    {
        /**
         * Creates a new 64bit integer.
         *
         * @param string $value integer value as a string
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-__construct
         */
        public function __construct($value) {}

        /**
         * Minimum possible Bigint value
         *
         * @return \Cassandra\Bigint minimum value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-min
         */
        public static function min() {}

        /**
         * Maximum possible Bigint value
         *
         * @return \Cassandra\Bigint maximum value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-max
         */
        public static function max() {}

        /**
         * Returns string representation of the integer value.
         *
         * @return string integer value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-__toString
         */
        public function __toString() {}

        /**
         * The type of this bigint.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-type
         */
        public function type() {}

        /**
         * Returns the integer value.
         *
         * @return string integer value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-value
         */
        public function value() {}

        /**
         * @param \Cassandra\Numeric $num a number to add to this one
         *
         * @return \Cassandra\Numeric sum
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-add
         */
        public function add($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to subtract from this one
         *
         * @return \Cassandra\Numeric difference
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-sub
         */
        public function sub($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to multiply this one by
         *
         * @return \Cassandra\Numeric product
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-mul
         */
        public function mul($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric quotient
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-div
         */
        public function div($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric remainder
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-mod
         */
        public function mod($num) {}

        /**
         * @return \Cassandra\Numeric absolute value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-abs
         */
        public function abs() {}

        /**
         * @return \Cassandra\Numeric negative value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-neg
         */
        public function neg() {}

        /**
         * @return \Cassandra\Numeric square root
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-sqrt
         */
        public function sqrt() {}

        /**
         * @return int this number as int
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-toInt
         */
        public function toInt() {}

        /**
         * @return float this number as float
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Bigint/#method-toDouble
         */
        public function toDouble() {}
    }

    /**
     * A future that resolves with Session.
     *
     * @see \Cassandra\Cluster::connectAsync()
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.FutureSession/
     */
    final class FutureSession implements Future
    {
        /**
         * Waits for a given future resource to resolve and throws errors if any.
         *
         * @param int|float|null $timeout A timeout in seconds
         *
         * @return \Cassandra\Session A connected session
         * @throws \Cassandra\Exception\TimeoutException
         *
         * @throws \Cassandra\Exception\InvalidArgumentException
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.FutureSession/#method-get
         */
        public function get($timeout) {}
    }

    /**
     * A PHP representation of the CQL `set` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/
     */
    final class Set implements Value, \Countable, \Iterator
    {
        /**
         * Creates a new collection of a given type.
         *
         * @param \Cassandra\Type $type
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/#method-__construct
         */
        public function __construct($type) {}

        /**
         * The type of this set.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/#method-type
         */
        public function type() {}

        /**
         * Array of values in this set.
         *
         * @return array values
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/#method-values
         */
        public function values() {}

        /**
         * Adds a value to this set.
         *
         * @param mixed $value Value
         *
         * @return bool whether the value has been added
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/#method-add
         */
        public function add($value) {}

        /**
         * Returns whether a value is in this set.
         *
         * @param mixed $value Value
         *
         * @return bool whether the value is in the set
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/#method-has
         */
        public function has($value) {}

        /**
         * Removes a value to this set.
         *
         * @param mixed $value Value
         *
         * @return bool whether the value has been removed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/#method-remove
         */
        public function remove($value) {}

        /**
         * Total number of elements in this set
         *
         * @return int count
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/#method-count
         */
        public function count() {}

        /**
         * Current element for iteration
         *
         * @return mixed current element
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/#method-current
         */
        public function current() {}

        /**
         * Current key for iteration
         *
         * @return int current key
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/#method-key
         */
        public function key() {}

        /**
         * Move internal iterator forward
         *
         * @return void
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/#method-next
         */
        public function next() {}

        /**
         * Check whether a current value exists
         *
         * @return bool
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/#method-valid
         */
        public function valid() {}

        /**
         * Rewind internal iterator
         *
         * @return void
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Set/#method-rewind
         */
        public function rewind() {}
    }

    /**
     * A PHP representation of an index
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultIndex/
     */
    final class DefaultIndex implements Index
    {
        /**
         * Returns the name of the index
         *
         * @return string Name of the index
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultIndex/#method-name
         */
        public function name() {}

        /**
         * Returns the kind of index
         *
         * @return string Kind of the index
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultIndex/#method-kind
         */
        public function kind() {}

        /**
         * Returns the target column of the index
         *
         * @return string Target column name of the index
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultIndex/#method-target
         */
        public function target() {}

        /**
         * Return a column's option by name
         *
         * @param string $name The name of the option
         *
         * @return \Cassandra\Value Value of an option by name
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultIndex/#method-option
         */
        public function option($name) {}

        /**
         * Returns all the index's options
         *
         * @return array A dictionary of `string` and `Value` pairs of the index's options.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultIndex/#method-options
         */
        public function options() {}

        /**
         * Returns the class name of the index
         *
         * @return string Class name of a custom index
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultIndex/#method-className
         */
        public function className() {}

        /**
         * Determines if the index is a custom index.
         *
         * @return bool true if a custom index
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultIndex/#method-isCustom
         */
        public function isCustom() {}
    }

    /**
     * A PHP representation of an aggregate
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultAggregate/
     */
    final class DefaultAggregate implements Aggregate
    {
        /**
         * Returns the full name of the aggregate
         *
         * @return string Full name of the aggregate including name and types
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultAggregate/#method-name
         */
        public function name() {}

        /**
         * Returns the simple name of the aggregate
         *
         * @return string Simple name of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultAggregate/#method-simpleName
         */
        public function simpleName() {}

        /**
         * Returns the argument types of the aggregate
         *
         * @return array Argument types of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultAggregate/#method-argumentTypes
         */
        public function argumentTypes() {}

        /**
         * Returns the state function of the aggregate
         *
         * @return \Cassandra\Function_ State public function of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultAggregate/#method-stateFunction
         */
        public function stateFunction() {}

        /**
         * Returns the final function of the aggregate
         *
         * @return \Cassandra\Function_ Final public function of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultAggregate/#method-finalFunction
         */
        public function finalFunction() {}

        /**
         * Returns the initial condition of the aggregate
         *
         * @return \Cassandra\Value Initial condition of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultAggregate/#method-initialCondition
         */
        public function initialCondition() {}

        /**
         * Returns the state type of the aggregate
         *
         * @return \Cassandra\Type State type of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultAggregate/#method-stateType
         */
        public function stateType() {}

        /**
         * Returns the return type of the aggregate
         *
         * @return \Cassandra\Type Return type of the aggregate
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultAggregate/#method-returnType
         */
        public function returnType() {}

        /**
         * Returns the signature of the aggregate
         *
         * @return string Signature of the aggregate (same as name())
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultAggregate/#method-signature
         */
        public function signature() {}
    }

    /**
     * A PHP representation of the CQL `timestamp` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timestamp/
     */
    final class Timestamp implements Value
    {
        /**
         * Creates a new timestamp from either unix timestamp and microseconds or
         * from the current time by default.
         *
         * @param int $seconds The number of seconds
         * @param int $microseconds The number of microseconds
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timestamp/#method-__construct
         */
        public function __construct($seconds, $microseconds) {}

        /**
         * The type of this timestamp.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timestamp/#method-type
         */
        public function type() {}

        /**
         * Unix timestamp.
         *
         * @return int seconds
         *
         * @see time
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timestamp/#method-time
         */
        public function time() {}

        /**
         * Microtime from this timestamp
         *
         * @param bool $get_as_float Whether to get this value as float
         *
         * @return float|string Float or string representation
         *
         * @see microtime
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timestamp/#method-microtime
         */
        public function microtime($get_as_float) {}

        /**
         * Converts current timestamp to PHP DateTime.
         *
         * @return \DateTime PHP representation
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timestamp/#method-toDateTime
         */
        public function toDateTime() {}

        /**
         * Returns a string representation of this timestamp.
         *
         * @return string timestamp
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timestamp/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * A PHP representation of the CQL `tinyint` datatype.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/
     */
    final class Tinyint implements Value, Numeric
    {
        /**
         * Creates a new 8-bit signed integer.
         *
         * @param int|float|string $value The value as an integer, float or string
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-__construct
         */
        public function __construct($value) {}

        /**
         * Minimum possible Tinyint value
         *
         * @return \Cassandra\Tinyint minimum value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-min
         */
        public static function min() {}

        /**
         * Maximum possible Tinyint value
         *
         * @return \Cassandra\Tinyint maximum value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-max
         */
        public static function max() {}

        /**
         * @return string
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-__toString
         */
        public function __toString() {}

        /**
         * The type of this value (tinyint).
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-type
         */
        public function type() {}

        /**
         * Returns the integer value.
         *
         * @return int integer value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-value
         */
        public function value() {}

        /**
         * @param \Cassandra\Numeric $num a number to add to this one
         *
         * @return \Cassandra\Numeric sum
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-add
         */
        public function add($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to subtract from this one
         *
         * @return \Cassandra\Numeric difference
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-sub
         */
        public function sub($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to multiply this one by
         *
         * @return \Cassandra\Numeric product
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-mul
         */
        public function mul($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric quotient
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-div
         */
        public function div($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric remainder
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-mod
         */
        public function mod($num) {}

        /**
         * @return \Cassandra\Numeric absolute value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-abs
         */
        public function abs() {}

        /**
         * @return \Cassandra\Numeric negative value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-neg
         */
        public function neg() {}

        /**
         * @return \Cassandra\Numeric square root
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-sqrt
         */
        public function sqrt() {}

        /**
         * @return int this number as int
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-toInt
         */
        public function toInt() {}

        /**
         * @return float this number as float
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Tinyint/#method-toDouble
         */
        public function toDouble() {}
    }

    /**
     * A PHP representation of the CQL `timeuuid` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timeuuid/
     */
    final class Timeuuid implements Value, UuidInterface
    {
        /**
         * Creates a timeuuid from a given timestamp or current time.
         *
         * @param int $timestamp Unix timestamp
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timeuuid/#method-__construct
         */
        public function __construct($timestamp) {}

        /**
         * Returns this timeuuid as string.
         *
         * @return string timeuuid
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timeuuid/#method-__toString
         */
        public function __toString() {}

        /**
         * The type of this timeuuid.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timeuuid/#method-type
         */
        public function type() {}

        /**
         * Returns this timeuuid as string.
         *
         * @return string timeuuid
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timeuuid/#method-uuid
         */
        public function uuid() {}

        /**
         * Returns the version of this timeuuid.
         *
         * @return int version of this timeuuid
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timeuuid/#method-version
         */
        public function version() {}

        /**
         * Unix timestamp.
         *
         * @return int seconds
         *
         * @see time
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timeuuid/#method-time
         */
        public function time() {}

        /**
         * Converts current timeuuid to PHP DateTime.
         *
         * @return \DateTime PHP representation
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Timeuuid/#method-toDateTime
         */
        public function toDateTime() {}
    }

    /**
     * A session is used to prepare and execute statements.
     *
     * @see \Cassandra\Cluster::connect()
     * @see \Cassandra\Cluster::connectAsync()
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSession/
     */
    final class DefaultSession implements Session
    {
        /**
         * Execute a query.
         *
         * Available execution options:
         * | Option Name        | Option **Type** | Option Details                                                                                           |
         * |--------------------|-----------------|----------------------------------------------------------------------------------------------------------|
         * | arguments          | array           | An array or positional or named arguments                                                                |
         * | consistency        | int             | A consistency constant e.g Dse::CONSISTENCY_ONE, Dse::CONSISTENCY_QUORUM, etc.                           |
         * | timeout            | int             | A number of rows to include in result for paging                                                         |
         * | paging_state_token | string          | A string token use to resume from the state of a previous result set                                     |
         * | retry_policy       | RetryPolicy     | A retry policy that is used to handle server-side failures for this request                              |
         * | serial_consistency | int             | Either Dse::CONSISTENCY_SERIAL or Dse::CONSISTENCY_LOCAL_SERIAL                                          |
         * | timestamp          | int\|string     | Either an integer or integer string timestamp that represents the number of microseconds since the epoch |
         * | execute_as         | string          | User to execute statement as                                                                             |
         *
         * @param string|\Cassandra\Statement $statement string or statement to be executed.
         * @param array|\Cassandra\ExecutionOptions|null $options Options to control execution of the query.
         *
         * @return \Cassandra\Rows A collection of rows.
         * @throws \Cassandra\Exception
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSession/#method-execute
         */
        public function execute($statement, $options) {}

        /**
         * Execute a query asynchronously. This method returns immediately, but
         * the query continues execution in the background.
         *
         * @param string|\Cassandra\Statement $statement string or statement to be executed.
         * @param array|\Cassandra\ExecutionOptions|null $options Options to control execution of the query.
         *
         * @return \Cassandra\FutureRows A future that can be used to retrieve the result.
         *
         * @see \Cassandra\Session::execute() for valid execution options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSession/#method-executeAsync
         */
        public function executeAsync($statement, $options) {}

        /**
         * Prepare a query for execution.
         *
         * @param string $cql The query to be prepared.
         * @param array|\Cassandra\ExecutionOptions|null $options Options to control preparing the query.
         *
         * @return \Cassandra\PreparedStatement A prepared statement that can be bound with parameters and executed.
         *
         * @throws \Cassandra\Exception
         *
         * @see \Cassandra\Session::execute() for valid execution options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSession/#method-prepare
         */
        public function prepare($cql, $options) {}

        /**
         * Asynchronously prepare a query for execution.
         *
         * @param string $cql The query to be prepared.
         * @param array|\Cassandra\ExecutionOptions|null $options Options to control preparing the query.
         *
         * @return \Cassandra\FuturePreparedStatement A future that can be used to retrieve the prepared statement.
         *
         * @see \Cassandra\Session::execute() for valid execution options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSession/#method-prepareAsync
         */
        public function prepareAsync($cql, $options) {}

        /**
         * Close the session and all its connections.
         *
         * @param float $timeout The amount of time in seconds to wait for the session to close.
         *
         * @return null Nothing.
         * @throws \Cassandra\Exception
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSession/#method-close
         */
        public function close($timeout) {}

        /**
         * Asynchronously close the session and all its connections.
         *
         * @return \Cassandra\FutureClose A future that can be waited on.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSession/#method-closeAsync
         */
        public function closeAsync() {}

        /**
         * Get performance and diagnostic metrics.
         *
         * @return array Performance/Diagnostic metrics.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSession/#method-metrics
         */
        public function metrics() {}

        /**
         * Get a snapshot of the cluster's current schema.
         *
         * @return \Cassandra\Schema A snapshot of the cluster's schema.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultSession/#method-schema
         */
        public function schema() {}
    }

    /**
     * A class for representing custom values.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Custom/
     */
    abstract class Custom implements Value
    {
        /**
         * The type of this value.
         *
         * @return \Cassandra\Type\Custom
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Custom/#method-type
         */
        abstract public function type();
    }

    /**
     * A PHP representation of a materialized view
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/
     */
    abstract class MaterializedView implements Table
    {
        /**
         * Returns the base table of the view
         *
         * @return \Cassandra\Table Base table of the view
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-baseTable
         */
        abstract public function baseTable();

        /**
         * Returns the name of this view
         *
         * @return string Name of the view
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-name
         */
        abstract public function name();

        /**
         * Return a view's option by name
         *
         * @param string $name The name of the option
         *
         * @return \Cassandra\Value Value of an option by name
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-option
         */
        abstract public function option($name);

        /**
         * Returns all the view's options
         *
         * @return array A dictionary of string and Value pairs of the
         *               view's options.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-options
         */
        abstract public function options();

        /**
         * Description of the view, if any
         *
         * @return string View description or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-comment
         */
        abstract public function comment();

        /**
         * Returns read repair chance
         *
         * @return float Read repair chance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-readRepairChance
         */
        abstract public function readRepairChance();

        /**
         * Returns local read repair chance
         *
         * @return float Local read repair chance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-localReadRepairChance
         */
        abstract public function localReadRepairChance();

        /**
         * Returns GC grace seconds
         *
         * @return int GC grace seconds
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-gcGraceSeconds
         */
        abstract public function gcGraceSeconds();

        /**
         * Returns caching options
         *
         * @return string Caching options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-caching
         */
        abstract public function caching();

        /**
         * Returns bloom filter FP chance
         *
         * @return float Bloom filter FP chance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-bloomFilterFPChance
         */
        abstract public function bloomFilterFPChance();

        /**
         * Returns memtable flush period in milliseconds
         *
         * @return int Memtable flush period in milliseconds
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-memtableFlushPeriodMs
         */
        abstract public function memtableFlushPeriodMs();

        /**
         * Returns default TTL.
         *
         * @return int Default TTL.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-defaultTTL
         */
        abstract public function defaultTTL();

        /**
         * Returns speculative retry.
         *
         * @return string Speculative retry.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-speculativeRetry
         */
        abstract public function speculativeRetry();

        /**
         * Returns index interval
         *
         * @return int Index interval
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-indexInterval
         */
        abstract public function indexInterval();

        /**
         * Returns compaction strategy class name
         *
         * @return string Compaction strategy class name
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-compactionStrategyClassName
         */
        abstract public function compactionStrategyClassName();

        /**
         * Returns compaction strategy options
         *
         * @return \Cassandra\Map Compaction strategy options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-compactionStrategyOptions
         */
        abstract public function compactionStrategyOptions();

        /**
         * Returns compression parameters
         *
         * @return \Cassandra\Map Compression parameters
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-compressionParameters
         */
        abstract public function compressionParameters();

        /**
         * Returns whether or not the `populate_io_cache_on_flush` is true
         *
         * @return bool Value of `populate_io_cache_on_flush` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-populateIOCacheOnFlush
         */
        abstract public function populateIOCacheOnFlush();

        /**
         * Returns whether or not the `replicate_on_write` is true
         *
         * @return bool Value of `replicate_on_write` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-replicateOnWrite
         */
        abstract public function replicateOnWrite();

        /**
         * Returns the value of `max_index_interval`
         *
         * @return int Value of `max_index_interval` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-maxIndexInterval
         */
        abstract public function maxIndexInterval();

        /**
         * Returns the value of `min_index_interval`
         *
         * @return int Value of `min_index_interval` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-minIndexInterval
         */
        abstract public function minIndexInterval();

        /**
         * Returns column by name
         *
         * @param string $name Name of the column
         *
         * @return \Cassandra\Column Column instance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-column
         */
        abstract public function column($name);

        /**
         * Returns all columns in this view
         *
         * @return array A list of `Column` instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-columns
         */
        abstract public function columns();

        /**
         * Returns the partition key columns of the view
         *
         * @return array A list of of `Column` instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-partitionKey
         */
        abstract public function partitionKey();

        /**
         * Returns both the partition and clustering key columns of the view
         *
         * @return array A list of of `Column` instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-primaryKey
         */
        abstract public function primaryKey();

        /**
         * Returns the clustering key columns of the view
         *
         * @return array A list of of `Column` instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-clusteringKey
         */
        abstract public function clusteringKey();

        /**
         * @return array A list of cluster column orders ('asc' and 'desc')
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.MaterializedView/#method-clusteringOrder
         */
        abstract public function clusteringOrder();
    }

    /**
     * A PHP representation of the CQL `time` type.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Time/
     */
    final class Time implements Value
    {
        /**
         * Creates a new Time object
         *
         * @param int|string $nanoseconds Number of nanoseconds since last microsecond
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Time/#method-__construct
         */
        public function __construct($nanoseconds) {}

        /**
         * @param \DateTime $datetime
         *
         * @return \Cassandra\Time
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Time/#method-fromDateTime
         */
        public static function fromDateTime($datetime) {}

        /**
         * The type of this date.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Time/#method-type
         */
        public function type() {}

        /**
         * @return int
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Time/#method-seconds
         */
        public function seconds() {}

        /**
         * @return string this date in string format: Time(nanoseconds=$nanoseconds)
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Time/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * Cluster object is used to create Sessions.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/
     */
    abstract class Type
    {
        /**
         * Get representation of ascii type
         *
         * @return \Cassandra\Type ascii type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-ascii
         */
        final public static function ascii() {}

        /**
         * Get representation of bigint type
         *
         * @return \Cassandra\Type bigint type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-bigint
         */
        final public static function bigint() {}

        /**
         * Get representation of smallint type
         *
         * @return \Cassandra\Type smallint type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-smallint
         */
        final public static function smallint() {}

        /**
         * Get representation of tinyint type
         *
         * @return \Cassandra\Type tinyint type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-tinyint
         */
        final public static function tinyint() {}

        /**
         * Get representation of blob type
         *
         * @return \Cassandra\Type blob type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-blob
         */
        final public static function blob() {}

        /**
         * Get representation of boolean type
         *
         * @return \Cassandra\Type boolean type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-boolean
         */
        final public static function boolean() {}

        /**
         * Get representation of counter type
         *
         * @return \Cassandra\Type counter type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-counter
         */
        final public static function counter() {}

        /**
         * Get representation of decimal type
         *
         * @return \Cassandra\Type decimal type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-decimal
         */
        final public static function decimal() {}

        /**
         * Get representation of double type
         *
         * @return \Cassandra\Type double type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-double
         */
        final public static function double() {}

        /**
         * Get representation of duration type
         *
         * @return \Cassandra\Type duration type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-duration
         */
        final public static function duration() {}

        /**
         * Get representation of float type
         *
         * @return \Cassandra\Type float type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-float
         */
        final public static function float() {}

        /**
         * Get representation of int type
         *
         * @return \Cassandra\Type int type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-int
         */
        final public static function int() {}

        /**
         * Get representation of text type
         *
         * @return \Cassandra\Type text type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-text
         */
        final public static function text() {}

        /**
         * Get representation of timestamp type
         *
         * @return \Cassandra\Type timestamp type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-timestamp
         */
        final public static function timestamp() {}

        /**
         * Get representation of date type
         *
         * @return \Cassandra\Type date type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-date
         */
        final public static function date() {}

        /**
         * Get representation of time type
         *
         * @return \Cassandra\Type time type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-time
         */
        final public static function time() {}

        /**
         * Get representation of uuid type
         *
         * @return \Cassandra\Type uuid type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-uuid
         */
        final public static function uuid() {}

        /**
         * Get representation of varchar type
         *
         * @return \Cassandra\Type varchar type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-varchar
         */
        final public static function varchar() {}

        /**
         * Get representation of varint type
         *
         * @return \Cassandra\Type varint type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-varint
         */
        final public static function varint() {}

        /**
         * Get representation of timeuuid type
         *
         * @return \Cassandra\Type timeuuid type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-timeuuid
         */
        final public static function timeuuid() {}

        /**
         * Get representation of inet type
         *
         * @return \Cassandra\Type inet type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-inet
         */
        final public static function inet() {}

        /**
         * Initialize a Collection type
         * ```php
         * <?php
         * use Type;
         *
         * $collection = Type::collection(Type::int())
         *                   ->create(1, 2, 3, 4, 5, 6, 7, 8, 9);
         *
         * var_dump($collection);
         * ```
         *
         * @param \Cassandra\Type $type The type of values
         *
         * @return \Cassandra\Type The collection type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-collection
         */
        final public static function collection($type) {}

        /**
         * Initialize a set type
         * ```
         * <?php
         * use Type;
         *
         * $set = Type::set(Type::varchar())
         *            ->create("a", "b", "c", "d", "e", "f", "g", "h", "i", "j");
         *
         * var_dump($set);
         * ```
         *
         * @param \Cassandra\Type $type The types of values
         *
         * @return \Cassandra\Type The set type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-set
         */
        final public static function set($type) {}

        /**
         * Initialize a map type
         * ```<?php
         * use Type;
         *
         * $map = Type::map(Type::int(), Type::varchar())
         *            ->create(1, "a", 2, "b", 3, "c", 4, "d", 5, "e", 6, "f")
         *
         * var_dump($map);```
         *
         * @param \Cassandra\Type $keyType The type of keys
         * @param \Cassandra\Type $valueType The type of values
         *
         * @return \Cassandra\Type The map type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-map
         */
        final public static function map($keyType, $valueType) {}

        /**
         * Initialize a tuple type
         * ```<?php
         * use Type;
         *
         * $tuple = Type::tuple(Type::varchar(), Type::int())
         *            ->create("a", 123);
         *
         * var_dump($tuple);```
         *
         * @param \Cassandra\Type $types A variadic list of types
         *
         * @return \Cassandra\Type The tuple type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-tuple
         */
        final public static function tuple($types) {}

        /**
         * Initialize a user type
         * ```<?php
         * use Type;
         *
         * $userType = Type::userType("a", Type::varchar(), "b", Type::int())
         *                 ->create("a", "abc", "b", 123);
         *
         * var_dump($userType);```
         *
         * @param \Cassandra\Type $types A variadic list of name/type pairs
         *
         * @return \Cassandra\Type The user type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-userType
         */
        final public static function userType($types) {}

        /**
         * Returns the name of this type as string.
         *
         * @return string Name of this type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-name
         */
        abstract public function name();

        /**
         * Returns string representation of this type.
         *
         * @return string String representation of this type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Type/#method-__toString
         */
        abstract public function __toString();
    }

    /**
     * A PHP representation of the CQL `varint` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/
     */
    final class Varint implements Value, Numeric
    {
        /**
         * Creates a new variable length integer.
         *
         * @param string $value integer value as a string
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-__construct
         */
        public function __construct($value) {}

        /**
         * Returns the integer value.
         *
         * @return string integer value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-__toString
         */
        public function __toString() {}

        /**
         * The type of this varint.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-type
         */
        public function type() {}

        /**
         * Returns the integer value.
         *
         * @return string integer value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-value
         */
        public function value() {}

        /**
         * @param \Cassandra\Numeric $num a number to add to this one
         *
         * @return \Cassandra\Numeric sum
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-add
         */
        public function add($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to subtract from this one
         *
         * @return \Cassandra\Numeric difference
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-sub
         */
        public function sub($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to multiply this one by
         *
         * @return \Cassandra\Numeric product
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-mul
         */
        public function mul($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric quotient
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-div
         */
        public function div($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric remainder
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-mod
         */
        public function mod($num) {}

        /**
         * @return \Cassandra\Numeric absolute value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-abs
         */
        public function abs() {}

        /**
         * @return \Cassandra\Numeric negative value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-neg
         */
        public function neg() {}

        /**
         * @return \Cassandra\Numeric square root
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-sqrt
         */
        public function sqrt() {}

        /**
         * @return int this number as int
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-toInt
         */
        public function toInt() {}

        /**
         * @return float this number as float
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Varint/#method-toDouble
         */
        public function toDouble() {}
    }

    /**
     * A PHP representation of the CQL `map` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/
     */
    final class Map implements Value, \Countable, \Iterator, \ArrayAccess
    {
        /**
         * Creates a new map of a given key and value type.
         *
         * @param \Cassandra\Type $keyType
         * @param \Cassandra\Type $valueType
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-__construct
         */
        public function __construct($keyType, $valueType) {}

        /**
         * The type of this map.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-type
         */
        public function type() {}

        /**
         * Returns all keys in the map as an array.
         *
         * @return array keys
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-keys
         */
        public function keys() {}

        /**
         * Returns all values in the map as an array.
         *
         * @return array values
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-values
         */
        public function values() {}

        /**
         * Sets key/value in the map.
         *
         * @param mixed $key key
         * @param mixed $value value
         *
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-set
         */
        public function set($key, $value) {}

        /**
         * Gets the value of the key in the map.
         *
         * @param mixed $key Key
         *
         * @return mixed Value or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-get
         */
        public function get($key) {}

        /**
         * Removes the key from the map.
         *
         * @param mixed $key Key
         *
         * @return bool Whether the key was removed or not, e.g. didn't exist
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-remove
         */
        public function remove($key) {}

        /**
         * Returns whether the key is in the map.
         *
         * @param mixed $key Key
         *
         * @return bool Whether the key is in the map or not
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-has
         */
        public function has($key) {}

        /**
         * Total number of elements in this map
         *
         * @return int count
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-count
         */
        public function count() {}

        /**
         * Current value for iteration
         *
         * @return mixed current value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-current
         */
        public function current() {}

        /**
         * Current key for iteration
         *
         * @return int current key
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-key
         */
        public function key() {}

        /**
         * Move internal iterator forward
         *
         * @return void
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-next
         */
        public function next() {}

        /**
         * Check whether a current value exists
         *
         * @return bool
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-valid
         */
        public function valid() {}

        /**
         * Rewind internal iterator
         *
         * @return void
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-rewind
         */
        public function rewind() {}

        /**
         * Sets the value at a given key
         *
         * @param mixed $key Key to use.
         * @param mixed $value Value to set.
         *
         * @return void
         * @throws \Cassandra\Exception\InvalidArgumentException when the type of key or value is wrong
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-offsetSet
         */
        public function offsetSet($key, $value) {}

        /**
         * Retrieves the value at a given key
         *
         * @param mixed $key Key to use.
         *
         * @return mixed Value or `null`
         * @throws \Cassandra\Exception\InvalidArgumentException when the type of key is wrong
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-offsetGet
         */
        public function offsetGet($key) {}

        /**
         * Deletes the value at a given key
         *
         * @param mixed $key Key to use.
         *
         * @return void
         * @throws \Cassandra\Exception\InvalidArgumentException when the type of key is wrong
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-offsetUnset
         */
        public function offsetUnset($key) {}

        /**
         * Returns whether the value a given key is present
         *
         * @param mixed $key Key to use.
         *
         * @return bool Whether the value at a given key is present
         * @throws \Cassandra\Exception\InvalidArgumentException when the type of key is wrong
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Map/#method-offsetExists
         */
        public function offsetExists($key) {}
    }

    /**
     * A PHP representation of the CQL `uuid` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Uuid/
     */
    final class Uuid implements Value, UuidInterface
    {
        /**
         * Creates a uuid from a given uuid string or a random one.
         *
         * @param string $uuid A uuid string
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Uuid/#method-__construct
         */
        public function __construct($uuid) {}

        /**
         * Returns this uuid as string.
         *
         * @return string uuid
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Uuid/#method-__toString
         */
        public function __toString() {}

        /**
         * The type of this uuid.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Uuid/#method-type
         */
        public function type() {}

        /**
         * Returns this uuid as string.
         *
         * @return string uuid
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Uuid/#method-uuid
         */
        public function uuid() {}

        /**
         * Returns the version of this uuid.
         *
         * @return int version of this uuid
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Uuid/#method-version
         */
        public function version() {}
    }

    /**
     * A PHP representation of the CQL `float` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/
     */
    final class Float_ implements Value, Numeric
    {
        /**
         * Creates a new float.
         *
         * @param float|int|string|\Cassandra\Float_ $value A float value as a string, number or Float
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-__construct
         */
        public function __construct($value) {}

        /**
         * Minimum possible Float value
         *
         * @return \Cassandra\Float_ minimum value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-min
         */
        public static function min() {}

        /**
         * Maximum possible Float value
         *
         * @return \Cassandra\Float_ maximum value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-max
         */
        public static function max() {}

        /**
         * Returns string representation of the float value.
         *
         * @return string float value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-__toString
         */
        public function __toString() {}

        /**
         * The type of this float.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-type
         */
        public function type() {}

        /**
         * Returns the float value.
         *
         * @return float float value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-value
         */
        public function value() {}

        /**
         * @return bool
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-isInfinite
         */
        public function isInfinite() {}

        /**
         * @return bool
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-isFinite
         */
        public function isFinite() {}

        /**
         * @return bool
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-isNaN
         */
        public function isNaN() {}

        /**
         * @param \Cassandra\Numeric $num a number to add to this one
         *
         * @return \Cassandra\Numeric sum
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-add
         */
        public function add($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to subtract from this one
         *
         * @return \Cassandra\Numeric difference
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-sub
         */
        public function sub($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to multiply this one by
         *
         * @return \Cassandra\Numeric product
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-mul
         */
        public function mul($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric quotient
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-div
         */
        public function div($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric remainder
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-mod
         */
        public function mod($num) {}

        /**
         * @return \Cassandra\Numeric absolute value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-abs
         */
        public function abs() {}

        /**
         * @return \Cassandra\Numeric negative value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-neg
         */
        public function neg() {}

        /**
         * @return \Cassandra\Numeric square root
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-sqrt
         */
        public function sqrt() {}

        /**
         * @return int this number as int
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-toInt
         */
        public function toInt() {}

        /**
         * @return float this number as float
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Float/#method-toDouble
         */
        public function toDouble() {}
    }

    /**
     * A PHP representation of the CQL `duration` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Duration/
     */
    final class Duration implements Value
    {
        /**
         * @param int|float|string|\Cassandra\Bigint $months Months attribute of the duration.
         * @param int|float|string|\Cassandra\Bigint $days Days attribute of the duration.
         * @param int|float|string|\Cassandra\Bigint $nanos Nanos attribute of the duration.
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Duration/#method-__construct
         */
        public function __construct($months, $days, $nanos) {}

        /**
         * The type of represented by the value.
         *
         * @return \Cassandra\Type the Cassandra type for Duration
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Duration/#method-type
         */
        public function type() {}

        /**
         * @return string the months attribute of this Duration
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Duration/#method-months
         */
        public function months() {}

        /**
         * @return string the days attribute of this Duration
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Duration/#method-days
         */
        public function days() {}

        /**
         * @return string the nanoseconds attribute of this Duration
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Duration/#method-nanos
         */
        public function nanos() {}

        /**
         * @return string string representation of this Duration; may be used as a literal parameter in CQL queries.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Duration/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * A PHP representation of a keyspace
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/
     */
    final class DefaultKeyspace implements Keyspace
    {
        /**
         * Returns keyspace name
         *
         * @return string Name
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-name
         */
        public function name() {}

        /**
         * Returns replication class name
         *
         * @return string Replication class
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-replicationClassName
         */
        public function replicationClassName() {}

        /**
         * Returns replication options
         *
         * @return \Cassandra\Map Replication options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-replicationOptions
         */
        public function replicationOptions() {}

        /**
         * Returns whether the keyspace has durable writes enabled
         *
         * @return string Whether durable writes are enabled
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-hasDurableWrites
         */
        public function hasDurableWrites() {}

        /**
         * Returns a table by name
         *
         * @param string $name Table name
         *
         * @return \Cassandra\Table
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-table
         */
        public function table($name) {}

        /**
         * Returns all tables defined in this keyspace
         *
         * @return array An array of `Table` instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-tables
         */
        public function tables() {}

        /**
         * Get user type by name
         *
         * @param string $name User type name
         *
         * @return \Cassandra\Type\UserType|null A user type or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-userType
         */
        public function userType($name) {}

        /**
         * Get all user types
         *
         * @return array An array of user types
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-userTypes
         */
        public function userTypes() {}

        /**
         * Get materialized view by name
         *
         * @param string $name Materialized view name
         *
         * @return \Cassandra\MaterizedView|null A materialized view or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-materializedView
         */
        public function materializedView($name) {}

        /**
         * Gets all materialized views
         *
         * @return array An array of materialized views
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-materializedViews
         */
        public function materializedViews() {}

        /**
         * Get a function by name and signature
         *
         * @param string $name Function name
         * @param string|\Cassandra\Type $params Function arguments
         *
         * @return \Cassandra\Function_|null A function or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-function
         */
        public function function_($name, ...$params) {}

        /**
         * Get all functions
         *
         * @return array An array of functions
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-functions
         */
        public function functions() {}

        /**
         * Get an aggregate by name and signature
         *
         * @param string $name Aggregate name
         * @param string|\Cassandra\Type $params Aggregate arguments
         *
         * @return \Cassandra\Aggregate|null An aggregate or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-aggregate
         */
        public function aggregate($name, ...$params) {}

        /**
         * Get all aggregates
         *
         * @return array An array of aggregates
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultKeyspace/#method-aggregates
         */
        public function aggregates() {}
    }

    /**
     * A PHP representation of the CQL `inet` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Inet/
     */
    final class Inet implements Value
    {
        /**
         * Creates a new IPv4 or IPv6 inet address.
         *
         * @param string $address any IPv4 or IPv6 address
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Inet/#method-__construct
         */
        public function __construct($address) {}

        /**
         * Returns the normalized string representation of the address.
         *
         * @return string address
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Inet/#method-__toString
         */
        public function __toString() {}

        /**
         * The type of this inet.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Inet/#method-type
         */
        public function type() {}

        /**
         * Returns the normalized string representation of the address.
         *
         * @return string address
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Inet/#method-address
         */
        public function address() {}
    }

    /**
     * A PHP representation of the CQL `date` type.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Date/
     */
    final class Date implements Value
    {
        /**
         * Creates a new Date object
         *
         * @param int $seconds Absolute seconds from epoch (1970, 1, 1), can be negative, defaults to current time.
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Date/#method-__construct
         */
        public function __construct($seconds) {}

        /**
         * Creates a new Date object from a \DateTime object.
         *
         * @param \DateTime $datetime A \DateTime object to convert.
         *
         * @return \DateTime PHP representation
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Date/#method-fromDateTime
         */
        public static function fromDateTime($datetime) {}

        /**
         * The type of this date.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Date/#method-type
         */
        public function type() {}

        /**
         * @return int Absolute seconds from epoch (1970, 1, 1), can be negative
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Date/#method-seconds
         */
        public function seconds() {}

        /**
         * Converts current date to PHP DateTime.
         *
         * @param \Cassandra\Time $time An optional Time object that is added to the DateTime object.
         *
         * @return \DateTime PHP representation
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Date/#method-toDateTime
         */
        public function toDateTime($time) {}

        /**
         * @return string this date in string format: Date(seconds=$seconds)
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Date/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * A PHP representation of a column
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultColumn/
     */
    final class DefaultColumn implements Column
    {
        /**
         * Returns the name of the column.
         *
         * @return string Name of the column or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultColumn/#method-name
         */
        public function name() {}

        /**
         * Returns the type of the column.
         *
         * @return \Cassandra\Type Type of the column
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultColumn/#method-type
         */
        public function type() {}

        /**
         * Returns whether the column is in descending or ascending order.
         *
         * @return bool Whether the column is stored in descending order.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultColumn/#method-isReversed
         */
        public function isReversed() {}

        /**
         * Returns true for static columns.
         *
         * @return bool Whether the column is static
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultColumn/#method-isStatic
         */
        public function isStatic() {}

        /**
         * Returns true for frozen columns.
         *
         * @return bool Whether the column is frozen
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultColumn/#method-isFrozen
         */
        public function isFrozen() {}

        /**
         * Returns name of the index if defined.
         *
         * @return string Name of the index if defined or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultColumn/#method-indexName
         */
        public function indexName() {}

        /**
         * Returns index options if present.
         *
         * @return string Index options if present or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultColumn/#method-indexOptions
         */
        public function indexOptions() {}
    }

    /**
     * A PHP representation of the CQL `blob` datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Blob/
     */
    final class Blob implements Value
    {
        /**
         * Creates a new bytes array.
         *
         * @param string $bytes any bytes
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Blob/#method-__construct
         */
        public function __construct($bytes) {}

        /**
         * Returns bytes as a hex string.
         *
         * @return string bytes as hexadecimal string
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Blob/#method-__toString
         */
        public function __toString() {}

        /**
         * The type of this blob.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Blob/#method-type
         */
        public function type() {}

        /**
         * Returns bytes as a hex string.
         *
         * @return string bytes as hexadecimal string
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Blob/#method-bytes
         */
        public function bytes() {}

        /**
         * Returns bytes as a binary string.
         *
         * @return string bytes as binary string
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Blob/#method-toBinaryString
         */
        public function toBinaryString() {}
    }

    /**
     * A PHP representation of a table
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/
     */
    final class DefaultTable implements Table
    {
        /**
         * Returns the name of this table
         *
         * @return string Name of the table
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-name
         */
        public function name() {}

        /**
         * Return a table's option by name
         *
         * @param string $name The name of the option
         *
         * @return \Cassandra\Value Value of an option by name
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-option
         */
        public function option($name) {}

        /**
         * Returns all the table's options
         *
         * @return array A dictionary of `string` and `Value` pairs of the table's options.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-options
         */
        public function options() {}

        /**
         * Description of the table, if any
         *
         * @return string Table description or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-comment
         */
        public function comment() {}

        /**
         * Returns read repair chance
         *
         * @return float Read repair chance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-readRepairChance
         */
        public function readRepairChance() {}

        /**
         * Returns local read repair chance
         *
         * @return float Local read repair chance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-localReadRepairChance
         */
        public function localReadRepairChance() {}

        /**
         * Returns GC grace seconds
         *
         * @return int GC grace seconds
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-gcGraceSeconds
         */
        public function gcGraceSeconds() {}

        /**
         * Returns caching options
         *
         * @return string Caching options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-caching
         */
        public function caching() {}

        /**
         * Returns bloom filter FP chance
         *
         * @return float Bloom filter FP chance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-bloomFilterFPChance
         */
        public function bloomFilterFPChance() {}

        /**
         * Returns memtable flush period in milliseconds
         *
         * @return int Memtable flush period in milliseconds
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-memtableFlushPeriodMs
         */
        public function memtableFlushPeriodMs() {}

        /**
         * Returns default TTL.
         *
         * @return int Default TTL.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-defaultTTL
         */
        public function defaultTTL() {}

        /**
         * Returns speculative retry.
         *
         * @return string Speculative retry.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-speculativeRetry
         */
        public function speculativeRetry() {}

        /**
         * Returns index interval
         *
         * @return int Index interval
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-indexInterval
         */
        public function indexInterval() {}

        /**
         * Returns compaction strategy class name
         *
         * @return string Compaction strategy class name
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-compactionStrategyClassName
         */
        public function compactionStrategyClassName() {}

        /**
         * Returns compaction strategy options
         *
         * @return \Cassandra\Map Compaction strategy options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-compactionStrategyOptions
         */
        public function compactionStrategyOptions() {}

        /**
         * Returns compression parameters
         *
         * @return \Cassandra\Map Compression parameters
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-compressionParameters
         */
        public function compressionParameters() {}

        /**
         * Returns whether or not the `populate_io_cache_on_flush` is true
         *
         * @return bool Value of `populate_io_cache_on_flush` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-populateIOCacheOnFlush
         */
        public function populateIOCacheOnFlush() {}

        /**
         * Returns whether or not the `replicate_on_write` is true
         *
         * @return bool Value of `replicate_on_write` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-replicateOnWrite
         */
        public function replicateOnWrite() {}

        /**
         * Returns the value of `max_index_interval`
         *
         * @return int Value of `max_index_interval` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-maxIndexInterval
         */
        public function maxIndexInterval() {}

        /**
         * Returns the value of `min_index_interval`
         *
         * @return int Value of `min_index_interval` or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-minIndexInterval
         */
        public function minIndexInterval() {}

        /**
         * Returns column by name
         *
         * @param string $name Name of the column
         *
         * @return \Cassandra\Column Column instance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-column
         */
        public function column($name) {}

        /**
         * Returns all columns in this table
         *
         * @return array A list of `Column` instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-columns
         */
        public function columns() {}

        /**
         * Returns the partition key columns of the table
         *
         * @return array A list of of `Column` instance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-partitionKey
         */
        public function partitionKey() {}

        /**
         * Returns both the partition and clustering key columns of the table
         *
         * @return array A list of of `Column` instance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-primaryKey
         */
        public function primaryKey() {}

        /**
         * Returns the clustering key columns of the table
         *
         * @return array A list of of `Column` instances
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-clusteringKey
         */
        public function clusteringKey() {}

        /**
         * @return array A list of cluster column orders ('asc' and 'desc')
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-clusteringOrder
         */
        public function clusteringOrder() {}

        /**
         * Get an index by name
         *
         * @param string $name Index name
         *
         * @return \Cassandra\Index|null An index or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-index
         */
        public function index($name) {}

        /**
         * Gets all indexes
         *
         * @return array An array of indexes
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-indexes
         */
        public function indexes() {}

        /**
         * Get materialized view by name
         *
         * @param string $name Materialized view name
         *
         * @return \Cassandra\MaterizedView|null A materialized view or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-materializedView
         */
        public function materializedView($name) {}

        /**
         * Gets all materialized views
         *
         * @return array An array of materialized views
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.DefaultTable/#method-materializedViews
         */
        public function materializedViews() {}
    }

    /**
     * A future that always resolves in a value.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.FutureValue/
     */
    final class FutureValue implements Future
    {
        /**
         * Waits for a given future resource to resolve and throws errors if any.
         *
         * @param int|float|null $timeout A timeout in seconds
         *
         * @return mixed A value
         * @throws \Cassandra\Exception\TimeoutException
         *
         * @throws \Cassandra\Exception\InvalidArgumentException
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.FutureValue/#method-get
         */
        public function get($timeout) {}
    }

    /**
     * A PHP representation of the CQL `decimal` datatype
     *
     * The actual value of a decimal is `$value * pow(10, $scale * -1)`
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/
     */
    final class Decimal implements Value, Numeric
    {
        /**
         * Creates a decimal from a given decimal string:
         *
         * ~~~{.php}
         * <?php
         * $decimal = new Cassandra::Decimal("1313123123.234234234234234234123");
         * ~~~
         *
         * @param string $value Any decimal string
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-__construct
         */
        public function __construct($value) {}

        /**
         * String representation of this decimal.
         *
         * @return string Decimal value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-__toString
         */
        public function __toString() {}

        /**
         * The type of this decimal.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-type
         */
        public function type() {}

        /**
         * Numeric value of this decimal as string.
         *
         * @return string Numeric value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-value
         */
        public function value() {}

        /**
         * Scale of this decimal as int.
         *
         * @return int Scale
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-scale
         */
        public function scale() {}

        /**
         * @param \Cassandra\Numeric $num a number to add to this one
         *
         * @return \Cassandra\Numeric sum
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-add
         */
        public function add($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to subtract from this one
         *
         * @return \Cassandra\Numeric difference
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-sub
         */
        public function sub($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to multiply this one by
         *
         * @return \Cassandra\Numeric product
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-mul
         */
        public function mul($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric quotient
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-div
         */
        public function div($num) {}

        /**
         * @param \Cassandra\Numeric $num a number to divide this one by
         *
         * @return \Cassandra\Numeric remainder
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-mod
         */
        public function mod($num) {}

        /**
         * @return \Cassandra\Numeric absolute value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-abs
         */
        public function abs() {}

        /**
         * @return \Cassandra\Numeric negative value
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-neg
         */
        public function neg() {}

        /**
         * @return \Cassandra\Numeric square root
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-sqrt
         */
        public function sqrt() {}

        /**
         * @return int this number as int
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-toInt
         */
        public function toInt() {}

        /**
         * @return float this number as float
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.Decimal/#method-toDouble
         */
        public function toDouble() {}
    }

    /**
     * A future returned from Session::closeAsync().
     *
     * @see \Cassandra\Session::closeAsync()
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.FutureClose/
     */
    final class FutureClose implements Future
    {
        /**
         * Waits for a given future resource to resolve and throws errors if any.
         *
         * @param int|float|null $timeout A timeout in seconds
         *
         * @return null Nothing
         * @throws \Cassandra\Exception\TimeoutException
         *
         * @throws \Cassandra\Exception\InvalidArgumentException
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.FutureClose/#method-get
         */
        public function get($timeout) {}
    }

    /**
     * Prepared statements are faster to execute because the server doesn't need
     * to process a statement's CQL during the execution.
     *
     * With token-awareness enabled in the driver, prepared statements are even
     * faster, because they are sent directly to replica nodes and avoid the extra
     * network hop.
     *
     * @see \Cassandra\Session::prepare()
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.PreparedStatement/
     */
    final class PreparedStatement implements Statement
    {
        private function __construct() {}
    }

    /**
     * Request execution options.
     *
     * @see \Cassandra\Session::execute()
     * @see \Cassandra\Session::executeAsync()
     * @see \Cassandra\Session::prepare()
     * @see \Cassandra\Session::prepareAsync()
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.ExecutionOptions/
     */
    #[Deprecated('Use an array of options instead of creating an instance of this class.')]
    final class ExecutionOptions
    {
        /**
         * Creates a new options object for execution.
         *
         * @param array $options various execution options
         *
         * @throws \Cassandra\Exception\InvalidArgumentException
         *
         * @see \Cassandra\Session::execute() for valid execution options
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.ExecutionOptions/#method-__construct
         */
        public function __construct($options) {}

        /**
         * @param mixed $name
         *
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.ExecutionOptions/#method-__get
         */
        public function __get($name) {}
    }

    /**
     * A PHP representation of the CQL UDT datatype
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.UserTypeValue/
     */
    final class UserTypeValue implements Value, \Countable, \Iterator
    {
        /**
         * Creates a new user type value with the given name/type pairs.
         *
         * @param array $types Array of types
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.UserTypeValue/#method-__construct
         */
        public function __construct($types) {}

        /**
         * The type of this user type value.
         *
         * @return \Cassandra\Type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.UserTypeValue/#method-type
         */
        public function type() {}

        /**
         * Array of values in this user type value.
         *
         * @return array values
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.UserTypeValue/#method-values
         */
        public function values() {}

        /**
         * Sets the value at name in this user type value.
         *
         * @param mixed $value A value or null
         *
         * @return void
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.UserTypeValue/#method-set
         */
        public function set($value) {}

        /**
         * Retrieves the value at a given name.
         *
         * @param string $name String of the field name
         *
         * @return mixed A value or null
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.UserTypeValue/#method-get
         */
        public function get($name) {}

        /**
         * Total number of elements in this user type value.
         *
         * @return int count
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.UserTypeValue/#method-count
         */
        public function count() {}

        /**
         * Current element for iteration
         *
         * @return mixed The current element
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.UserTypeValue/#method-current
         */
        public function current() {}

        /**
         * Current key for iteration
         *
         * @return int current key
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.UserTypeValue/#method-key
         */
        public function key() {}

        /**
         * Move internal iterator forward
         *
         * @return void
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.UserTypeValue/#method-next
         */
        public function next() {}

        /**
         * Check whether a current value exists
         *
         * @return bool
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.UserTypeValue/#method-valid
         */
        public function valid() {}

        /**
         * Rewind internal iterator
         *
         * @return void
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/class.UserTypeValue/#method-rewind
         */
        public function rewind() {}
    }
}

/**
 * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/
 */

namespace Cassandra\Cluster {
    /**
     * Cluster builder allows fluent configuration of the cluster instance.
     *
     * @see \Cassandra::cluster()
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/
     */
    final class Builder
    {
        /**
         * Returns a Cluster Instance.
         *
         * @return \Cassandra\Cluster Cluster instance
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-build
         */
        public function build() {}

        /**
         * Configures default consistency for all requests.
         *
         * @param int $consistency A consistency level, must be one of Cassandra::CONSISTENCY_* values
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withDefaultConsistency
         */
        public function withDefaultConsistency($consistency) {}

        /**
         * Configures default page size for all results.
         * Set to `null` to disable paging altogether.
         *
         * @param int|null $pageSize default page size
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withDefaultPageSize
         */
        public function withDefaultPageSize($pageSize) {}

        /**
         * Configures default timeout for future resolution in blocking operations
         * Set to null to disable (default).
         *
         * @param float|null $timeout Timeout value in seconds, can be fractional
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withDefaultTimeout
         */
        public function withDefaultTimeout($timeout) {}

        /**
         * Configures the initial endpoints. Note that the driver will
         * automatically discover and connect to the rest of the cluster.
         *
         * @param string ...$host one or more ip addresses or hostnames
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withContactPoints
         */
        public function withContactPoints(...$host) {}

        /**
         * Specify a different port to be used when connecting to the cluster.
         *
         * @param int $port a number between 1 and 65535
         *
         * @return \Cassandra\Cluster\Builder self
         * @throws \Cassandra\Exception\InvalidArgumentException
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withPort
         */
        public function withPort($port) {}

        /**
         * Configures this cluster to use a round robin load balancing policy.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withRoundRobinLoadBalancingPolicy
         */
        public function withRoundRobinLoadBalancingPolicy() {}

        /**
         * Configures this cluster to use a datacenter aware round robin load balancing policy.
         *
         * @param string $localDatacenter Name of the local datacenter
         * @param int $hostPerRemoteDatacenter Maximum number of hosts to try in remote datacenters
         * @param bool $useRemoteDatacenterForLocalConsistencies Allow using hosts from remote datacenters to execute statements with local consistencies
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withDatacenterAwareRoundRobinLoadBalancingPolicy
         */
        public function withDatacenterAwareRoundRobinLoadBalancingPolicy($localDatacenter, $hostPerRemoteDatacenter, $useRemoteDatacenterForLocalConsistencies) {}

        /**
         * Sets the blacklist hosts. Any host in the blacklist will be ignored and
         * a connection will not be established. This is useful for ensuring that
         * the driver will not connection to a predefied set of hosts.
         *
         * @param string $hosts A comma delimited list of addresses.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withBlackListHosts
         */
        public function withBlackListHosts($hosts) {}

        /**
         * Sets the whitelist hosts. Any host not in the whitelist will be ignored
         * and a connection will not be established. This policy is useful for
         * ensuring that the driver will only connect to a predefined set of hosts.
         *
         * @param string $hosts A comma delimited list of addresses.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withWhiteListHosts
         */
        public function withWhiteListHosts($hosts) {}

        /**
         * Sets the blacklist datacenters. Any datacenter in the blacklist will be
         * ignored and a connection will not be established to any host in those
         * datacenters. This policy is useful for ensuring the driver will not
         * connect to any host in a specific datacenter.
         *
         * @param string $dcs A comma delimited list of datacenters.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withBlackListDCs
         */
        public function withBlackListDCs($dcs) {}

        /**
         * Sets the whitelist datacenters. Any host not in a whitelisted datacenter
         * will be ignored. This policy is useful for ensuring the driver will only
         * connect to hosts in specific datacenters.
         *
         * @param string $dcs A comma delimited list of datacenters.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withWhiteListDCs
         */
        public function withWhiteListDCs($dcs) {}

        /**
         * Enable token aware routing.
         *
         * @param bool $enabled Whether to enable token aware routing (optional)
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withTokenAwareRouting
         */
        public function withTokenAwareRouting($enabled) {}

        /**
         * Configures plain-text authentication.
         *
         * @param string $username Username
         * @param string $password Password
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withCredentials
         */
        public function withCredentials($username, $password) {}

        /**
         * Timeout used for establishing TCP connections.
         *
         * @param float $timeout Timeout value in seconds, can be fractional
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withConnectTimeout
         */
        public function withConnectTimeout($timeout) {}

        /**
         * Timeout used for waiting for a response from a node.
         *
         * @param float $timeout Timeout value in seconds, can be fractional
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withRequestTimeout
         */
        public function withRequestTimeout($timeout) {}

        /**
         * Set up ssl context.
         *
         * @param \Cassandra\SSLOptions $options a preconfigured ssl context
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withSSL
         */
        public function withSSL($options) {}

        /**
         * Enable persistent sessions and clusters.
         *
         * @param bool $enabled whether to enable persistent sessions and clusters
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withPersistentSessions
         */
        public function withPersistentSessions($enabled) {}

        /**
         * Force the driver to use a specific binary protocol version.
         *
         * Apache Cassandra 1.2+ supports protocol version 1
         * Apache Cassandra 2.0+ supports protocol version 2
         * Apache Cassandra 2.1+ supports protocol version 3
         * Apache Cassandra 2.2+ supports protocol version 4
         *
         * NOTE: Apache Cassandra 3.x supports protocol version 3 and 4 only
         *
         * @param int $version The protocol version
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withProtocolVersion
         */
        public function withProtocolVersion($version) {}

        /**
         * Total number of IO threads to use for handling the requests.
         *
         * Note: number of io threads * core connections per host <= total number
         *       of connections <= number of io threads * max connections per host
         *
         * @param int $count total number of threads.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withIOThreads
         */
        public function withIOThreads($count) {}

        /**
         * Set the size of connection pools used by the driver. Pools are fixed
         * when only `$core` is given, when a `$max` is specified as well,
         * additional connections will be created automatically based on current
         * load until the maximum number of connection has been reached. When
         * request load goes down, extra connections are automatically cleaned up
         * until only the core number of connections is left.
         *
         * @param int $core minimum connections to keep open to any given host
         * @param int $max maximum connections to keep open to any given host
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withConnectionsPerHost
         */
        public function withConnectionsPerHost($core, $max) {}

        /**
         * Specify interval in seconds that the driver should wait before attempting
         * to re-establish a closed connection.
         *
         * @param float $interval interval in seconds
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withReconnectInterval
         */
        public function withReconnectInterval($interval) {}

        /**
         * Enables/disables latency-aware routing.
         *
         * @param bool $enabled whether to actually enable or disable the routing.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withLatencyAwareRouting
         */
        public function withLatencyAwareRouting($enabled) {}

        /**
         * Disables nagle algorithm for lower latency.
         *
         * @param bool $enabled whether to actually enable or disable nodelay.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withTCPNodelay
         */
        public function withTCPNodelay($enabled) {}

        /**
         * Enables/disables TCP keepalive.
         *
         * @param float|null $delay The period of inactivity in seconds, after
         *                          which the keepalive probe should be sent over
         *                          the connection. If set to `null`, disables
         *                          keepalive probing.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withTCPKeepalive
         */
        public function withTCPKeepalive($delay) {}

        /**
         * Configures the retry policy.
         *
         * @param \Cassandra\Cluster\RetryPolicy $policy the retry policy to use.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withRetryPolicy
         */
        public function withRetryPolicy($policy) {}

        /**
         * Sets the timestamp generator.
         *
         * @param \Cassandra\TimestampGenerator $generator A timestamp generator that will be used
         *                                                 to generate timestamps for statements.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withTimestampGenerator
         */
        public function withTimestampGenerator($generator) {}

        /**
         * Enables/disables Schema Metadata.
         *
         * If disabled this allows the driver to skip over retrieving and
         * updating schema metadata, but it also disables the usage of token-aware
         * routing and $session->schema() will always return an empty object. This
         * can be useful for reducing the startup overhead of short-lived sessions.
         *
         * @param bool $enabled whether the driver fetches and maintains schema metadata.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withSchemaMetadata
         */
        public function withSchemaMetadata($enabled) {}

        /**
         * Enables/disables Hostname Resolution.
         *
         * If enabled the driver will resolve hostnames for IP addresses using
         * reverse IP lookup. This is useful for authentication (Kerberos) or
         * encryption SSL services that require a valid hostname for verification.
         *
         * Important: It's possible that the underlying C/C++ driver does not
         * support hostname resolution. A PHP warning will be emitted if the driver
         * does not support hostname resolution.
         *
         * @param bool $enabled whether the driver uses hostname resolution.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withHostnameResolution
         */
        public function withHostnameResolution($enabled) {}

        /**
         * Enables/disables Randomized Contact Points.
         *
         * If enabled this allows the driver randomly use contact points in order
         * to evenly spread the load across the cluster and prevent
         * hotspots/load spikes during notifications (e.g. massive schema change).
         *
         * Note: This setting should only be disabled for debugging and testing.
         *
         * @param bool $enabled whether the driver uses randomized contact points.
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withRandomizedContactPoints
         */
        public function withRandomizedContactPoints($enabled) {}

        /**
         * Specify interval in seconds that the driver should wait before attempting
         * to send heartbeat messages and control the amount of time the connection
         * must be idle before sending heartbeat messages. This is useful for
         * preventing intermediate network devices from dropping connections.
         *
         * @param float $interval interval in seconds (0 to disable heartbeat).
         *
         * @return \Cassandra\Cluster\Builder self
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Cluster/class.Builder/#method-withConnectionHeartbeatInterval
         */
        public function withConnectionHeartbeatInterval($interval) {}
    }
}

/**
 * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/TimestampGenerator/
 */

namespace Cassandra\TimestampGenerator {
    /**
     * A timestamp generator that allows the server-side to assign timestamps.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/TimestampGenerator/class.ServerSide/
     */
    final class ServerSide implements \Cassandra\TimestampGenerator {}

    /**
     * A timestamp generator that generates monotonically increasing timestamps
     * client-side. The timestamps generated have a microsecond granularity with
     * the sub-millisecond part generated using a counter. The implementation
     * guarantees that no more than 1000 timestamps will be generated for a given
     * clock tick even if shared by multiple session objects. If that rate is
     * exceeded then a warning is logged and timestamps stop incrementing until
     * the next clock tick.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/TimestampGenerator/class.Monotonic/
     */
    final class Monotonic implements \Cassandra\TimestampGenerator {}
}

/**
 * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/RetryPolicy/
 */

namespace Cassandra\RetryPolicy {
    /**
     * The default retry policy. This policy retries a query, using the
     * request's original consistency level, in the following cases:
     *
     * * On a read timeout, if enough replicas replied but the data was not received.
     * * On a write timeout, if a timeout occurs while writing a distributed batch log.
     * * On unavailable, it will move to the next host.
     *
     * In all other cases the error will be returned.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/RetryPolicy/class.DefaultPolicy/
     */
    final class DefaultPolicy implements \Cassandra\RetryPolicy {}

    /**
     * A retry policy that will downgrade the consistency of a request in
     * an attempt to save a request in cases where there is any chance of success. A
     * write request will succeed if there is at least a single copy persisted and a
     * read request will succeed if there is some data available even if it increases
     * the risk of reading stale data. This policy will retry in the same scenarios as
     * the default policy, and it will also retry in the following case:
     *
     * * On a read timeout, if some replicas responded but is lower than
     *   required by the current consistency level then retry with a lower
     *   consistency level
     * * On a write timeout, Retry unlogged batches at a lower consistency level
     *   if at least one replica responded. For single queries and batch if any
     *   replicas responded then consider the request successful and swallow the
     *   error.
     * * On unavailable, retry at a lower consistency if at lease one replica
     *   responded.
     *
     * Important: This policy may attempt to retry requests with a lower
     * consistency level. Using this policy can break consistency guarantees.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/RetryPolicy/class.DowngradingConsistency/
     */
    final class DowngradingConsistency implements \Cassandra\RetryPolicy {}

    /**
     * A retry policy that never retries and allows all errors to fallthrough.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/RetryPolicy/class.Fallthrough/
     */
    final class Fallthrough implements \Cassandra\RetryPolicy {}

    /**
     * A retry policy that logs the decisions of its child policy.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/RetryPolicy/class.Logging/
     */
    final class Logging implements \Cassandra\RetryPolicy
    {
        /**
         * Creates a new Logging retry policy.
         *
         * @param \Cassandra\RetryPolicy $childPolicy Any retry policy other than Logging
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/RetryPolicy/class.Logging/#method-__construct
         */
        public function __construct($childPolicy) {}
    }
}

/**
 * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/
 */

namespace Cassandra\Type {
    /**
     * A class that represents the tuple type. The tuple type is able to represent
     * a composite type of one or more types accessed by index.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Tuple/
     */
    final class Tuple extends \Cassandra\Type
    {
        private function __construct() {}

        /**
         * Returns "tuple"
         *
         * @return string "tuple"
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Tuple/#method-name
         */
        public function name() {}

        /**
         * Returns type representation in CQL, e.g. `tuple<varchar, int>`
         *
         * @return string Type representation in CQL
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Tuple/#method-__toString
         */
        public function __toString() {}

        /**
         * Returns types of values
         *
         * @return array An array of types
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Tuple/#method-types
         */
        public function types() {}

        /**
         * Creates a new Tuple from the given values. When no values given,
         * creates a tuple with null for the values.
         *
         * @param mixed ...$values One or more values to be added to the tuple.
         *
         * @return \Cassandra\Tuple A tuple with given values.
         * @throws \Cassandra\Exception\InvalidArgumentException when values given are of a
         *                                            different type than what the
         *                                            tuple expects.
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Tuple/#method-create
         */
        public function create(...$values) {}
    }

    /**
     * A class that represents the list type. The list type contains the type of the
     * elements contain in the list.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Collection/
     */
    final class Collection extends \Cassandra\Type
    {
        private function __construct() {}

        /**
         * Returns "list"
         *
         * @return string "list"
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Collection/#method-name
         */
        public function name() {}

        /**
         * Returns type of values
         *
         * @return \Cassandra\Type Type of values
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Collection/#method-valueType
         */
        public function valueType() {}

        /**
         * Returns type representation in CQL, e.g. `list<varchar>`
         *
         * @return string Type representation in CQL
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Collection/#method-__toString
         */
        public function __toString() {}

        /**
         * Creates a new Collection from the given values.  When no values
         * given, creates an empty list.
         *
         * @param mixed ...$value One or more values to be added to the list.
         *
         * @return \Cassandra\Collection A list with given values.
         * @throws \Cassandra\Exception\InvalidArgumentException when values given are of a
         *                                            different type than what this
         *                                            list type expects.
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Collection/#method-create
         */
        public function create(...$value) {}
    }

    /**
     * A class that represents the set type. The set type contains the type of the
     * elements contain in the set.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Set/
     */
    final class Set extends \Cassandra\Type
    {
        private function __construct() {}

        /**
         * Returns "set"
         *
         * @return string "set"
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Set/#method-name
         */
        public function name() {}

        /**
         * Returns type of values
         *
         * @return \Cassandra\Type Type of values
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Set/#method-valueType
         */
        public function valueType() {}

        /**
         * Returns type representation in CQL, e.g. `set<varchar>`
         *
         * @return string Type representation in CQL
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Set/#method-__toString
         */
        public function __toString() {}

        /**
         * Creates a new Set from the given values.
         *
         * @param mixed ...$value One or more values to be added to the set. When no values are given, creates an empty set.
         *
         * @return \Cassandra\Set A set with given values.
         * @throws \Cassandra\Exception\InvalidArgumentException when values given are of a
         *                                            different type than what this
         *                                            set type expects.
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Set/#method-create
         */
        public function create(...$value) {}
    }

    /**
     * A class that represents a custom type.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Custom/
     */
    final class Custom extends \Cassandra\Type
    {
        private function __construct() {}

        /**
         * Returns the name of this type as string.
         *
         * @return string The name of this type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Custom/#method-name
         */
        public function name() {}

        /**
         * Returns string representation of this type.
         *
         * @return string String representation of this type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Custom/#method-__toString
         */
        public function __toString() {}

        /**
         * @param mixed $value
         *
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Custom/#method-create
         */
        public function create($value) {}
    }

    /**
     * A class that represents a user type. The user type is able to represent a
     * composite type of one or more types accessed by name.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.UserType/
     */
    final class UserType extends \Cassandra\Type
    {
        private function __construct() {}

        /**
         * Associate the user type with a name.
         *
         * @param string $name Name of the user type.
         *
         * @return null Nothing.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.UserType/#method-withName
         */
        public function withName($name) {}

        /**
         * Returns type name for the user type
         *
         * @return string Name of this type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.UserType/#method-name
         */
        public function name() {}

        /**
         * Associate the user type with a keyspace.
         *
         * @param string $keyspace Keyspace that contains the user type.
         *
         * @return null Nothing.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.UserType/#method-withKeyspace
         */
        public function withKeyspace($keyspace) {}

        /**
         * Returns keyspace for the user type
         *
         * @return string
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.UserType/#method-keyspace
         */
        public function keyspace() {}

        /**
         * Returns type representation in CQL, e.g. keyspace1.type_name1 or
         * `userType<name1:varchar, name2:int>`.
         *
         * @return string Type representation in CQL
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.UserType/#method-__toString
         */
        public function __toString() {}

        /**
         * Returns types of values
         *
         * @return array An array of types
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.UserType/#method-types
         */
        public function types() {}

        /**
         * Creates a new UserTypeValue from the given name/value pairs. When
         * no values given, creates an empty user type.
         *
         * @param mixed ...$value One or more name/value pairs to be added to the user type.
         *
         * @return \Cassandra\UserTypeValue A user type value with given name/value pairs.
         * @throws \Cassandra\Exception\InvalidArgumentException when values given are of a
         *                                            different types than what the
         *                                            user type expects.
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.UserType/#method-create
         */
        public function create(...$value) {}
    }

    /**
     * A class that represents the map type. The map type contains two types that
     * represents the types of the key and value contained in the map.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Map/
     */
    final class Map extends \Cassandra\Type
    {
        private function __construct() {}

        /**
         * Returns "map"
         *
         * @return string "map"
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Map/#method-name
         */
        public function name() {}

        /**
         * Returns type of keys
         *
         * @return \Cassandra\Type Type of keys
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Map/#method-keyType
         */
        public function keyType() {}

        /**
         * Returns type of values
         *
         * @return \Cassandra\Type Type of values
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Map/#method-valueType
         */
        public function valueType() {}

        /**
         * Returns type representation in CQL, e.g. `map<varchar, int>`
         *
         * @return string Type representation in CQL
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Map/#method-__toString
         */
        public function __toString() {}

        /**
         * Creates a new Map from the given values.
         *
         * ```<?php
         * use Type;
         * use Uuid;
         *
         * $type = Type::map(Type::uuid(), Type::varchar());
         * $map = $type->create(new Uuid(), 'first uuid',
         *                      new Uuid(), 'second uuid',
         *                      new Uuid(), 'third uuid');
         *
         * var_dump($map);```
         *
         *
         *                          is a key and each even value is a value for the
         *                          map, e.g. `create(key, value, key, value)`.
         *                          When no values given, creates an empty map.
         *
         * @param mixed ...$value An even number of values, where each odd value
         *
         * @return \Cassandra\Map A set with given values.
         * @throws \Cassandra\Exception\InvalidArgumentException when keys or values given are
         *                                            of a different type than what
         *                                            this map type expects.
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Map/#method-create
         */
        public function create(...$value) {}
    }

    /**
     * A class that represents a primitive type (e.g. `varchar` or `bigint`)
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Scalar/
     */
    final class Scalar extends \Cassandra\Type
    {
        private function __construct() {}

        /**
         * Returns the name of this type as string.
         *
         * @return string Name of this type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Scalar/#method-name
         */
        public function name() {}

        /**
         * Returns string representation of this type.
         *
         * @return string String representation of this type
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Scalar/#method-__toString
         */
        public function __toString() {}

        /**
         * @param mixed $value
         *
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Type/class.Scalar/#method-create
         */
        public function create($value) {}
    }
}

/**
 * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/SSLOptions/
 */

namespace Cassandra\SSLOptions {
    /**
     * SSLOptions builder allows fluent configuration of ssl options.
     *
     * @see \Cassandra::ssl()
     * @see \Cassandra\Cluster\Builder::withSSL()
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/SSLOptions/class.Builder/
     */
    final class Builder
    {
        /**
         * Builds SSL options.
         *
         * @return \Cassandra\SSLOptions ssl options configured accordingly.
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/SSLOptions/class.Builder/#method-build
         */
        public function build() {}

        /**
         * Adds a trusted certificate. This is used to verify node's identity.
         *
         * @param string ...$path one or more paths to files containing a PEM formatted certificate.
         *
         * @return \Cassandra\Cluster\Builder self
         * @throws \Cassandra\Exception\InvalidArgumentException
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/SSLOptions/class.Builder/#method-withTrustedCerts
         */
        public function withTrustedCerts(...$path) {}

        /**
         * Disable certificate verification.
         *
         * @param int $flags
         *
         * @return \Cassandra\Cluster\Builder self
         * @throws \Cassandra\Exception\InvalidArgumentException
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/SSLOptions/class.Builder/#method-withVerifyFlags
         */
        public function withVerifyFlags($flags) {}

        /**
         * Set client-side certificate chain.
         *
         * This is used to authenticate the client on the server-side. This should contain the entire Certificate
         * chain starting with the certificate itself.
         *
         * @param string $path path to a file containing a PEM formatted certificate.
         *
         * @return \Cassandra\Cluster\Builder self
         * @throws \Cassandra\Exception\InvalidArgumentException
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/SSLOptions/class.Builder/#method-withClientCert
         */
        public function withClientCert($path) {}

        /**
         * Set client-side private key. This is used to authenticate the client on
         * the server-side.
         *
         * @param string $path Path to the private key file
         * @param string|null $passphrase Passphrase for the private key, if any
         *
         * @return \Cassandra\Cluster\Builder self
         * @throws \Cassandra\Exception\InvalidArgumentException
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/SSLOptions/class.Builder/#method-withPrivateKey
         */
        public function withPrivateKey($path, $passphrase) {}
    }
}

/**
 * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/
 */

namespace Cassandra\Exception {
    use JetBrains\PhpStorm\Pure;

    /**
     * ConfigurationException is raised when query is syntactically correct but
     * invalid because of some configuration issue.
     * For example when attempting to drop a non-existent keyspace.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ConfigurationException/
     */
    class ConfigurationException extends ValidationException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ConfigurationException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ConfigurationException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ConfigurationException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * Cassandra domain exception.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.DomainException/
     */
    class DomainException extends \DomainException implements \Cassandra\Exception
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.DomainException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.DomainException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.DomainException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * InvalidQueryException is raised when query is syntactically correct but invalid.
     * For example when attempting to create a table without specifying a keyspace.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.InvalidQueryException/
     */
    class InvalidQueryException extends ValidationException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.InvalidQueryException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.InvalidQueryException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.InvalidQueryException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * UnpreparedException is raised when a given prepared statement id does not
     * exist on the server. The driver should be automatically re-preparing the
     * statement in this case. Seeing this error could be considered a bug.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.UnpreparedException/
     */
    class UnpreparedException extends ValidationException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.UnpreparedException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.UnpreparedException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.UnpreparedException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * Cassandra invalid argument exception.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.InvalidArgumentException/
     */
    class InvalidArgumentException extends \InvalidArgumentException implements \Cassandra\Exception
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.InvalidArgumentException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.InvalidArgumentException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.InvalidArgumentException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * ServerException is raised when something unexpected happened on the server.
     * This exception is most likely due to a server-side bug.
     * **NOTE** This exception and all its children are generated on the server.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ServerException/
     */
    class ServerException extends RuntimeException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ServerException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ServerException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ServerException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * Cassandra domain exception.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.RangeException/
     */
    class RangeException extends \RangeException implements \Cassandra\Exception
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.RangeException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.RangeException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.RangeException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * UnauthorizedException is raised when the current user doesn't have
     * sufficient permissions to access data.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.UnauthorizedException/
     */
    class UnauthorizedException extends ValidationException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.UnauthorizedException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.UnauthorizedException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.UnauthorizedException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * Cassandra logic exception.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.LogicException/
     */
    class LogicException extends \LogicException implements \Cassandra\Exception
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.LogicException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.LogicException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.LogicException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * UnavailableException is raised when a coordinator detected that there aren't
     * enough replica nodes available to fulfill the request.
     *
     * NOTE: Request has not even been forwarded to the replica nodes in this case.
     * @see https://github.com/apache/cassandra/blob/cassandra-2.1/doc/native_protocol_v1.spec#L667-L677 Description of the Unavailable error in the native protocol v1 spec.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.UnavailableException/
     */
    class UnavailableException extends ExecutionException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.UnavailableException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.UnavailableException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.UnavailableException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * AuthenticationException is raised when client was not configured with valid
     * authentication credentials.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.AuthenticationException/
     */
    class AuthenticationException extends RuntimeException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.AuthenticationException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.AuthenticationException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.AuthenticationException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * OverloadedException is raised when a node is overloaded.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.OverloadedException/
     */
    class OverloadedException extends ServerException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.OverloadedException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.OverloadedException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.OverloadedException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * ReadTimeoutException is raised when a coordinator failed to receive acks
     * from the required number of replica nodes in time during a read.
     * @see https://github.com/apache/cassandra/blob/cassandra-2.1/doc/native_protocol_v1.spec#L709-L726 Description of ReadTimeout error in the native protocol spec
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ReadTimeoutException/
     */
    class ReadTimeoutException extends ExecutionException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ReadTimeoutException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ReadTimeoutException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ReadTimeoutException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * IsBootstrappingException is raised when a node is bootstrapping.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.IsBootstrappingException/
     */
    class IsBootstrappingException extends ServerException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.IsBootstrappingException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.IsBootstrappingException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.IsBootstrappingException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * ProtocolException is raised when a client did not follow server's protocol,
     * e.g. sending a QUERY message before STARTUP. Seeing this error can be
     * considered a bug.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ProtocolException/
     */
    class ProtocolException extends RuntimeException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ProtocolException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ProtocolException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ProtocolException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * ExecutionException is raised when something went wrong during request execution.
     * @see \Cassandra\Exception\TruncateException
     * @see \Cassandra\Exception\UnavailableException
     * @see \Cassandra\Exception\ReadTimeoutException
     * @see \Cassandra\Exception\WriteTimeoutException
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ExecutionException/
     */
    class ExecutionException extends RuntimeException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ExecutionException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ExecutionException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ExecutionException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * InvalidSyntaxException is raised when CQL in the request is syntactically incorrect.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.InvalidSyntaxException/
     */
    class InvalidSyntaxException extends ValidationException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.InvalidSyntaxException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.InvalidSyntaxException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.InvalidSyntaxException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * Cassandra runtime exception.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.RuntimeException/
     */
    class RuntimeException extends \RuntimeException implements \Cassandra\Exception
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.RuntimeException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.RuntimeException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.RuntimeException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * TimeoutException is generally raised when a future did not resolve
     * within a given time interval.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.TimeoutException/
     */
    class TimeoutException extends RuntimeException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.TimeoutException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.TimeoutException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.TimeoutException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * ValidationException is raised on invalid request, before even attempting to
     * execute it.
     * @see \Cassandra\Exception\InvalidSyntaxException
     * @see \Cassandra\Exception\UnauthorizedException
     * @see \Cassandra\Exception\InvalidQueryException
     * @see \Cassandra\Exception\ConfigurationException
     * @see \Cassandra\Exception\AlreadyExistsException
     * @see \Cassandra\Exception\UnpreparedException
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ValidationException/
     */
    class ValidationException extends RuntimeException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ValidationException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ValidationException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.ValidationException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * TruncateException is raised when something went wrong during table
     * truncation.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.TruncateException/
     */
    class TruncateException extends ExecutionException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.TruncateException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.TruncateException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.TruncateException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * AlreadyExistsException is raised when attempting to re-create existing keyspace.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.AlreadyExistsException/
     */
    class AlreadyExistsException extends ConfigurationException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.AlreadyExistsException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.AlreadyExistsException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.AlreadyExistsException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * Cassandra domain exception.
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.DivideByZeroException/
     */
    class DivideByZeroException extends RangeException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.DivideByZeroException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.DivideByZeroException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.DivideByZeroException/#method-__toString
         */
        public function __toString() {}
    }

    /**
     * WriteTimeoutException is raised when a coordinator failed to receive acks
     * from the required number of replica nodes in time during a write.
     * @see https://github.com/apache/cassandra/blob/cassandra-2.1/doc/native_protocol_v1.spec#L683-L708 Description of WriteTimeout error in the native protocol spec
     * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.WriteTimeoutException/
     */
    class WriteTimeoutException extends ExecutionException
    {
        /**
         * @param mixed $message
         * @param mixed $code
         * @param mixed $previous
         *
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.WriteTimeoutException/#method-__construct
         */
        #[Pure]
        public function __construct($message, $code, $previous) {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.WriteTimeoutException/#method-__wakeup
         */
        public function __wakeup() {}

        /**
         * @return mixed
         * @link https://docs.datastax.com/en/developer/php-driver/latest/api/Cassandra/Exception/class.WriteTimeoutException/#method-__toString
         */
        public function __toString() {}
    }
}
