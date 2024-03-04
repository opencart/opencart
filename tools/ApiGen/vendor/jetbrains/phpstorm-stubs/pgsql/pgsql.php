<?php

// Start of pgsql v.
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;

/**
 * Open a PostgreSQL connection
 * @link https://php.net/manual/en/function.pg-connect.php
 * @param string $connection_string <p>
 * The <i>connection_string</i> can be empty to use all default parameters, or it
 * can contain one or more parameter settings separated by whitespace.
 * Each parameter setting is in the form keyword = value. Spaces around
 * the equal sign are optional. To write an empty value or a value
 * containing spaces, surround it with single quotes, e.g., keyword =
 * 'a value'. Single quotes and backslashes within the value must be
 * escaped with a backslash, i.e., \' and \\.
 * </p>
 * <p>
 * The currently recognized parameter keywords are:
 * <i>host</i>, <i>hostaddr</i>, <i>port</i>,
 * <i>dbname</i> (defaults to value of <i>user</i>),
 * <i>user</i>,
 * <i>password</i>, <i>connect_timeout</i>,
 * <i>options</i>, <i>tty</i> (ignored), <i>sslmode</i>,
 * <i>requiressl</i> (deprecated in favor of <i>sslmode</i>), and
 * <i>service</i>. Which of these arguments exist depends
 * on your PostgreSQL version.
 * </p>
 * <p>
 * The <i>options</i> parameter can be used to set command line parameters
 * to be invoked by the server.
 * </p>
 * @param int $flags <p>
 * If <b>PGSQL_CONNECT_FORCE_NEW</b> is passed, then a new connection
 * is created, even if the <i>connection_string</i> is identical to
 * an existing connection.
 * </p>
 * @return resource|false PostgreSQL connection resource on success, <b>FALSE</b> on failure.
 */
#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|false'], default: 'resource|false')]
function pg_connect(
    string $connection_string,
    int $flags = 0,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $host = '',
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $port = '',
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $options = '',
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $tty = '',
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $dbname = '',
) {}

/**
 * Open a persistent PostgreSQL connection
 * @link https://php.net/manual/en/function.pg-pconnect.php
 * @param string $connection_string <p>
 * The <i>connection_string</i> can be empty to use all default parameters, or it
 * can contain one or more parameter settings separated by whitespace.
 * Each parameter setting is in the form keyword = value. Spaces around
 * the equal sign are optional. To write an empty value or a value
 * containing spaces, surround it with single quotes, e.g., keyword =
 * 'a value'. Single quotes and backslashes within the value must be
 * escaped with a backslash, i.e., \' and \\.
 * </p>
 * <p>
 * The currently recognized parameter keywords are:
 * <i>host</i>, <i>hostaddr</i>, <i>port</i>,
 * <i>dbname</i>, <i>user</i>,
 * <i>password</i>, <i>connect_timeout</i>,
 * <i>options</i>, <i>tty</i> (ignored), <i>sslmode</i>,
 * <i>requiressl</i> (deprecated in favor of <i>sslmode</i>), and
 * <i>service</i>. Which of these arguments exist depends
 * on your PostgreSQL version.
 * </p>
 * @param int $flags <p>
 * If <b>PGSQL_CONNECT_FORCE_NEW</b> is passed, then a new connection
 * is created, even if the <i>connection_string</i> is identical to
 * an existing connection.
 * </p>
 * @return resource|false PostgreSQL connection resource on success, <b>FALSE</b> on failure.
 */
#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|false'], default: 'resource|false')]
function pg_pconnect(
    string $connection_string,
    #[PhpStormStubsElementAvailable(from: '8.0')] int $flags = 0,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $host = '',
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $port = '',
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $options = '',
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $tty = '',
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $dbname = '',
) {}

/**
 * Closes a PostgreSQL connection
 * @link https://php.net/manual/en/function.pg-close.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_close(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): bool {}

/**
 * Poll the status of an in-progress asynchronous PostgreSQL connection attempt.
 * @link https://php.net/manual/en/function.pg-connect-poll.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @return int <b>PGSQL_POLLING_FAILED</b>, <b>PGSQL_POLLING_READING</b>, <b>PGSQL_POLLING_WRITING</b>,
 * <b>PGSQL_POLLING_OK</b>, or <b>PGSQL_POLLING_ACTIVE</b>.
 * @since 5.6
 */
function pg_connect_poll(
    #[PhpStormStubsElementAvailable(from: '5.6', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection
): int {}

/**
 * Get connection status
 * @link https://php.net/manual/en/function.pg-connection-status.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @return int <b>PGSQL_CONNECTION_OK</b> or
 * <b>PGSQL_CONNECTION_BAD</b>.
 */
function pg_connection_status(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection): int {}

/**
 * Get connection is busy or not
 * @link https://php.net/manual/en/function.pg-connection-busy.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @return bool <b>TRUE</b> if the connection is busy, <b>FALSE</b> otherwise.
 */
function pg_connection_busy(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection): bool {}

/**
 * Reset connection (reconnect)
 * @link https://php.net/manual/en/function.pg-connection-reset.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_connection_reset(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection): bool {}

/**
 * Get a read only handle to the socket underlying a PostgreSQL connection
 * @link https://php.net/manual/en/function.pg-socket.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @return resource|false A socket resource on success or <b>FALSE</b> on failure.
 * @since 5.6
 */
function pg_socket(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection) {}

/**
 * Returns the host name associated with the connection
 * @link https://php.net/manual/en/function.pg-host.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return string|false A string containing the name of the host the
 * <i>connection</i> is to, or <b>FALSE</b> on error.
 */
function pg_host(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): string {}

/**
 * Get the database name
 * @link https://php.net/manual/en/function.pg-dbname.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return string|false A string containing the name of the database the
 * <i>connection</i> is to, or <b>FALSE</b> on error.
 */
function pg_dbname(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): string {}

/**
 * Return the port number associated with the connection
 * @link https://php.net/manual/en/function.pg-port.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return string A string containing the port number of the database server the connection is to, or empty string on error.
 */
function pg_port(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): string {}

/**
 * Return the TTY name associated with the connection
 * @link https://php.net/manual/en/function.pg-tty.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return string A string containing the debug TTY of
 * the <i>connection</i>, or <b>FALSE</b> on error.
 */
function pg_tty(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): string {}

/**
 * Get the options associated with the connection
 * @link https://php.net/manual/en/function.pg-options.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return string A string containing the <i>connection</i>
 * options, or <b>FALSE</b> on error.
 */
function pg_options(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): string {}

/**
 * Returns an array with client, protocol and server version (when available)
 * @link https://php.net/manual/en/function.pg-version.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return array an array with client, protocol
 * and server keys and values (if available). Returns
 * <b>FALSE</b> on error or invalid connection.
 */
#[ArrayShape(["client" => "string", "protocol" => "int", "server" => "string"])]
function pg_version(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): array {}

/**
 * Ping database connection
 * @link https://php.net/manual/en/function.pg-ping.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_ping(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): bool {}

/**
 * Looks up a current parameter setting of the server.
 * @link https://php.net/manual/en/function.pg-parameter-status.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $name <p>
 * Possible <i>param_name</i> values include server_version,
 * server_encoding, client_encoding,
 * is_superuser, session_authorization,
 * DateStyle, TimeZone, and
 * integer_datetimes.
 * </p>
 * @return string|false A string containing the value of the parameter, <b>FALSE</b> on failure or invalid
 * <i>param_name</i>.
 */
function pg_parameter_status(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection, string $name = null): string|false {}

/**
 * Returns the current in-transaction status of the server.
 * @link https://php.net/manual/en/function.pg-transaction-status.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @return int The status can be <b>PGSQL_TRANSACTION_IDLE</b> (currently idle),
 * <b>PGSQL_TRANSACTION_ACTIVE</b> (a command is in progress),
 * <b>PGSQL_TRANSACTION_INTRANS</b> (idle, in a valid transaction block),
 * or <b>PGSQL_TRANSACTION_INERROR</b> (idle, in a failed transaction block).
 * <b>PGSQL_TRANSACTION_UNKNOWN</b> is reported if the connection is bad.
 * <b>PGSQL_TRANSACTION_ACTIVE</b> is reported only when a query
 * has been sent to the server and not yet completed.
 */
function pg_transaction_status(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection): int {}

/**
 * Execute a query
 * @link https://php.net/manual/en/function.pg-query.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $query <p>
 * The SQL statement or statements to be executed. When multiple statements are passed to the function,
 * they are automatically executed as one transaction, unless there are explicit BEGIN/COMMIT commands
 * included in the query string. However, using multiple transactions in one function call is not recommended.
 * </p>
 * <p>
 * String interpolation of user-supplied data is extremely dangerous and is
 * likely to lead to SQL
 * injection vulnerabilities. In most cases
 * <b>pg_query_params</b> should be preferred, passing
 * user-supplied values as parameters rather than substituting them into
 * the query string.
 * </p>
 * <p>
 * Any user-supplied data substituted directly into a query string should
 * be properly escaped.
 * </p>
 * @return resource|false A query result resource on success or <b>FALSE</b> on failure.
 */
#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result|false'], default: 'resource|false')]
function pg_query(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $query = null
) {}

/**
 * Submits a command to the server and waits for the result, with the ability to pass parameters separately from the SQL command text.
 * @link https://php.net/manual/en/function.pg-query-params.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $query <p>
 * The parameterized SQL statement. Must contain only a single statement.
 * (multiple statements separated by semi-colons are not allowed.) If any parameters
 * are used, they are referred to as $1, $2, etc.
 * </p>
 * <p>
 * User-supplied values should always be passed as parameters, not
 * interpolated into the query string, where they form possible
 * SQL injection
 * attack vectors and introduce bugs when handling data containing quotes.
 * If for some reason you cannot use a parameter, ensure that interpolated
 * values are properly escaped.
 * </p>
 * @param array $params <p>
 * An array of parameter values to substitute for the $1, $2, etc. placeholders
 * in the original prepared query string. The number of elements in the array
 * must match the number of placeholders.
 * </p>
 * <p>
 * Values intended for bytea fields are not supported as
 * parameters. Use <b>pg_escape_bytea</b> instead, or use the
 * large object functions.
 * </p>
 * @return resource|false A query result resource on success or <b>FALSE</b> on failure.
 */
#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result|false'], default: 'resource|false')]
function pg_query_params(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $query = '',
    #[PhpStormStubsElementAvailable(from: '8.0')] $query,
    array $params = null
) {}

/**
 * Submits a request to create a prepared statement with the
 * given parameters, and waits for completion.
 * @link https://php.net/manual/en/function.pg-prepare.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $statement_name <p>
 * The name to give the prepared statement. Must be unique per-connection. If
 * "" is specified, then an unnamed statement is created, overwriting any
 * previously defined unnamed statement.
 * </p>
 * @param string $query <p>
 * The parameterized SQL statement. Must contain only a single statement.
 * (multiple statements separated by semi-colons are not allowed.) If any parameters
 * are used, they are referred to as $1, $2, etc.
 * </p>
 * @return resource|false A query result resource on success or <b>FALSE</b> on failure.
 */
#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result|false'], default: 'resource|false')]
function pg_prepare(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $statement_name = '',
    #[PhpStormStubsElementAvailable(from: '8.0')] string $statement_name,
    string $query = null
) {}

/**
 * Sends a request to execute a prepared statement with given parameters, and waits for the result.
 * @link https://php.net/manual/en/function.pg-execute.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $statement_name <p>
 * The name of the prepared statement to execute. if
 * "" is specified, then the unnamed statement is executed. The name must have
 * been previously prepared using <b>pg_prepare</b>,
 * <b>pg_send_prepare</b> or a PREPARE SQL
 * command.
 * </p>
 * @param array $params <p>
 * An array of parameter values to substitute for the $1, $2, etc. placeholders
 * in the original prepared query string. The number of elements in the array
 * must match the number of placeholders.
 * </p>
 * <p>
 * Elements are converted to strings by calling this function.
 * </p>
 * @return resource|false A query result resource on success or <b>FALSE</b> on failure.
 */
#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result|false'], default: 'resource|false')]
function pg_execute(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] string $statement_name = '',
    #[PhpStormStubsElementAvailable(from: '8.0')] $statement_name,
    array $params = null
) {}

/**
 * Sends asynchronous query
 * @link https://php.net/manual/en/function.pg-send-query.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @param string $query <p>
 * The SQL statement or statements to be executed.
 * </p>
 * <p>
 * Data inside the query should be properly escaped.
 * </p>
 * @return int|bool <b>TRUE</b> on success or <b>FALSE</b> on failure.</p>
 * <p>
 * Use <b>pg_get_result</b> to determine the query result.
 */
function pg_send_query(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $query
): int|bool {}

/**
 * Submits a command and separate parameters to the server without waiting for the result(s).
 * @link https://php.net/manual/en/function.pg-send-query-params.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @param string $query <p>
 * The parameterized SQL statement. Must contain only a single statement.
 * (multiple statements separated by semi-colons are not allowed.) If any parameters
 * are used, they are referred to as $1, $2, etc.
 * </p>
 * @param array $params <p>
 * An array of parameter values to substitute for the $1, $2, etc. placeholders
 * in the original prepared query string. The number of elements in the array
 * must match the number of placeholders.
 * </p>
 * @return int|bool <b>TRUE</b> on success or <b>FALSE</b> on failure.</p>
 * <p>
 * Use <b>pg_get_result</b> to determine the query result.
 */
function pg_send_query_params(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $query,
    array $params
): int|bool {}

/**
 * Sends a request to create a prepared statement with the given parameters, without waiting for completion.
 * @link https://php.net/manual/en/function.pg-send-prepare.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $statement_name <p>
 * The name to give the prepared statement. Must be unique per-connection. If
 * "" is specified, then an unnamed statement is created, overwriting any
 * previously defined unnamed statement.
 * </p>
 * @param string $query <p>
 * The parameterized SQL statement. Must contain only a single statement.
 * (multiple statements separated by semi-colons are not allowed.) If any parameters
 * are used, they are referred to as $1, $2, etc.
 * </p>
 * @return int|bool <b>TRUE</b> on success, <b>FALSE</b> on failure. Use <b>pg_get_result</b>
 * to determine the query result.
 */
function pg_send_prepare(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $statement_name,
    string $query
): int|bool {}

/**
 * Sends a request to execute a prepared statement with given parameters, without waiting for the result(s).
 * @link https://php.net/manual/en/function.pg-send-execute.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $statement_name <p>
 * The name of the prepared statement to execute. if
 * "" is specified, then the unnamed statement is executed. The name must have
 * been previously prepared using <b>pg_prepare</b>,
 * <b>pg_send_prepare</b> or a PREPARE SQL
 * command.
 * </p>
 * @param array $params <p>
 * An array of parameter values to substitute for the $1, $2, etc. placeholders
 * in the original prepared query string. The number of elements in the array
 * must match the number of placeholders.
 * </p>
 * @return int|bool <b>TRUE</b> on success, <b>FALSE</b> on failure. Use <b>pg_get_result</b>
 * to determine the query result.
 */
function pg_send_execute(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $statement_name,
    array $params
): int|bool {}

/**
 * Cancel an asynchronous query
 * @link https://php.net/manual/en/function.pg-cancel-query.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_cancel_query(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection): bool {}

/**
 * Returns values from a result resource
 * @link https://php.net/manual/en/function.pg-fetch-result.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $row <p>
 * Row number in result to fetch. Rows are numbered from 0 upwards. If omitted,
 * next row is fetched.
 * </p>
 * @param mixed $field <p>
 * A string representing the name of the field (column) to fetch, otherwise
 * an int representing the field number to fetch. Fields are
 * numbered from 0 upwards.
 * </p>
 * @return string|false|null Boolean is returned as &#x00022;t&#x00022; or &#x00022;f&#x00022;. All
 * other types, including arrays are returned as strings formatted
 * in the same default PostgreSQL manner that you would see in the
 * psql program. Database NULL
 * values are returned as <b>NULL</b>.
 * </p>
 * <p>
 * <b>FALSE</b> is returned if <i>row</i> exceeds the number
 * of rows in the set, or on any other error.
 */
function pg_fetch_result(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $row = 0,
    #[PhpStormStubsElementAvailable(from: '8.0')] $row,
    string|int $field = null
): string|false|null {}

/**
 * Get a row as an enumerated array
 * @link https://php.net/manual/en/function.pg-fetch-row.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $row [optional] <p>
 * Row number in result to fetch. Rows are numbered from 0 upwards. If
 * omitted or <b>NULL</b>, the next row is fetched.
 * </p>
 * @param int $mode [optional]
 * @return array|false An array, indexed from 0 upwards, with each value
 * represented as a string. Database NULL
 * values are returned as <b>NULL</b>.
 * </p>
 * <p>
 * <b>FALSE</b> is returned if <i>row</i> exceeds the number
 * of rows in the set, there are no more rows, or on any other error.
 */
function pg_fetch_row(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, ?int $row = null, int $mode = 2): array|false {}

/**
 * Fetch a row as an associative array
 * @link https://php.net/manual/en/function.pg-fetch-assoc.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $row [optional] <p>
 * Row number in result to fetch. Rows are numbered from 0 upwards. If
 * omitted or <b>NULL</b>, the next row is fetched.
 * </p>
 * @return array|false An array indexed associatively (by field name).
 * Each value in the array is represented as a
 * string. Database NULL
 * values are returned as <b>NULL</b>.
 * </p>
 * <p>
 * <b>FALSE</b> is returned if <i>row</i> exceeds the number
 * of rows in the set, there are no more rows, or on any other error.
 */
function pg_fetch_assoc(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, ?int $row = null): array|false {}

/**
 * Fetch a row as an array
 * @link https://php.net/manual/en/function.pg-fetch-array.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $row [optional] <p>
 * Row number in result to fetch. Rows are numbered from 0 upwards. If
 * omitted or <b>NULL</b>, the next row is fetched.
 * </p>
 * @param int $mode [optional] <p>
 * An optional parameter that controls
 * how the returned array is indexed.
 * <i>result_type</i> is a constant and can take the
 * following values: <b>PGSQL_ASSOC</b>,
 * <b>PGSQL_NUM</b> and <b>PGSQL_BOTH</b>.
 * Using <b>PGSQL_NUM</b>, <b>pg_fetch_array</b>
 * will return an array with numerical indices, using
 * <b>PGSQL_ASSOC</b> it will return only associative indices
 * while <b>PGSQL_BOTH</b>, the default, will return both
 * numerical and associative indices.
 * </p>
 * @return array|false An array indexed numerically (beginning with 0) or
 * associatively (indexed by field name), or both.
 * Each value in the array is represented as a
 * string. Database NULL
 * values are returned as <b>NULL</b>.
 * </p>
 * <p>
 * <b>FALSE</b> is returned if <i>row</i> exceeds the number
 * of rows in the set, there are no more rows, or on any other error.
 */
function pg_fetch_array(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, ?int $row = null, int $mode = PGSQL_BOTH): array|false {}

/**
 * Fetch a row as an object
 * @link https://php.net/manual/en/function.pg-fetch-object.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int|null $row [optional] <p>
 * Row number in result to fetch. Rows are numbered from 0 upwards. If
 * omitted or <b>NULL</b>, the next row is fetched.
 * </p>
 * @param string $class [optional] <p>
 * Ignored and deprecated.
 * </p>
 * @param array $constructor_args [optional] <p>
 * </p>
 * @return object|false An object with one attribute for each field
 * name in the result. Database NULL
 * values are returned as <b>NULL</b>.
 * </p>
 * <p>
 * <b>FALSE</b> is returned if <i>row</i> exceeds the number
 * of rows in the set, there are no more rows, or on any other error.
 */
function pg_fetch_object(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result,
    ?int $row = null,
    string $class = 'stdClass',
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $l = null,
    array $constructor_args = []
): object|false {}

/**
 * Fetches all rows from a result as an array
 * @link https://php.net/manual/en/function.pg-fetch-all.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $mode [optional] <p>
 * An optional parameter that controls
 * how the returned array is indexed.
 * <i>result_type</i> is a constant and can take the
 * following values: <b>PGSQL_ASSOC</b>,
 * <b>PGSQL_NUM</b> and <b>PGSQL_BOTH</b>.
 * Using <b>PGSQL_NUM</b>, <b>pg_fetch_array</b>
 * will return an array with numerical indices, using
 * <b>PGSQL_ASSOC</b> it will return only associative indices
 * while <b>PGSQL_BOTH</b>, the default, will return both
 * numerical and associative indices.
 * </p>
 * @return array An array with all rows in the result. Each row is an array
 * of field values indexed by field name.
 * </p>
 * <p>
 * <b>FALSE</b> is returned if there are no rows in the result, or on any
 * other error.
 */
function pg_fetch_all(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, int $mode = PGSQL_ASSOC): array {}

/**
 * Fetches all rows in a particular result column as an array
 * @link https://php.net/manual/en/function.pg-fetch-all-columns.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $field [optional] <p>
 * Column number, zero-based, to be retrieved from the result resource. Defaults
 * to the first column if not specified.
 * </p>
 * @return array An array with all values in the result column.
 * <p>
 * <b>FALSE</b> is returned if <i>column</i> is larger than the number
 * of columns in the result, or on any other error.
 * </p>
 */
function pg_fetch_all_columns(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, int $field = 0): array {}

/**
 * Returns number of affected records (tuples)
 * @link https://php.net/manual/en/function.pg-affected-rows.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @return int The number of rows affected by the query. If no tuple is
 * affected, it will return 0.
 */
function pg_affected_rows(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result): int {}

/**
 * Get asynchronous query result
 * @link https://php.net/manual/en/function.pg-get-result.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @return resource|false The result resource, or <b>FALSE</b> if no more results are available.
 */
#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result|false'], default: 'resource|false')]
function pg_get_result(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection) {}

/**
 * Set internal row offset in result resource
 * @link https://php.net/manual/en/function.pg-result-seek.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $row <p>
 * Row to move the internal offset to in the <i>result</i> resource.
 * Rows are numbered starting from zero.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_result_seek(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, int $row): bool {}

/**
 * Get status of query result
 * @link https://php.net/manual/en/function.pg-result-status.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $mode [optional] <p>
 * Either <b>PGSQL_STATUS_LONG</b> to return the numeric status
 * of the <i>result</i>, or <b>PGSQL_STATUS_STRING</b>
 * to return the command tag of the <i>result</i>.
 * If not specified, <b>PGSQL_STATUS_LONG</b> is the default.
 * </p>
 * @return string|int Possible return values are <b>PGSQL_EMPTY_QUERY</b>,
 * <b>PGSQL_COMMAND_OK</b>, <b>PGSQL_TUPLES_OK</b>, <b>PGSQL_COPY_OUT</b>,
 * <b>PGSQL_COPY_IN</b>, <b>PGSQL_BAD_RESPONSE</b>, <b>PGSQL_NONFATAL_ERROR</b> and
 * <b>PGSQL_FATAL_ERROR</b> if <b>PGSQL_STATUS_LONG</b> is
 * specified. Otherwise, a string containing the PostgreSQL command tag is returned.
 */
function pg_result_status(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, int $mode = PGSQL_STATUS_LONG): string|int {}

/**
 * Free result memory
 * @link https://php.net/manual/en/function.pg-free-result.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_free_result(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result): bool {}

/**
 * Returns the last row's OID
 * @link https://php.net/manual/en/function.pg-last-oid.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @return string|int|false A string containing the OID assigned to the most recently inserted
 * row in the specified <i>connection</i>, or <b>FALSE</b> on error or
 * no available OID.
 */
function pg_last_oid(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result): string|int|false {}

/**
 * Returns the number of rows in a result
 * @link https://php.net/manual/en/function.pg-num-rows.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @return int The number of rows in the result. On error, -1 is returned.
 */
function pg_num_rows(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result): int {}

/**
 * Returns the number of fields in a result
 * @link https://php.net/manual/en/function.pg-num-fields.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @return int The number of fields (columns) in the result. On error, -1 is returned.
 */
function pg_num_fields(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result): int {}

/**
 * Returns the name of a field
 * @link https://php.net/manual/en/function.pg-field-name.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $field <p>
 * Field number, starting from 0.
 * </p>
 * @return string|false The field name, or <b>FALSE</b> on error.
 */
function pg_field_name(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, int $field): string {}

/**
 * Returns the field number of the named field
 * @link https://php.net/manual/en/function.pg-field-num.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param string $field <p>
 * The name of the field.
 * </p>
 * @return int The field number (numbered from 0), or -1 on error.
 */
function pg_field_num(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, string $field): int {}

/**
 * Returns the internal storage size of the named field
 * @link https://php.net/manual/en/function.pg-field-size.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $field <p>
 * Field number, starting from 0.
 * </p>
 * @return int The internal field storage size (in bytes). -1 indicates a variable
 * length field. <b>FALSE</b> is returned on error.
 */
function pg_field_size(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, int $field): int {}

/**
 * Returns the type name for the corresponding field number
 * @link https://php.net/manual/en/function.pg-field-type.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $field <p>
 * Field number, starting from 0.
 * </p>
 * @return string|false A string containing the base name of the field's type, or <b>FALSE</b>
 * on error.
 */
function pg_field_type(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, int $field): string {}

/**
 * Returns the type ID (OID) for the corresponding field number
 * @link https://php.net/manual/en/function.pg-field-type-oid.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $field <p>
 * Field number, starting from 0.
 * </p>
 * @return string|int The OID of the field's base type. <b>FALSE</b> is returned on error.
 */
function pg_field_type_oid(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, int $field): string|int {}

/**
 * Returns the printed length
 * @link https://php.net/manual/en/function.pg-field-prtlen.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $row
 * @param mixed $field
 * @return int|false The field printed length, or <b>FALSE</b> on error.
 */
function pg_field_prtlen(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $row = 0,
    #[PhpStormStubsElementAvailable(from: '8.0')] $row,
    string|int $field = null
): int|false {}

/**
 * Test if a field is SQL NULL
 * @link https://php.net/manual/en/function.pg-field-is-null.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $row <p>
 * Row number in result to fetch. Rows are numbered from 0 upwards. If omitted,
 * current row is fetched.
 * </p>
 * @param mixed $field <p>
 * Field number (starting from 0) as an integer or
 * the field name as a string.
 * </p>
 * @return int|false 1 if the field in the given row is SQL NULL, 0
 * if not. <b>FALSE</b> is returned if the row is out of range, or upon any other error.
 */
function pg_field_is_null(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $row = 0,
    #[PhpStormStubsElementAvailable(from: '8.0')] $row,
    string|int $field = null
): int|false {}

/**
 * Returns the name or oid of the tables field
 * @link https://php.net/manual/en/function.pg-field-table.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @param int $field <p>
 * Field number, starting from 0.
 * </p>
 * @param bool $oid_only [optional] <p>
 * By default the tables name that field belongs to is returned but
 * if <i>oid_only</i> is set to <b>TRUE</b>, then the
 * oid will instead be returned.
 * </p>
 * @return string|int|false On success either the fields table name or oid. Or, <b>FALSE</b> on failure.
 */
function pg_field_table(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, int $field, bool $oid_only = false): string|int|false {}

/**
 * Gets SQL NOTIFY message
 * @link https://php.net/manual/en/function.pg-get-notify.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @param int $mode [optional] <p>
 * An optional parameter that controls
 * how the returned array is indexed.
 * <i>result_type</i> is a constant and can take the
 * following values: <b>PGSQL_ASSOC</b>,
 * <b>PGSQL_NUM</b> and <b>PGSQL_BOTH</b>.
 * Using <b>PGSQL_NUM</b>, <b>pg_get_notify</b>
 * will return an array with numerical indices, using
 * <b>PGSQL_ASSOC</b> it will return only associative indices
 * while <b>PGSQL_BOTH</b>, the default, will return both
 * numerical and associative indices.
 * </p>
 * @return array|false An array containing the NOTIFY message name and backend PID.
 * Otherwise if no NOTIFY is waiting, then <b>FALSE</b> is returned.
 */
#[ArrayShape(["message" => "string", "pid" => "int", "payload" => "string"])]
function pg_get_notify(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    int $mode = 1
): array|false {}

/**
 * Gets the backend's process ID
 * @link https://php.net/manual/en/function.pg-get-pid.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @return int The backend database process ID.
 */
function pg_get_pid(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
): int {}

/**
 * Get error message associated with result
 * @link https://php.net/manual/en/function.pg-result-error.php
 * @param resource $result <p>
 * PostgreSQL query result resource, returned by <b>pg_query</b>,
 * <b>pg_query_params</b> or <b>pg_execute</b>
 * (among others).
 * </p>
 * @return string|false a string if there is an error associated with the
 * <i>result</i> parameter, <b>FALSE</b> otherwise.
 */
function pg_result_error(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result): string|false {}

/**
 * Returns an individual field of an error report.
 * @link https://php.net/manual/en/function.pg-result-error-field.php
 * @param resource $result <p>
 * A PostgreSQL query result resource from a previously executed
 * statement.
 * </p>
 * @param int $field_code <p>
 * Possible <i>fieldcode</i> values are: <b>PGSQL_DIAG_SEVERITY</b>,
 * <b>PGSQL_DIAG_SQLSTATE</b>, <b>PGSQL_DIAG_MESSAGE_PRIMARY</b>,
 * <b>PGSQL_DIAG_MESSAGE_DETAIL</b>,
 * <b>PGSQL_DIAG_MESSAGE_HINT</b>, <b>PGSQL_DIAG_STATEMENT_POSITION</b>,
 * <b>PGSQL_DIAG_INTERNAL_POSITION</b> (PostgreSQL 8.0+ only),
 * <b>PGSQL_DIAG_INTERNAL_QUERY</b> (PostgreSQL 8.0+ only),
 * <b>PGSQL_DIAG_CONTEXT</b>, <b>PGSQL_DIAG_SOURCE_FILE</b>,
 * <b>PGSQL_DIAG_SOURCE_LINE</b> or
 * <b>PGSQL_DIAG_SOURCE_FUNCTION</b>.
 * </p>
 * @return string|null|false A string containing the contents of the error field, <b>NULL</b> if the field does not exist or <b>FALSE</b>
 * on failure.
 */
function pg_result_error_field(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result,
    int $field_code
): string|false|null {}

/**
 * Get the last error message string of a connection
 * @link https://php.net/manual/en/function.pg-last-error.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return string A string containing the last error message on the
 * given <i>connection</i>, or <b>FALSE</b> on error.
 */
function pg_last_error(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): string {}

/**
 * Returns the last notice message from PostgreSQL server
 * @link https://php.net/manual/en/function.pg-last-notice.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @param int $mode [optional] <p>
 * One of <b>PGSQL_NOTICE_LAST</b> (to return last notice),
 * <b>PGSQL_NOTICE_ALL</b> (to return all notices), or
 * <b>PGSQL_NOTICE_CLEAR</b> (to clear notices).
 * </p>
 * @return array|string|bool A string containing the last notice on the
 * given <i>connection</i> with <b>PGSQL_NOTICE_LAST</b>,
 * an array with <b>PGSQL_NOTICE_ALL</b>,
 * a bool with <b>PGSQL_NOTICE_CLEAR</b>, or
 * <b>FALSE</b> on error.
 */
function pg_last_notice(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection, int $mode = PGSQL_NOTICE_LAST): array|string|bool {}

/**
 * Send a NULL-terminated string to PostgreSQL backend
 * @link https://php.net/manual/en/function.pg-put-line.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $query <p>
 * A line of text to be sent directly to the PostgreSQL backend. A NULL
 * terminator is added automatically.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_put_line(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $query = null
): bool {}

/**
 * Sync with PostgreSQL backend
 * @link https://php.net/manual/en/function.pg-end-copy.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_end_copy(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): bool {}

/**
 * Copy a table to an array
 * @link https://php.net/manual/en/function.pg-copy-to.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @param string $table_name <p>
 * Name of the table from which to copy the data into <i>rows</i>.
 * </p>
 * @param string $separator [optional] <p>
 * The token that separates values for each field in each element of
 * <i>rows</i>. Default is TAB.
 * </p>
 * @param string $null_as [optional] <p>
 * How SQL NULL values are represented in the
 * <i>rows</i>. Default is \N ("\\N").
 * </p>
 * @return array|false An array with one element for each line of COPY data.
 * It returns <b>FALSE</b> on failure.
 */
function pg_copy_to(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $table_name,
    string $separator = '	',
    string $null_as = '\\\\N'
): array|false {}

/**
 * Insert records into a table from an array
 * @link https://php.net/manual/en/function.pg-copy-from.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @param string $table_name <p>
 * Name of the table into which to copy the <i>rows</i>.
 * </p>
 * @param array $rows <p>
 * An array of data to be copied into <i>table_name</i>.
 * Each value in <i>rows</i> becomes a row in <i>table_name</i>.
 * Each value in <i>rows</i> should be a delimited string of the values
 * to insert into each field. Values should be linefeed terminated.
 * </p>
 * @param string $separator [optional] <p>
 * The token that separates values for each field in each element of
 * <i>rows</i>. Default is TAB.
 * </p>
 * @param string $null_as [optional] <p>
 * How SQL NULL values are represented in the
 * <i>rows</i>. Default is \N ("\\N").
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_copy_from(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $table_name,
    array $rows,
    string $separator = '	',
    string $null_as = '\\\\N'
): bool {}

/**
 * Enable tracing a PostgreSQL connection
 * @link https://php.net/manual/en/function.pg-trace.php
 * @param string $filename <p>
 * The full path and file name of the file in which to write the
 * trace log. Same as in <b>fopen</b>.
 * </p>
 * @param string $mode [optional] <p>
 * An optional file access mode, same as for <b>fopen</b>.
 * </p>
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_trace(string $filename, string $mode = "w", #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): bool {}

/**
 * Disable tracing of a PostgreSQL connection
 * @link https://php.net/manual/en/function.pg-untrace.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return bool Always returns <b>TRUE</b>.
 */
function pg_untrace(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): bool {}

/**
 * Create a large object
 * @link https://php.net/manual/en/function.pg-lo-create.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param mixed $oid [optional] <p>
 * If an <i>object_id</i> is given the function
 * will try to create a large object with this id, else a free
 * object id is assigned by the server. The parameter
 * was added in PHP 5.3 and relies on functionality that first
 * appeared in PostgreSQL 8.1.
 * </p>
 * @return string|int|false A large object OID or <b>FALSE</b> on error.
 */
function pg_lo_create(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection = null, $oid = null): string|int|false {}

/**
 * Delete a large object
 * @link https://php.net/manual/en/function.pg-lo-unlink.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param int $oid <p>
 * The OID of the large object in the database.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_lo_unlink(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    $oid = null
): bool {}

/**
 * Open a large object
 * @link https://php.net/manual/en/function.pg-lo-open.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param int $oid <p>
 * The OID of the large object in the database.
 * </p>
 * @param string $mode <p>
 * Can be either "r" for read-only, "w" for write only or "rw" for read and
 * write.
 * </p>
 * @return resource|false A large object resource or <b>FALSE</b> on error.
 */
#[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob|false'], default: 'resource|false')]
function pg_lo_open(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    $oid = null,
    string $mode = null
) {}

/**
 * Close a large object
 * @link https://php.net/manual/en/function.pg-lo-close.php
 * @param resource $lob
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_lo_close(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob'], default: 'resource')] $lob): bool {}

/**
 * Read a large object
 * @link https://php.net/manual/en/function.pg-lo-read.php
 * @param resource $lob <p>
 * PostgreSQL large object (LOB) resource, returned by <b>pg_lo_open</b>.
 * </p>
 * @param int $length [optional] <p>
 * An optional maximum number of bytes to return.
 * </p>
 * @return string|false A string containing <i>len</i> bytes from the
 * large object, or <b>FALSE</b> on error.
 */
function pg_lo_read(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob'], default: 'resource')] $lob, int $length = 8192): string|false {}

/**
 * Write to a large object
 * @link https://php.net/manual/en/function.pg-lo-write.php
 * @param resource $lob <p>
 * PostgreSQL large object (LOB) resource, returned by <b>pg_lo_open</b>.
 * </p>
 * @param string $data <p>
 * The data to be written to the large object. If <i>len</i> is
 * specified and is less than the length of <i>data</i>, only
 * <i>len</i> bytes will be written.
 * </p>
 * @param int $length [optional] <p>
 * An optional maximum number of bytes to write. Must be greater than zero
 * and no greater than the length of <i>data</i>. Defaults to
 * the length of <i>data</i>.
 * </p>
 * @return int|false The number of bytes written to the large object, or <b>FALSE</b> on error.
 */
function pg_lo_write(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob'], default: 'resource')] $lob, string $data, ?int $length = null): int|false {}

/**
 * Reads an entire large object and send straight to browser
 * @link https://php.net/manual/en/function.pg-lo-read-all.php
 * @param resource $lob <p>
 * PostgreSQL large object (LOB) resource, returned by <b>pg_lo_open</b>.
 * </p>
 * @return int|false Number of bytes read or <b>FALSE</b> on error.
 */
function pg_lo_read_all(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob'], default: 'resource')] $lob): int {}

/**
 * Import a large object from file
 * @link https://php.net/manual/en/function.pg-lo-import.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $pathname <p>
 * The full path and file name of the file on the client
 * filesystem from which to read the large object data.
 * </p>
 * @param mixed $object_id [optional] <p>
 * If an <i>object_id</i> is given the function
 * will try to create a large object with this id, else a free
 * object id is assigned by the server. The parameter
 * was added in PHP 5.3 and relies on functionality that first
 * appeared in PostgreSQL 8.1.
 * </p>
 * @return string|int|false The OID of the newly created large object, or
 * <b>FALSE</b> on failure.
 */
function pg_lo_import(
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    $pathname,
    $object_id = null
): string|int|false {}

/**
 * Export a large object to file
 * @link https://php.net/manual/en/function.pg-lo-export.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param int $oid <p>
 * The OID of the large object in the database.
 * </p>
 * @param string $pathname <p>
 * The full path and file name of the file in which to write the
 * large object on the client filesystem.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_lo_export(
    #[PhpStormStubsElementAvailable('8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    $oid,
    $pathname
): bool {}

/**
 * Seeks position within a large object
 * @link https://php.net/manual/en/function.pg-lo-seek.php
 * @param resource $lob <p>
 * PostgreSQL large object (LOB) resource, returned by <b>pg_lo_open</b>.
 * </p>
 * @param int $offset <p>
 * The number of bytes to seek.
 * </p>
 * @param int $whence [optional] <p>
 * One of the constants <b>PGSQL_SEEK_SET</b> (seek from object start),
 * <b>PGSQL_SEEK_CUR</b> (seek from current position)
 * or <b>PGSQL_SEEK_END</b> (seek from object end) .
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function pg_lo_seek(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob'], default: 'resource')] $lob, int $offset, int $whence = PGSQL_SEEK_CUR): bool {}

/**
 * Returns current seek position a of large object
 * @link https://php.net/manual/en/function.pg-lo-tell.php
 * @param resource $lob <p>
 * PostgreSQL large object (LOB) resource, returned by <b>pg_lo_open</b>.
 * </p>
 * @return int The current seek offset (in number of bytes) from the beginning of the large
 * object. If there is an error, the return value is negative.
 */
function pg_lo_tell(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob'], default: 'resource')] $lob): int {}

/**
 *  Truncates a large object
 * @link https://www.php.net/manual/en/function.pg-lo-truncate.php
 * @param resource $lob <p>
 * PostgreSQL large object (LOB) resource, returned by <b>pg_lo_open</b>.
 * </p>
 * @param int $size The number of bytes to truncate.
 * @return bool Returns true on success or false on failure.
 */
function pg_lo_truncate(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob'], default: 'resource')] $lob,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] int $size = 0,
    #[PhpStormStubsElementAvailable(from: '8.0')] int $size
): bool {}

/**
 * Escape a string for query
 * @link https://php.net/manual/en/function.pg-escape-string.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $string <p>
 * A string containing text to be escaped.
 * </p>
 * @return string A string containing the escaped data.
 */
function pg_escape_string(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $string = null
): string {}

/**
 * Escape a string for insertion into a bytea field
 * @link https://php.net/manual/en/function.pg-escape-bytea.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $string <p>
 * A string containing text or binary data to be inserted into a bytea
 * column.
 * </p>
 * @return string A string containing the escaped data.
 */
function pg_escape_bytea(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $string = null
): string {}

/**
 * Escape a identifier for insertion into a text field
 * @link https://php.net/manual/en/function.pg-escape-identifier.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $string <p>
 * A string containing text to be escaped.
 * </p>
 * @return string|false A string containing the escaped data.
 * @since 5.4.4
 */
function pg_escape_identifier(
    #[PhpStormStubsElementAvailable(from: '5.4', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $string = null
): string|false {}

/**
 * Escape a literal for insertion into a text field
 * @link https://php.net/manual/en/function.pg-escape-literal.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $string <p>
 * A string containing text to be escaped.
 * </p>
 * @return string|false A string containing the escaped data.
 * @since 5.4.4
 */
function pg_escape_literal(
    #[PhpStormStubsElementAvailable(from: '5.4', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $string = null
): string|false {}

/**
 * Unescape binary for bytea type
 * @link https://php.net/manual/en/function.pg-unescape-bytea.php
 * @param string $string <p>
 * A string containing PostgreSQL bytea data to be converted into
 * a PHP binary string.
 * </p>
 * @return string A string containing the unescaped data.
 */
function pg_unescape_bytea(string $string): string {}

/**
 * Determines the verbosity of messages returned by <b>pg_last_error</b>
 * and <b>pg_result_error</b>.
 * @link https://php.net/manual/en/function.pg-set-error-verbosity.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param int $verbosity <p>
 * The required verbosity: <b>PGSQL_ERRORS_TERSE</b>,
 * <b>PGSQL_ERRORS_DEFAULT</b>
 * or <b>PGSQL_ERRORS_VERBOSE</b>.
 * </p>
 * @return int|false The previous verbosity level: <b>PGSQL_ERRORS_TERSE</b>,
 * <b>PGSQL_ERRORS_DEFAULT</b>
 * or <b>PGSQL_ERRORS_VERBOSE</b>.
 */
function pg_set_error_verbosity(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    int $verbosity = null
): int|false {}

/**
 * Gets the client encoding
 * @link https://php.net/manual/en/function.pg-client-encoding.php
 * @param resource $connection [optional] <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @return string|false The client encoding, or <b>FALSE</b> on error.
 */
function pg_client_encoding(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection = null): string {}

/**
 * Set the client encoding
 * @link https://php.net/manual/en/function.pg-set-client-encoding.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource. When
 * <i>connection</i> is not present, the default connection
 * is used. The default connection is the last connection made by
 * <b>pg_connect</b> or <b>pg_pconnect</b>.
 * </p>
 * @param string $encoding <p>
 * The required client encoding. One of SQL_ASCII, EUC_JP,
 * EUC_CN, EUC_KR, EUC_TW,
 * UNICODE, MULE_INTERNAL, LATINX (X=1...9),
 * KOI8, WIN, ALT, SJIS,
 * BIG5 or WIN1250.
 * </p>
 * <p>
 * The exact list of available encodings depends on your PostgreSQL version, so check your
 * PostgreSQL manual for a more specific list.
 * </p>
 * @return int 0 on success or -1 on error.
 */
function pg_set_client_encoding(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $encoding = null
): int {}

/**
 * Get meta data for table
 * @link https://php.net/manual/en/function.pg-meta-data.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @param string $table_name <p>
 * The name of the table.
 * </p>
 * @return array|false An array of the table definition, or <b>FALSE</b> on error.
 */
function pg_meta_data(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $table_name,
    #[PhpStormStubsElementAvailable(from: '8.0')] bool $extended = false
): array|false {}

/**
 * Convert associative array values into suitable for SQL statement
 * @link https://php.net/manual/en/function.pg-convert.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @param string $table_name <p>
 * Name of the table against which to convert types.
 * </p>
 * @param array $values <p>
 * Data to be converted.
 * </p>
 * @param int $flags [optional] <p>
 * Any number of <b>PGSQL_CONV_IGNORE_DEFAULT</b>,
 * <b>PGSQL_CONV_FORCE_NULL</b> or
 * <b>PGSQL_CONV_IGNORE_NOT_NULL</b>, combined.
 * </p>
 * @return array|false An array of converted values, or <b>FALSE</b> on error.
 */
function pg_convert(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $table_name,
    array $values,
    int $flags = 0
): array|false {}

/**
 * Insert array into table
 * @link https://php.net/manual/en/function.pg-insert.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @param string $table_name <p>
 * Name of the table into which to insert rows. The table <i>table_name</i> must at least
 * have as many columns as <i>assoc_array</i> has elements.
 * </p>
 * @param array $values <p>
 * An array whose keys are field names in the table <i>table_name</i>,
 * and whose values are the values of those fields that are to be inserted.
 * </p>
 * @param int $flags [optional] <p>
 * Any number of <b>PGSQL_CONV_OPTS</b>,
 * <b>PGSQL_DML_NO_CONV</b>,
 * <b>PGSQL_DML_EXEC</b>,
 * <b>PGSQL_DML_ASYNC</b> or
 * <b>PGSQL_DML_STRING</b> combined. If <b>PGSQL_DML_STRING</b> is part of the
 * <i>options</i> then query string is returned.
 * </p>
 * @return mixed <b>TRUE</b> on success or <b>FALSE</b> on failure. Returns string if <b>PGSQL_DML_STRING</b> is passed
 * via <i>options</i>.
 */
#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result|string|bool'], default: 'resource|string|bool')]
function pg_insert(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $table_name,
    array $values,
    int $flags = PGSQL_DML_EXEC
) {}

/**
 * Update table
 * @link https://php.net/manual/en/function.pg-update.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @param string $table_name <p>
 * Name of the table into which to update rows.
 * </p>
 * @param array $values <p>
 * An array whose keys are field names in the table <i>table_name</i>,
 * and whose values are what matched rows are to be updated to.
 * </p>
 * @param array $conditions <p>
 * An array whose keys are field names in the table <i>table_name</i>,
 * and whose values are the conditions that a row must meet to be updated.
 * </p>
 * @param int $flags [optional] <p>
 * Any number of <b>PGSQL_CONV_OPTS</b>,
 * <b>PGSQL_DML_NO_CONV</b>,
 * <b>PGSQL_DML_EXEC</b> or
 * <b>PGSQL_DML_STRING</b> combined. If <b>PGSQL_DML_STRING</b> is part of the
 * <i>options</i> then query string is returned.
 * </p>
 * @return string|bool <b>TRUE</b> on success or <b>FALSE</b> on failure. Returns string if <b>PGSQL_DML_STRING</b> is passed
 * via <i>options</i>.
 */
function pg_update(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $table_name,
    array $values,
    array $conditions,
    int $flags = PGSQL_DML_EXEC
): string|bool {}

/**
 * Deletes records
 * @link https://php.net/manual/en/function.pg-delete.php
 * @param resource $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @param string $table_name <p>
 * Name of the table from which to delete rows.
 * </p>
 * @param array $conditions <p>
 * An array whose keys are field names in the table <i>table_name</i>,
 * and whose values are the values of those fields that are to be deleted.
 * </p>
 * @param int $flags [optional] <p>
 * Any number of <b>PGSQL_CONV_FORCE_NULL</b>,
 * <b>PGSQL_DML_NO_CONV</b>,
 * <b>PGSQL_DML_EXEC</b> or
 * <b>PGSQL_DML_STRING</b> combined. If <b>PGSQL_DML_STRING</b> is part of the
 * <i>options</i> then query string is returned.
 * </p>
 * @return string|bool <b>TRUE</b> on success or <b>FALSE</b> on failure. Returns string if <b>PGSQL_DML_STRING</b> is passed
 * via <i>options</i>.
 */
function pg_delete(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $table_name,
    array $conditions,
    int $flags = PGSQL_DML_EXEC
): string|bool {}

/**
 * Select records
 * @link https://php.net/manual/en/function.pg-select.php
 * @param resource|PgSql\Connection $connection <p>
 * PostgreSQL database connection resource.
 * </p>
 * @param string $table_name <p>
 * Name of the table from which to select rows.
 * </p>
 * @param array $conditions <p>
 * An array whose keys are field names in the table <i>table_name</i>,
 * and whose values are the conditions that a row must meet to be retrieved.
 * </p>
 * @param int $flags [optional] <p>
 * Any number of <b>PGSQL_CONV_FORCE_NULL</b>,
 * <b>PGSQL_DML_NO_CONV</b>,
 * <b>PGSQL_DML_EXEC</b>,
 * <b>PGSQL_DML_ASYNC</b> or
 * <b>PGSQL_DML_STRING</b> combined. If <b>PGSQL_DML_STRING</b> is part of the
 * <i>options</i> then query string is returned.
 * </p>
 * @param int $mode [optional] <p>
 * An optional parameter that controls
 * how the returned array is indexed.
 * <i>result_type</i> is a constant and can take the
 * following values: <b>PGSQL_ASSOC</b>,
 * <b>PGSQL_NUM</b> and <b>PGSQL_BOTH</b>.
 * Using <b>PGSQL_NUM</b>, <b>pg_fetch_array</b>
 * will return an array with numerical indices, using
 * <b>PGSQL_ASSOC</b> it will return only associative indices
 * while <b>PGSQL_BOTH</b>, the default, will return both
 * numerical and associative indices.
 * </p>
 * @return array|string|false <b>TRUE</b> on success or <b>FALSE</b> on failure. Returns string if <b>PGSQL_DML_STRING</b> is passed
 * via <i>options</i>.
 */
function pg_select(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $table_name,
    array $conditions,
    int $flags = PGSQL_DML_EXEC,
    int $mode = PGSQL_ASSOC
): array|string|false {}

/**
 * @param $connection
 * @param $query
 * @return mixed
 */
#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result|false'], default: 'resource|false')]
function pg_exec(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $query = null
) {}

/**
 * @param $result
 * @return string|int|false
 * @deprecated
 */
function pg_getlastoid(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result): string|int|false {}

/**
 * @param $result
 * @return int
 * @deprecated
 */
function pg_cmdtuples(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result): int {} // TODO remove

/**
 * @param $connection [optional]
 * @return string
 * @deprecated
 */
function pg_errormessage(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection): string {}

/**
 * @param $result
 * @return int
 * @deprecated
 */
function pg_numrows(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result): int {}

/**
 * @param $result
 * @return int
 * @deprecated
 */
function pg_numfields(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result): int {}

/**
 * @param $result
 * @param $field
 * @return string
 * @deprecated
 */
function pg_fieldname(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, int $field): string {}

/**
 * @param $result
 * @param $field
 * @return int
 * @deprecated
 */
function pg_fieldsize(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, int $field): int {}

/**
 * @param $result
 * @param $field
 * @return string
 * @deprecated
 */
function pg_fieldtype(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, int $field): string {}

/**
 * @param $result
 * @param $field
 * @return int
 * @deprecated
 */
function pg_fieldnum(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result, string $field): int {}

/**
 * @param $result
 * @param $row
 * @param $field [optional]
 * @return int|false
 * @deprecated
 */
function pg_fieldprtlen(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $row = 0,
    #[PhpStormStubsElementAvailable(from: '8.0')] $row,
    string|int $field
): int|false {}

/**
 * @param $result
 * @param $row
 * @param $field [optional]
 * @return int|false
 * @deprecated
 */
function pg_fieldisnull(
    #[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $row = 0,
    #[PhpStormStubsElementAvailable(from: '8.0')] $row,
    string|int $field
): int|false {}

/**
 * @param $result
 * @return bool
 * @deprecated
 */
function pg_freeresult(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result): bool {}

/**
 * @param PgSql\Result|resource $result
 * @param $row
 * @param $field
 * @deprecated
 */
function pg_result(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Result'], default: 'resource')] $result,
    #[PhpStormStubsElementAvailable(from: '8.0')] $row,
    #[PhpStormStubsElementAvailable(from: '8.0')] string|int $field = null
): string|null|false {}

/**
 * @param $lob
 * @deprecated
 */
function pg_loreadall(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob'], default: 'resource')] $lob): int {} // TODO remove

/**
 * @param $connection [optional]
 * @param $oid [optional]
 * @return string|int|false
 * @deprecated
 */
function pg_locreate(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection, $oid): string|int|false {}

/**
 * @param $connection
 * @param $oid [optional]
 * @return bool
 * @deprecated
 */
function pg_lounlink(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    $oid
): bool {}

/**
 * @param $connection
 * @param $oid [optional]
 * @param $mode [optional]
 * @return resource
 * @deprecated
 */
#[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob|false'], default: 'resource|false')]
function pg_loopen(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    $oid,
    string $mode
) {}

/**
 * @param $lob
 * @return bool
 * @deprecated
 */
function pg_loclose(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob'], default: 'resource')] $lob): bool {}

/**
 * @param $lob
 * @param $length
 * @return string|false
 * @deprecated
 */
function pg_loread(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob'], default: 'resource')] $lob, int $length = 8192): string|false {}

/**
 * @param $lob
 * @param $data
 * @param $length [optional]
 * @return int|false
 * @deprecated
 */
function pg_lowrite(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Lob'], default: 'resource')] $lob, string $data, ?int $length): int|false {}

/**
 * @param $connection
 * @param $filename [optional]
 * @param $oid [optional]
 * @return string|int|false
 * @deprecated
 */
function pg_loimport(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    $filename,
    $oid
): string|int|false {}

/**
 * @param $connection
 * @param $oid [optional]
 * @param $filename [optional]
 * @return bool
 * @deprecated
 */
function pg_loexport(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    $oid,
    $filename
): bool {}

/**
 * @param $connection [optional]
 * @return string
 * @deprecated
 */
function pg_clientencoding(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection|null'], default: 'resource')] $connection): string {}

/**
 * @param $connection
 * @param $encoding [optional]
 * @return int
 * @deprecated
 */
function pg_setclientencoding(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $connection = null,
    #[PhpStormStubsElementAvailable(from: '8.0')] #[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection,
    string $encoding
): int {}

/**
 * Reads input on the connection
 * @link https://www.php.net/manual/en/function.pg-consume-input.php
 * @param PgSql\Connection|resource $connection
 * @return bool true if no error occurred, or false if there was an error.
 * Note that true does not necessarily indicate that input was waiting to be read.
 */
function pg_consume_input(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection): bool {}

/**
 * Flush outbound query data on the connection
 * @link https://www.php.net/manual/en/function.pg-flush.php
 * @param PgSql\Connection|resource $connection
 * @return int|bool Returns true if the flush was successful or no data was waiting to be flushed, 0 if part of the pending
 * data was flushed but more remains or false on failure.
 */
function pg_flush(#[LanguageLevelTypeAware(['8.1' => 'PgSql\Connection'], default: 'resource')] $connection): int|bool {}

define('PGSQL_LIBPQ_VERSION', "14.5");
define('PGSQL_LIBPQ_VERSION_STR', "14.5");

/**
 * Passed to <b>pg_connect</b> to force the creation of a new connection,
 * rather than re-using an existing identical connection.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_CONNECT_FORCE_NEW', 2);

/**
 * Passed to <b>pg_fetch_array</b>. Return an associative array of field
 * names and values.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_ASSOC', 1);

/**
 * Passed to <b>pg_fetch_array</b>. Return a numerically indexed array of field
 * numbers and values.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_NUM', 2);

/**
 * Passed to <b>pg_fetch_array</b>. Return an array of field values
 * that is both numerically indexed (by field number) and associated (by field name).
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_BOTH', 3);

/**
 * Returned by <b>pg_connection_status</b> indicating that the database
 * connection is in an invalid state.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_CONNECTION_BAD', 1);

/**
 * Returned by <b>pg_connection_status</b> indicating that the database
 * connection is in a valid state.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_CONNECTION_OK', 0);

/**
 * Returned by <b>pg_transaction_status</b>. Connection is
 * currently idle, not in a transaction.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_TRANSACTION_IDLE', 0);

/**
 * Returned by <b>pg_transaction_status</b>. A command
 * is in progress on the connection. A query has been sent via the connection
 * and not yet completed.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_TRANSACTION_ACTIVE', 1);

/**
 * Returned by <b>pg_transaction_status</b>. The connection
 * is idle, in a transaction block.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_TRANSACTION_INTRANS', 2);

/**
 * Returned by <b>pg_transaction_status</b>. The connection
 * is idle, in a failed transaction block.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_TRANSACTION_INERROR', 3);

/**
 * Returned by <b>pg_transaction_status</b>. The connection
 * is bad.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_TRANSACTION_UNKNOWN', 4);

/**
 * Passed to <b>pg_set_error_verbosity</b>.
 * Specified that returned messages include severity, primary text,
 * and position only; this will normally fit on a single line.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_ERRORS_TERSE', 0);

/**
 * Passed to <b>pg_set_error_verbosity</b>.
 * The default mode produces messages that include the above
 * plus any detail, hint, or context fields (these may span
 * multiple lines).
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_ERRORS_DEFAULT', 1);

/**
 * Passed to <b>pg_set_error_verbosity</b>.
 * The verbose mode includes all available fields.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_ERRORS_VERBOSE', 2);

/**
 * Passed to <b>pg_lo_seek</b>. Seek operation is to begin
 * from the start of the object.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_SEEK_SET', 0);

/**
 * Passed to <b>pg_lo_seek</b>. Seek operation is to begin
 * from the current position.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_SEEK_CUR', 1);

/**
 * Passed to <b>pg_lo_seek</b>. Seek operation is to begin
 * from the end of the object.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_SEEK_END', 2);

/**
 * Passed to <b>pg_result_status</b>. Indicates that
 * numerical result code is desired.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_STATUS_LONG', 1);

/**
 * Passed to <b>pg_result_status</b>. Indicates that
 * textual result command tag is desired.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_STATUS_STRING', 2);

/**
 * Returned by <b>pg_result_status</b>. The string sent to the server
 * was empty.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_EMPTY_QUERY', 0);

/**
 * Returned by <b>pg_result_status</b>. Successful completion of a
 * command returning no data.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_COMMAND_OK', 1);

/**
 * Returned by <b>pg_result_status</b>. Successful completion of a command
 * returning data (such as a SELECT or SHOW).
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_TUPLES_OK', 2);

/**
 * Returned by <b>pg_result_status</b>. Copy Out (from server) data
 * transfer started.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_COPY_OUT', 3);

/**
 * Returned by <b>pg_result_status</b>. Copy In (to server) data
 * transfer started.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_COPY_IN', 4);

/**
 * Returned by <b>pg_result_status</b>. The server's response
 * was not understood.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_BAD_RESPONSE', 5);

/**
 * Returned by <b>pg_result_status</b>. A nonfatal error
 * (a notice or warning) occurred.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_NONFATAL_ERROR', 6);

/**
 * Returned by <b>pg_result_status</b>. A fatal error
 * occurred.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_FATAL_ERROR', 7);

/**
 * Passed to <b>pg_result_error_field</b>.
 * The severity; the field contents are ERROR,
 * FATAL, or PANIC (in an error message), or
 * WARNING, NOTICE, DEBUG,
 * INFO, or LOG (in a notice message), or a localized
 * translation of one of these. Always present.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_DIAG_SEVERITY', 83);

/**
 * Passed to <b>pg_result_error_field</b>.
 * The SQLSTATE code for the error. The SQLSTATE code identifies the type of error
 * that has occurred; it can be used by front-end applications to perform specific
 * operations (such as error handling) in response to a particular database error.
 * This field is not localizable, and is always present.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_DIAG_SQLSTATE', 67);

/**
 * Passed to <b>pg_result_error_field</b>.
 * The primary human-readable error message (typically one line). Always present.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_DIAG_MESSAGE_PRIMARY', 77);

/**
 * Passed to <b>pg_result_error_field</b>.
 * Detail: an optional secondary error message carrying more detail about the problem. May run to multiple lines.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_DIAG_MESSAGE_DETAIL', 68);

/**
 * Passed to <b>pg_result_error_field</b>.
 * Hint: an optional suggestion what to do about the problem. This is intended to differ from detail in that it
 * offers advice (potentially inappropriate) rather than hard facts. May run to multiple lines.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_DIAG_MESSAGE_HINT', 72);

/**
 * Passed to <b>pg_result_error_field</b>.
 * A string containing a decimal integer indicating an error cursor position as an index into the original
 * statement string. The first character has index 1, and positions are measured in characters not bytes.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_DIAG_STATEMENT_POSITION', 80);

/**
 * Passed to <b>pg_result_error_field</b>.
 * This is defined the same as the <b>PG_DIAG_STATEMENT_POSITION</b> field, but
 * it is used when the cursor position refers to an internally generated
 * command rather than the one submitted by the client. The
 * <b>PG_DIAG_INTERNAL_QUERY</b> field will always appear when this
 * field appears.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_DIAG_INTERNAL_POSITION', 112);

/**
 * Passed to <b>pg_result_error_field</b>.
 * The text of a failed internally-generated command. This could be, for example, a
 * SQL query issued by a PL/pgSQL function.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_DIAG_INTERNAL_QUERY', 113);

/**
 * Passed to <b>pg_result_error_field</b>.
 * An indication of the context in which the error occurred. Presently
 * this includes a call stack traceback of active procedural language
 * functions and internally-generated queries. The trace is one entry
 * per line, most recent first.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_DIAG_CONTEXT', 87);

/**
 * Passed to <b>pg_result_error_field</b>.
 * The file name of the PostgreSQL source-code location where the error
 * was reported.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_DIAG_SOURCE_FILE', 70);

/**
 * Passed to <b>pg_result_error_field</b>.
 * The line number of the PostgreSQL source-code location where the
 * error was reported.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_DIAG_SOURCE_LINE', 76);

/**
 * Passed to <b>pg_result_error_field</b>.
 * The name of the PostgreSQL source-code function reporting the error.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_DIAG_SOURCE_FUNCTION', 82);

/**
 * Passed to <b>pg_convert</b>.
 * Ignore default values in the table during conversion.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_CONV_IGNORE_DEFAULT', 2);

/**
 * Passed to <b>pg_convert</b>.
 * Use SQL NULL in place of an empty string.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_CONV_FORCE_NULL', 4);

/**
 * Passed to <b>pg_convert</b>.
 * Ignore conversion of <b>NULL</b> into SQL NOT NULL columns.
 * @link https://php.net/manual/en/pgsql.constants.php
 */
define('PGSQL_CONV_IGNORE_NOT_NULL', 8);
define('PGSQL_DML_NO_CONV', 256);
define('PGSQL_DML_EXEC', 512);
define('PGSQL_DML_ASYNC', 1024);
define('PGSQL_DML_STRING', 2048);

/**
 * @link https://php.net/manual/en/function.pg-last-notice.php
 * @since 7.1
 */
define('PGSQL_NOTICE_LAST', 1);

/**
 * @link https://php.net/manual/en/function.pg-last-notice.php
 * @since 7.1
 */
define('PGSQL_NOTICE_ALL', 2);

/**
 * @link https://php.net/manual/en/function.pg-last-notice.php
 * @since 7.1
 */
define('PGSQL_NOTICE_CLEAR', 3);

const PGSQL_CONNECT_ASYNC = 4;
const PGSQL_CONNECTION_AUTH_OK = 5;
const PGSQL_CONNECTION_AWAITING_RESPONSE = 4;
const PGSQL_CONNECTION_MADE = 3;
const PGSQL_CONNECTION_SETENV = 6;
const PGSQL_CONNECTION_STARTED = 2;
const PGSQL_DML_ESCAPE = 4096;
const PGSQL_POLLING_ACTIVE = 4;
const PGSQL_POLLING_FAILED = 0;
const PGSQL_POLLING_OK = 3;
const PGSQL_POLLING_READING = 1;
const PGSQL_POLLING_WRITING = 2;
const PGSQL_DIAG_SCHEMA_NAME = 115;
const PGSQL_DIAG_TABLE_NAME = 116;
const PGSQL_DIAG_COLUMN_NAME = 99;
const PGSQL_DIAG_DATATYPE_NAME = 100;
const PGSQL_DIAG_CONSTRAINT_NAME = 110;
const PGSQL_DIAG_SEVERITY_NONLOCALIZED = 86;
// End of pgsql v.
