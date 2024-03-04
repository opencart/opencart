<?php
/**
 * This is a modern binding to the mature [libpq](http://www.postgresql.org/docs/current/static/libpq.html), the official PostgreSQL C-client library.
 *
 * ### Highlights:
 *
 * * Nearly 100% support for [asynchronous usage](pq/Connection/: Asynchronous Usage).
 * * Extended [type support by pg_type](pq/Types/: Overview).
 * * Fetching simple [multi-dimensional array maps](pq/Result/map).
 * * Working [Gateway implementation](https://bitbucket.org/m6w6/pq-gateway).
 */

namespace pq;

use pq;

/**
 * Fast import/export using COPY.
 */
class COPY
{
    /**
     * Start a COPY operation from STDIN to the PostgreSQL server.
     */
    public const FROM_STDIN = 0;

    /**
     * Start a COPY operation from the server to STDOUT.
     */
    public const TO_STDOUT = 1;

    /**
     * The connection to the PostgreSQL server.
     *
     * @public
     * @readonly
     * @var \pq\Connection
     */
    public $connection;

    /**
     * The expression of the COPY statement used to start the operation.
     *
     * @public
     * @readonly
     * @var string
     */
    public $expression;

    /**
     * The drection of the COPY operation (pq\COPY::FROM_STDIN or pq\COPY::TO_STDOUT).
     *
     * @public
     * @readonly
     * @var int
     */
    public $direction;

    /**
     * Any additional options used to start the COPY operation.
     *
     * @public
     * @readonly
     * @var string
     */
    public $options;

    /**
     * Start a COPY operation.
     *
     * @param \pq\Connection $conn The connection to use for the COPY operation.
     * @param string $expression The expression generating the data to copy.
     * @param int $direction Data direction (pq\COPY::FROM_STDIN or pq\COPY::TO_STDOUT).
     * @param string $options Any COPY options (see the [official PostgreSQL documentation](http://www.postgresql.org/docs/current/static/sql-copy.html) for details.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function __construct(pq\Connection $conn, string $expression, int $direction, string $options = null) {}

    /**
     * End the COPY operation to the server during pq\Result::COPY_IN state.
     *
     * @param string $error If set, the COPY operation will abort with provided message.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function end(string $error = null) {}

    /**
     * Receive data from the server during pq\Result::COPY_OUT state.
     *
     * @param string &$data Data read from the server.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return bool success.
     */
    public function get(string &$data) {}

    /**
     * Send data to the server during pq\Result::COPY_IN state.
     *
     * @param string $data Data to send to the server.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function put(string $data) {}
}
/**
 * Request cancellation of an asynchronous query.
 */
class Cancel
{
    /**
     * The connection to cancel the query on.
     *
     * @public
     * @readonly
     * @var \pq\Connection
     */
    public $connection;

    /**
     * Create a new cancellation request for an [asynchronous](pq/Connection/: Asynchronous Usage) query.
     *
     * @param \pq\Connection $conn The connection to request cancellation on.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function __construct(pq\Connection $conn) {}

    /**
     * Perform the cancellation request.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function cancel() {}
}
/**
 * The connection to the PostgreSQL server.
 *
 * See the [General Usage](pq/Connection/: General Usage) page for an introduction on how to use this class.
 */
class Connection
{
    /**
     * (Re-)open a persistent connection.
     */
    public const PERSISTENT = 2;

    /**
     * If the connection is not already open, perform the connection attempt [asynchronously](pq/Connection/: Asynchronous Usage).
     */
    public const ASYNC = 1;

    /**
     * Everything well; if a connection has been newly and synchronously created, the connection will always have this status right after creation.
     */
    public const OK = 0;

    /**
     * Broken connection; consider pq\Connection::reset() or recreation.
     */
    public const BAD = 1;

    /**
     * Waiting for connection to be made.
     */
    public const STARTED = 2;

    /**
     * Connection okay; waiting to send.
     */
    public const MADE = 3;

    /**
     * Waiting for a response from the server.
     */
    public const AWAITING_RESPONSE = 4;

    /**
     * Received authentication; waiting for backend start-up to finish.
     */
    public const AUTH_OK = 5;

    /**
     * Negotiating SSL encryption.
     */
    public const SSL_STARTUP = 7;

    /**
     * Negotiating environment-driven parameter settings.
     */
    public const SETENV = 6;

    /**
     * No active transaction.
     */
    public const TRANS_IDLE = 0;

    /**
     * A transaction command is in progress.
     */
    public const TRANS_ACTIVE = 1;

    /**
     * Idling in a valid transaction block.
     */
    public const TRANS_INTRANS = 2;

    /**
     * Idling in a failed transaction block.
     */
    public const TRANS_INERROR = 3;

    /**
     * Bad connection.
     */
    public const TRANS_UNKNOWN = 4;

    /**
     * The connection procedure has failed.
     */
    public const POLLING_FAILED = 0;

    /**
     * Select for read-readiness.
     */
    public const POLLING_READING = 1;

    /**
     * Select for write-readiness.
     */
    public const POLLING_WRITING = 2;

    /**
     * The connection has been successfully made.
     */
    public const POLLING_OK = 3;

    /**
     * Register the event handler for notices.
     */
    public const EVENT_NOTICE = 'notice';

    /**
     * Register the event handler for any created results.
     */
    public const EVENT_RESULT = 'result';

    /**
     * Register the event handler for connection resets.
     */
    public const EVENT_RESET = 'reset';

    /**
     * A [connection status constant](pq/Connection#Connection.Status:) value.
     *
     * @public
     * @readonly
     * @var int
     */
    public $status;

    /**
     * A [transaction status constant](pq/Connection#Transaction.Status:) value.
     *
     * @public
     * @readonly
     * @var int
     */
    public $transactionStatus;

    /**
     * The server socket resource.
     *
     * @public
     * @readonly
     * @var resource
     */
    public $socket;

    /**
     * Whether the connection is busy with [asynchronous operations](pq/Connection/: Asynchronous Usage).
     *
     * @public
     * @readonly
     * @var bool
     */
    public $busy;

    /**
     * Any error message on failure.
     *
     * @public
     * @readonly
     * @var string
     */
    public $errorMessage;

    /**
     * List of registered event handlers.
     *
     * @public
     * @readonly
     * @var array
     */
    public $eventHandlers;

    /**
     * Connection character set.
     *
     * @public
     * @var string
     */
    public $encoding = null;

    /**
     * Whether to fetch [asynchronous](pq/Connection/: Asynchronous Usage) results in unbuffered mode, i.e. each row generates a distinct pq\Result.
     *
     * @public
     * @var bool
     */
    public $unbuffered = false;

    /**
     * Whether to set the underlying socket nonblocking, useful for asynchronous handling of writes. See also pq\Connection::flush().
     *
     * ### Connection Information:
     *
     * @public
     * @var bool
     */
    public $nonblocking = false;

    /**
     * The database name of the connection.
     *
     * @public
     * @readonly
     * @var string
     */
    public $db;

    /**
     * The user name of the connection.
     *
     * @public
     * @readonly
     * @var string
     */
    public $user;

    /**
     * The password of the connection.
     *
     * @public
     * @readonly
     * @var string
     */
    public $pass;

    /**
     * The server host name of the connection.
     *
     * @public
     * @readonly
     * @var string
     */
    public $host;

    /**
     * The port of the connection.
     *
     * @public
     * @readonly
     * @var string
     */
    public $port;

    /**
     * The command-line options passed in the connection request.
     *
     * ### Inheritable Defaults:
     *
     * @public
     * @readonly
     * @var string
     */
    public $options;

    /**
     * Default fetch type for future pq\Result instances.
     *
     * @public
     * @var int
     */
    public $defaultFetchType = \pq\Result::FETCH_ARRAY;

    /**
     * Default conversion bitmask for future pq\Result instances.
     *
     * @public
     * @var int
     */
    public $defaultAutoConvert = \pq\Result::CONV_ALL;

    /**
     * Default transaction isolation level for future pq\Transaction instances.
     *
     * @public
     * @var int
     */
    public $defaultTransactionIsolation = \pq\Transaction::READ_COMMITTED;

    /**
     * Default transaction readonlyness for future pq\Transaction instances.
     *
     * @public
     * @var bool
     */
    public $defaultTransactionReadonly = false;

    /**
     * Default transaction deferrability for future pq\Transaction instances.
     *
     * @public
     * @var bool
     */
    public $defaultTransactionDeferrable = false;

    /**
     * Create a new PostgreSQL connection.
     * See also [General Usage](pq/Connection/: General Usage).
     *
     * @param string $dsn A ***connection string*** as described [in the PostgreSQL documentation](http://www.postgresql.org/docs/current/static/libpq-connect.html#LIBPQ-CONNSTRING).
     * @param int $flags See [connection flag constants](pq/Connection#Connection.Flags:).
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function __construct(string $dsn = "", int $flags = 0) {}

    /**
     * Declare a cursor for a query.
     *
     * @param string $name The identifying name of the cursor.
     * @param int $flags Any combination of pq\Cursor constants.
     * @param string $query The query for which to open a cursor.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\BadMethodCallException
     * @return \pq\Cursor an open cursor instance.
     */
    public function declare(string $name, int $flags, string $query) {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) declare a cursor for a query.
     *
     * > ***NOTE***:
     *   If pq\Connection::$unbuffered is TRUE, each call to pq\Connection::getResult() will generate a distinct pq\Result containing exactly one row.
     *
     * @param string $name The identifying name of the cursor.
     * @param int $flags Any combination of pq\Cursor constants.
     * @param string $query The query for which to open a cursor.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\BadMethodCallException
     * @return \pq\Cursor an open cursor instance.
     */
    public function declareAsync(string $name, int $flags, string $query) {}

    /**
     * Escape binary data for use within a query with the type bytea.
     *
     * ***NOTE:***
     * The result is not wrapped in single quotes.
     *
     * @param string $binary The binary data to escape.
     * @throws \pq\Exception\BadMethodCallException
     * @return string|false string the escaped binary data.
     * 		 or FALSE if escaping fails.
     */
    public function escapeBytea(string $binary) {}

    /**
     * [Execute one or multiple SQL queries](pq/Connection/: Executing Queries) on the connection.
     *
     * ***NOTE:***
     * Only the last result will be returned, if the query string contains more than one SQL query.
     *
     * @param string $query The queries to send to the server, separated by semi-colon.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\DomainException
     * @return \pq\Result
     */
    public function exec(string $query) {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) [execute an SQL query](pq/Connection: Executing Queries) on the connection.
     *
     * > ***NOTE***:
     *   If pq\Connection::$unbuffered is TRUE, each call to pq\Connection::getResult() will generate a distinct pq\Result containing exactly one row.
     *
     * @param string $query The query to send to the server.
     * @param callable $callback as function(pq\Result $res)
     *   The callback to execute when the query finishes.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function execAsync(string $query, callable $callback = null) {}

    /**
     * [Execute an SQL query](pq/Connection: Executing Queries) with properly escaped parameters substituted.
     *
     * @param string $query The query to execute.
     * @param array $params The parameter list to substitute.
     * @param array $types Corresponding list of type OIDs for the parameters.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\DomainException
     * @return \pq\Result
     */
    public function execParams(string $query, array $params, array $types = null) {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) [execute an SQL query](pq/Connection: Executing Queries) with properly escaped parameters substituted.
     *
     * > ***NOTE***:
     *   If pq\Connection::$unbuffered is TRUE, each call to pq\Connection::getResult() will generate a distinct pq\Result containing exactly one row.
     *
     * @param string $query The query to execute.
     * @param array $params The parameter list to substitute.
     * @param array $types Corresponding list of type OIDs for the parameters.
     * @param callable $cb as function(\pq\Result $res) : void
     *   Result handler callback.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\BadMethodCallException
     */
    public function execParamsAsync(string $query, array $params, array $types = null, callable $cb = null) {}

    /**
     * Flush pending writes on the connection.
     * Call after sending any command or data on a nonblocking connection.
     *
     * If it returns FALSE, wait for the socket to become read or write-ready.
     * If it becomes write-ready, call pq\Connection::flush() again.
     * If it becomes read-ready, call pq\Connection::poll(), then call pq\Connection::flush() again.
     * Repeat until pq\Connection::flush() returns TRUE.
     *
     * ***NOTE:***
     * This method was added in v1.1.0, resp. v2.1.0.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\RuntimeException
     * @return bool whether everything has been flushed.
     */
    public function flush() {}

    /**
     * Fetch the result of an [asynchronous](pq/Connection/: Asynchronous Usage) query.
     *
     * If the query hasn't finished yet, the call will block until the result is available.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @return \pq\Result|null NULL if there has not been a query
     * 		 or \pq\Result when the query has finished
     */
    public function getResult() {}

    /**
     * Listen on $channel for notifications.
     * See pq\Connection::unlisten().
     *
     * @param string $channel The channel to listen on.
     * @param callable $listener as function(string $channel, string $message, int $pid)
     *   A callback automatically called whenever a notification on $channel arrives.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function listen(string $channel, callable $listener) {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) start listening on $channel for notifications.
     * See pq\Connection::listen().
     *
     * @param string $channel The channel to listen on.
     * @param callable $listener as function(string $channel, string $message, int $pid)
     *   A callback automatically called whenever a notification on $channel arrives.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function listenAsync(string $channel, callable $listener) {}

    /**
     * Notify all listeners on $channel with $message.
     *
     * @param string $channel The channel to notify.
     * @param string $message The message to send.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function notify(string $channel, string $message) {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) start notifying all listeners on $channel with $message.
     *
     * @param string $channel The channel to notify.
     * @param string $message The message to send.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function notifyAsync(string $channel, string $message) {}

    /**
     * Stops listening for an event type.
     *
     * @param string $event Any pq\Connection::EVENT_*.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @return bool success.
     */
    public function off(string $event) {}

    /**
     * Listen for an event.
     *
     * @param string $event Any pq\Connection::EVENT_*.
     * @param callable $callback as function(pq\Connection $c[, pq\Result $r)
     *   The callback to invoke on event.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @return int number of previously attached event listeners.
     */
    public function on(string $event, callable $callback) {}

    /**
     * Poll an [asynchronously](pq/Connection/: Asynchronous Usage) operating connection.
     * See pq\Connection::resetAsync() for an usage example.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\BadMethodCallException
     * @return int pq\Connection::POLLING_* constant
     */
    public function poll() {}

    /**
     * Prepare a named statement for later execution with pq\Statement::execute().
     *
     * @param string $name The identifying name of the prepared statement.
     * @param string $query The query to prepare.
     * @param array $types An array of type OIDs for the substitution parameters.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return \pq\Statement a prepared statement instance.
     */
    public function prepare(string $name, string $query, array $types = null) {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) prepare a named statement for later execution with pq\Statement::exec().
     *
     * > ***NOTE***:
     *   If pq\Connection::$unbuffered is TRUE, each call to pq\Connection::getResult() will generate a distinct pq\Result containing exactly one row.
     *
     * @param string $name The identifying name of the prepared statement.
     * @param string $query The query to prepare.
     * @param array $types An array of type OIDs for the substitution parameters.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return \pq\Statement a prepared statement instance.
     */
    public function prepareAsync(string $name, string $query, array $types = null) {}

    /**
     * Quote a string for safe use in a query.
     * The result is truncated at any zero byte and wrapped in single quotes.
     *
     * ***NOTE:***
     * Beware of matching character encodings.
     *
     * @param string $payload The payload to quote for use in a query.
     * @throws \pq\Exception\BadMethodCallException
     * @return string|false string a single-quote wrapped string safe for literal use in a query.
     * 		 or FALSE if quoting fails.
     */
    public function quote(string $payload) {}

    /**
     * Quote an identifier for safe usage as name.
     *
     * ***NOTE:***
     * Beware of case-sensitivity.
     *
     * @param string $name The name to quote.
     * @throws \pq\Exception\BadMethodCallException
     * @return string|false string the quoted identifier.
     * 		 or FALSE if quoting fails.
     */
    public function quoteName(string $name) {}

    /**
     * Attempt to reset a possibly broken connection to a working state.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function reset() {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) reset a possibly broken connection to a working state.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function resetAsync() {}

    /**
     * Set a data type converter.
     *
     * @param \pq\Converter $converter An instance implementing pq\Converter.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     */
    public function setConverter(pq\Converter $converter) {}

    /**
     * Begin a transaction.
     *
     * @param int $isolation Any pq\Transaction isolation level constant
     *   (defaults to pq\Connection::$defaultTransactionIsolation).
     * @param bool $readonly Whether the transaction executes only reads
     *   (defaults to pq\Connection::$defaultTransactionReadonly).
     * @param bool $deferrable Whether the transaction is deferrable
     *   (defaults to pq\Connection::$defaultTransactionDeferrable).
     *
     * ***NOTE:***
     * A transaction can only be deferrable if it also is readonly and serializable.
     * See the official [PostgreSQL documentation](http://www.postgresql.org/docs/current/static/sql-set-transaction.html) for further information.
     *
     * @return \pq\Transaction a begun transaction instance.
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\InvalidArgumentException
     */
    public function startTransaction(int $isolation = \pq\Transaction::READ_COMMITTED, bool $readonly = false, bool $deferrable = false) {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) begin a transaction.
     *
     * @param int $isolation Any pq\Transaction isolation level constant
     *   (defaults to pq\Connection::$defaultTransactionIsolation).
     * @param bool $readonly Whether the transaction executes only reads
     *   (defaults to pq\Connection::$defaultTransactionReadonly).
     * @param bool $deferrable Whether the transaction is deferrable
     *   (defaults to pq\Connection::$defaultTransactionDeferrable).
     *
     * ***NOTE:***
     * A transaction can only be deferrable if it also is readonly and serializable.
     * See the official [PostgreSQL documentation](http://www.postgresql.org/docs/current/static/sql-set-transaction.html) for further information.
     *
     * @return \pq\Transaction an asynchronously begun transaction instance.
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\InvalidArgumentException
     */
    public function startTransactionAsync(int $isolation = \pq\Transaction::READ_COMMITTED, bool $readonly = false, bool $deferrable = false) {}

    /**
     * Trace protocol communication with the server.
     *
     * ***NOTE:***
     * Calling pq\Connection::trace() without argument or NULL stops tracing.
     *
     * @param resource $stream The resource to which the protocol trace will be output.
     *   (The stream must be castable to STDIO).
     * @throws \pq\Exception\BadMethodCallException
     * @return bool success.
     */
    public function trace($stream = null) {}

    /**
     * Unescape bytea data retrieved from the server.
     *
     * @param string $bytea Bytea data retrieved from the server.
     * @throws \pq\Exception\BadMethodCallException
     * @return string|false string unescaped binary data.
     * 		 or FALSE if unescaping fails.
     */
    public function unescapeBytea(string $bytea) {}

    /**
     * Stop listening for notifications on channel $channel.
     * See pq\Connection::listen().
     *
     * @param string $channel The name of a channel which is currently listened on.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function unlisten(string $channel) {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) stop listening for notifications on channel $channel.
     * See pq\Connection::unlisten() and pq\Connection::listenAsync().
     *
     * @param string $channel The name of a channel which is currently listened on.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function unlistenAsync(string $channel) {}

    /**
     * Stop applying a data type converter.
     *
     * @param \pq\Converter $converter A converter previously set with pq\Connection::setConverter().
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     */
    public function unsetConverter(pq\Converter $converter) {}
}
/**
 * Interface for type conversions.
 */
interface Converter
{
    /**
     * Convert a string received from the PostgreSQL server back to a PHP type.
     *
     * @param string $data String data received from the server.
     * @param int $type The type OID of the data. Irrelevant for single-type converters.
     * @return mixed the value converted to a PHP type.
     */
    public function convertFromString(string $data, int $type);

    /**
     * Convert a value to a string for use in a query.
     *
     * @param mixed $value The PHP value which should be converted to a string.
     * @param int $type The type OID the converter should handle. Irrelevant for singly-type converters.
     * @return string a textual representation of the value accepted by the PostgreSQL server.
     */
    public function convertToString($value, int $type);

    /**
     * Announce which types the implementing converter can handle.
     *
     * @return array OIDs of handled types.
     */
    public function convertTypes();
}
/**
 * Declare a cursor.
 */
class Cursor
{
    /**
     * Causes the cursor to return data in binary rather than in text format. You probably do not want to use that.
     */
    public const BINARY = 1;

    /**
     * The data returned by the cursor should be unaffected by updates to the tables underlying the cursor that take place after the cursor was opened.
     */
    public const INSENSITIVE = 2;

    /**
     * The cursor should stay usable after the transaction that created it was successfully committed.
     */
    public const WITH_HOLD = 4;

    /**
     * Force that rows can be retrieved in any order from the cursor.
     */
    public const SCROLL = 16;

    /**
     * Force that rows are only retrievable in sequiential order.
     *
     * ***NOTE:***
     * See the [notes in the official PostgreSQL documentation](http://www.postgresql.org/docs/current/static/sql-declare.html#SQL-DECLARE-NOTES) for more information.
     */
    public const NO_SCROLL = 32;

    /**
     * The connection the cursor was declared on.
     *
     * @public
     * @readonly
     * @var \pq\Connection
     */
    public $connection;

    /**
     * The identifying name of the cursor.
     *
     * @public
     * @readonly
     * @var string
     */
    public $name;

    /**
     * Declare a cursor.
     * See pq\Connection::declare().
     *
     * @param \pq\Connection $connection The connection on which the cursor should be declared.
     * @param string $name The name of the cursor.
     * @param int $flags See pq\Cursor constants.
     * @param string $query The query for which the cursor should be opened.
     * @param bool $async Whether to declare the cursor [asynchronously](pq/Connection/: Asynchronous Usage).
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function __construct(pq\Connection $connection, string $name, int $flags, string $query, bool $async) {}

    /**
     * Close an open cursor.
     * This is a no-op on already closed cursors.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function close() {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) close an open cursor.
     * See pq\Cursor::close().
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function closeAsync() {}

    /**
     * Fetch rows from the cursor.
     * See pq\Cursor::move().
     *
     * @param string $spec What to fetch.
     *
     * ### Fetch argument:
     *
     * FETCH and MOVE usually accepts arguments like the following, where `count` is the number of rows:
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return \pq\Result the fetched row(s).
     */
    public function fetch(string $spec = "1") {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) fetch rows from the cursor.
     * See pq\Cursor::fetch().
     *
     * @param string $spec What to fetch.
     * @param callable $callback as function(pq\Result $res)
     *   A callback to execute when the result is ready.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function fetchAsync(string $spec = "1", callable $callback = null) {}

    /**
     * Move the cursor.
     * See pq\Cursor::fetch().
     *
     * @param string $spec What to fetch.
     *
     * ### Fetch argument:
     *
     * FETCH and MOVE usually accepts arguments like the following, where `count` is the number of rows:
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return \pq\Result command status.
     */
    public function move(string $spec = "1") {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) move the cursor.
     * See pq\Cursor::move().
     *
     * @param string $spec What to fetch.
     * @param callable $callback as function(pq\Result $res)
     *   A callback to execute when the command completed.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function moveAsync(string $spec = "1", callable $callback = null) {}

    /**
     * Reopen a cursor.
     * This is a no-op on already open cursors.
     *
     * ***NOTE:***
     * Only cursors closed by pq\Cursor::close() will be reopened.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function open() {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) reopen a cursor.
     * See pq\Cursor::open().
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function openAsync() {}
}
/**
 * A simple DateTime wrapper with predefined formats which supports stringification and JSON.
 */
class DateTime extends \DateTime implements \JsonSerializable
{
    /**
     * The default format of any date/time type automatically converted by pq\Result (depends on the actual type of the column).
     *
     * @public
     * @var string
     */
    public $format = "Y-m-d H:i:s.uO";

    /**
     * Stringify the DateTime instance according to pq\DateTime::$format.
     *
     * @return string the DateTime as string.
     */
    public function __toString() {}

    /**
     * Serialize to JSON.
     * Alias of pq\DateTime::__toString().
     *
     * @return string the DateTime stringified according to pq\DateTime::$format.
     */
    public function jsonSerialize() {}
}
/**
 * A base interface for all pq\Exception classes.
 */
interface Exception
{
    /**
     * An invalid argument was passed to a method (pq\Exception\InvalidArgumentException).
     */
    public const INVALID_ARGUMENT = 0;

    /**
     * A runtime exception occurred (pq\Exception\RuntimeException).
     */
    public const RUNTIME = 1;

    /**
     * The connection failed (pq\Exception\RuntimeException).
     */
    public const CONNECTION_FAILED = 2;

    /**
     * An input/output exception occurred (pq\Exception\RuntimeException).
     */
    public const IO = 3;

    /**
     * Escaping an argument or identifier failed internally (pq\Exception\RuntimeException).
     */
    public const ESCAPE = 4;

    /**
     * An object's constructor was not called (pq\Exception\BadMethodCallException).
     */
    public const UNINITIALIZED = 6;

    /**
     * Calling this method was not expected (yet) (pq\Exception\BadMethodCallException).
     */
    public const BAD_METHODCALL = 5;

    /**
     * SQL syntax error (pq\Exception\DomainException).
     */
    public const SQL = 8;

    /**
     * Implementation domain error (pq\Exception\DomainException).
     */
    public const DOMAIN = 7;
}
/**
 * A *large object*.
 *
 * ***NOTE:***
 * Working with *large objects* requires an active transaction.
 */
class LOB
{
    /**
     * 0, representing an invalid OID.
     */
    public const INVALID_OID = 0;

    /**
     * Read/write mode.
     */
    public const RW = 393216;

    /**
     * The transaction wrapping the operations on the *large object*.
     *
     * @public
     * @readonly
     * @var \pq\Transaction
     */
    public $transaction;

    /**
     * The OID of the *large object*.
     *
     * @public
     * @readonly
     * @var int
     */
    public $oid;

    /**
     * The stream connected to the *large object*.
     *
     * @public
     * @readonly
     * @var resource
     */
    public $stream;

    /**
     * Open or create a *large object*.
     * See pq\Transaction::openLOB() and pq\Transaction::createLOB().
     *
     * @param \pq\Transaction $txn The transaction which wraps the *large object* operations.
     * @param int $oid The OID of the existing *large object* to open.
     * @param int $mode Access mode (read, write or read/write).
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function __construct(pq\Transaction $txn, int $oid = \pq\LOB::INVALID_OID, int $mode = \pq\LOB::RW) {}

    /**
     * Read a string of data from the current position of the *large object*.
     *
     * @param int $length The amount of bytes to read from the *large object*.
     * @param int &$read The amount of bytes actually read from the *large object*.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return string the data read.
     */
    public function read(int $length = 0x1000, int &$read = null) {}

    /**
     * Seek to a position within the *large object*.
     *
     * @param int $offset The position to seek to.
     * @param int $whence From where to seek (SEEK_SET, SEEK_CUR or SEEK_END).
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return int the new position.
     */
    public function seek(int $offset, int $whence = SEEK_SET) {}

    /**
     * Retrieve the current position within the *large object*.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return int the current position.
     */
    public function tell() {}

    /**
     * Truncate the *large object*.
     *
     * @param int $length The length to truncate to.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function truncate(int $length = 0) {}

    /**
     * Write data to the *large object*.
     *
     * @param string $data The data that should be written to the current position.
     * @return int the number of bytes written.
     */
    public function write(string $data) {}
}
/**
 * A query result.
 *
 * See [Fetching Results](pq/Result/: Fetching Results) for a general overview.
 */
class Result implements \Traversable, \Countable
{
    /**
     * The query sent to the server was empty.
     */
    public const EMPTY_QUERY = 0;

    /**
     * The query did not generate a result set and completed successfully.
     */
    public const COMMAND_OK = 1;

    /**
     * The query successfully generated a result set.
     */
    public const TUPLES_OK = 2;

    /**
     * The result contains a single row of the result set when using pq\Connection::$unbuffered.
     */
    public const SINGLE_TUPLE = 9;

    /**
     * COPY data can be received from the server.
     */
    public const COPY_OUT = 3;

    /**
     * COPY data can be sent to the server.
     */
    public const COPY_IN = 4;

    /**
     * COPY in/out data transfer in progress.
     */
    public const COPY_BOTH = 8;

    /**
     * The server sent a bad response.
     */
    public const BAD_RESPONSE = 5;

    /**
     * A nonfatal error (notice or warning) occurred.
     */
    public const NONFATAL_ERROR = 6;

    /**
     * A fatal error occurred.
     */
    public const FATAL_ERROR = 7;

    /**
     * Fetch rows numerically indexed, where the index start with 0.
     */
    public const FETCH_ARRAY = 0;

    /**
     * Fetch rows associatively indexed by column name.
     */
    public const FETCH_ASSOC = 1;

    /**
     * Fetch rows as stdClass instance, where the column names are the property names.
     */
    public const FETCH_OBJECT = 2;

    /**
     * Automatically convert 'f' and 't' to FALSE and TRUE and vice versa.
     */
    public const CONV_BOOL = 1;

    /**
     * Automatically convert integral strings to either int if it fits into maximum integer size or else to float and vice versa.
     */
    public const CONV_INT = 2;

    /**
     * Automatically convert floating point numbers.
     */
    public const CONV_FLOAT = 4;

    /**
     * Do all scalar conversions listed above.
     */
    public const CONV_SCALAR = 15;

    /**
     * Automatically convert arrays.
     */
    public const CONV_ARRAY = 16;

    /**
     * Automatically convert date strings to pq\DateTime and vice versa.
     */
    public const CONV_DATETIME = 32;

    /**
     * Automatically convert JSON.
     */
    public const CONV_JSON = 256;

    /**
     * Do all of the above.
     */
    public const CONV_ALL = 65535;

    /**
     * A [status constant](pq/Result#Status.values:).
     *
     * @public
     * @readonly
     * @var int
     */
    public $status;

    /**
     * The accompanying status message.
     *
     * @public
     * @readonly
     * @var string
     */
    public $statusMessage;

    /**
     * Any error message if $status indicates an error.
     *
     * @public
     * @readonly
     * @var string
     */
    public $errorMessage;

    /**
     * The number of rows in the result set.
     *
     * @public
     * @readonly
     * @var int
     */
    public $numRows;

    /**
     * The number of fields in a single tuple of the result set.
     *
     * @public
     * @readonly
     * @var int
     */
    public $numCols;

    /**
     * The number of rows affected by a statement.
     *
     * @public
     * @readonly
     * @var int
     */
    public $affectedRows;

    /**
     * Error details. See [PQresultErrorField](https://www.postgresql.org/docs/current/static/libpq-exec.html#LIBPQ-PQRESULTERRORFIELD) docs.
     *
     * @public
     * @readonly
     * @var array
     */
    public $diag;

    /**
     * The [type of return value](pq/Result#Fetch.types:) the fetch methods should return when no fetch type argument was given. Defaults to pq\Connection::$defaultFetchType.
     *
     * @public
     * @var int
     */
    public $fetchType = \pq\Result::FETCH_ARRAY;

    /**
     * What [type of conversions](pq/Result#Conversion.bits:) to perform automatically.
     *
     * @public
     * @var int
     */
    public $autoConvert = \pq\Result::CONV_ALL;

    /**
     * Bind a variable to a result column.
     * See pq\Result::fetchBound().
     *
     * @param mixed $col The column name or index to bind to.
     * @param mixed $var The variable reference.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @return bool success.
     */
    public function bind($col, $var) {}

    /**
     * Count number of rows in this result set.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @return int the number of rows.
     */
    public function count() {}

    /**
     * Describe a prepared statement.
     *
     * ***NOTE:***
     * This will only return meaningful information for a result of pq\Statement::desc().
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @return array list of parameter type OIDs for the prepared statement.
     */
    public function desc() {}

    /**
     * Fetch all rows at once.
     *
     * @param int $fetch_type The type the return value should have, see pq\Result::FETCH_* constants, defaults to pq\Result::$fetchType.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @return array all fetched rows.
     */
    public function fetchAll(int $fetch_type = null) {}

    /**
     * Fetch all rows of a single column.
     *
     * @param int $col The column name or index to fetch.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return array list of column values.
     */
    public function fetchAllCols(int $col = 0) {}

    /**
     * Iteratively fetch a row into bound variables.
     * See pq\Result::bind().
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return array|null array the fetched row as numerically indexed array.
     * 		 or NULL when iteration ends.
     */
    public function fetchBound() {}

    /**
     * Iteratively fetch a single column.
     *
     * @param mixed $ref The variable where the column value will be stored in.
     * @param mixed $col The column name or index.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return bool|null bool success.
     * 		 or NULL when iteration ends.
     */
    public function fetchCol($ref, $col = 0) {}

    /**
     * Iteratively fetch a row.
     *
     * @param int $fetch_type The type the return value should have, see pq\Result::FETCH_* constants, defaults to pq\Result::$fetchType.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return array|array|object|null array numerically indexed for pq\Result::FETCH_ARRAY
     * 		 or array associatively indexed for pq\Result::FETCH_ASSOC
     * 		 or object stdClass instance for pq\Result::FETCH_OBJECT
     * 		 or NULL when iteration ends.
     */
    public function fetchRow(int $fetch_type = null) {}

    /**
     * Fetch the complete result set as a simple map, a *multi dimensional array*, each dimension indexed by a column.
     *
     * @param mixed $keys The the column indices/names used to index the map.
     * @param mixed $vals The column indices/names which should build up the leaf entry of the map.
     * @param int $fetch_type The type the return value should have, see pq\Result::FETCH_* constants, defaults to pq\Result::$fetchType.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return array |object, the mapped columns.
     */
    public function map($keys = 0, $vals = null, int $fetch_type = null) {}
}
/**
 * A named prepared statement.
 * See pq\Connection::prepare().
 */
class Statement
{
    /**
     * The connection to the server.
     *
     * @public
     * @readonly
     * @var \pq\Connection
     */
    public $connection;

    /**
     * The identifiying name of the prepared statement.
     *
     * @public
     * @readonly
     * @var string
     */
    public $name;

    /**
     * The query string used to prepare the statement.
     *
     * @public
     * @readonly
     * @var string
     */
    public $query;

    /**
     * List of corresponding query parameter type OIDs for the prepared statement.
     *
     * @public
     * @readonly
     * @var array
     */
    public $types;

    /**
     * Prepare a new statement.
     * See pq\Connection::prepare().
     *
     * @param \pq\Connection $conn The connection to prepare the statement on.
     * @param string $name The name identifying this statement.
     * @param string $query The actual query to prepare.
     * @param array $types A list of corresponding query parameter type OIDs.
     * @param bool $async Whether to prepare the statement [asynchronously](pq/Connection/: Asynchronous Usage).
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\DomainException
     */
    public function __construct(pq\Connection $conn, string $name, string $query, array $types = null, bool $async = false) {}

    /**
     * Bind a variable to an input parameter.
     *
     * @param int $param_no The parameter index to bind to.
     * @param mixed &$param_ref The variable to bind.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     */
    public function bind(int $param_no, &$param_ref) {}

    /**
     * Free the server resources used by the prepared statement, so it can no longer be executed.
     * This is done implicitly when the object is destroyed.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function deallocate() {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) free the server resources used by the
     * prepared statement, so it can no longer be executed.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function deallocateAsync() {}

    /**
     * Describe the parameters of the prepared statement.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\DomainException
     * @return array list of type OIDs of the substitution parameters.
     */
    public function desc() {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) describe the parameters of the prepared statement.
     *
     * @param callable $callback as function(array $oids)
     *   A callback to receive list of type OIDs of the substitution parameters.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function descAsync(callable $callback) {}

    /**
     * Execute the prepared statement.
     *
     * @param array $params Any parameters to substitute in the prepared statement (defaults to any bou
     *   nd variables).
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return \pq\Result the result of the execution of the prepared statement.
     */
    public function exec(array $params = null) {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) execute the prepared statement.
     *
     * @param array $params Any parameters to substitute in the prepared statement (defaults to any bou
     *   nd variables).
     * @param callable $cb as function(\pq\Result $res) : void
     *   Result handler callback.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function execAsync(array $params = null, callable $cb = null) {}

    /**
     * Re-prepare a statement that has been deallocated. This is a no-op on already open statements.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function prepare() {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) re-prepare a statement that has been
     * deallocated. This is a no-op on already open statements.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function prepareAsync() {}
}
/**
 * A database transaction.
 *
 * ***NOTE:***
 * Transactional properties like pq\Transaction::$isolation, pq\Transaction::$readonly and pq\Transaction::$deferrable can be changed after the transaction begun and the first query has been executed. Doing this will lead to appropriate `SET TRANSACTION` queries.
 */
class Transaction
{
    /**
     * Transaction isolation level where only rows committed before the transaction began can be seen.
     */
    public const READ_COMMITTED = 0;

    /**
     * Transaction isolation level where only rows committed before the first query was executed in this transaction.
     */
    public const REPEATABLE_READ = 1;

    /**
     * Transaction isolation level that guarantees serializable repeatability which might lead to serialization_failure on high concurrency.
     */
    public const SERIALIZABLE = 2;

    /**
     * The connection the transaction was started on.
     *
     * @public
     * @readonly
     * @var \pq\Connection
     */
    public $connection;

    /**
     * The transaction isolation level.
     *
     * @public
     * @var int
     */
    public $isolation = \pq\Transaction::READ_COMMITTED;

    /**
     * Whether this transaction performs read only queries.
     *
     * @public
     * @var bool
     */
    public $readonly = false;

    /**
     * Whether the transaction is deferrable. See pq\Connection::startTransaction().
     *
     * @public
     * @var bool
     */
    public $deferrable = false;

    /**
     * Start a transaction.
     * See pq\Connection::startTransaction().
     *
     * @param \pq\Connection $conn The connection to start the transaction on.
     * @param bool $async Whether to start the transaction [asynchronously](pq/Connection/: Asynchronous Usage).
     * @param int $isolation The transaction isolation level (defaults to pq\Connection::$defaultTransactionIsolation).
     * @param bool $readonly Whether the transaction is readonly (defaults to pq\Connection::$defaultTransactionReadonly).
     * @param bool $deferrable Whether the transaction is deferrable (defaults to pq\Connection::$defaultTransactionDeferrable).
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function __construct(pq\Connection $conn, bool $async = false, int $isolation = \pq\Transaction::READ_COMMITTED, bool $readonly = false, bool $deferrable = false) {}

    /**
     * Commit the transaction or release the previous savepoint.
     * See pq\Transaction::savepoint().
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\DomainException
     */
    public function commit() {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) commit the transaction or release the previous savepoint.
     * See pq\Transaction::commit() and pq\Transaction::savepoint().
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function commitAsync() {}

    /**
     * Create a new *large object* and open it.
     * See pq\Transaction::openLOB().
     *
     * @param int $mode How to open the *large object* (read, write or both; see pq\LOB constants).
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return \pq\LOB instance of the new *large object*.
     */
    public function createLOB(int $mode = \pq\LOB::RW) {}

    /**
     * Export a *large object* to a local file.
     * See pq\Transaction::importLOB().
     *
     * @param int $oid The OID of the *large object*.
     * @param string $path The path of a local file to export to.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function exportLOB(int $oid, string $path) {}

    /**
     * Export a snapshot for transaction synchronization.
     * See pq\Transaction::importSnapshot().
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\DomainException
     * @return string the snapshot identifier usable with pq\Transaction::importSnapshot().
     */
    public function exportSnapshot() {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) export a snapshot for transaction synchronization.
     * See pq\Transaction::exportSnapshot().
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function exportSnapshotAsync() {}

    /**
     * Import a local file into a *large object*.
     *
     * @param string $local_path A path to a local file to import.
     * @param int $oid The target OID. A new *large object* will be created if INVALID_OID.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return int the (new) OID of the *large object*.
     */
    public function importLOB(string $local_path, int $oid = \pq\LOB::INVALID_OID) {}

    /**
     * Import a snapshot from another transaction to synchronize with.
     * See pq\Transaction::exportSnapshot().
     *
     * ***NOTE:***
     * The transaction must have an isolation level of at least pq\Transaction::REPEATABLE_READ.
     *
     * @param string $snapshot_id The snapshot identifier obtained by exporting a snapshot from a transaction.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\DomainException
     */
    public function importSnapshot(string $snapshot_id) {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) import a snapshot from another transaction to synchronize with.
     * See pq\Transaction::importSnapshot().
     *
     * ***NOTE:***
     * The transaction must have an isolation level of at least pq\Transaction::REPEATABLE_READ.
     *
     * @param string $snapshot_id The snapshot identifier obtained by exporting a snapshot from a transaction.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function importSnapshotAsync(string $snapshot_id) {}

    /**
     * Open a *large object*.
     * See pq\Transaction::createLOB().
     *
     * @param int $oid The OID of the *large object*.
     * @param int $mode Operational mode; read, write or both.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return \pq\LOB instance of the opened *large object*.
     */
    public function openLOB(int $oid, int $mode = \pq\LOB::RW) {}

    /**
     * Rollback the transaction or to the previous savepoint within this transaction.
     * See pq\Transaction::commit() and pq\Transaction::savepoint().
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @throws \pq\Exception\DomainException
     */
    public function rollback() {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) rollback the transaction or to the previous savepoint within this transaction.
     * See pq\Transaction::rollback() and pq\Transaction::savepoint().
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function rollbackAsync() {}

    /**
     * Create a `SAVEPOINT` within this transaction.
     *
     * ***NOTE:***
     * pq\Transaction tracks an internal counter as savepoint identifier.
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function savepoint() {}

    /**
     * [Asynchronously](pq/Connection/: Asynchronous Usage) create a `SAVEPOINT` within this transaction.
     * See pq\Transaction::savepoint().
     *
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function savepointAsync() {}

    /**
     * Unlink a *large object*.
     * See pq\Transaction::createLOB().
     *
     * @param int $oid The OID of the *large object*.
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     * @return \pq\LOB instance of the opened *large object*.
     */
    public function unlinkLOB(int $oid) {}
}
/**
 * Accessor to the PostgreSQL `pg_type` relation.
 * See [here for an overview](pq/Types/: Overview).
 */
class Types implements \ArrayAccess
{
    /**
     * OID of the `bool` type.
     */
    public const BOOL = 16;

    /**
     * OID of the `bytea` type.
     */
    public const BYTEA = 17;

    /**
     * OID of the `char` type.
     */
    public const CHAR = 18;

    /**
     * OID of the `name` type.
     */
    public const NAME = 19;

    /**
     * OID of the `int8` type.
     */
    public const INT8 = 20;

    /**
     * OID of the `int2` type.
     */
    public const INT2 = 21;

    /**
     * OID of the `int2vector` type.
     */
    public const INT2VECTOR = 22;

    /**
     * OID of the `int4` type.
     */
    public const INT4 = 23;

    /**
     * OID of the `regproc` type.
     */
    public const REGPROC = 24;

    /**
     * OID of the `text` type.
     */
    public const TEXT = 25;

    /**
     * OID of the `oid` type.
     */
    public const OID = 26;

    /**
     * OID of the `tid` type.
     */
    public const TID = 27;

    /**
     * OID of the `xid` type.
     */
    public const XID = 28;

    /**
     * OID of the `cid` type.
     */
    public const CID = 29;

    /**
     * OID of the `oidvector` type.
     */
    public const OIDVECTOR = 30;

    /**
     * OID of the `pg_type` type.
     */
    public const PG_TYPE = 71;

    /**
     * OID of the `pg_attribute` type.
     */
    public const PG_ATTRIBUTE = 75;

    /**
     * OID of the `pg_proc` type.
     */
    public const PG_PROC = 81;

    /**
     * OID of the `pg_class` type.
     */
    public const PG_CLASS = 83;

    /**
     * OID of the `json` type.
     */
    public const JSON = 114;

    /**
     * OID of the `xml` type.
     */
    public const XML = 142;

    /**
     * OID of the `xmlarray` type.
     */
    public const XMLARRAY = 143;

    /**
     * OID of the `jsonarray` type.
     */
    public const JSONARRAY = 199;

    /**
     * OID of the `pg_node_tree` type.
     */
    public const PG_NODE_TREE = 194;

    /**
     * OID of the `smgr` type.
     */
    public const SMGR = 210;

    /**
     * OID of the `point` type.
     */
    public const POINT = 600;

    /**
     * OID of the `lseg` type.
     */
    public const LSEG = 601;

    /**
     * OID of the `path` type.
     */
    public const PATH = 602;

    /**
     * OID of the `box` type.
     */
    public const BOX = 603;

    /**
     * OID of the `polygon` type.
     */
    public const POLYGON = 604;

    /**
     * OID of the `line` type.
     */
    public const LINE = 628;

    /**
     * OID of the `linearray` type.
     */
    public const LINEARRAY = 629;

    /**
     * OID of the `float4` type.
     */
    public const FLOAT4 = 700;

    /**
     * OID of the `float8` type.
     */
    public const FLOAT8 = 701;

    /**
     * OID of the `abstime` type.
     */
    public const ABSTIME = 702;

    /**
     * OID of the `reltime` type.
     */
    public const RELTIME = 703;

    /**
     * OID of the `tinterval` type.
     */
    public const TINTERVAL = 704;

    /**
     * OID of the `unknown` type.
     */
    public const UNKNOWN = 705;

    /**
     * OID of the `circle` type.
     */
    public const CIRCLE = 718;

    /**
     * OID of the `circlearray` type.
     */
    public const CIRCLEARRAY = 719;

    /**
     * OID of the `money` type.
     */
    public const MONEY = 790;

    /**
     * OID of the `moneyarray` type.
     */
    public const MONEYARRAY = 791;

    /**
     * OID of the `macaddr` type.
     */
    public const MACADDR = 829;

    /**
     * OID of the `inet` type.
     */
    public const INET = 869;

    /**
     * OID of the `cidr` type.
     */
    public const CIDR = 650;

    /**
     * OID of the `boolarray` type.
     */
    public const BOOLARRAY = 1000;

    /**
     * OID of the `byteaarray` type.
     */
    public const BYTEAARRAY = 1001;

    /**
     * OID of the `chararray` type.
     */
    public const CHARARRAY = 1002;

    /**
     * OID of the `namearray` type.
     */
    public const NAMEARRAY = 1003;

    /**
     * OID of the `int2array` type.
     */
    public const INT2ARRAY = 1005;

    /**
     * OID of the `int2vectorarray` type.
     */
    public const INT2VECTORARRAY = 1006;

    /**
     * OID of the `int4array` type.
     */
    public const INT4ARRAY = 1007;

    /**
     * OID of the `regprocarray` type.
     */
    public const REGPROCARRAY = 1008;

    /**
     * OID of the `textarray` type.
     */
    public const TEXTARRAY = 1009;

    /**
     * OID of the `oidarray` type.
     */
    public const OIDARRAY = 1028;

    /**
     * OID of the `tidarray` type.
     */
    public const TIDARRAY = 1010;

    /**
     * OID of the `xidarray` type.
     */
    public const XIDARRAY = 1011;

    /**
     * OID of the `cidarray` type.
     */
    public const CIDARRAY = 1012;

    /**
     * OID of the `oidvectorarray` type.
     */
    public const OIDVECTORARRAY = 1013;

    /**
     * OID of the `bpchararray` type.
     */
    public const BPCHARARRAY = 1014;

    /**
     * OID of the `varchararray` type.
     */
    public const VARCHARARRAY = 1015;

    /**
     * OID of the `int8array` type.
     */
    public const INT8ARRAY = 1016;

    /**
     * OID of the `pointarray` type.
     */
    public const POINTARRAY = 1017;

    /**
     * OID of the `lsegarray` type.
     */
    public const LSEGARRAY = 1018;

    /**
     * OID of the `patharray` type.
     */
    public const PATHARRAY = 1019;

    /**
     * OID of the `boxarray` type.
     */
    public const BOXARRAY = 1020;

    /**
     * OID of the `float4array` type.
     */
    public const FLOAT4ARRAY = 1021;

    /**
     * OID of the `float8array` type.
     */
    public const FLOAT8ARRAY = 1022;

    /**
     * OID of the `abstimearray` type.
     */
    public const ABSTIMEARRAY = 1023;

    /**
     * OID of the `reltimearray` type.
     */
    public const RELTIMEARRAY = 1024;

    /**
     * OID of the `tintervalarray` type.
     */
    public const TINTERVALARRAY = 1025;

    /**
     * OID of the `polygonarray` type.
     */
    public const POLYGONARRAY = 1027;

    /**
     * OID of the `aclitem` type.
     */
    public const ACLITEM = 1033;

    /**
     * OID of the `aclitemarray` type.
     */
    public const ACLITEMARRAY = 1034;

    /**
     * OID of the `macaddrarray` type.
     */
    public const MACADDRARRAY = 1040;

    /**
     * OID of the `inetarray` type.
     */
    public const INETARRAY = 1041;

    /**
     * OID of the `cidrarray` type.
     */
    public const CIDRARRAY = 651;

    /**
     * OID of the `cstringarray` type.
     */
    public const CSTRINGARRAY = 1263;

    /**
     * OID of the `bpchar` type.
     */
    public const BPCHAR = 1042;

    /**
     * OID of the `varchar` type.
     */
    public const VARCHAR = 1043;

    /**
     * OID of the `date` type.
     */
    public const DATE = 1082;

    /**
     * OID of the `time` type.
     */
    public const TIME = 1083;

    /**
     * OID of the `timestamp` type.
     */
    public const TIMESTAMP = 1114;

    /**
     * OID of the `timestamparray` type.
     */
    public const TIMESTAMPARRAY = 1115;

    /**
     * OID of the `datearray` type.
     */
    public const DATEARRAY = 1182;

    /**
     * OID of the `timearray` type.
     */
    public const TIMEARRAY = 1183;

    /**
     * OID of the `timestamptz` type.
     */
    public const TIMESTAMPTZ = 1184;

    /**
     * OID of the `timestamptzarray` type.
     */
    public const TIMESTAMPTZARRAY = 1185;

    /**
     * OID of the `interval` type.
     */
    public const INTERVAL = 1186;

    /**
     * OID of the `intervalarray` type.
     */
    public const INTERVALARRAY = 1187;

    /**
     * OID of the `numericarray` type.
     */
    public const NUMERICARRAY = 1231;

    /**
     * OID of the `timetz` type.
     */
    public const TIMETZ = 1266;

    /**
     * OID of the `timetzarray` type.
     */
    public const TIMETZARRAY = 1270;

    /**
     * OID of the `bit` type.
     */
    public const BIT = 1560;

    /**
     * OID of the `bitarray` type.
     */
    public const BITARRAY = 1561;

    /**
     * OID of the `varbit` type.
     */
    public const VARBIT = 1562;

    /**
     * OID of the `varbitarray` type.
     */
    public const VARBITARRAY = 1563;

    /**
     * OID of the `numeric` type.
     */
    public const NUMERIC = 1700;

    /**
     * OID of the `refcursor` type.
     */
    public const REFCURSOR = 1790;

    /**
     * OID of the `refcursorarray` type.
     */
    public const REFCURSORARRAY = 2201;

    /**
     * OID of the `regprocedure` type.
     */
    public const REGPROCEDURE = 2202;

    /**
     * OID of the `regoper` type.
     */
    public const REGOPER = 2203;

    /**
     * OID of the `regoperator` type.
     */
    public const REGOPERATOR = 2204;

    /**
     * OID of the `regclass` type.
     */
    public const REGCLASS = 2205;

    /**
     * OID of the `regtype` type.
     */
    public const REGTYPE = 2206;

    /**
     * OID of the `regprocedurearray` type.
     */
    public const REGPROCEDUREARRAY = 2207;

    /**
     * OID of the `regoperarray` type.
     */
    public const REGOPERARRAY = 2208;

    /**
     * OID of the `regoperatorarray` type.
     */
    public const REGOPERATORARRAY = 2209;

    /**
     * OID of the `regclassarray` type.
     */
    public const REGCLASSARRAY = 2210;

    /**
     * OID of the `regtypearray` type.
     */
    public const REGTYPEARRAY = 2211;

    /**
     * OID of the `uuid` type.
     */
    public const UUID = 2950;

    /**
     * OID of the `uuidarray` type.
     */
    public const UUIDARRAY = 2951;

    /**
     * OID of the `tsvector` type.
     */
    public const TSVECTOR = 3614;

    /**
     * OID of the `gtsvector` type.
     */
    public const GTSVECTOR = 3642;

    /**
     * OID of the `tsquery` type.
     */
    public const TSQUERY = 3615;

    /**
     * OID of the `regconfig` type.
     */
    public const REGCONFIG = 3734;

    /**
     * OID of the `regdictionary` type.
     */
    public const REGDICTIONARY = 3769;

    /**
     * OID of the `tsvectorarray` type.
     */
    public const TSVECTORARRAY = 3643;

    /**
     * OID of the `gtsvectorarray` type.
     */
    public const GTSVECTORARRAY = 3644;

    /**
     * OID of the `tsqueryarray` type.
     */
    public const TSQUERYARRAY = 3645;

    /**
     * OID of the `regconfigarray` type.
     */
    public const REGCONFIGARRAY = 3735;

    /**
     * OID of the `regdictionaryarray` type.
     */
    public const REGDICTIONARYARRAY = 3770;

    /**
     * OID of the `txid_snapshot` type.
     */
    public const TXID_SNAPSHOT = 2970;

    /**
     * OID of the `txid_snapshotarray` type.
     */
    public const TXID_SNAPSHOTARRAY = 2949;

    /**
     * OID of the `int4range` type.
     */
    public const INT4RANGE = 3904;

    /**
     * OID of the `int4rangearray` type.
     */
    public const INT4RANGEARRAY = 3905;

    /**
     * OID of the `numrange` type.
     */
    public const NUMRANGE = 3906;

    /**
     * OID of the `numrangearray` type.
     */
    public const NUMRANGEARRAY = 3907;

    /**
     * OID of the `tsrange` type.
     */
    public const TSRANGE = 3908;

    /**
     * OID of the `tsrangearray` type.
     */
    public const TSRANGEARRAY = 3909;

    /**
     * OID of the `tstzrange` type.
     */
    public const TSTZRANGE = 3910;

    /**
     * OID of the `tstzrangearray` type.
     */
    public const TSTZRANGEARRAY = 3911;

    /**
     * OID of the `daterange` type.
     */
    public const DATERANGE = 3912;

    /**
     * OID of the `daterangearray` type.
     */
    public const DATERANGEARRAY = 3913;

    /**
     * OID of the `int8range` type.
     */
    public const INT8RANGE = 3926;

    /**
     * OID of the `int8rangearray` type.
     */
    public const INT8RANGEARRAY = 3927;

    /**
     * OID of the `record` type.
     */
    public const RECORD = 2249;

    /**
     * OID of the `recordarray` type.
     */
    public const RECORDARRAY = 2287;

    /**
     * OID of the `cstring` type.
     */
    public const CSTRING = 2275;

    /**
     * OID of the `any` type.
     */
    public const ANY = 2276;

    /**
     * OID of the `anyarray` type.
     */
    public const ANYARRAY = 2277;

    /**
     * OID of the `void` type.
     */
    public const VOID = 2278;

    /**
     * OID of the `trigger` type.
     */
    public const TRIGGER = 2279;

    /**
     * OID of the `event_trigger` type.
     */
    public const EVENT_TRIGGER = 3838;

    /**
     * OID of the `language_handler` type.
     */
    public const LANGUAGE_HANDLER = 2280;

    /**
     * OID of the `internal` type.
     */
    public const INTERNAL = 2281;

    /**
     * OID of the `opaque` type.
     */
    public const OPAQUE = 2282;

    /**
     * OID of the `anyelement` type.
     */
    public const ANYELEMENT = 2283;

    /**
     * OID of the `anynonarray` type.
     */
    public const ANYNONARRAY = 2776;

    /**
     * OID of the `anyenum` type.
     */
    public const ANYENUM = 3500;

    /**
     * OID of the `fdw_handler` type.
     */
    public const FDW_HANDLER = 3115;

    /**
     * OID of the `anyrange` type.
     */
    public const ANYRANGE = 3831;

    /**
     * The connection which was used to obtain type information.
     *
     * @public
     * @readonly
     * @var \pq\Connection
     */
    public $connection;

    /**
     * Create a new instance populated with information obtained from the `pg_type` relation.
     *
     * @param \pq\Connection $conn The connection to use.
     * @param array $namespaces Which namespaces to query (defaults to `public` and `pg_catalog`).
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function __construct(pq\Connection $conn, array $namespaces = null) {}

    /**
     * Refresh type information from `pg_type`.
     *
     * @param array $namespaces Which namespaces to query (defaults to `public` and `pg_catalog`).
     * @throws \pq\Exception\InvalidArgumentException
     * @throws \pq\Exception\BadMethodCallException
     * @throws \pq\Exception\RuntimeException
     */
    public function refresh(array $namespaces = null) {}
}

namespace pq\Exception;

/**
 * A method call was not expected.
 */
class BadMethodCallException extends \BadMethodCallException implements \pq\Exception {}
/**
 * Implementation or SQL syntax error.
 */
class DomainException extends \DomainException implements \pq\Exception
{
    /**
     * The SQLSTATE code, see the [official documentation](http://www.postgresql.org/docs/current/static/errcodes-appendix.html) for further information.
     *
     * @public
     * @readonly
     * @var string
     */
    public $sqlstate;
}
/**
 * An invalid argument was passed to a method.
 */
class InvalidArgumentException extends \InvalidArgumentException implements \pq\Exception {}
/**
 * A runtime exception occurred.
 */
class RuntimeException extends \RuntimeException implements \pq\Exception {}
