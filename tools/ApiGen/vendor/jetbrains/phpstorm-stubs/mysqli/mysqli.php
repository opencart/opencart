<?php
/**
 * Start of mysqli extension stubs v.0.1
 * @link https://php.net/manual/en/book.mysqli.php
 */

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;

/**
 * mysqli_sql_exception
 */
final class mysqli_sql_exception extends RuntimeException
{
    /**
     * The sql state with the error.
     *
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    protected $sqlstate;

    /**
     * The error code
     *
     * @var int
     */
    protected $code;

    /**
     * @since 8.1
     */
    public function getSqlState(): string {}
}

/**
 * MySQLi Driver.
 * @link https://php.net/manual/en/class.mysqli-driver.php
 */
final class mysqli_driver
{
    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $client_info;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $client_version;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $driver_version;

    /**
     * @var string
     */
    public $embedded;

    /**
     * @var bool
     */
    #[LanguageLevelTypeAware(['8.1' => 'bool'], default: '')]
    public $reconnect;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $report_mode;
}

/**
 * Represents a connection between PHP and a MySQL database.
 * @link https://php.net/manual/en/class.mysqli.php
 */
class mysqli
{
    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|int'], default: '')]
    public $affected_rows;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $client_info;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $client_version;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $connect_errno;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $connect_error;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $errno;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $error;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $field_count;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $host_info;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $info;

    /**
     * @var int|string
     */
    #[LanguageLevelTypeAware(['8.1' => 'int|string'], default: '')]
    public $insert_id;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $server_info;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $server_version;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $sqlstate;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $protocol_version;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $thread_id;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $warning_count;

    /**
     * @var array A list of errors, each as an associative array containing the errno, error, and sqlstate.
     * @link https://secure.php.net/manual/en/mysqli.error-list.php
     */
    #[LanguageLevelTypeAware(['8.1' => 'array'], default: '')]
    public $error_list;
    public $stat;

    /**
     * Open a new connection to the MySQL server
     * @link https://php.net/manual/en/mysqli.construct.php
     * @param string $hostname [optional] Can be either a host name or an IP address. Passing the NULL value or the string "localhost" to this parameter, the local host is assumed. When possible, pipes will be used instead of the TCP/IP protocol. Prepending host by p: opens a persistent connection. mysqli_change_user() is automatically called on connections opened from the connection pool. Defaults to ini_get("mysqli.default_host")
     * @param string $username [optional] The MySQL user name. Defaults to ini_get("mysqli.default_user")
     * @param string $password [optional] If not provided or NULL, the MySQL server will attempt to authenticate the user against those user records which have no password only. This allows one username to be used with different permissions (depending on if a password as provided or not). Defaults to ini_get("mysqli.default_pw")
     * @param string $database [optional] If provided will specify the default database to be used when performing queries. Defaults to ""
     * @param int $port [optional] Specifies the port number to attempt to connect to the MySQL server. Defaults to ini_get("mysqli.default_port")
     * @param string $socket [optional] Specifies the socket or named pipe that should be used. Defaults to ini_get("mysqli.default_socket")
     */
    public function __construct(
        ?string $hostname = null,
        ?string $username = null,
        ?string $password = null,
        ?string $database = null,
        ?int $port = null,
        ?string $socket = null
    ) {}

    /**
     * Turns on or off auto-committing database modifications
     * @link https://php.net/manual/en/mysqli.autocommit.php
     * @param bool $enable <p>
     * Whether to turn on auto-commit or not.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function autocommit(bool $enable): bool {}

    /**
     * Starts a transaction
     * @link https://secure.php.net/manual/en/mysqli.begin-transaction.php
     * @param int $flags [optional]
     * @param string $name [optional]
     * @return bool true on success or false on failure.
     * @since 5.5
     */
    #[TentativeType]
    public function begin_transaction(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = 0,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $name = null
    ): bool {}

    /**
     * Changes the user of the specified database connection
     * @link https://php.net/manual/en/mysqli.change-user.php
     * @param string $username <p>
     * The MySQL user name.
     * </p>
     * @param string $password <p>
     * The MySQL password.
     * </p>
     * @param string $database <p>
     * The database to change to.
     * </p>
     * <p>
     * If desired, the null value may be passed resulting in only changing
     * the user and not selecting a database. To select a database in this
     * case use the <b>mysqli_select_db</b> function.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function change_user(string $username, string $password, ?string $database): bool {}

    /**
     * Returns the current character set of the database connection
     * @link https://php.net/manual/en/mysqli.character-set-name.php
     * @return string The current character set of the connection
     */
    #[TentativeType]
    public function character_set_name(): string {}

    /**
     * @removed 5.4
     */
    #[Deprecated(since: '5.3')]
    public function client_encoding() {}

    /**
     * Closes a previously opened database connection
     * @link https://php.net/manual/en/mysqli.close.php
     * @return bool true on success or false on failure.
     */
    public function close() {}

    /**
     * Commits the current transaction
     * @link https://php.net/manual/en/mysqli.commit.php
     * @param int $flags A bitmask of MYSQLI_TRANS_COR_* constants.
     * @param string $name If provided then COMMIT $name is executed.
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function commit(int $flags = 0, ?string $name = null): bool {}

    /**
     * @link https://php.net/manual/en/function.mysqli-connect.php
     * @param string|null $hostname [optional]
     * @param string|null $username [optional]
     * @param string|null $password [optional]
     * @param string|null $database [optional]
     * @param int|null $port [optional]
     * @param string|null $socket [optional]
     * @return bool
     */
    #[TentativeType]
    public function connect(
        ?string $hostname = null,
        ?string $username = null,
        ?string $password = null,
        ?string $database = null,
        ?int $port = null,
        ?string $socket = null
    ): bool {}

    /**
     * Dump debugging information into the log
     * @link https://php.net/manual/en/mysqli.dump-debug-info.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function dump_debug_info(): bool {}

    /**
     * Performs debugging operations
     * @link https://php.net/manual/en/mysqli.debug.php
     * @param string $options <p>
     * A string representing the debugging operation to perform
     * </p>
     * @return bool true.
     */
    public function debug(string $options) {}

    /**
     * Returns a character set object
     * @link https://php.net/manual/en/mysqli.get-charset.php
     * @return object|null The function returns a character set object with the following properties:
     * <i>charset</i>
     * <p>Character set name</p>
     * <i>collation</i>
     * <p>Collation name</p>
     * <i>dir</i>
     * <p>Directory the charset description was fetched from (?) or "" for built-in character sets</p>
     * <i>min_length</i>
     * <p>Minimum character length in bytes</p>
     * <i>max_length</i>
     * <p>Maximum character length in bytes</p>
     * <i>number</i>
     * <p>Internal character set number</p>
     * <i>state</i>
     * <p>Character set status (?)</p>
     */
    #[TentativeType]
    public function get_charset(): ?object {}

    /**
     * @param mysqli $mysql
     * @param string $query
     * @param array|null $params
     * @return mysqli_result|bool
     * @see mysqli_execute_query
     * @since 8.2
     */
    public function execute_query(string $query, ?array $params = null): mysqli_result|bool {}

    /**
     * Returns the MySQL client version as a string
     * @link https://php.net/manual/en/mysqli.get-client-info.php
     * @return string A string that represents the MySQL client library version
     */
    #[TentativeType]
    public function get_client_info(): string {}

    /**
     * Returns statistics about the client connection
     * @link https://php.net/manual/en/mysqli.get-connection-stats.php
     * @return array an array with connection stats.
     */
    #[TentativeType]
    public function get_connection_stats(): array {}

    /**
     * Returns the version of the MySQL server
     * @link https://php.net/manual/en/mysqli.get-server-info.php
     * @return string A character string representing the server version.
     */
    #[TentativeType]
    public function get_server_info(): string {}

    /**
     * Get result of SHOW WARNINGS
     * @link https://php.net/manual/en/mysqli.get-warnings.php
     * @return mysqli_warning|false
     */
    #[TentativeType]
    public function get_warnings(): mysqli_warning|false {}

    /**
     * Initializes MySQLi object
     * @link https://php.net/manual/en/mysqli.init.php
     * @return bool|null
     * @deprecated 8.1
     */
    public function init() {}

    /**
     * Asks the server to kill a MySQL thread
     * @link https://php.net/manual/en/mysqli.kill.php
     * @param int $process_id
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function kill(int $process_id): bool {}

    /**
     * Performs one or more queries on the database
     * @link https://php.net/manual/en/mysqli.multi-query.php
     * @param string $query <p>
     * A string containing the queries to be executed.
     * Multiple queries must be separated by a semicolon.
     * </p>
     * <p>
     * If the query contains any variable input then parameterized
     * prepared statements should be used instead. Alternatively,
     * the data must be properly formatted and all strings must be
     * escaped using the <b>mysqli_real_escape_string</b> function.
     * </p>
     * @return bool false if the first statement failed.
     * To retrieve subsequent errors from other statements you have to call
     * <b>mysqli_next_result</b> first.
     */
    #[TentativeType]
    public function multi_query(string $query): bool {}

    /**
     * @link https://php.net/manual/en/mysqli.construct.php
     * @param string $host [optional]
     * @param string $username [optional]
     * @param string $password [optional]
     * @param string $database [optional]
     * @param int $port [optional]
     * @param string $socket [optional]
     *
     * @removed 8.0
     */
    public function mysqli($host = null, $username = null, $password = null, $database = null, $port = null, $socket = null) {}

    /**
     * Check if there are any more query results from a multi query
     * @link https://php.net/manual/en/mysqli.more-results.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function more_results(): bool {}

    /**
     * Prepare next result from multi_query
     * @link https://php.net/manual/en/mysqli.next-result.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function next_result(): bool {}

    /**
     * Set options
     * @link https://php.net/manual/en/mysqli.options.php
     * @param int $option <p>
     * The option that you want to set. It can be one of the following values:
     * <table>
     * Valid options
     * <tr valign="top">
     * <td>Name</td>
     * <td>Description</td>
     * </tr>
     * <tr valign="top">
     * <td><b>MYSQLI_OPT_CONNECT_TIMEOUT</b></td>
     * <td>connection timeout in seconds (supported on Windows with TCP/IP since PHP 5.3.1)</td>
     * </tr>
     * <tr valign="top">
     * <td><b>MYSQLI_OPT_LOCAL_INFILE</b></td>
     * <td>enable/disable use of LOAD LOCAL INFILE</td>
     * </tr>
     * <tr valign="top">
     * <td><b>MYSQLI_INIT_COMMAND</b></td>
     * <td>command to execute after when connecting to MySQL server</td>
     * </tr>
     * <tr valign="top">
     * <td><b>MYSQLI_READ_DEFAULT_FILE</b></td>
     * <td>
     * Read options from named option file instead of my.cnf
     * </td>
     * </tr>
     * <tr valign="top">
     * <td><b>MYSQLI_READ_DEFAULT_GROUP</b></td>
     * <td>
     * Read options from the named group from my.cnf
     * or the file specified with <b>MYSQL_READ_DEFAULT_FILE</b>
     * </td>
     * </tr>
     * <tr valign="top">
     * <td><b>MYSQLI_SERVER_PUBLIC_KEY</b></td>
     * <td>
     * RSA public key file used with the SHA-256 based authentication.
     * </td>
     * </tr>
     * </table>
     * </p>
     * @param string|int $value <p>
     * The value for the option.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function options(int $option, $value): bool {}

    /**
     * Pings a server connection, or tries to reconnect if the connection has gone down
     * @link https://php.net/manual/en/mysqli.ping.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function ping(): bool {}

    /**
     * Prepares an SQL statement for execution
     * @link https://php.net/manual/en/mysqli.prepare.php
     * @param string $query <p>
     * The query, as a string. It must consist of a single SQL statement.
     * </p>
     * <p>
     * The SQL statement may contain zero or more parameter markers
     * represented by question mark (?) characters
     * at the appropriate positions.
     * </p>
     * <p>
     * The markers are legal only in certain places in SQL statements.
     * For example, they are permitted in the VALUES()
     * list of an INSERT statement (to specify column
     * values for a row), or in a comparison with a column in a
     * WHERE clause to specify a comparison value.
     * </p>
     * <p>
     * However, they are not permitted for identifiers (such as table or
     * column names), or to specify both operands of a binary operator such as the = equal
     * sign. The latter restriction is necessary because it would be
     * impossible to determine the parameter type.
     * In general, parameters are legal
     * only in Data Manipulation Language (DML) statements, and not in Data
     * Definition Language (DDL) statements.
     * </p>
     * @return mysqli_stmt|false <b>mysqli_prepare</b> returns a statement object or false if an error occurred.
     */
    #[TentativeType]
    public function prepare(string $query): mysqli_stmt|false {}

    /**
     * Performs a query on the database
     * @link https://php.net/manual/en/mysqli.query.php
     * @param string $query <p>
     * The query string.
     * </p>
     * <p>
     * If the query contains any variable input then parameterized
     * prepared statements should be used instead. Alternatively,
     * the data must be properly formatted and all strings must be
     * escaped using the <b>mysqli_real_escape_string</b> function.
     * </p>
     * @param int $result_mode [optional] <p>
     * The result mode can be one of 3 constants indicating
     * how the result will be returned from the MySQL server.
     * </p>
     * <p>
     * <b>MYSQLI_STORE_RESULT</b> (default) - returns a <b>mysqli_result</b>
     * object with buffered result set.
     * </p>
     * <p>
     * <b>MYSQLI_USE_RESULT</b> - returns a <b>mysqli_result</b> object
     * with unbuffered result set. As long as there are pending records
     * waiting to be fetched, the connection line will be busy and all
     * subsequent calls will return error Commands out of sync. To avoid
     * the error all records must be fetched from the server or the result
     * set must be discarded by calling <b>mysqli_free_result</b>.
     * </p>
     * <p>
     * <b>MYSQLI_ASYNC</b> (available with mysqlnd) - the query is performed
     * asynchronously and no result set is immediately returned.
     * <b>mysqli_poll</b> is then used to get results from such queries.
     * Used in combination with either
     * <b>MYSQLI_STORE_RESULT</b> or <b>MYSQLI_USE_RESULT</b> constant.
     * </p>
     * @return mysqli_result|bool Returns false on failure.
     * For successful queries which produce a result set,
     * such as SELECT, SHOW, DESCRIBE or EXPLAIN,
     * <b>mysqli_query</b> will return a <b>mysqli_result</b> object.
     * For other successful queries <b>mysqli_query</b> will
     * return true.
     */
    #[TentativeType]
    public function query(
        string $query,
        #[PhpStormStubsElementAvailable(from: '7.1')] int $result_mode = MYSQLI_STORE_RESULT
    ): mysqli_result|bool {}

    /**
     * Opens a connection to a mysql server
     * @link https://php.net/manual/en/mysqli.real-connect.php
     * @param string $hostname [optional] <p>
     * Can be either a host name or an IP address. Passing the null value
     * or the string "localhost" to this parameter, the local host is
     * assumed. When possible, pipes will be used instead of the TCP/IP
     * protocol.
     * </p>
     * @param string $username [optional] <p>
     * The MySQL user name.
     * </p>
     * @param string $password [optional] <p>
     * If provided or null, the MySQL server will attempt to authenticate
     * the user against those user records which have no password only. This
     * allows one username to be used with different permissions (depending
     * on if a password as provided or not).
     * </p>
     * @param string $database [optional] <p>
     * If provided will specify the default database to be used when
     * performing queries.
     * </p>
     * @param int $port [optional] <p>
     * Specifies the port number to attempt to connect to the MySQL server.
     * </p>
     * @param string $socket [optional] <p>
     * Specifies the socket or named pipe that should be used.
     * </p>
     * <p>
     * Specifying the <i>socket</i> parameter will not
     * explicitly determine the type of connection to be used when
     * connecting to the MySQL server. How the connection is made to the
     * MySQL database is determined by the <i>host</i>
     * parameter.
     * </p>
     * @param int $flags [optional] <p>
     * With the parameter <i>flags</i> you can set different
     * connection options:
     * </p>
     * <table>
     * Supported flags
     * <tr valign="top">
     * <td>Name</td>
     * <td>Description</td>
     * </tr>
     * <tr valign="top">
     * <td><b>MYSQLI_CLIENT_COMPRESS</b></td>
     * <td>Use compression protocol</td>
     * </tr>
     * <tr valign="top">
     * <td><b>MYSQLI_CLIENT_FOUND_ROWS</b></td>
     * <td>return number of matched rows, not the number of affected rows</td>
     * </tr>
     * <tr valign="top">
     * <td><b>MYSQLI_CLIENT_IGNORE_SPACE</b></td>
     * <td>Allow spaces after function names. Makes all function names reserved words.</td>
     * </tr>
     * <tr valign="top">
     * <td><b>MYSQLI_CLIENT_INTERACTIVE</b></td>
     * <td>
     * Allow interactive_timeout seconds (instead of
     * wait_timeout seconds) of inactivity before closing the connection
     * </td>
     * </tr>
     * <tr valign="top">
     * <td><b>MYSQLI_CLIENT_SSL</b></td>
     * <td>Use SSL (encryption)</td>
     * </tr>
     * </table>
     * <p>
     * For security reasons the <b>MULTI_STATEMENT</b> flag is
     * not supported in PHP. If you want to execute multiple queries use the
     * <b>mysqli_multi_query</b> function.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function real_connect(
        ?string $hostname = null,
        ?string $username = null,
        ?string $password = null,
        ?string $database = null,
        ?int $port = null,
        ?string $socket = null,
        int $flags = null
    ): bool {}

    /**
     * Escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection
     * @link https://php.net/manual/en/mysqli.real-escape-string.php
     * @param string $string <p>
     * The string to be escaped.
     * </p>
     * <p>
     * Characters encoded are NUL (ASCII 0), \n, \r, \, ', ", and
     * Control-Z.
     * </p>
     * @return string an escaped string.
     */
    #[TentativeType]
    public function real_escape_string(string $string): string {}

    /**
     * Poll connections
     * @link https://php.net/manual/en/mysqli.poll.php
     * @param array &$read <p>
     * </p>
     * @param array &$error <p>
     * </p>
     * @param array &$reject <p>
     * </p>
     * @param int $seconds <p>
     * Number of seconds to wait, must be non-negative.
     * </p>
     * @param int $microseconds [optional] <p>
     * Number of microseconds to wait, must be non-negative.
     * </p>
     * @return int|false number of ready connections in success, false otherwise.
     */
    #[TentativeType]
    public static function poll(?array &$read, ?array &$error, array &$reject, int $seconds, int $microseconds = 0): int|false {}

    /**
     * Get result from async query
     * @link https://php.net/manual/en/mysqli.reap-async-query.php
     * @return mysqli_result|false mysqli_result in success, false otherwise.
     */
    #[TentativeType]
    public function reap_async_query(): mysqli_result|bool {}

    /**
     * Escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection
     * @param string $string The string to be escaped.
     * Characters encoded are NUL (ASCII 0), \n, \r, \, ', ", and Control-Z.
     * @return string
     * @link https://secure.php.net/manual/en/mysqli.real-escape-string.php
     */
    #[TentativeType]
    public function escape_string(string $string): string {}

    /**
     * Execute an SQL query
     * @link https://php.net/manual/en/mysqli.real-query.php
     * @param string $query <p>
     * The query, as a string.
     * </p>
     * <p>
     * If the query contains any variable input then parameterized
     * prepared statements should be used instead. Alternatively,
     * the data must be properly formatted and all strings must be
     * escaped using the <b>mysqli_real_escape_string</b> function.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function real_query(string $query): bool {}

    /**
     * Removes the named savepoint from the set of savepoints of the current transaction
     * @link https://php.net/manual/en/mysqli.release-savepoint.php
     * @param string $name The identifier of the savepoint.
     * @return bool Returns TRUE on success or FALSE on failure.
     * @since 5.5
     */
    #[TentativeType]
    public function release_savepoint(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name): bool {}

    /**
     * Rolls back current transaction
     * @link https://php.net/manual/en/mysqli.rollback.php
     * @param int $flags [optional] A bitmask of MYSQLI_TRANS_COR_* constants.
     * @param string $name [optional] If provided then ROLLBACK $name is executed.
     * @return bool true on success or false on failure.
     * @since 5.5 Added flags and name parameters.
     */
    #[TentativeType]
    public function rollback(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = 0,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $name = null
    ): bool {}

    /**
     * Set a named transaction savepoint
     * @link https://secure.php.net/manual/en/mysqli.savepoint.php
     * @param string $name
     * @return bool Returns TRUE on success or FALSE on failure.
     * @since 5.5
     */
    #[TentativeType]
    public function savepoint(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name): bool {}

    /**
     * Selects the default database for database queries
     * @link https://php.net/manual/en/mysqli.select-db.php
     * @param string $database <p>
     * The database name.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function select_db(string $database): bool {}

    /**
     * Sets the client character set
     * @link https://php.net/manual/en/mysqli.set-charset.php
     * @param string $charset <p>
     * The desired character set.
     * </p>
     * @return bool true on success or false on failure
     */
    #[TentativeType]
    public function set_charset(string $charset): bool {}

    /**
     * @link https://php.net/manual/en/function.mysqli-set-opt
     * @param int $option
     * @param string|int $value
     */
    #[TentativeType]
    public function set_opt(int $option, $value): bool {}

    /**
     * Used for establishing secure connections using SSL
     * @link https://secure.php.net/manual/en/mysqli.ssl-set.php
     * @param string $key <p>
     * The path name to the key file.
     * </p>
     * @param string $certificate <p>
     * The path name to the certificate file.
     * </p>
     * @param string $ca_certificate <p>
     * The path name to the certificate authority file.
     * </p>
     * @param string $ca_path <p>
     * The pathname to a directory that contains trusted SSL CA certificates in PEM format.
     * </p>
     * @param string $cipher_algos <p>
     * A list of allowable ciphers to use for SSL encryption.
     * </p>
     * @return bool This function always returns TRUE value.
     */
    public function ssl_set(?string $key, ?string $certificate, ?string $ca_certificate, ?string $ca_path, ?string $cipher_algos) {}

    /**
     * Gets the current system status
     * @link https://php.net/manual/en/mysqli.stat.php
     * @return string|false A string describing the server status. false if an error occurred.
     */
    #[TentativeType]
    public function stat(): string|false {}

    /**
     * Initializes a statement and returns an object for use with mysqli_stmt_prepare
     * @link https://php.net/manual/en/mysqli.stmt-init.php
     * @return mysqli_stmt an object.
     */
    #[TentativeType]
    public function stmt_init(): mysqli_stmt|false {}

    /**
     * Transfers a result set from the last query
     * @link https://php.net/manual/en/mysqli.store-result.php
     * @param int $mode [optional] The option that you want to set
     * @return mysqli_result|false a buffered result object or false if an error occurred.
     * </p>
     * <p>
     * <b>mysqli_store_result</b> returns false in case the query
     * didn't return a result set (if the query was, for example an INSERT
     * statement). This function also returns false if the reading of the
     * result set failed. You can check if you have got an error by checking
     * if <b>mysqli_error</b> doesn't return an empty string, if
     * <b>mysqli_errno</b> returns a non zero value, or if
     * <b>mysqli_field_count</b> returns a non zero value.
     * Also possible reason for this function returning false after
     * successful call to <b>mysqli_query</b> can be too large
     * result set (memory for it cannot be allocated). If
     * <b>mysqli_field_count</b> returns a non-zero value, the
     * statement should have produced a non-empty result set.
     */
    #[TentativeType]
    public function store_result(int $mode = 0): mysqli_result|false {}

    /**
     * Returns whether thread safety is given or not
     * @link https://php.net/manual/en/mysqli.thread-safe.php
     * @return bool true if the client library is thread-safe, otherwise false.
     */
    #[TentativeType]
    public function thread_safe(): bool {}

    /**
     * Initiate a result set retrieval
     * @link https://php.net/manual/en/mysqli.use-result.php
     * @return mysqli_result|false an unbuffered result object or false if an error occurred.
     */
    #[TentativeType]
    public function use_result(): mysqli_result|false {}

    /**
     * @link https://php.net/manual/en/mysqli.refresh
     * @param int $flags MYSQLI_REFRESH_*
     * @return bool TRUE if the refresh was a success, otherwise FALSE
     * @since 5.3
     */
    #[TentativeType]
    public function refresh(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags): bool {}
}

/**
 * Represents one or more MySQL warnings.
 * @link https://php.net/manual/en/class.mysqli-warning.php
 */
final class mysqli_warning
{
    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $message;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $sqlstate;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $errno;

    /**
     * The __construct purpose
     * @link https://php.net/manual/en/mysqli-warning.construct.php
     */
    #[PhpStormStubsElementAvailable(from: '8.0')]
    private function __construct() {}

    /**
     * The __construct purpose
     * @link https://php.net/manual/en/mysqli-warning.construct.php
     */
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')]
    protected function __construct() {}

    /**
     * Move to the next warning
     * @link https://php.net/manual/en/mysqli-warning.next.php
     * @return bool True if it successfully moved to the next warning
     */
    public function next(): bool {}
}

/**
 * Represents the result set obtained from a query against the database.
 * Implements Traversable since 5.4
 * @link https://php.net/manual/en/class.mysqli-result.php
 */
class mysqli_result implements IteratorAggregate
{
    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $current_field;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $field_count;

    /**
     * @var array|null
     */
    #[LanguageLevelTypeAware(['8.1' => 'array|null'], default: '')]
    public $lengths;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int|string'], default: '')]
    public $num_rows;

    /**
     * @var mixed
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $type;

    /**
     * Constructor (no docs available)
     * @param object $mysql
     * @param int $result_mode [optional]
     */
    public function __construct(
        #[PhpStormStubsElementAvailable(from: '8.0')] mysqli $mysql,
        #[PhpStormStubsElementAvailable(from: '8.0')] int $result_mode = MYSQLI_STORE_RESULT
    ) {}

    /**
     * Frees the memory associated with a result
     * @return void
     * @link https://php.net/manual/en/mysqli-result.free.php
     */
    #[TentativeType]
    public function close(): void {}

    /**
     * Frees the memory associated with a result
     * @link https://php.net/manual/en/mysqli-result.free.php
     * @return void
     */
    #[TentativeType]
    public function free(): void {}

    /**
     * Adjusts the result pointer to an arbitrary row in the result
     * @link https://php.net/manual/en/mysqli-result.data-seek.php
     * @param int $offset <p>
     * The field offset. Must be between zero and the total number of rows
     * minus one (0..<b>mysqli_num_rows</b> - 1).
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function data_seek(int $offset): bool {}

    /**
     * Returns the next field in the result set
     * @link https://php.net/manual/en/mysqli-result.fetch-field.php
     * @return object|false an object which contains field definition information or false
     * if no field information is available.
     * </p>
     * <p>
     * <table>
     * Object properties
     * <tr valign="top">
     * <td>Property</td>
     * <td>Description</td>
     * </tr>
     * <tr valign="top">
     * <td>name</td>
     * <td>The name of the column</td>
     * </tr>
     * <tr valign="top">
     * <td>orgname</td>
     * <td>Original column name if an alias was specified</td>
     * </tr>
     * <tr valign="top">
     * <td>table</td>
     * <td>The name of the table this field belongs to (if not calculated)</td>
     * </tr>
     * <tr valign="top">
     * <td>orgtable</td>
     * <td>Original table name if an alias was specified</td>
     * </tr>
     * <tr valign="top">
     * <td>def</td>
     * <td>Reserved for default value, currently always ""</td>
     * </tr>
     * <tr valign="top">
     * <td>db</td>
     * <td>Database (since PHP 5.3.6)</td>
     * </tr>
     * <tr valign="top">
     * <td>catalog</td>
     * <td>The catalog name, always "def" (since PHP 5.3.6)</td>
     * </tr>
     * <tr valign="top">
     * <td>max_length</td>
     * <td>The maximum width of the field for the result set.</td>
     * </tr>
     * <tr valign="top">
     * <td>length</td>
     * <td>The width of the field, as specified in the table definition.</td>
     * </tr>
     * <tr valign="top">
     * <td>charsetnr</td>
     * <td>The character set number for the field.</td>
     * </tr>
     * <tr valign="top">
     * <td>flags</td>
     * <td>An integer representing the bit-flags for the field.</td>
     * </tr>
     * <tr valign="top">
     * <td>type</td>
     * <td>The data type used for this field</td>
     * </tr>
     * <tr valign="top">
     * <td>decimals</td>
     * <td>The number of decimals used (for integer fields)</td>
     * </tr>
     * </table>
     */
    #[TentativeType]
    public function fetch_field(): object|false {}

    /**
     * Returns an array of objects representing the fields in a result set
     * @link https://php.net/manual/en/mysqli-result.fetch-fields.php
     * @return array an array of objects containing field definition information.
     * </p>
     * <p>
     * <table>
     * Object properties
     * <tr valign="top">
     * <td>Property</td>
     * <td>Description</td>
     * </tr>
     * <tr valign="top">
     * <td>name</td>
     * <td>The name of the column</td>
     * </tr>
     * <tr valign="top">
     * <td>orgname</td>
     * <td>Original column name if an alias was specified</td>
     * </tr>
     * <tr valign="top">
     * <td>table</td>
     * <td>The name of the table this field belongs to (if not calculated)</td>
     * </tr>
     * <tr valign="top">
     * <td>orgtable</td>
     * <td>Original table name if an alias was specified</td>
     * </tr>
     * <tr valign="top">
     * <td>def</td>
     * <td>The default value for this field, represented as a string</td>
     * </tr>
     * <tr valign="top">
     * <td>max_length</td>
     * <td>The maximum width of the field for the result set.</td>
     * </tr>
     * <tr valign="top">
     * <td>length</td>
     * <td>The width of the field, as specified in the table definition.</td>
     * </tr>
     * <tr valign="top">
     * <td>charsetnr</td>
     * <td>The character set number for the field.</td>
     * </tr>
     * <tr valign="top">
     * <td>flags</td>
     * <td>An integer representing the bit-flags for the field.</td>
     * </tr>
     * <tr valign="top">
     * <td>type</td>
     * <td>The data type used for this field</td>
     * </tr>
     * <tr valign="top">
     * <td>decimals</td>
     * <td>The number of decimals used (for integer fields)</td>
     * </tr>
     * </table>
     */
    #[TentativeType]
    public function fetch_fields(): array {}

    /**
     * Fetch meta-data for a single field
     * @link https://php.net/manual/en/mysqli-result.fetch-field-direct.php
     * @param int $index <p>
     * The field number. This value must be in the range from
     * 0 to number of fields - 1.
     * </p>
     * @return object|false an object which contains field definition information or false
     * if no field information for specified fieldnr is
     * available.
     * </p>
     * <p>
     * <table>
     * Object attributes
     * <tr valign="top">
     * <td>Attribute</td>
     * <td>Description</td>
     * </tr>
     * <tr valign="top">
     * <td>name</td>
     * <td>The name of the column</td>
     * </tr>
     * <tr valign="top">
     * <td>orgname</td>
     * <td>Original column name if an alias was specified</td>
     * </tr>
     * <tr valign="top">
     * <td>table</td>
     * <td>The name of the table this field belongs to (if not calculated)</td>
     * </tr>
     * <tr valign="top">
     * <td>orgtable</td>
     * <td>Original table name if an alias was specified</td>
     * </tr>
     * <tr valign="top">
     * <td>def</td>
     * <td>The default value for this field, represented as a string</td>
     * </tr>
     * <tr valign="top">
     * <td>max_length</td>
     * <td>The maximum width of the field for the result set.</td>
     * </tr>
     * <tr valign="top">
     * <td>length</td>
     * <td>The width of the field, as specified in the table definition.</td>
     * </tr>
     * <tr valign="top">
     * <td>charsetnr</td>
     * <td>The character set number for the field.</td>
     * </tr>
     * <tr valign="top">
     * <td>flags</td>
     * <td>An integer representing the bit-flags for the field.</td>
     * </tr>
     * <tr valign="top">
     * <td>type</td>
     * <td>The data type used for this field</td>
     * </tr>
     * <tr valign="top">
     * <td>decimals</td>
     * <td>The number of decimals used (for integer fields)</td>
     * </tr>
     * </table>
     */
    #[TentativeType]
    public function fetch_field_direct(int $index): object|false {}

    /**
     * Fetches all result rows as an associative array, a numeric array, or both
     * @link https://php.net/manual/en/mysqli-result.fetch-all.php
     * @param int $mode [optional] <p>
     * This optional parameter is a constant indicating what type of array
     * should be produced from the current row data. The possible values for
     * this parameter are the constants MYSQLI_ASSOC,
     * MYSQLI_NUM, or MYSQLI_BOTH.
     * </p>
     * @return array an array of associative or numeric arrays holding result rows.
     */
    #[TentativeType]
    public function fetch_all(#[PhpStormStubsElementAvailable(from: '7.0')] int $mode = MYSQLI_NUM): array {}

    /**
     * Fetch the next row of a result set as an associative, a numeric array, or both
     * @link https://php.net/manual/en/mysqli-result.fetch-array.php
     * @param int $mode [optional] <p>
     * This optional parameter is a constant indicating what type of array
     * should be produced from the current row data. The possible values for
     * this parameter are the constants <b>MYSQLI_ASSOC</b>,
     * <b>MYSQLI_NUM</b>, or <b>MYSQLI_BOTH</b>.
     * </p>
     * <p>
     * By using the <b>MYSQLI_ASSOC</b> constant this function
     * will behave identically to the <b>mysqli_fetch_assoc</b>,
     * while <b>MYSQLI_NUM</b> will behave identically to the
     * <b>mysqli_fetch_row</b> function. The final option
     * <b>MYSQLI_BOTH</b> will create a single array with the
     * attributes of both.
     * </p>
     * @return array|false|null an array representing the fetched row, null if there
     * are no more rows in the result set, or false on failure.
     */
    #[TentativeType]
    public function fetch_array(int $mode = MYSQLI_BOTH): array|false|null {}

    /**
     * Fetch the next row of a result set as an associative array
     * @link https://php.net/manual/en/mysqli-result.fetch-assoc.php
     * @return array|false|null an associative array representing the fetched row,
     * where each key in the array represents the name of one of the result set's columns, null if there
     * are no more rows in the result set, or false on failure.
     */
    #[TentativeType]
    public function fetch_assoc(): array|false|null {}

    /**
     * @template T
     *
     * Fetch the next row of a result set as an object
     * @link https://php.net/manual/en/mysqli-result.fetch-object.php
     * @param class-string<T> $class [optional] <p>
     * The name of the class to instantiate, set the properties of and return.
     * If not specified, a <b>stdClass</b> object is returned.
     * </p>
     * @param null|array $constructor_args [optional] <p>
     * An optional array of parameters to pass to the constructor
     * for <i>class_name</i> objects.
     * </p>
     * @return T|stdClass|false|null an object representing the fetched row, where each property
     * represents the name of the result set's column, null if there
     * are no more rows in the result set, or false on failure.
     */
    #[TentativeType]
    public function fetch_object(string $class = 'stdClass', array $constructor_args = null): object|false|null {}

    /**
     * Fetch the next row of a result set as an enumerated array
     * @link https://php.net/manual/en/mysqli-result.fetch-row.php
     * @return array|false|null an enumerated array representing
     * the fetched row, null if there
     * are no more rows in the result set, or false on failure.
     */
    #[TentativeType]
    public function fetch_row(): array|false|null {}

    /**
     * Fetch a single column from the next row of a result set
     *
     * @param int $column [optional] <p>
     * 0-indexed number of the column you wish to retrieve from the row.
     * If no value is supplied, the first column will be returned.
     * </p>
     * @return string|int|float|false|null a single column from
     * the next row of a result set or false if there are no more rows.
     */
    #[PhpStormStubsElementAvailable('8.1')]
    public function fetch_column(int $column = 0): string|int|float|false|null {}

    /**
     * Set result pointer to a specified field offset
     * @link https://php.net/manual/en/mysqli-result.field-seek.php
     * @param int $index <p>
     * The field number. This value must be in the range from
     * 0 to number of fields - 1.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function field_seek(int $index): bool {}

    /**
     * Frees the memory associated with a result
     * @return void
     * @link https://php.net/manual/en/mysqli-result.free.php
     */
    #[TentativeType]
    public function free_result(): void {}

    /**
     * @return Iterator
     * @since 8.0
     */
    public function getIterator(): Iterator {}
}

/**
 * Represents a prepared statement.
 * @link https://php.net/manual/en/class.mysqli-stmt.php
 */
class mysqli_stmt
{
    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int|string'], default: '')]
    public $affected_rows;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int|string'], default: '')]
    public $insert_id;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int|string'], default: '')]
    public $num_rows;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $param_count;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $field_count;

    /**
     * @var int
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $errno;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $error;

    /**
     * @var array
     */
    #[LanguageLevelTypeAware(['8.1' => 'array'], default: '')]
    public $error_list;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $sqlstate;

    /**
     * @var string
     */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    public $id;

    /**
     * mysqli_stmt constructor
     * @param mysqli $mysql
     * @param string $query [optional]
     */
    public function __construct($mysql, $query) {}

    /**
     * Used to get the current value of a statement attribute
     * @link https://php.net/manual/en/mysqli-stmt.attr-get.php
     * @param int $attribute The attribute that you want to get.
     * @return int Returns the value of the attribute.
     */
    #[TentativeType]
    public function attr_get(int $attribute): int {}

    /**
     * Used to modify the behavior of a prepared statement
     * @link https://php.net/manual/en/mysqli-stmt.attr-set.php
     * @param int $attribute <p>
     * The attribute that you want to set. It can have one of the following values:
     * <table>
     * Attribute values
     * <tr valign="top">
     * <td>Character</td>
     * <td>Description</td>
     * </tr>
     * <tr valign="top">
     * <td>MYSQLI_STMT_ATTR_UPDATE_MAX_LENGTH</td>
     * <td>
     * If set to 1, causes <b>mysqli_stmt_store_result</b> to
     * update the metadata MYSQL_FIELD->max_length value.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>MYSQLI_STMT_ATTR_CURSOR_TYPE</td>
     * <td>
     * Type of cursor to open for statement when <b>mysqli_stmt_execute</b>
     * is invoked. <i>mode</i> can be MYSQLI_CURSOR_TYPE_NO_CURSOR
     * (the default) or MYSQLI_CURSOR_TYPE_READ_ONLY.
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>MYSQLI_STMT_ATTR_PREFETCH_ROWS</td>
     * <td>
     * Number of rows to fetch from server at a time when using a cursor.
     * <i>mode</i> can be in the range from 1 to the maximum
     * value of unsigned long. The default is 1.
     * </td>
     * </tr>
     * </table>
     * </p>
     * <p>
     * If you use the MYSQLI_STMT_ATTR_CURSOR_TYPE option with
     * MYSQLI_CURSOR_TYPE_READ_ONLY, a cursor is opened for the
     * statement when you invoke <b>mysqli_stmt_execute</b>. If there
     * is already an open cursor from a previous <b>mysqli_stmt_execute</b> call,
     * it closes the cursor before opening a new one. <b>mysqli_stmt_reset</b>
     * also closes any open cursor before preparing the statement for re-execution.
     * <b>mysqli_stmt_free_result</b> closes any open cursor.
     * </p>
     * <p>
     * If you open a cursor for a prepared statement, <b>mysqli_stmt_store_result</b>
     * is unnecessary.
     * </p>
     * @param int $value <p>The value to assign to the attribute.</p>
     * @return bool
     */
    #[TentativeType]
    public function attr_set(int $attribute, int $value): bool {}

    /**
     * Binds variables to a prepared statement as parameters
     * @link https://php.net/manual/en/mysqli-stmt.bind-param.php
     * @param string $types <p>
     * A string that contains one or more characters which specify the types
     * for the corresponding bind variables:
     * <table>
     * Type specification chars
     * <tr valign="top">
     * <td>Character</td>
     * <td>Description</td>
     * </tr>
     * <tr valign="top">
     * <td>i</td>
     * <td>corresponding variable has type integer</td>
     * </tr>
     * <tr valign="top">
     * <td>d</td>
     * <td>corresponding variable has type double</td>
     * </tr>
     * <tr valign="top">
     * <td>s</td>
     * <td>corresponding variable has type string</td>
     * </tr>
     * <tr valign="top">
     * <td>b</td>
     * <td>corresponding variable is a blob and will be sent in packets</td>
     * </tr>
     * </table>
     * </p>
     * @param mixed &$var1 <p>
     * The number of variables and length of string
     * types must match the parameters in the statement.
     * </p>
     * @param mixed &...$_ [optional]
     * @return bool true on success or false on failure.
     */
    public function bind_param($types, &$var1, &...$_) {}

    /**
     * Binds variables to a prepared statement for result storage
     * @link https://php.net/manual/en/mysqli-stmt.bind-result.php
     * @param mixed &$var1 The variable to be bound.
     * @param mixed &...$_ The variables to be bound.
     * @return bool true on success or false on failure.
     */
    public function bind_result(&$var1, &...$_) {}

    /**
     * Closes a prepared statement
     * @link https://php.net/manual/en/mysqli-stmt.close.php
     * @return bool true on success or false on failure.
     */
    public function close() {}

    /**
     * Seeks to an arbitrary row in statement result set
     * @link https://php.net/manual/en/mysqli-stmt.data-seek.php
     * @param int $offset <p>
     * Must be between zero and the total number of rows minus one (0..
     * <b>mysqli_stmt_num_rows</b> - 1).
     * </p>
     * @return void
     */
    #[TentativeType]
    public function data_seek(int $offset): void {}

    /**
     * Executes a prepared statement
     * @link https://php.net/manual/en/mysqli-stmt.execute.php
     * @param array|null $params [optional] An optional list array with as many elements
     * as there are bound parameters in the SQL statement being executed. Each value is treated as a string.
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function execute(#[PhpStormStubsElementAvailable('8.1')] ?array $params = null): bool {}

    /**
     * Fetch results from a prepared statement into the bound variables
     * @link https://php.net/manual/en/mysqli-stmt.fetch.php
     * @return bool|null
     */
    #[TentativeType]
    public function fetch(): ?bool {}

    /**
     * Get result of SHOW WARNINGS
     * @link https://php.net/manual/en/mysqli-stmt.get-warnings.php
     * @return object|false
     */
    #[TentativeType]
    public function get_warnings(): mysqli_warning|false {}

    /**
     * Returns result set metadata from a prepared statement
     * @link https://php.net/manual/en/mysqli-stmt.result-metadata.php
     * @return mysqli_result|false a result object or false if an error occurred.
     */
    #[TentativeType]
    public function result_metadata(): mysqli_result|false {}

    /**
     * Check if there are more query results from a multiple query
     * @link https://php.net/manual/en/mysqli-stmt.more-results.php
     * @return bool
     */
    #[TentativeType]
    public function more_results(): bool {}

    /**
     * Reads the next result from a multiple query
     * @link https://php.net/manual/en/mysqli-stmt.next-result.php
     * @return bool
     */
    #[TentativeType]
    public function next_result(): bool {}

    /**
     * Return the number of rows in statements result set
     * @link https://php.net/manual/en/mysqli-stmt.num-rows.php
     * @return string|int An integer representing the number of rows in result set.
     */
    #[TentativeType]
    public function num_rows(): string|int {}

    /**
     * Send data in blocks
     * @link https://php.net/manual/en/mysqli-stmt.send-long-data.php
     * @param int $param_num <p>
     * Indicates which parameter to associate the data with. Parameters are
     * numbered beginning with 0.
     * </p>
     * @param string $data <p>
     * A string containing data to be sent.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function send_long_data(int $param_num, string $data): bool {}

    /**
     * No documentation available
     * @removed 5.4
     */
    #[Deprecated(since: '5.3')]
    public function stmt() {}

    /**
     * Frees stored result memory for the given statement handle
     * @link https://php.net/manual/en/mysqli-stmt.free-result.php
     * @return void
     */
    #[TentativeType]
    public function free_result(): void {}

    /**
     * Resets a prepared statement
     * @link https://php.net/manual/en/mysqli-stmt.reset.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function reset(): bool {}

    /**
     * Prepare an SQL statement for execution
     * @link https://php.net/manual/en/mysqli-stmt.prepare.php
     * @param string $query <p>
     * The query, as a string. It must consist of a single SQL statement.
     * </p>
     * <p>
     * The SQL statement may contain zero or more parameter markers
     * represented by question mark (?) characters at the appropriate positions.
     * </p>
     * <p>
     * The markers are legal only in certain places in SQL statements.
     * For example, they are permitted in the VALUES() list of an INSERT statement
     * (to specify column values for a row), or in a comparison with a column in
     * a WHERE clause to specify a comparison value.
     * </p>
     * <p>
     * However, they are not permitted for identifiers (such as table or column names),
     * or to specify both operands of a binary operator such as the =
     * equal sign. The latter restriction is necessary because it would be impossible
     * to determine the parameter type. In general, parameters are legal only in Data
     * Manipulation Language (DML) statements, and not in Data Definition Language
     * (DDL) statements.
     * </p>
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function prepare(string $query): bool {}

    /**
     * Stores a result set in an internal buffer
     * @link https://php.net/manual/en/mysqli-stmt.store-result.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function store_result(): bool {}

    /**
     * Gets a result set from a prepared statement as a mysqli_result object
     * @link https://php.net/manual/en/mysqli-stmt.get-result.php
     * @return mysqli_result|false Returns a resultset or FALSE on failure
     */
    #[TentativeType]
    public function get_result(): mysqli_result|false {}
}

/**
 * Gets the number of affected rows in a previous MySQL operation
 * @link https://secure.php.net/manual/en/mysqli.affected-rows.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string|int An integer greater than zero indicates the number of rows affected or retrieved.
 * Zero indicates that no records were updated for an UPDATE statement,
 * no rows matched the WHERE clause in the query or that no query has yet been executed. -1 indicates that the query returned an error
 * or that <b>mysqli_affected_rows</b> was called for an unbuffered SELECT query.
 */
function mysqli_affected_rows(mysqli $mysql): string|int {}

/**
 * Turns on or off auto-committing database modifications
 * @link https://secure.php.net/manual/en/mysqli.autocommit.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param bool $enable Whether to turn on auto-commit or not.
 * @return bool
 */
function mysqli_autocommit(mysqli $mysql, bool $enable): bool {}

/**
 * Starts a transaction
 * @link https://secure.php.net/manual/en/mysqli.begin-transaction.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $flags [optional]
 * @param string|null $name [optional]
 * @return bool true on success or false on failure.
 * @since 5.5
 */
function mysqli_begin_transaction(mysqli $mysql, int $flags = 0, ?string $name): bool {}

/**
 * Changes the user of the specified database connection
 * @link https://php.net/manual/en/mysqli.change-user.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $username The MySQL user name.
 * @param string $password The MySQL password.
 * @param string|null $database The database to change to. If desired, the NULL value may be passed resulting in only changing the user and not selecting a database.
 * @return bool
 */
function mysqli_change_user(mysqli $mysql, string $username, string $password, ?string $database): bool {}

/**
 * Returns the current character set of the database connection
 * @link https://php.net/manual/en/mysqli.character-set-name.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string The current character set of the connection
 */
function mysqli_character_set_name(mysqli $mysql): string {}

/**
 * Closes a previously opened database connection
 * @link https://php.net/manual/en/mysqli.close.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return bool
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function mysqli_close(mysqli $mysql): bool {}

/**
 * Commits the current transaction
 * @link https://php.net/manual/en/mysqli.commit.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $flags [optional] A bitmask of MYSQLI_TRANS_COR_* constants
 * @param string|null $name [optional] If provided then COMMITname is executed
 * @return bool
 */
function mysqli_commit(mysqli $mysql, int $flags = 0, ?string $name = null): bool {}

/**
 * Open a new connection to the MySQL server
 * Alias of <b>mysqli::__construct</b>
 * @link https://php.net/manual/en/mysqli.construct.php
 * @param string|null $hostname Can be either a host name or an IP address. Passing the NULL value or the string "localhost" to this parameter, the local host is assumed. When possible, pipes will be used instead of the TCP/IP protocol.
 * @param string|null $username The MySQL user name.
 * @param string|null $password If not provided or NULL, the MySQL server will attempt to authenticate the user against those user records which have no password only.
 * @param string|null $database If provided will specify the default database to be used when performing queries.
 * @param int|null $port Specifies the port number to attempt to connect to the MySQL server.
 * @param string|null $socket Specifies the socket or named pipe that should be used.
 * @return mysqli|false object which represents the connection to a MySQL Server or false if an error occurred.
 */
function mysqli_connect(?string $hostname = null, ?string $username = null, ?string $password = null, ?string $database = null, ?int $port = null, ?string $socket = null): mysqli|false {}

/**
 * Returns the error code from last connect call
 * @link https://php.net/manual/en/mysqli.connect-errno.php
 * @return int Last error code number from the last call to mysqli_connect(). Zero means no error occurred.
 */
function mysqli_connect_errno(): int {}

/**
 * Returns a string description of the last connect error
 * @link https://php.net/manual/en/mysqli.connect-error.php
 * @return string|null Last error message string from the last call to mysqli_connect().
 */
function mysqli_connect_error(): ?string {}

/**
 * Adjusts the result pointer to an arbitrary row in the result
 * @link https://php.net/manual/en/mysqli-result.data-seek.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @param int $offset
 * @return bool Returns TRUE on success or FALSE on failure.
 */
function mysqli_data_seek(mysqli_result $result, int $offset): bool {}

/**
 * Dump debugging information into the log
 * @link https://php.net/manual/en/mysqli.dump-debug-info.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return bool
 */
function mysqli_dump_debug_info(mysqli $mysql): bool {}

/**
 * Performs debugging operations using the Fred Fish debugging library.
 * @link https://php.net/manual/en/mysqli.debug.php
 * @param string $options
 * @return bool
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function mysqli_debug(string $options): bool {}

/**
 * Returns the error code for the most recent function call
 * @link https://php.net/manual/en/mysqli.errno.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int An error code value for the last call, if it failed. zero means no error occurred.
 */
function mysqli_errno(mysqli $mysql): int {}

/**
 * Returns a list of errors from the last command executed
 * @link https://php.net/manual/en/mysqli.error-list.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return array A list of errors, each as an associative array containing the errno, error, and sqlstate.
 * @since 5.4
 */
#[ArrayShape([
    "errno" => "int",
    "sqlstate" => "string",
    "error" => "string",
])]
function mysqli_error_list(mysqli $mysql): array {}

/**
 * Returns a list of errors from the last statement executed
 * @link https://secure.php.net/manual/en/mysqli-stmt.error-list.php
 * @param mysqli_stmt $statement A statement identifier returned by mysqli_stmt_init().
 * @return array A list of errors, each as an associative array containing the errno, error, and sqlstate.
 * @since 5.4
 */
function mysqli_stmt_error_list(mysqli_stmt $statement): array {}

/**
 * Returns a string description of the last error
 * @link https://secure.php.net/manual/en/mysqli.error.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string
 */
function mysqli_error(mysqli $mysql): string {}

/**
 * Executes a prepared statement
 * @link https://php.net/manual/en/mysqli-stmt.execute.php
 * @param mysqli_stmt $statement
 * @param array|null $params [optional] An optional list array with as many elements
 * as there are bound parameters in the SQL statement being executed. Each value is treated as a string.
 * @return bool true on success or false on failure.
 */
function mysqli_stmt_execute(mysqli_stmt $statement, #[PhpStormStubsElementAvailable('8.1')] ?array $params = null): bool {}

/**
 * Executes a prepared statement
 * Alias for <b>mysqli_stmt_execute</b>
 * @link https://php.net/manual/en/function.mysqli-execute.php
 * @param mysqli_stmt $statement
 * @param array|null $params [optional] An optional list array with as many elements
 * as there are bound parameters in the SQL statement being executed. Each value is treated as a string.
 * @return bool
 */
#[Deprecated(since: '5.3')]
function mysqli_execute(mysqli_stmt $statement, #[PhpStormStubsElementAvailable('8.1')] ?array $params = null): bool {}

/**
 * @param mysqli $mysql
 * @param string $query
 * @param array|null $params
 * @return mysqli_result|bool
 * @since 8.2
 */
function mysqli_execute_query(mysqli $mysql, string $query, ?array $params = null): mysqli_result|bool {}

/**
 * Returns the next field in the result set
 * @link https://secure.php.net/manual/en/mysqli-result.fetch-field.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @return object|false Returns an object which contains field definition information or FALSE if no field information is available.
 */
function mysqli_fetch_field(mysqli_result $result): object|false {}

/**
 * Returns an array of objects representing the fields in a result set
 * @link https://secure.php.net/manual/en/mysqli-result.fetch-fields.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @return array Returns an array of objects which contains field definition information.
 */
function mysqli_fetch_fields(mysqli_result $result): array {}

/**
 * Fetch meta-data for a single field
 * @link https://secure.php.net/manual/en/mysqli-result.fetch-field-direct.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @param int $index The field number. This value must be in the range from 0 to number of fields - 1.
 * @return object|false Returns an object which contains field definition information or FALSE if no field information for specified fieldnr is available.
 */
function mysqli_fetch_field_direct(mysqli_result $result, int $index): object|false {}

/**
 * Returns the lengths of the columns of the current row in the result set
 * @link https://php.net/manual/en/mysqli-result.lengths.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @return int[]|false An array of integers representing the size of each column (not including any terminating null characters). FALSE if an error occurred.
 */
function mysqli_fetch_lengths(mysqli_result $result): array|false {}

/**
 * Fetch all result rows as an associative array, a numeric array, or both
 * @link https://php.net/manual/en/mysqli-result.fetch-all.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @param int $mode
 * @return array Returns an array of associative or numeric arrays holding result rows.
 */
function mysqli_fetch_all(
    mysqli_result $result,
    #[PhpStormStubsElementAvailable(from: '7.0')] int $mode = MYSQLI_NUM
): array {}

/**
 * Fetch the next row of a result set as an associative, a numeric array, or both
 * @link https://php.net/manual/en/mysqli-result.fetch-array.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @param int $mode
 * @return array|false|null an array representing the fetched row,
 * null if there are no more rows in the result set, or false on failure.
 */
function mysqli_fetch_array(mysqli_result $result, int $mode = MYSQLI_BOTH): array|false|null {}

/**
 * Fetch the next row of a result set as an associative array
 * @link https://php.net/manual/en/mysqli-result.fetch-assoc.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @return array|false|null an associative array representing the fetched row,
 * where each key in the array represents the name of one of the result set's columns,
 * null if there are no more rows in the result set, or false on failure.
 */
function mysqli_fetch_assoc(mysqli_result $result): array|null|false {}

/**
 * @template T
 *
 * Fetch the next row of a result set as an object
 * @link https://php.net/manual/en/mysqli-result.fetch-object.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @param class-string<T> $class [optional] The name of the class to instantiate, set the properties of and return. If not specified, a stdClass object is returned.
 * @param array $constructor_args [optional] An optional array of parameters to pass to the constructor for class_name objects.
 * @return T|stdClass|null|false an object representing the fetched row,
 * where each property represents the name of the result set's column,
 * null if there are no more rows in the result set, or false on failure.
 */
function mysqli_fetch_object(mysqli_result $result, string $class = 'stdClass', array $constructor_args = []): object|null|false {}

/**
 * Fetch the next row of a result set as an enumerated array
 * @link https://php.net/manual/en/mysqli-result.fetch-row.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @return array|null|false an enumerated array representing the fetched row,
 * null if there are no more rows in the result set, or false on failure.
 * @link https://php.net/manual/en/mysqli-result.fetch-row.php
 */
function mysqli_fetch_row(mysqli_result $result): array|false|null {}

/**
 * Fetch a single column from the next row of a result set
 * @link https://php.net/manual/en/mysqli-result.fetch-column.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @param int $column [optional] <p>
 * 0-indexed number of the column you wish to retrieve from the row.
 * If no value is supplied, the first column will be returned.
 * </p>
 * @return string|int|float|false|null a single column from
 * the next row of a result set or false if there are no more rows.
 */
#[PhpStormStubsElementAvailable('8.1')]
function mysqli_fetch_column(mysqli_result $result, int $column = 0): string|int|float|false|null {}

/**
 * Returns the number of columns for the most recent query
 * @link https://php.net/manual/en/mysqli.field-count.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int An integer representing the number of fields in a result set.
 */
function mysqli_field_count(mysqli $mysql): int {}

/**
 * Set result pointer to a specified field offset
 * @link https://php.net/manual/en/mysqli-result.field-seek.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @param int $index The field number. This value must be in the range from 0 to number of fields - 1.
 * @return bool
 */
function mysqli_field_seek(mysqli_result $result, int $index): bool {}

/**
 * Get current field offset of a result pointer
 * @link https://php.net/manual/en/mysqli-result.current-field.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @return int
 */
function mysqli_field_tell(mysqli_result $result): int {}

/**
 * Frees the memory associated with a result
 * @link https://php.net/manual/en/mysqli-result.free.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @return void
 */
function mysqli_free_result(mysqli_result $result): void {}

/**
 * Returns client Zval cache statistics
 * Available only with mysqlnd.
 * @link https://php.net/manual/en/function.mysqli-get-cache-stats.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return array|false an array with client Zval cache stats if success, false otherwise.
 * @removed 5.4
 */
function mysqli_get_cache_stats(mysqli $mysql) {}

/**
 * Returns statistics about the client connection
 * @link https://php.net/manual/en/mysqli.get-connection-stats.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return array an array with connection stats.
 */
function mysqli_get_connection_stats(mysqli $mysql): array {}

/**
 * Returns client per-process statistics
 * @link https://php.net/manual/en/function.mysqli-get-client-stats.php
 * @return array an array with client stats.
 */
function mysqli_get_client_stats(): array {}

/**
 * Returns a character set object
 * @link https://php.net/manual/en/mysqli.get-charset.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return object|null
 */
function mysqli_get_charset(mysqli $mysql): ?object {}

/**
 * Get MySQL client info
 * @link https://php.net/manual/en/mysqli.get-client-info.php
 * @param mysqli|null $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string|null A string that represents the MySQL client library version
 */
#[LanguageLevelTypeAware(['8.0' => 'string'], default: '?string')]
function mysqli_get_client_info(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.1')] mysqli $mysql,
    #[PhpStormStubsElementAvailable(from: '8.0')] ?mysqli $mysql = null
) {}

/**
 * Returns the MySQL client version as an integer
 * @link https://php.net/manual/en/mysqli.get-client-version.php
 * @return int
 */
function mysqli_get_client_version(#[PhpStormStubsElementAvailable(from: '5.3', to: '7.3')] $link): int {}

/**
 * Returns a string representing the type of connection used
 * @link https://php.net/manual/en/mysqli.get-host-info.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string A character string representing the server hostname and the connection type.
 */
function mysqli_get_host_info(mysqli $mysql): string {}

/**
 * Return information about open and cached links
 * @link https://php.net/manual/en/function.mysqli-get-links-stats.php
 * @return array mysqli_get_links_stats() returns an associative array with three elements, keyed as follows:
 * <dl>
 * <dt>
 * <code>total</code></dt>
 * <dd>
 * <p>
 * An integer indicating the total number of open links in
 * any state.
 * </p>
 * </dd>
 *
 * <dt>
 * <code>active_plinks</code></dt>
 * <dd>
 * <p>
 * An integer representing the number of active persistent
 * connections.
 * </p>
 * </dd>
 *
 * <dt>
 * <code>cached_plinks</code></dt>
 * <dd>
 * <p>
 * An integer representing the number of inactive persistent
 * connections.
 * </p>
 * </dd>
 *
 * </dl>
 * @since 5.6
 */
#[ArrayShape(["total" => "int", "active_plinks" => "int", "cached_plinks" => "int"])]
function mysqli_get_links_stats(): array {}

/**
 * Returns the version of the MySQL protocol used
 * @link https://php.net/manual/en/mysqli.get-proto-info.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int Returns an integer representing the protocol version
 */
function mysqli_get_proto_info(mysqli $mysql): int {}

/**
 * Returns the version of the MySQL server
 * @link https://php.net/manual/en/mysqli.get-server-info.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string A character string representing the server version.
 */
function mysqli_get_server_info(mysqli $mysql): string {}

/**
 * Returns the version of the MySQL server as an integer
 * @link https://php.net/manual/en/mysqli.get-server-version.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int An integer representing the server version.
 * The form of this version number is main_version * 10000 + minor_version * 100 + sub_version (i.e. version 4.1.0 is 40100).
 */
function mysqli_get_server_version(mysqli $mysql): int {}

/**
 * Get result of SHOW WARNINGS
 * @link https://php.net/manual/en/mysqli.get-warnings.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return mysqli_warning|false
 */
function mysqli_get_warnings(mysqli $mysql): mysqli_warning|false {}

/**
 * Initializes MySQLi and returns a resource for use with mysqli_real_connect()
 * @link https://php.net/manual/en/mysqli.init.php
 * @return mysqli|false
 * @see mysqli_real_connect()
 */
function mysqli_init(): mysqli|false {}

/**
 * Retrieves information about the most recently executed query
 * @link https://php.net/manual/en/mysqli.info.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string|null A character string representing additional information about the most recently executed query.
 */
function mysqli_info(mysqli $mysql): ?string {}

/**
 * Returns the value generated for an AUTO_INCREMENT column by the last query
 * @link https://php.net/manual/en/mysqli.insert-id.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int|string The value of the AUTO_INCREMENT field that was updated by the previous query. Returns zero if there was no previous query on the connection or if the query did not update an AUTO_INCREMENT value.
 * If the number is greater than the maximum int value, it will be returned as a string.
 */
function mysqli_insert_id(mysqli $mysql): string|int {}

/**
 * Asks the server to kill a MySQL thread
 * @link https://php.net/manual/en/mysqli.kill.php
 * @see mysqli_thread_id()
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $process_id
 * @return bool
 */
function mysqli_kill(mysqli $mysql, int $process_id): bool {}

/**
 * Unsets user defined handler for load local infile command
 * @link https://php.net/manual/en/mysqli.set-local-infile-default.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return void
 * @removed 5.5
 */
function mysqli_set_local_infile_default(mysqli $mysql) {}

/**
 * Set callback function for LOAD DATA LOCAL INFILE command
 * @link https://php.net/manual/en/mysqli.set-local-infile-handler.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param callable $read_func
 * @return bool
 * @removed 5.5
 */
function mysqli_set_local_infile_handler(mysqli $mysql, callable $read_func): bool {}

/**
 * Check if there are any more query results from a multi query
 * @link https://php.net/manual/en/mysqli.more-results.php
 * @see mysqli_multi_query()
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return bool
 */
function mysqli_more_results(mysqli $mysql): bool {}

/**
 * Performs one or more queries on the database
 * @link https://php.net/manual/en/mysqli.multi-query.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $query A string containing the queries to be executed. Multiple queries must be separated by a semicolon.
 * @return bool Returns FALSE if the first statement failed. To retrieve subsequent errors from other statements you have to call mysqli_next_result() first.
 */
function mysqli_multi_query(
    mysqli $mysql,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] string $query,
    #[PhpStormStubsElementAvailable(from: '7.1', to: '7.4')] string $query = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] string $query
): bool {}

/**
 * Prepare next result from multi_query
 * @link https://php.net/manual/en/mysqli.next-result.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return bool
 */
function mysqli_next_result(mysqli $mysql): bool {}

/**
 * Gets the number of fields in the result set
 * @link https://php.net/manual/en/mysqli-result.field-count.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @return int
 */
function mysqli_num_fields(mysqli_result $result): int {}

/**
 * Gets the number of rows in a result
 * @link https://php.net/manual/en/mysqli-result.num-rows.php
 * @param mysqli_result $result A mysqli_result object returned by mysqli_query(),
 * mysqli_store_result(), mysqli_use_result() or mysqli_stmt_get_result().
 * @return string|int Returns number of rows in the result set.
 */
function mysqli_num_rows(mysqli_result $result): string|int {}

/**
 * Set options
 * @link https://php.net/manual/en/mysqli.options.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $option
 * @param string|int $value
 * @return bool
 */
function mysqli_options(mysqli $mysql, int $option, $value): bool {}

/**
 * Pings a server connection, or tries to reconnect if the connection has gone down
 * @link https://php.net/manual/en/mysqli.ping.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return bool
 */
function mysqli_ping(mysqli $mysql): bool {}

/**
 * Poll connections
 * @link https://php.net/manual/en/mysqli.poll.php
 * @param array|null &$read
 * @param array|null &$error
 * @param array &$reject
 * @param int $seconds
 * @param int $microseconds [optional]
 * @return int|false number of ready connections upon success, FALSE otherwise.
 */
function mysqli_poll(?array &$read, ?array &$error, array &$reject, int $seconds, int $microseconds = 0): int|false {}

/**
 * Prepares an SQL statement for execution
 * @link https://php.net/manual/en/mysqli.prepare.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $query The query, as a string. It must consist of a single SQL statement.
 * The SQL statement may contain zero or more parameter markers represented by question mark (?) characters at the appropriate positions.
 * @return mysqli_stmt|false A statement object or FALSE if an error occurred.
 */
function mysqli_prepare(mysqli $mysql, string $query): mysqli_stmt|false {}

/**
 * Enables or disables internal report functions
 * @link https://php.net/manual/en/function.mysqli-report.php
 * @param int $flags <p>
 * <table>
 * Supported flags
 * <tr valign="top">
 * <td>Name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td><b>MYSQLI_REPORT_OFF</b></td>
 * <td>Turns reporting off</td>
 * </tr>
 * <tr valign="top">
 * <td><b>MYSQLI_REPORT_ERROR</b></td>
 * <td>Report errors from mysqli function calls</td>
 * </tr>
 * <tr valign="top">
 * <td><b>MYSQLI_REPORT_STRICT</b></td>
 * <td>
 * Throw <b>mysqli_sql_exception</b> for errors
 * instead of warnings
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MYSQLI_REPORT_INDEX</b></td>
 * <td>Report if no index or bad index was used in a query</td>
 * </tr>
 * <tr valign="top">
 * <td><b>MYSQLI_REPORT_ALL</b></td>
 * <td>Set all options (report all)</td>
 * </tr>
 * </table>
 * </p>
 * @return bool
 */
function mysqli_report(int $flags): bool {}

/**
 * Performs a query on the database
 * @link https://php.net/manual/en/mysqli.query.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $query An SQL query
 * @param int $result_mode
 * @return mysqli_result|bool
 * For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object.
 * For other successful queries mysqli_query() will return TRUE.
 * Returns FALSE on failure.
 */
function mysqli_query(
    mysqli $mysql,
    string $query,
    #[PhpStormStubsElementAvailable(from: '7.1')] int $result_mode = MYSQLI_STORE_RESULT
): mysqli_result|bool {}

/**
 * Opens a connection to a mysql server
 * @link https://php.net/manual/en/mysqli.real-connect.php
 * @see mysqli_connect()
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string|null $hostname [optional]
 * @param string|null $username [optional]
 * @param string|null $password [optional]
 * @param string|null $database [optional]
 * @param int|null $port [optional]
 * @param string|null $socket [optional]
 * @param int $flags
 * @return bool
 */
function mysqli_real_connect(mysqli $mysql, ?string $hostname, ?string $username, ?string $password, ?string $database, ?int $port, ?string $socket, int $flags = 0): bool {}

/**
 * Escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection
 * @link https://php.net/manual/en/mysqli.real-escape-string.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $string The string to be escaped. Characters encoded are NUL (ASCII 0), \n, \r, \, ', ", and Control-Z.
 * @return string
 */
function mysqli_real_escape_string(mysqli $mysql, string $string): string {}

/**
 * Execute an SQL query
 * @link https://php.net/manual/en/mysqli.real-query.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $query
 * @return bool
 */
function mysqli_real_query(
    mysqli $mysql,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] string $query,
    #[PhpStormStubsElementAvailable(from: '7.1', to: '7.4')] string $query = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] string $query
): bool {}

/**
 * Get result from async query
 * Available only with mysqlnd.
 * @link https://php.net/manual/en/mysqli.reap-async-query.php
 * @see mysqli_poll()
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return mysqli_result|bool mysqli_result in success, FALSE otherwise.
 */
function mysqli_reap_async_query(mysqli $mysql): mysqli_result|bool {}

/**
 * Removes the named savepoint from the set of savepoints of the current transaction
 * @link https://secure.php.net/manual/en/mysqli.release-savepoint.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $name
 * @return bool Returns TRUE on success or FALSE on failure.
 * @since 5.5
 */
function mysqli_release_savepoint(mysqli $mysql, string $name): bool {}

/**
 * Rolls back current transaction
 * @link https://php.net/manual/en/mysqli.rollback.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $flags [optional] A bitmask of MYSQLI_TRANS_COR_* constants
 * @param string|null $name [optional] If provided then ROLLBACKname is executed
 * @return bool
 */
function mysqli_rollback(mysqli $mysql, int $flags = 0, ?string $name): bool {}

/**
 * Set a named transaction savepoint
 * @link https://secure.php.net/manual/en/mysqli.savepoint.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $name
 * @return bool Returns TRUE on success or FALSE on failure.
 * @since 5.5
 */
function mysqli_savepoint(mysqli $mysql, string $name): bool {}

/**
 * Selects the default database for database queries
 * @link https://php.net/manual/en/mysqli.select-db.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $database
 * @return bool
 */
function mysqli_select_db(mysqli $mysql, string $database): bool {}

/**
 * Sets the client character set
 * @link https://php.net/manual/en/mysqli.set-charset.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $charset
 * @return bool
 */
function mysqli_set_charset(mysqli $mysql, string $charset): bool {}

/**
 * Returns the total number of rows changed, deleted, inserted, or matched by the last statement executed
 * @link https://php.net/manual/en/mysqli-stmt.affected-rows.php
 * @param mysqli_stmt $statement
 * @return int|string If the number of affected rows is greater than maximum PHP int value, the number of affected rows will be returned as a string value.
 */
function mysqli_stmt_affected_rows(mysqli_stmt $statement): string|int {}

/**
 * Used to get the current value of a statement attribute
 * @link https://php.net/manual/en/mysqli-stmt.attr-get.php
 * @param mysqli_stmt $statement
 * @param int $attribute
 * @return int|false Returns FALSE if the attribute is not found, otherwise returns the value of the attribute.
 */
#[LanguageLevelTypeAware(["8.0" => "int"], default: "int|false")]
function mysqli_stmt_attr_get(mysqli_stmt $statement, int $attribute): false|int {}

/**
 * Used to modify the behavior of a prepared statement
 * @link https://php.net/manual/en/mysqli-stmt.attr-set.php
 * @param mysqli_stmt $statement
 * @param int $attribute
 * @param int $value
 * @return bool
 */
function mysqli_stmt_attr_set(mysqli_stmt $statement, int $attribute, int $value): bool {}

/**
 * Returns the number of fields in the given statement
 * @link https://php.net/manual/en/mysqli-stmt.field-count.php
 * @param mysqli_stmt $statement
 * @return int
 */
function mysqli_stmt_field_count(mysqli_stmt $statement): int {}

/**
 * Initializes a statement and returns an object for use with mysqli_stmt_prepare
 * @link https://php.net/manual/en/mysqli.stmt-init.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return mysqli_stmt|false
 */
function mysqli_stmt_init(mysqli $mysql): mysqli_stmt|false {}

/**
 * Prepares an SQL statement for execution
 * @link https://php.net/manual/en/mysqli-stmt.prepare.php
 * @param mysqli_stmt $statement
 * @param string $query
 * @return bool
 */
function mysqli_stmt_prepare(mysqli_stmt $statement, string $query): bool {}

/**
 * Returns result set metadata from a prepared statement
 * @link https://php.net/manual/en/mysqli-stmt.result-metadata.php
 * @param mysqli_stmt $statement
 * @return mysqli_result|false Returns a result object or FALSE if an error occurred
 */
function mysqli_stmt_result_metadata(mysqli_stmt $statement): mysqli_result|false {}

/**
 * Send data in blocks
 * @link https://php.net/manual/en/mysqli-stmt.send-long-data.php
 * @param mysqli_stmt $statement
 * @param int $param_num
 * @param string $data
 * @return bool
 */
function mysqli_stmt_send_long_data(mysqli_stmt $statement, int $param_num, string $data): bool {}

/**
 * Binds variables to a prepared statement as parameters
 * @link https://php.net/manual/en/mysqli-stmt.bind-param.php
 * @param mysqli_stmt $statement A statement identifier returned by mysqli_stmt_init()
 * @param string $types <p>
 * A string that contains one or more characters which specify the types
 * for the corresponding bind variables:
 * <table>
 * Type specification chars
 * <tr valign="top">
 * <td>Character</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>i</td>
 * <td>corresponding variable has type integer</td>
 * </tr>
 * <tr valign="top">
 * <td>d</td>
 * <td>corresponding variable has type double</td>
 * </tr>
 * <tr valign="top">
 * <td>s</td>
 * <td>corresponding variable has type string</td>
 * </tr>
 * <tr valign="top">
 * <td>b</td>
 * <td>corresponding variable is a blob and will be sent in packets</td>
 * </tr>
 * </table>
 * </p>
 * @param mixed &$var1 <p>
 * The number of variables and length of string
 * types must match the parameters in the statement.
 * </p>
 * @param mixed &...$vars
 * @return bool true on success or false on failure.
 */
function mysqli_stmt_bind_param(
    mysqli_stmt $statement,
    string $types,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] mixed &$vars,
    mixed &...$vars
): bool {}

/**
 * Binds variables to a prepared statement for result storage
 * @link https://php.net/manual/en/mysqli-stmt.bind-result.php
 * @param mysqli_stmt $statement Statement
 * @param mixed &...$vars The variables to be bound.
 * @return bool
 */
function mysqli_stmt_bind_result(
    mysqli_stmt $statement,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] mixed &$vars,
    mixed &...$vars
): bool {}

/**
 * Fetch results from a prepared statement into the bound variables
 * @link https://php.net/manual/en/mysqli-stmt.fetch.php
 * @param mysqli_stmt $statement
 * @return bool|null
 */
function mysqli_stmt_fetch(mysqli_stmt $statement): ?bool {}

/**
 * Frees stored result memory for the given statement handle
 * @link https://php.net/manual/en/mysqli-stmt.free-result.php
 * @param mysqli_stmt $statement
 * @return void
 */
function mysqli_stmt_free_result(mysqli_stmt $statement): void {}

/**
 * Gets a result set from a prepared statement as a mysqli_result object
 * @link https://php.net/manual/en/mysqli-stmt.get-result.php
 * @param mysqli_stmt $statement
 * @return mysqli_result|false Returns false on failure. For successful queries which produce a result set,
 * such as SELECT, SHOW, DESCRIBE or EXPLAIN, mysqli_stmt_get_result() will return a mysqli_result object.
 * For other successful queries, mysqli_stmt_get_result() will return false.
 */
function mysqli_stmt_get_result(mysqli_stmt $statement): mysqli_result|false {}

/**
 * Get result of SHOW WARNINGS
 * @link https://php.net/manual/en/mysqli-stmt.get-warnings.php
 * @param mysqli_stmt $statement
 * @return mysqli_warning|false (not documented, but it's probably a mysqli_warning object)
 */
function mysqli_stmt_get_warnings(mysqli_stmt $statement): mysqli_warning|false {}

/**
 * Get the ID generated from the previous INSERT operation
 * @link https://php.net/manual/en/mysqli-stmt.insert-id.php
 * @param mysqli_stmt $statement
 * @return string|int
 */
function mysqli_stmt_insert_id(mysqli_stmt $statement): string|int {}

/**
 * Resets a prepared statement
 * @link https://php.net/manual/en/mysqli-stmt.reset.php
 * @param mysqli_stmt $statement
 * @return bool
 */
function mysqli_stmt_reset(mysqli_stmt $statement): bool {}

/**
 * Returns the number of parameter for the given statement
 * @link https://php.net/manual/en/mysqli-stmt.param-count.php
 * @param mysqli_stmt $statement
 * @return int
 */
function mysqli_stmt_param_count(mysqli_stmt $statement): int {}

/**
 * Returns the SQLSTATE error from previous MySQL operation
 * @link https://php.net/manual/en/mysqli.sqlstate.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string Returns a string containing the SQLSTATE error code for the last error. The error code consists of five characters. '00000' means no error.
 */
function mysqli_sqlstate(mysqli $mysql): string {}

/**
 * Gets the current system status
 * @link https://php.net/manual/en/mysqli.stat.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string|false A string describing the server status. FALSE if an error occurred.
 */
function mysqli_stat(mysqli $mysql): string|false {}

/**
 * Used for establishing secure connections using SSL
 * @link https://secure.php.net/manual/en/mysqli.ssl-set.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string|null $key The path name to the key file
 * @param string|null $certificate The path name to the certificate file
 * @param string|null $ca_certificate The path name to the certificate authority file
 * @param string|null $ca_path The pathname to a directory that contains trusted SSL CA certificates in PEM format
 * @param string|null $cipher_algos A list of allowable ciphers to use for SSL encryption
 * @return bool This function always returns TRUE value.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function mysqli_ssl_set(
    mysqli $mysql,
    #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: 'string')] $key,
    #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: 'string')] $certificate,
    #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: 'string')] $ca_certificate,
    #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: 'string')] $ca_path,
    #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: 'string')] $cipher_algos
): bool {}

/**
 * Closes a prepared statement
 * @link https://php.net/manual/en/mysqli-stmt.close.php
 * @param mysqli_stmt $statement
 * @return bool
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function mysqli_stmt_close(mysqli_stmt $statement): bool {}

/**
 * Seeks to an arbitrary row in statement result set
 * @link https://php.net/manual/en/mysqli-stmt.data-seek.php
 * @param mysqli_stmt $statement
 * @param int $offset
 * @return void
 */
function mysqli_stmt_data_seek(mysqli_stmt $statement, int $offset): void {}

/**
 * Returns the error code for the most recent statement call
 * @link https://php.net/manual/en/mysqli-stmt.errno.php
 * @param mysqli_stmt $statement
 * @return int
 */
function mysqli_stmt_errno(mysqli_stmt $statement): int {}

/**
 * Returns a string description for last statement error
 * @link https://php.net/manual/en/mysqli-stmt.error.php
 * @param mysqli_stmt $statement
 * @return string
 */
function mysqli_stmt_error(mysqli_stmt $statement): string {}

/**
 * Check if there are more query results from a multiple query
 * @link https://php.net/manual/en/mysqli-stmt.more-results.php
 * @param mysqli_stmt $statement
 * @return bool
 */
function mysqli_stmt_more_results(mysqli_stmt $statement): bool {}

/**
 * Reads the next result from a multiple query
 * @link https://php.net/manual/en/mysqli-stmt.next-result.php
 * @param mysqli_stmt $statement
 * @return bool
 */
function mysqli_stmt_next_result(mysqli_stmt $statement): bool {}

/**
 * Return the number of rows in statements result set
 * @link https://php.net/manual/en/mysqli-stmt.num-rows.php
 * @param mysqli_stmt $statement
 * @return string|int
 */
function mysqli_stmt_num_rows(mysqli_stmt $statement): string|int {}

/**
 * Returns SQLSTATE error from previous statement operation
 * @link https://php.net/manual/en/mysqli-stmt.sqlstate.php
 * @param mysqli_stmt $statement
 * @return string Returns a string containing the SQLSTATE error code for the last error. The error code consists of five characters. '00000' means no error.
 */
function mysqli_stmt_sqlstate(mysqli_stmt $statement): string {}

/**
 * Transfers a result set from a prepared statement
 * @link https://php.net/manual/en/mysqli-stmt.store-result.php
 * @param mysqli_stmt $statement
 * @return bool
 */
function mysqli_stmt_store_result(mysqli_stmt $statement): bool {}

/**
 * Transfers a result set from the last query
 * @link https://php.net/manual/en/mysqli.store-result.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $mode [optional] The option that you want to set
 * @return mysqli_result|false
 */
function mysqli_store_result(mysqli $mysql, int $mode = 0): mysqli_result|false {}

/**
 * Returns the thread ID for the current connection
 * @link https://php.net/manual/en/mysqli.thread-id.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int Returns the Thread ID for the current connection.
 */
function mysqli_thread_id(mysqli $mysql): int {}

/**
 * Returns whether thread safety is given or not
 * @link https://php.net/manual/en/mysqli.thread-safe.php
 * @return bool
 */
function mysqli_thread_safe(): bool {}

/**
 * Initiate a result set retrieval
 * @link https://php.net/manual/en/mysqli.use-result.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return mysqli_result|false
 */
function mysqli_use_result(mysqli $mysql): mysqli_result|false {}

/**
 * Returns the number of warnings from the last query for the given link
 * @link https://php.net/manual/en/mysqli.warning-count.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int
 */
function mysqli_warning_count(mysqli $mysql): int {}

/**
 * Flushes tables or caches, or resets the replication server information
 * @link https://php.net/manual/en/mysqli.refresh.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $flags
 * @return bool
 */
function mysqli_refresh(mysqli $mysql, int $flags): bool {}

/**
 * Alias for <b>mysqli_stmt_bind_param</b>
 * @link https://php.net/manual/en/function.mysqli-bind-param.php
 * @param mysqli_stmt $statement
 * @param string $types
 * @removed 5.4
 */
#[Deprecated(since: '5.3')]
function mysqli_bind_param(mysqli_stmt $statement, string $types) {}

/**
 * Alias for <b>mysqli_stmt_bind_result</b>
 * @link https://php.net/manual/en/function.mysqli-bind-result.php
 * @param mysqli_stmt $statement
 * @param string $types
 * @param mixed &$var1
 * @removed 5.4
 */
#[Deprecated(since: '5.3')]
function mysqli_bind_result(mysqli_stmt $statement, string $types, mixed &$var1) {}

/**
 * Alias of <b>mysqli_character_set_name</b>
 * @link https://php.net/manual/en/function.mysqli-client-encoding.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string
 * @removed 5.4
 */
#[Deprecated(since: '5.3')]
function mysqli_client_encoding(mysqli $mysql): string {}

/**
 * Alias of <b>mysqli_real_escape_string</b>
 * @link https://php.net/manual/en/function.mysqli-escape-string.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $string The string to be escaped
 * @return string
 */
function mysqli_escape_string(
    mysqli $mysql,
    string $string,
    #[PhpStormStubsElementAvailable(from: '7.1', to: '7.4')] $resultmode = null
): string {}

/**
 * Alias for <b>mysqli_stmt_fetch</b>
 * @link https://php.net/manual/en/function.mysqli-fetch.php
 * @param mysqli_stmt $statement
 * @return bool
 * @removed 5.4
 */
#[Deprecated(since: '5.3')]
function mysqli_fetch(mysqli_stmt $statement): bool {}

/**
 * Alias for <b>mysqli_stmt_param_count</b>
 * @link https://php.net/manual/en/function.mysqli-param-count.php
 * @param mysqli_stmt $statement
 * @return int
 * @removed 5.4
 */
#[Deprecated(since: '5.3')]
function mysqli_param_count(mysqli_stmt $statement): int {}

/**
 * Alias for <b>mysqli_stmt_result_metadata</b>
 * @link https://php.net/manual/en/function.mysqli-get-metadata.php
 * @param mysqli_stmt $statement
 * @return mysqli_result|false Returns a result object or FALSE if an error occurred
 * @removed 5.4
 */
#[Deprecated(since: '5.3')]
function mysqli_get_metadata(mysqli_stmt $statement): false|mysqli_result {}

/**
 * Alias for <b>mysqli_stmt_send_long_data</b>
 * @link https://php.net/manual/en/function.mysqli-send-long-data.php
 * @param mysqli_stmt $statement
 * @param int $param_num
 * @param string $data
 * @return bool
 * @removed 5.4
 */
#[Deprecated(since: '5.3')]
function mysqli_send_long_data(mysqli_stmt $statement, int $param_num, string $data): bool {}

/**
 * Alias of <b>mysqli_options</b>
 * @link https://php.net/manual/en/function.mysqli-set-opt.php
 * @param mysqli $mysql A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $option
 * @param string|int $value
 * @return bool
 */
function mysqli_set_opt(
    #[PhpStormStubsElementAvailable(from: '8.0')] mysqli $mysql,
    #[PhpStormStubsElementAvailable(from: '8.0')] int $option,
    #[PhpStormStubsElementAvailable(from: '8.0')] $value
): bool {}

/**
 * <p>
 * Read options from the named group from my.cnf
 * or the file specified with <b>MYSQLI_READ_DEFAULT_FILE</b>
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_READ_DEFAULT_GROUP', 5);

/**
 * <p>
 * Read options from the named option file instead of from my.cnf
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_READ_DEFAULT_FILE', 4);

/**
 * <p>
 * Connect timeout in seconds
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_OPT_CONNECT_TIMEOUT', 0);

/**
 * <p>
 * Enables command LOAD LOCAL INFILE
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_OPT_LOCAL_INFILE', 8);

/**
 * <p>
 * RSA public key file used with the SHA-256 based authentication.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_SERVER_PUBLIC_KEY', 35);

/**
 * <p>
 * Command to execute when connecting to MySQL server. Will automatically be re-executed when reconnecting.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_INIT_COMMAND', 3);
define('MYSQLI_OPT_NET_CMD_BUFFER_SIZE', 202);
define('MYSQLI_OPT_NET_READ_BUFFER_SIZE', 203);
define('MYSQLI_OPT_INT_AND_FLOAT_NATIVE', 201);

/**
 * <p>
 * Use SSL (encrypted protocol). This option should not be set by application programs;
 * it is set internally in the MySQL client library
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_CLIENT_SSL', 2048);

/**
 * <p>
 * Use compression protocol
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_CLIENT_COMPRESS', 32);

/**
 * <p>
 * Allow interactive_timeout seconds
 * (instead of wait_timeout seconds) of inactivity before
 * closing the connection. The client's session
 * wait_timeout variable will be set to
 * the value of the session interactive_timeout variable.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_CLIENT_INTERACTIVE', 1024);

/**
 * <p>
 * Allow spaces after function names. Makes all functions names reserved words.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_CLIENT_IGNORE_SPACE', 256);

/**
 * <p>
 * Don't allow the db_name.tbl_name.col_name syntax.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_CLIENT_NO_SCHEMA', 16);
define('MYSQLI_CLIENT_FOUND_ROWS', 2);

/**
 * <p>
 * For using buffered resultsets
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_STORE_RESULT', 0);

/**
 * <p>
 * For using unbuffered resultsets
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_USE_RESULT', 1);
define('MYSQLI_ASYNC', 8);

/**
 * <p>
 * Columns are returned into the array having the fieldname as the array index.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_ASSOC', 1);

/**
 * <p>
 * Columns are returned into the array having an enumerated index.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_NUM', 2);

/**
 * <p>
 * Columns are returned into the array having both a numerical index and the fieldname as the associative index.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_BOTH', 3);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_STMT_ATTR_UPDATE_MAX_LENGTH', 0);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_STMT_ATTR_CURSOR_TYPE', 1);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_CURSOR_TYPE_NO_CURSOR', 0);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_CURSOR_TYPE_READ_ONLY', 1);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_CURSOR_TYPE_FOR_UPDATE', 2);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_CURSOR_TYPE_SCROLLABLE', 4);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_STMT_ATTR_PREFETCH_ROWS', 2);

/**
 * <p>
 * Indicates that a field is defined as NOT NULL
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_NOT_NULL_FLAG', 1);

/**
 * <p>
 * Field is part of a primary index
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_PRI_KEY_FLAG', 2);

/**
 * <p>
 * Field is part of a unique index.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_UNIQUE_KEY_FLAG', 4);

/**
 * <p>
 * Field is part of an index.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_MULTIPLE_KEY_FLAG', 8);

/**
 * <p>
 * Field is defined as BLOB
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_BLOB_FLAG', 16);

/**
 * <p>
 * Field is defined as UNSIGNED
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_UNSIGNED_FLAG', 32);

/**
 * <p>
 * Field is defined as ZEROFILL
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_ZEROFILL_FLAG', 64);

/**
 * <p>
 * Field is defined as AUTO_INCREMENT
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_AUTO_INCREMENT_FLAG', 512);

/**
 * <p>
 * Field is defined as TIMESTAMP
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TIMESTAMP_FLAG', 1024);

/**
 * <p>
 * Field is defined as SET
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_SET_FLAG', 2048);

/**
 * <p>
 * Field is defined as NUMERIC
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_NUM_FLAG', 32768);

/**
 * <p>
 * Field is part of an multi-index
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_PART_KEY_FLAG', 16384);

/**
 * <p>
 * Field is part of GROUP BY
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_GROUP_FLAG', 32768);

/**
 * <p>
 * Field is defined as ENUM. Available since PHP 5.3.0.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_ENUM_FLAG', 256);
define('MYSQLI_BINARY_FLAG', 128);
define('MYSQLI_NO_DEFAULT_VALUE_FLAG', 4096);
define('MYSQLI_ON_UPDATE_NOW_FLAG', 8192);

define('MYSQLI_TRANS_START_READ_ONLY', 4);
define('MYSQLI_TRANS_START_READ_WRITE', 2);
define('MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT', 1);
/**
 * <p>
 * Field is defined as DECIMAL
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_DECIMAL', 0);

/**
 * <p>
 * Field is defined as TINYINT
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_TINY', 1);

/**
 * <p>
 * Field is defined as SMALLINT
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_SHORT', 2);

/**
 * <p>
 * Field is defined as INT
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_LONG', 3);

/**
 * <p>
 * Field is defined as FLOAT
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_FLOAT', 4);

/**
 * <p>
 * Field is defined as DOUBLE
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_DOUBLE', 5);

/**
 * <p>
 * Field is defined as DEFAULT NULL
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_NULL', 6);

/**
 * <p>
 * Field is defined as TIMESTAMP
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_TIMESTAMP', 7);

/**
 * <p>
 * Field is defined as BIGINT
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_LONGLONG', 8);

/**
 * <p>
 * Field is defined as MEDIUMINT
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_INT24', 9);

/**
 * <p>
 * Field is defined as DATE
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_DATE', 10);

/**
 * <p>
 * Field is defined as TIME
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_TIME', 11);

/**
 * <p>
 * Field is defined as DATETIME
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_DATETIME', 12);

/**
 * <p>
 * Field is defined as YEAR
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_YEAR', 13);

/**
 * <p>
 * Field is defined as DATE
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_NEWDATE', 14);

/**
 * <p>
 * Field is defined as ENUM
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_ENUM', 247);

/**
 * <p>
 * Field is defined as SET
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_SET', 248);

/**
 * <p>
 * Field is defined as TINYBLOB
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_TINY_BLOB', 249);

/**
 * <p>
 * Field is defined as MEDIUMBLOB
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_MEDIUM_BLOB', 250);

/**
 * <p>
 * Field is defined as LONGBLOB
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_LONG_BLOB', 251);

/**
 * <p>
 * Field is defined as BLOB
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_BLOB', 252);

/**
 * <p>
 * Field is defined as VARCHAR
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_VAR_STRING', 253);

/**
 * <p>
 * Field is defined as STRING
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_STRING', 254);

/**
 * <p>
 * Field is defined as CHAR
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_CHAR', 1);

/**
 * <p>
 * Field is defined as INTERVAL
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_INTERVAL', 247);

/**
 * <p>
 * Field is defined as GEOMETRY
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_GEOMETRY', 255);

/**
 * <p>
 * Precision math DECIMAL or NUMERIC field (MySQL 5.0.3 and up)
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_NEWDECIMAL', 246);

/**
 * <p>
 * Field is defined as BIT (MySQL 5.0.3 and up)
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_TYPE_BIT', 16);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_SET_CHARSET_NAME', 7);

/**
 * <p>
 * No more data available for bind variable
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_NO_DATA', 100);

/**
 * <p>
 * Data truncation occurred. Available since PHP 5.1.0 and MySQL 5.0.5.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_DATA_TRUNCATED', 101);

/**
 * <p>
 * Report if no index or bad index was used in a query.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REPORT_INDEX', 4);

/**
 * <p>
 * Report errors from mysqli function calls.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REPORT_ERROR', 1);

/**
 * <p>
 * Throw a mysqli_sql_exception for errors instead of warnings.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REPORT_STRICT', 2);

/**
 * <p>
 * Set all options on (report all).
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REPORT_ALL', 255);

/**
 * <p>
 * Turns reporting off.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REPORT_OFF', 0);

/**
 * <p>
 * Is set to 1 if <b>mysqli_debug</b> functionality is enabled.
 * </p>
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_DEBUG_TRACE_ENABLED', 0);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_SERVER_QUERY_NO_GOOD_INDEX_USED', 16);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_SERVER_QUERY_NO_INDEX_USED', 32);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REFRESH_GRANT', 1);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REFRESH_LOG', 2);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REFRESH_TABLES', 4);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REFRESH_HOSTS', 8);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REFRESH_STATUS', 16);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REFRESH_THREADS', 32);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REFRESH_SLAVE', 64);

/**
 * @link https://php.net/manual/en/mysqli.constants.php
 */
define('MYSQLI_REFRESH_MASTER', 128);

define('MYSQLI_SERVER_QUERY_WAS_SLOW', 2048);
define('MYSQLI_REFRESH_BACKUP_LOG', 2097152);

// End of mysqli v.0.1

/** @link https://php.net/manual/en/mysqli.constants.php */
define('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT', 21);
/** @link https://php.net/manual/en/mysqli.constants.php */
define('MYSQLI_SET_CHARSET_DIR', 6);
/** @link https://php.net/manual/en/mysqli.constants.php */
define('MYSQLI_SERVER_PS_OUT_PARAMS', 4096);

define('MYSQLI_CLIENT_SSL_VERIFY_SERVER_CERT', 1073741824);

define('MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT', 64);
define('MYSQLI_CLIENT_CAN_HANDLE_EXPIRED_PASSWORDS', 4194304);
define('MYSQLI_OPT_CAN_HANDLE_EXPIRED_PASSWORDS', 37);
define('MYSQLI_OPT_READ_TIMEOUT', 11);
define('MYSQLI_STORE_RESULT_COPY_DATA', 16);
define('MYSQLI_TYPE_JSON', 245);
define('MYSQLI_TRANS_COR_AND_CHAIN', 1);
define('MYSQLI_TRANS_COR_AND_NO_CHAIN', 2);
define('MYSQLI_TRANS_COR_RELEASE', 4);
define('MYSQLI_TRANS_COR_NO_RELEASE', 8);
define('MYSQLI_OPT_LOAD_DATA_LOCAL_DIR', 43);
define('MYSQLI_REFRESH_REPLICA', 64);
/**
 * @since 8.1
 */
define('MYSQLI_IS_MARIADB', 0);
