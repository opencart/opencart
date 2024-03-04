<?php

// Start of mysql v.1.0
// @deprecated 5.5 entire extension is deprecated in favor of mysqli
use JetBrains\PhpStorm\Deprecated;

/**
 * Open a connection to a MySQL Server
 * @link https://php.net/manual/en/function.mysql-connect.php
 * @param string $server [optional] <p>
 * The MySQL server. It can also include a port number. e.g.
 * "hostname:port" or a path to a local socket e.g. ":/path/to/socket" for
 * the localhost.
 * </p>
 * <p>
 * If the PHP directive
 * mysql.default_host is undefined (default), then the default
 * value is 'localhost:3306'. In "ini.sql.safe-mode", this parameter is ignored
 * and value 'localhost:3306' is always used.
 * </p>
 * @param string $username [optional] <p>
 * The username. Default value is defined by mysql.default_user. In
 * "ini.sql.safe-mode", this parameter is ignored and the name of the user that
 * owns the server process is used.
 * </p>
 * @param string $password [optional] <p>
 * The password. Default value is defined by mysql.default_password. In
 * "ini.sql.safe-mode", this parameter is ignored and empty password is used.
 * </p>
 * @param bool $new_link [optional] <p>
 * If a second call is made to <b>mysql_connect</b>
 * with the same arguments, no new link will be established, but
 * instead, the link identifier of the already opened link will be
 * returned. The <i>new_link</i> parameter modifies this
 * behavior and makes <b>mysql_connect</b> always open
 * a new link, even if <b>mysql_connect</b> was called
 * before with the same parameters.
 * In "ini.sql.safe-mode", this parameter is ignored.
 * </p>
 * @param int $client_flags [optional] <p>
 * The <i>client_flags</i> parameter can be a combination
 * of the following constants:
 * 128 (enable LOAD DATA LOCAL handling),
 * <b>MYSQL_CLIENT_SSL</b>,
 * <b>MYSQL_CLIENT_COMPRESS</b>,
 * <b>MYSQL_CLIENT_IGNORE_SPACE</b> or
 * <b>MYSQL_CLIENT_INTERACTIVE</b>.
 * Read the section about for further information.
 * In "ini.sql.safe-mode", this parameter is ignored.
 * </p>
 * @return resource|false a MySQL link identifier on success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_connect($server = 'ini_get("mysql.default_host")', $username = 'ini_get("mysql.default_user")', $password = 'ini_get("mysql.default_password")', $new_link = false, $client_flags = 0) {}

/**
 * Open a persistent connection to a MySQL server
 * @link https://php.net/manual/en/function.mysql-pconnect.php
 * @param string $server [optional] <p>
 * The MySQL server. It can also include a port number. e.g.
 * "hostname:port" or a path to a local socket e.g. ":/path/to/socket" for
 * the localhost.
 * </p>
 * <p>
 * If the PHP directive
 * mysql.default_host is undefined (default), then the default
 * value is 'localhost:3306'
 * </p>
 * @param string $username [optional] <p>
 * The username. Default value is the name of the user that owns the
 * server process.
 * </p>
 * @param string $password [optional] <p>
 * The password. Default value is an empty password.
 * </p>
 * @param int $client_flags [optional] <p>
 * The <i>client_flags</i> parameter can be a combination
 * of the following constants:
 * 128 (enable LOAD DATA LOCAL handling),
 * <b>MYSQL_CLIENT_SSL</b>,
 * <b>MYSQL_CLIENT_COMPRESS</b>,
 * <b>MYSQL_CLIENT_IGNORE_SPACE</b> or
 * <b>MYSQL_CLIENT_INTERACTIVE</b>.
 * </p>
 * @return resource|false a MySQL persistent link identifier on success, or false on
 * failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_pconnect($server = 'ini_get("mysql.default_host")', $username = 'ini_get("mysql.default_user")', $password = 'ini_get("mysql.default_password")', $client_flags = null) {}

/**
 * Close MySQL connection
 * @link https://php.net/manual/en/function.mysql-close.php
 * @param resource $link_identifier [optional]
 * @return bool true on success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_close($link_identifier = null) {}

/**
 * Select a MySQL database
 * @link https://php.net/manual/en/function.mysql-select-db.php
 * @param string $database_name <p>
 * The name of the database that is to be selected.
 * </p>
 * @param resource $link_identifier [optional]
 * @return bool true on success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_select_db($database_name, $link_identifier = null) {}

/**
 * Send a MySQL query
 * @link https://php.net/manual/en/function.mysql-query.php
 * @param string $query <p>
 * An SQL query
 * </p>
 * <p>
 * The query string should not end with a semicolon.
 * Data inside the query should be properly escaped.
 * </p>
 * @param resource $link_identifier [optional]
 * @return resource|bool For SELECT, SHOW, DESCRIBE, EXPLAIN and other statements returning resultset,
 * <b>mysql_query</b>
 * returns a resource on success, or false on
 * error.
 * </p>
 * <p>
 * For other type of SQL statements, INSERT, UPDATE, DELETE, DROP, etc,
 * <b>mysql_query</b> returns true on success
 * or false on error.
 * </p>
 * <p>
 * The returned result resource should be passed to
 * <b>mysql_fetch_array</b>, and other
 * functions for dealing with result tables, to access the returned data.
 * </p>
 * <p>
 * Use <b>mysql_num_rows</b> to find out how many rows
 * were returned for a SELECT statement or
 * <b>mysql_affected_rows</b> to find out how many
 * rows were affected by a DELETE, INSERT, REPLACE, or UPDATE
 * statement.
 * </p>
 * <p>
 * <b>mysql_query</b> will also fail and return false
 * if the user does not have permission to access the table(s) referenced by
 * the query.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_query($query, $link_identifier = null) {}

/**
 * @deprecated 5.5
 * Send an SQL query to MySQL without fetching and buffering the result rows.
 * @link https://php.net/manual/en/function.mysql-unbuffered-query.php
 * @param string $query <p>
 * The SQL query to execute.
 * </p>
 * <p>
 * Data inside the query should be properly escaped.
 * </p>
 * @param resource $link_identifier [optional]
 * @return resource|bool For SELECT, SHOW, DESCRIBE or EXPLAIN statements,
 * <b>mysql_unbuffered_query</b>
 * returns a resource on success, or false on
 * error.
 * </p>
 * <p>
 * For other type of SQL statements, UPDATE, DELETE, DROP, etc,
 * <b>mysql_unbuffered_query</b> returns true on success
 * or false on error.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_unbuffered_query($query, $link_identifier = null) {}

/**
 * Selects a database and executes a query on it
 * @link https://php.net/manual/en/function.mysql-db-query.php
 * @param string $database <p>
 * The name of the database that will be selected.
 * </p>
 * @param string $query <p>
 * The MySQL query.
 * </p>
 * <p>
 * Data inside the query should be properly escaped.
 * </p>
 * @param resource $link_identifier [optional]
 * @return resource|bool a positive MySQL result resource to the query result,
 * or false on error. The function also returns true/false for
 * INSERT/UPDATE/DELETE
 * queries to indicate success/failure.
 * @removed 7.0
 * @see mysql_select_db()
 * @see mysql_query()
 */
#[Deprecated('Use mysql_select_db() and mysql_query() instead', since: '5.3')]
function mysql_db_query($database, $query, $link_identifier = null) {}

/**
 * List databases available on a MySQL server
 * @link https://php.net/manual/en/function.mysql-list-dbs.php
 * @param resource $link_identifier [optional]
 * @return resource|false a result pointer resource on success, or false on
 * failure. Use the <b>mysql_tablename</b> function to traverse
 * this result pointer, or any function for result tables, such as
 * <b>mysql_fetch_array</b>.
 * @removed 7.0
 */
#[Deprecated(since: '5.4')]
function mysql_list_dbs($link_identifier = null) {}

/**
 * List tables in a MySQL database
 * @link https://php.net/manual/en/function.mysql-list-tables.php
 * @param string $database <p>
 * The name of the database
 * </p>
 * @param resource $link_identifier [optional]
 * @return resource|false A result pointer resource on success or false on failure.
 * <p>
 * Use the <b>mysql_tablename</b> function to
 * traverse this result pointer, or any function for result tables,
 * such as <b>mysql_fetch_array</b>.
 * </p>
 * @removed 7.0
 */
#[Deprecated(since: '5.3')]
function mysql_list_tables($database, $link_identifier = null) {}

/**
 * List MySQL table fields
 * @link https://php.net/manual/en/function.mysql-list-fields.php
 * @param string $database_name <p>
 * The name of the database that's being queried.
 * </p>
 * @param string $table_name <p>
 * The name of the table that's being queried.
 * </p>
 * @param resource $link_identifier [optional]
 * @return resource|false A result pointer resource on success, or false on
 * failure.
 * </p>
 * <p>
 * The returned result can be used with <b>mysql_field_flags</b>,
 * <b>mysql_field_len</b>,
 * <b>mysql_field_name</b>
 * <b>mysql_field_type</b>.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_list_fields($database_name, $table_name, $link_identifier = null) {}

/**
 * List MySQL processes
 * @link https://php.net/manual/en/function.mysql-list-processes.php
 * @param resource $link_identifier [optional]
 * @return resource|false A result pointer resource on success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_list_processes($link_identifier = null) {}

/**
 * Returns the text of the error message from previous MySQL operation
 * @link https://php.net/manual/en/function.mysql-error.php
 * @param resource $link_identifier [optional]
 * @return string the error text from the last MySQL function, or
 * '' (empty string) if no error occurred.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_error($link_identifier = null) {}

/**
 * Returns the numerical value of the error message from previous MySQL operation
 * @link https://php.net/manual/en/function.mysql-errno.php
 * @param resource $link_identifier [optional]
 * @return int the error number from the last MySQL function, or
 * 0 (zero) if no error occurred.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_errno($link_identifier = null) {}

/**
 * Get number of affected rows in previous MySQL operation
 * @link https://php.net/manual/en/function.mysql-affected-rows.php
 * @param resource $link_identifier [optional]
 * @return int the number of affected rows on success, and -1 if the last query
 * failed.
 * </p>
 * <p>
 * If the last query was a DELETE query with no WHERE clause, all
 * of the records will have been deleted from the table but this
 * function will return zero with MySQL versions prior to 4.1.2.
 * </p>
 * <p>
 * When using UPDATE, MySQL will not update columns where the new value is the
 * same as the old value. This creates the possibility that
 * <b>mysql_affected_rows</b> may not actually equal the number
 * of rows matched, only the number of rows that were literally affected by
 * the query.
 * </p>
 * <p>
 * The REPLACE statement first deletes the record with the same primary key
 * and then inserts the new record. This function returns the number of
 * deleted records plus the number of inserted records.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_affected_rows($link_identifier = null) {}

/**
 * Get the ID generated in the last query
 * @link https://php.net/manual/en/function.mysql-insert-id.php
 * @param resource $link_identifier [optional]
 * @return int The ID generated for an AUTO_INCREMENT column by the previous
 * query on success, 0 if the previous
 * query does not generate an AUTO_INCREMENT value, or false if
 * no MySQL connection was established.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_insert_id($link_identifier = null) {}

/**
 * Get result data
 * @link https://php.net/manual/en/function.mysql-result.php
 * @param resource $result
 * @param int $row <p>
 * The row number from the result that's being retrieved. Row numbers
 * start at 0.
 * </p>
 * @param mixed $field [optional] <p>
 * The name or offset of the field being retrieved.
 * </p>
 * <p>
 * It can be the field's offset, the field's name, or the field's table
 * dot field name (tablename.fieldname). If the column name has been
 * aliased ('select foo as bar from...'), use the alias instead of the
 * column name. If undefined, the first field is retrieved.
 * </p>
 * @return string The contents of one cell from a MySQL result set on success, or
 * false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_result($result, $row, $field = 0) {}

/**
 * Get number of rows in result
 * @link https://php.net/manual/en/function.mysql-num-rows.php
 * @param resource $result <p>The result resource that is being evaluated. This result comes from a call to mysql_query().</p>
 * @return int|false <p>The number of rows in the result set on success or FALSE on failure. </p>
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_num_rows($result) {}

/**
 * Get number of fields in result
 * @link https://php.net/manual/en/function.mysql-num-fields.php
 * @param resource $result
 * @return int the number of fields in the result set resource on
 * success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_num_fields($result) {}

/**
 * Get a result row as an enumerated array
 * @link https://php.net/manual/en/function.mysql-fetch-row.php
 * @param resource $result
 * @return array an numerical array of strings that corresponds to the fetched row, or
 * false if there are no more rows.
 * </p>
 * <p>
 * <b>mysql_fetch_row</b> fetches one row of data from
 * the result associated with the specified result identifier. The
 * row is returned as an array. Each result column is stored in an
 * array offset, starting at offset 0.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_fetch_row($result) {}

/**
 * Fetch a result row as an associative array, a numeric array, or both
 * @link https://php.net/manual/en/function.mysql-fetch-array.php
 * @param resource $result
 * @param int $result_type [optional] <p>
 * The type of array that is to be fetched. It's a constant and can
 * take the following values: <b>MYSQL_ASSOC</b>,
 * <b>MYSQL_NUM</b>, and
 * <b>MYSQL_BOTH</b>.
 * </p>
 * @return array|false an array of strings that corresponds to the fetched row, or false
 * if there are no more rows. The type of returned array depends on
 * how <i>result_type</i> is defined. By using
 * <b>MYSQL_BOTH</b> (default), you'll get an array with both
 * associative and number indices. Using <b>MYSQL_ASSOC</b>, you
 * only get associative indices (as <b>mysql_fetch_assoc</b>
 * works), using <b>MYSQL_NUM</b>, you only get number indices
 * (as <b>mysql_fetch_row</b> works).
 * </p>
 * <p>
 * If two or more columns of the result have the same field names,
 * the last column will take precedence. To access the other column(s)
 * of the same name, you must use the numeric index of the column or
 * make an alias for the column. For aliased columns, you cannot
 * access the contents with the original column name.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_fetch_array($result, $result_type = MYSQL_BOTH) {}

/**
 * Fetch a result row as an associative array
 * @link https://php.net/manual/en/function.mysql-fetch-assoc.php
 * @param resource $result
 * @return array an associative array of strings that corresponds to the fetched row, or
 * false if there are no more rows.
 * </p>
 * <p>
 * If two or more columns of the result have the same field names,
 * the last column will take precedence. To access the other
 * column(s) of the same name, you either need to access the
 * result with numeric indices by using
 * <b>mysql_fetch_row</b> or add alias names.
 * See the example at the <b>mysql_fetch_array</b>
 * description about aliases.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_fetch_assoc($result) {}

/**
 * @template T
 *
 * Fetch a result row as an object
 * @link https://php.net/manual/en/function.mysql-fetch-object.php
 * @param resource $result
 * @param class-string<T> $class_name [optional] <p>
 * The name of the class to instantiate, set the properties of and return.
 * If not specified, a <b>stdClass</b> object is returned.
 * </p>
 * @param array $params [optional] <p>
 * An optional array of parameters to pass to the constructor
 * for <i>class_name</i> objects.
 * </p>
 * @return T|stdClass an object with string properties that correspond to the
 * fetched row, or false if there are no more rows.
 * </p>
 * <p>
 * mysql_fetch_row fetches one row of data from
 * the result associated with the specified result identifier. The
 * row is returned as an array. Each result column is stored in an
 * array offset, starting at offset 0.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_fetch_object($result, $class_name = 'stdClass', array $params = null) {}

/**
 * Move internal result pointer
 * @link https://php.net/manual/en/function.mysql-data-seek.php
 * @param resource $result
 * @param int $row_number <p>
 * The desired row number of the new result pointer.
 * </p>
 * @return bool true on success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_data_seek($result, $row_number) {}

/**
 * Get the length of each output in a result
 * @link https://php.net/manual/en/function.mysql-fetch-lengths.php
 * @param resource $result
 * @return array|false An array of lengths on success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_fetch_lengths($result) {}

/**
 * Get column information from a result and return as an object
 * @link https://php.net/manual/en/function.mysql-fetch-field.php
 * @param resource $result
 * @param int $field_offset [optional] <p>
 * The numerical field offset. If the field offset is not specified, the
 * next field that was not yet retrieved by this function is retrieved.
 * The <i>field_offset</i> starts at 0.
 * </p>
 * @return object an object containing field information. The properties
 * of the object are:
 * </p>
 * <p>
 * name - column name
 * table - name of the table the column belongs to
 * def - default value of the column
 * max_length - maximum length of the column
 * not_null - 1 if the column cannot be null
 * primary_key - 1 if the column is a primary key
 * unique_key - 1 if the column is a unique key
 * multiple_key - 1 if the column is a non-unique key
 * numeric - 1 if the column is numeric
 * blob - 1 if the column is a BLOB
 * type - the type of the column
 * unsigned - 1 if the column is unsigned
 * zerofill - 1 if the column is zero-filled
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_fetch_field($result, $field_offset = 0) {}

/**
 * Set result pointer to a specified field offset
 * @link https://php.net/manual/en/function.mysql-field-seek.php
 * @param resource $result
 * @param int $field_offset
 * @return bool true on success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_field_seek($result, $field_offset) {}

/**
 * Free result memory
 * @link https://php.net/manual/en/function.mysql-free-result.php
 * @param resource $result
 * @return bool true on success or false on failure.
 * <p>
 * If a non-resource is used for the result, an
 * error of level E_WARNING will be emitted. It's worth noting that
 * mysql_query only returns a resource
 * for SELECT, SHOW, EXPLAIN, and DESCRIBE queries.
 * </p>
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_free_result($result) {}

/**
 * Get the name of the specified field in a result
 * @link https://php.net/manual/en/function.mysql-field-name.php
 * @param resource $result
 * @param int $field_offset
 * @return string|false The name of the specified field index on success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_field_name($result, $field_offset) {}

/**
 * Get name of the table the specified field is in
 * @link https://php.net/manual/en/function.mysql-field-table.php
 * @param resource $result
 * @param int $field_offset
 * @return string The name of the table on success.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_field_table($result, $field_offset) {}

/**
 * Returns the length of the specified field
 * @link https://php.net/manual/en/function.mysql-field-len.php
 * @param resource $result
 * @param int $field_offset
 * @return int|false The length of the specified field index on success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_field_len($result, $field_offset) {}

/**
 * Get the type of the specified field in a result
 * @link https://php.net/manual/en/function.mysql-field-type.php
 * @param resource $result
 * @param int $field_offset
 * @return string The returned field type
 * will be one of "int", "real",
 * "string", "blob", and others as
 * detailed in the MySQL
 * documentation.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_field_type($result, $field_offset) {}

/**
 * Get the flags associated with the specified field in a result
 * @link https://php.net/manual/en/function.mysql-field-flags.php
 * @param resource $result
 * @param int $field_offset
 * @return string|false a string of flags associated with the result or false on failure.
 * <p>
 * The following flags are reported, if your version of MySQL
 * is current enough to support them: "not_null",
 * "primary_key", "unique_key",
 * "multiple_key", "blob",
 * "unsigned", "zerofill",
 * "binary", "enum",
 * "auto_increment" and "timestamp".
 * </p>
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_field_flags($result, $field_offset) {}

/**
 * Escapes a string for use in a mysql_query
 * @link https://php.net/manual/en/function.mysql-escape-string.php
 * @param string $unescaped_string <p>
 * The string that is to be escaped.
 * </p>
 * @return string the escaped string.
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_real_escape_string(%parameter0%)', since: '5.3')]
function mysql_escape_string($unescaped_string) {}

/**
 * Escapes special characters in a string for use in an SQL statement
 * @link https://php.net/manual/en/function.mysql-real-escape-string.php
 * @param string $unescaped_string <p>
 * The string that is to be escaped.
 * </p>
 * @param resource $link_identifier [optional]
 * @return string|false the escaped string, or false on error.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_real_escape_string($unescaped_string, $link_identifier = null) {}

/**
 * Get current system status
 * @link https://php.net/manual/en/function.mysql-stat.php
 * @param resource $link_identifier [optional]
 * @return string a string with the status for uptime, threads, queries, open tables,
 * flush tables and queries per second. For a complete list of other status
 * variables, you have to use the SHOW STATUS SQL command.
 * If <i>link_identifier</i> is invalid, null is returned.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_stat($link_identifier = null) {}

/**
 * Return the current thread ID
 * @link https://php.net/manual/en/function.mysql-thread-id.php
 * @param resource $link_identifier [optional]
 * @return int|false The thread ID on success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_thread_id($link_identifier = null) {}

/**
 * Returns the name of the character set
 * @link https://php.net/manual/en/function.mysql-client-encoding.php
 * @param resource $link_identifier [optional]
 * @return string the default character set name for the current connection.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_client_encoding($link_identifier = null) {}

/**
 * Ping a server connection or reconnect if there is no connection
 * @link https://php.net/manual/en/function.mysql-ping.php
 * @param resource $link_identifier [optional]
 * @return bool true if the connection to the server MySQL server is working,
 * otherwise false.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_ping($link_identifier = null) {}

/**
 * Get MySQL client info
 * @link https://php.net/manual/en/function.mysql-get-client-info.php
 * @return string The MySQL client version.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_get_client_info() {}

/**
 * Get MySQL host info
 * @link https://php.net/manual/en/function.mysql-get-host-info.php
 * @param resource $link_identifier [optional]
 * @return string a string describing the type of MySQL connection in use for the
 * connection or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_get_host_info($link_identifier = null) {}

/**
 * Get MySQL protocol info
 * @link https://php.net/manual/en/function.mysql-get-proto-info.php
 * @param resource $link_identifier [optional]
 * @return int|false the MySQL protocol on success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_get_proto_info($link_identifier = null) {}

/**
 * Get MySQL server info
 * @link https://php.net/manual/en/function.mysql-get-server-info.php
 * @param resource $link_identifier [optional]
 * @return string|false the MySQL server version on success or false on failure.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_get_server_info($link_identifier = null) {}

/**
 * Get information about the most recent query
 * @link https://php.net/manual/en/function.mysql-info.php
 * @param resource $link_identifier [optional]
 * @return string|false information about the statement on success, or false on
 * failure. See the example below for which statements provide information,
 * and what the returned value may look like. Statements that are not listed
 * will return false.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_info($link_identifier = null) {}

/**
 * Sets the client character set
 * @link https://php.net/manual/en/function.mysql-set-charset.php
 * @param string $charset <p>
 * A valid character set name.
 * </p>
 * @param resource $link_identifier [optional]
 * @return bool true on success or false on failure.
 * @since 5.2.3
 * @removed 7.0
 * @see mysqli_set_charset()
 */
#[Deprecated(replacement: 'Use mysqli_set_charset instead', since: '5.5')]
function mysql_set_charset($charset, $link_identifier = null) {}

/**
 * @param $database_name
 * @param $query
 * @param $link_identifier [optional]
 * @removed 7.0
 */
#[Deprecated(replacement: "mysql_db_query(%parametersList%)", since: '5.3')]
function mysql($database_name, $query, $link_identifier) {}

/**
 * @param $result
 * @param $field_index
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_field_name(%parametersList%)', since: '5.5')]
function mysql_fieldname($result, $field_index) {}

/**
 * @param $result
 * @param $field_offset
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_field_table(%parametersList%)', since: '5.5')]
function mysql_fieldtable($result, $field_offset) {}

/**
 * @param $result
 * @param $field_offset
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_field_len(%parametersList%)', since: '5.5')]
function mysql_fieldlen($result, $field_offset) {}

/**
 * @param $result
 * @param $field_offset
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_field_type(%parametersList%)', since: '5.5')]
function mysql_fieldtype($result, $field_offset) {}

/**
 * @param $result
 * @param $field_offset
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_field_flags(%parametersList%)', since: '5.5')]
function mysql_fieldflags($result, $field_offset) {}

/**
 * @param $database_name
 * @param $link_identifier [optional]
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_select_db(%parametersList%)', since: '5.5')]
function mysql_selectdb($database_name, $link_identifier) {}

/**
 * @param $result
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_free_result(%parametersList%)', since: '5.5')]
function mysql_freeresult($result) {}

/**
 * @param $result
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_num_fields(%parametersList%)', since: '5.5')]
function mysql_numfields($result) {}

/**
 * (PHP 4, PHP 5)
 * Alias of mysql_num_rows()
 * @link https://php.net/manual/en/function.mysql-num-rows.php
 * @param resource $result <p>The result resource that is being evaluated. This result comes from a call to mysql_query().</p>
 * @return int|false <p>The number of rows in the result set on success or FALSE on failure. </p>
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_num_rows(%parametersList%)', since: '5.5')]
function mysql_numrows($result) {}

/**
 * @param $link_identifier [optional]
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_list_dbs(%parametersList%)', since: '5.5')]
function mysql_listdbs($link_identifier) {}

/**
 * @param $database_name
 * @param $link_identifier [optional]
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_list_tables(%parametersList%)', since: '5.5')]
function mysql_listtables($database_name, $link_identifier) {}

/**
 * @param $database_name
 * @param $table_name
 * @param $link_identifier [optional]
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_list_fields(%parametersList%)', since: '5.5')]
function mysql_listfields($database_name, $table_name, $link_identifier) {}

/**
 * Retrieves database name from the call to {@see mysql_list_dbs}
 * @link https://php.net/manual/en/function.mysql-db-name.php
 * @param resource $result <p>
 * The result pointer from a call to <b>mysql_list_dbs</b>.
 * </p>
 * @param int $row <p>
 * The index into the result set.
 * </p>
 * @param mixed $field [optional] <p>
 * The field name.
 * </p>
 * @return string|false the database name on success, and false on failure. If false
 * is returned, use <b>mysql_error</b> to determine the nature
 * of the error.
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_db_name($result, $row, $field = null) {}

/**
 * @param $result
 * @param $row
 * @param $field [optional]
 * @removed 7.0
 */
#[Deprecated(replacement: 'mysql_db_name(%parametersList%)', since: '5.5')]
function mysql_dbname($result, $row, $field) {}

/**
 * Get table name of field
 * @link https://php.net/manual/en/function.mysql-tablename.php
 * @param resource $result <p>
 * A result pointer resource that's returned from
 * <b>mysql_list_tables</b>.
 * </p>
 * @param int $i <p>
 * The integer index (row/table number)
 * </p>
 * @return string|false The name of the table on success or false on failure.
 * <p>
 * Use the <b>mysql_tablename</b> function to
 * traverse this result pointer, or any function for result tables,
 * such as <b>mysql_fetch_array</b>.
 * </p>
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_tablename($result, $i) {}

/**
 * @param $result
 * @param $row
 * @param $field [optional]
 * @removed 7.0
 */
#[Deprecated(since: '5.5')]
function mysql_table_name($result, $row, $field) {}

/**
 * Columns are returned into the array having the fieldname as the array
 * index.
 * @link https://php.net/manual/en/mysql.constants.php
 * @deprecated 5.5
 * @removed 7.0
 */
define('MYSQL_ASSOC', 1);

/**
 * Columns are returned into the array having a numerical index to the
 * fields. This index starts with 0, the first field in the result.
 * @link https://php.net/manual/en/mysql.constants.php
 * @deprecated 5.5
 * @removed 7.0
 */
define('MYSQL_NUM', 2);

/**
 * Columns are returned into the array having both a numerical index
 * and the fieldname as the array index.
 * @link https://php.net/manual/en/mysql.constants.php
 * @deprecated 5.5
 * @removed 7.0
 */
define('MYSQL_BOTH', 3);

/**
 * Use compression protocol
 * @link https://php.net/manual/en/mysql.constants.php
 * @deprecated 5.5
 * @removed 7.0
 */
define('MYSQL_CLIENT_COMPRESS', 32);

/**
 * Use SSL encryption. This flag is only available with version 4.x
 * of the MySQL client library or newer. Version 3.23.x is bundled both
 * with PHP 4 and Windows binaries of PHP 5.
 * @link https://php.net/manual/en/mysql.constants.php
 * @deprecated 5.5
 * @removed 7.0
 */
define('MYSQL_CLIENT_SSL', 2048);

/**
 * Allow interactive_timeout seconds (instead of wait_timeout) of
 * inactivity before closing the connection.
 * @link https://php.net/manual/en/mysql.constants.php
 * @deprecated 5.5
 * @removed 7.0
 */
define('MYSQL_CLIENT_INTERACTIVE', 1024);

/**
 * Allow space after function names
 * @link https://php.net/manual/en/mysql.constants.php
 * @deprecated 5.5
 * @removed 7.0
 */
define('MYSQL_CLIENT_IGNORE_SPACE', 256);

// End of mysql v.1.0
