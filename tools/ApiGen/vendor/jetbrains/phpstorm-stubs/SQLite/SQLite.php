<?php

// Start of SQLite v.2.0-dev
use JetBrains\PhpStorm\Pure;

/**
 * @link https://php.net/manual/en/ref.sqlite.php
 */
class SQLiteDatabase
{
    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * @link https://php.net/manual/en/function.sqlite-open.php
     * @param string $filename <p>The filename of the SQLite database. If the file does not exist, SQLite will attempt to create it. PHP must have write permissions to the file if data is inserted, the database schema is modified or to create the database if it does not exist.</p>
     * @param int $mode [optional] <p>The mode of the file. Intended to be used to open the database in read-only mode. Presently, this parameter is ignored by the sqlite library. The default value for mode is the octal value 0666 and this is the recommended value.</p>
     * @param string &$error_message [optional] <p>Passed by reference and is set to hold a descriptive error message explaining why the database could not be opened if there was an error.</p>
     */
    final public function __construct($filename, $mode = 0666, &$error_message) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * @link https://php.net/manual/en/function.sqlite-query.php
     * @param string $query <p>
     * The query to be executed.
     * </p>
     * <p>
     * Data inside the query should be {@link https://php.net/manual/en/function.sqlite-escape-string.php properly escaped}.
     * </p>
     * @param int $result_type [optional]
     * <p>The optional <i>result_type</i> parameter accepts a constant and determines how the returned array will be indexed. Using <b>SQLITE_ASSOC</b> will return only associative indices (named fields) while <b>SQLITE_NUM</b> will return only numerical indices (ordinal field numbers). <b>SQLITE_BOTH</b> will return both associative and numerical indices. <b>SQLITE_BOTH</b> is the default for this function.</p>
     * @param string &$error_message [optional] <p>The specified variable will be filled if an error occurs. This is specially important because SQL syntax errors can't be fetched using the {@see sqlite_last_error()} function.</p>
     * @return resource|false <p>
     * This function will return a result handle or <b>FALSE</b> on failure.
     * For queries that return rows, the result handle can then be used with
     * functions such as {@see sqlite_fetch_array()} and
     * {@see sqlite_seek()}.
     * </p>
     * <p>
     * Regardless of the query type, this function will return <b>FALSE</b> if the
     * query failed.
     * </p>
     * <p>
     * {@see sqlite_query()} returns a buffered, seekable result
     * handle.  This is useful for reasonably small queries where you need to
     * be able to randomly access the rows.  Buffered result handles will
     * allocate memory to hold the entire result and will not return until it
     * has been fetched.  If you only need sequential access to the data, it is
     * recommended that you use the much higher performance
     * {@see sqlite_unbuffered_query()} instead.
     * </p>
     */
    public function query($query, $result_type, &$error_message) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * @link https://php.net/manual/en/function.sqlite-exec.php
     * @param string $query <p>
     * The query to be executed.
     * </p>
     * <p>
     * Data inside the query should be {@link https://php.net/manual/en/function.sqlite-escape-string.php properly escaped}.
     * </p>
     * @param string &$error_message [optional] <p>The specified variable will be filled if an error occurs. This is specially important because SQL syntax errors can't be fetched using the
     * {@see sqlite_last_error()} function.</p>
     * @return bool <p>
     * This function will return a boolean result; <b>TRUE</b> for success or <b>FALSE</b> for failure.
     * If you need to run a query that returns rows, see {@see sqlite_query()}.
     * </p>
     * <p>The column names returned by
     * <b>SQLITE_ASSOC</b> and <b>SQLITE_BOTH</b> will be
     * case-folded according to the value of the
     * {@link https://php.net/manual/en/sqlite.configuration.php#ini.sqlite.assoc-case sqlite.assoc_case} configuration
     * option.</p>
     */
    public function queryExec($query, &$error_message) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Execute a query against a given database and returns an array
     * @link https://php.net/manual/en/function.sqlite-array-query.php
     * @param string $query <p>
     * The query to be executed.
     * </p>
     * <p>
     * Data inside the query should be {@link https://php.net/manual/en/function.sqlite-escape-string.php properly escaped}.
     * </p>
     * @param int $result_type [optional] <p>The optional <i>result_type</i>
     * parameter accepts a constant and determines how the returned array will be
     * indexed. Using <b>SQLITE_ASSOC</b> will return only associative
     * indices (named fields) while <b>SQLITE_NUM</b> will return
     * only numerical indices (ordinal field numbers). <b>SQLITE_BOTH</b>
     * will return both associative and numerical indices.
     * <b>SQLITE_BOTH</b> is the default for this function.</p>
     * @param bool $decode_binary [optional] <p>When the <i>decode_binary</i>
     * parameter is set to <b>TRUE</b> (the default), PHP will decode the binary encoding
     * it applied to the data if it was encoded using the
     * {@see sqlite_escape_string()}.  You should normally leave this
     * value at its default, unless you are interoperating with databases created by
     * other sqlite capable applications.</p>
     * <p>
     * @return array|false
     * Returns an array of the entire result set; <b>FALSE</b> otherwise.
     * </p>
     * <p>The column names returned by
     * <b>SQLITE_ASSOC</b> and <b>SQLITE_BOTH</b> will be
     * case-folded according to the value of the
     * {@link https://php.net/manual/en/sqlite.configuration.php#ini.sqlite.assoc-case sqlite.assoc_case} configuration
     * option.</p>
     */
    public function arrayQuery($query, $result_type, $decode_binary) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.1)
     * Executes a query and returns either an array for one single column or the value of the first row
     * @link https://php.net/manual/en/function.sqlite-single-query.php
     * @param string $query
     * @param bool $first_row_only [optional]
     * @param bool $decode_binary [optional]
     * @return array
     */
    public function singleQuery($query, $first_row_only, $decode_binary) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Execute a query that does not prefetch and buffer all data
     * @link https://php.net/manual/en/function.sqlite-unbuffered-query.php
     * @param string $query  <p>
     * The query to be executed.
     * </p>
     * <p>
     * Data inside the query should be {@link https://php.net/manual/en/function.sqlite-escape-string.php properly escaped}.
     * </p>
     * @param int $result_type [optional] <p>The optional <i>result_type</i> parameter accepts a constant and determines how the returned array will be indexed.
     * Using <b>SQLITE_ASSOC</b> will return only associative indices (named fields) while <b>SQLITE_NUM</b> will return only numerical indices (ordinal field numbers).
     * <b>SQLITE_BOTH</b> will return both associative and numerical indices. <b>SQLITE_BOTH</b> is the default for this function.
     * </p>
     * @param string &$error_message [optional]
     * @return resource Returns a result handle or <b>FALSE</b> on failure.
     * {@see sqlite_unbuffered_query()} returns a sequential forward-only result set that can only be used to read each row, one after the other.
     */
    public function unbufferedQuery($query, $result_type = SQLITE_BOTH, &$error_message = null) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Returns the rowid of the most recently inserted row
     * @link https://php.net/manual/en/function.sqlite-last-insert-rowid.php
     * @return int Returns the row id, as an integer.
     */
    public function lastInsertRowid() {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Returns the number of rows that were changed by the most recent SQL statement
     * @link https://php.net/manual/en/function.sqlite-changes.php
     * @return int Returns the number of changed rows.
     */
    public function changes() {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Register an aggregating UDF for use in SQL statements
     * @link https://php.net/manual/en/function.sqlite-create-aggregate.php
     * @param string $function_name <p>The name of the function used in SQL statements.</p>
     * @param callable $step_func <p>Callback function called for each row of the result set. Function parameters are &$context, $value, ....</p>
     * @param callable $finalize_func <p>Callback function to aggregate the "stepped" data from each row. Function parameter is &$context and the function should return the final result of aggregation.</p>
     * @param int $num_args [optional] <p>Hint to the SQLite parser if the callback function accepts a predetermined number of arguments.</p>
     */
    public function createAggregate($function_name, $step_func, $finalize_func, $num_args = -1) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Registers a "regular" User Defined Function for use in SQL statements
     * @link https://php.net/manual/en/function.sqlite-create-function.php
     * @param string $function_name <p>The name of the function used in SQL statements.</p>
     * @param callable $callback <p>
     * Callback function to handle the defined SQL function.
     * </p>
     * <blockquote><p><b>Note</b>:
     * Callback functions should return a type understood by SQLite (i.e.
     * {@link https://php.net/manual/en/language.types.intro.php scalar type}).
     * </p></blockquote>
     * @param int $num_args [optional]   <blockquote><p><b>Note</b>: Two alternative syntaxes are
     * supported for compatibility with other database extensions (such as MySQL).
     * The preferred form is the first, where the <i>dbhandle</i>
     * parameter is the first parameter to the function.</p></blockquote>
     */
    public function createFunction($function_name, $callback, $num_args = -1) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Set busy timeout duration, or disable busy handlers
     * @link https://php.net/manual/en/function.sqlite-busy-timeout.php
     * @param int $milliseconds <p> The number of milliseconds. When set to 0, busy handlers will be disabled and SQLite will return immediately with a <b>SQLITE_BUSY</b> status code if another process/thread has the database locked for an update.
     * PHP sets the default busy timeout to be 60 seconds when the database is opened.</p>
     * @return int <p>Returns an error code, or 0 if no error occurred.</p>
     */
    public function busyTimeout($milliseconds) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Returns the error code of the last error for a database
     * @link https://php.net/manual/en/function.sqlite-last-error.php
     * @return int Returns an error code, or 0 if no error occurred.
     */
    public function lastError() {}

    /**
     * (PHP 5 &lt; 5.4.0)
     * Return an array of column types from a particular table
     * @link https://php.net/manual/en/function.sqlite-fetch-column-types.php
     * @param string $table_name <p>The table name to query.</p>
     * @param int $result_type [optional] <p>
     * The optional <i>result_type</i> parameter accepts a
     * constant and determines how the returned array will be indexed. Using
     * <b>SQLITE_ASSOC</b> will return only associative indices
     * (named fields) while <b>SQLITE_NUM</b> will return only
     * numerical indices (ordinal field numbers).
     * <b>SQLITE_ASSOC</b> is the default for
     * this function.
     * </p>
     * @return array <p>
     * Returns an array of column data types; <b>FALSE</b> on error.
     * </p>
     * <p>The column names returned by
     * <b>SQLITE_ASSOC</b> and <b>SQLITE_BOTH</b> will be
     * case-folded according to the value of the
     * {@link https://php.net/manual/en/sqlite.configuration.php#ini.sqlite.assoc-case sqlite.assoc_case} configuration
     * option.</p>
     */
    public function fetchColumnTypes($table_name, $result_type = SQLITE_ASSOC) {}
}

/**
 * @link https://php.net/manual/en/ref.sqlite.php
 */
final class SQLiteResult implements Iterator, Countable
{
    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Fetches the next row from a result set as an array
     * @link https://php.net/manual/en/function.sqlite-fetch-array.php
     * @param int $result_type [optional]
     * <p>
     * The optional <i>result_type</i>
     * parameter accepts a constant and determines how the returned array will be
     * indexed. Using <b>SQLITE_ASSOC</b> will return only associative
     * indices (named fields) while <b>SQLITE_NUM</b> will return
     * only numerical indices (ordinal field numbers). <b>SQLITE_BOTH</b>
     * will return both associative and numerical indices.
     * <b>SQLITE_BOTH</b> is the default for this function.
     * </p>
     * @param bool $decode_binary [optional] <p>When the <i>decode_binary</i>
     * parameter is set to <b>TRUE</b> (the default), PHP will decode the binary encoding
     * it applied to the data if it was encoded using the
     * {@link https://php.net/manual/en/sqlite.configuration.php#ini.sqlite.assoc-case sqlite.assoc_case}. You should normally leave this
     * value at its default, unless you are interoperating with databases created by
     * other sqlite capable applications.</p>
     * @return array
     * <p>
     * Returns an array of the next row from a result set; <b>FALSE</b> if the
     * next position is beyond the final row.
     * </p>
     * <p>The column names returned by
     * <b>SQLITE_ASSOC</b> and <b>SQLITE_BOTH</b> will be
     * case-folded according to the value of the
     * {@link https://php.net/manual/en/sqlite.configuration.php#ini.sqlite.assoc-case sqlite.assoc_case}  configuration
     * option.</p>
     */
    public function fetch($result_type = SQLITE_BOTH, $decode_binary = true) {}

    /**
     * (PHP 5 &lt; 5.4.0)
     * Fetches the next row from a result set as an object
     * @link https://php.net/manual/en/function.sqlite-fetch-object.php
     * @param string $class_name [optional]
     * @param array $ctor_params [optional]
     * @param bool $decode_binary [optional]
     * @return object
     */
    public function fetchObject($class_name, $ctor_params, $decode_binary = true) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.1)
     * Fetches the first column of a result set as a string
     * @link https://php.net/manual/en/function.sqlite-fetch-single.php
     * @param bool $decode_binary [optional]
     * @return string <p>Returns the first column value, as a string.</p>
     */
    public function fetchSingle($decode_binary = true) {}

    /**
     * (PHP 5 &lt; 5.4.0)
     * Fetches the next row from a result set as an object
     * @link https://www.php.net/manual/en/function.sqlite-fetch-all.php
     * @param int $result_type [optional] <p>
     * The optional result_type parameter accepts a constant and determines how the returned array will be indexed.
     * Using SQLITE_ASSOC will return only associative indices (named fields) while SQLITE_NUM will return only numerical indices (ordinal field numbers).
     * {@see SQLITE_BOTH} will return both associative and numerical indices. {@see SQLITE_BOTH} is the default for this function.</p>
     * @param bool $decode_binary [optional] <p> When the decode_binary parameter is set to TRUE (the default),
     * PHP will decode the binary encoding it applied to the data if it was encoded using the {@see sqlite_escape_string()}.
     * You should normally leave this value at its default, unless you are interoperating with databases created by other sqlite capable applications.</p>
     * @return object
     */
    public function fetchAll($result_type, $decode_binary = true) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Fetches a column from the current row of a result set
     * @link https://php.net/manual/en/function.sqlite-column.php
     * @param $index_or_name
     * @param $decode_binary [optional] <p>When the <i>decode_binary</i>
     * parameter is set to <b>TRUE</b> (the default), PHP will decode the binary encoding
     * it applied to the data if it was encoded using the
     * {@see sqlite_escape_string()}.  You should normally leave this
     * value at its default, unless you are interoperating with databases created by
     * other sqlite capable applications.</p>
     * @return mixed <p>Returns the column value</p>
     */
    public function column($index_or_name, $decode_binary = true) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Returns the number of fields in a result set
     * @link https://php.net/manual/en/function.sqlite-num-fields.php
     * @return int <p>Returns the number of fields, as an integer.</p>
     */
    public function numFields() {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Returns the name of a particular field
     * @link https://php.net/manual/en/function.sqlite-field-name.php
     * @param int $field_index <p>The ordinal column number in the result set.</p>
     * @return string <p>
     * Returns the name of a field in an SQLite result set, given the ordinal
     * column number; <b>FALSE</b> on error.
     * </p>
     * <p>The column names returned by
     * <b>SQLITE_ASSOC</b> and <b>SQLITE_BOTH</b> will be
     * case-folded according to the value of the
     * {@link https://php.net/manual/en/sqlite.configuration.php#ini.sqlite.assoc-case sqlite.assoc_case}configuration
     * option.</p>
     */
    public function fieldName($field_index) {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Fetches the current row from a result set as an array
     * @link https://php.net/manual/en/function.sqlite-current.php
     * @param int $result_type [optional] <p>The optional <i>result_type</i>
     * parameter accepts a constant and determines how the returned array will be
     * indexed. Using {@see SQLITE_ASSOC} will return only associative
     * indices (named fields) while {@see SQLITE_NUM} will return
     * only numerical indices (ordinal field numbers). <b>SQLITE_BOTH</b>
     * will return both associative and numerical indices.
     * {@see SQLITE_BOTH} is the default for this function.</p>
     * @param bool $decode_binary [optional] <p>When the <i>decode_binary</i>
     * parameter is set to <b>TRUE</b> (the default), PHP will decode the binary encoding
     * it applied to the data if it was encoded using the
     * {@see sqlite_escape_string()}.  You should normally leave this
     * value at its default, unless you are interoperating with databases created by
     * other sqlite capable applications.</p>
     * @return array <p>
     * Returns an array of the current row from a result set; <b>FALSE</b> if the
     * current position is beyond the final row.
     * </p>
     * <p>The column names returned by
     * <b>SQLITE_ASSOC</b> and <b>SQLITE_BOTH</b> will be
     * case-folded according to the value of the
     * {@link https://php.net/manual/en/sqlite.configuration.php#ini.sqlite.assoc-case sqlite.assoc_case} configuration
     * option.</p>
     */
    public function current($result_type = SQLITE_BOTH, $decode_binary = true) {}

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key() {}

    /**
     * Seek to the next row number
     * @link https://php.net/manual/en/function.sqlite-next.php
     * @return bool Returns <b>TRUE</b> on success, or <b>FALSE</b> if there are no more rows.
     * @since 5.0.0
     */
    public function next() {}

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool <p>
     * Returns <b>TRUE</b> if there are more rows available from the
     * <i>result</i> handle, or <b>FALSE</b> otherwise.
     * </p>
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

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int <p>The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * </p>
     * @since 5.1.0
     */
    public function count() {}

    /**
     * Seek to the previous row number of a result set
     * @link https://php.net/manual/en/function.sqlite-prev.php
     * @return bool <p> Returns <b>TRUE</b> on success, or <b>FALSE</b> if there are no more previous rows.
     * </p>
     * @since 5.4.0
     */
    public function prev() {}

    /**
     *@since 5.4.0
     * Returns whether or not a previous row is available
     * @link https://php.net/manual/en/function.sqlite-has-prev.php
     * @return bool <p>
     * Returns <b>TRUE</b> if there are more previous rows available from the
     * <i>result</i> handle, or <b>FALSE</b> otherwise.
     * </p>
     */
    public function hasPrev() {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Returns the number of rows in a buffered result set
     * @link https://php.net/manual/en/function.sqlite-num-rows.php
     * @return int Returns the number of rows, as an integer.
     */
    public function numRows() {}

    /**
     * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)
     * Seek to a particular row number of a buffered result set
     * @link https://php.net/manual/en/function.sqlite-seek.php
     * @param $row
     * <p>
     * The ordinal row number to seek to.  The row number is zero-based (0 is
     * the first row).
     * </p>
     * <blockquote><p><b>Note</b>: </p><p>This function cannot be used with
     * unbuffered result handles.</p></blockquote>
     */
    public function seek($row) {}
}

/**
 * Represents an unbuffered SQLite result set. Unbuffered results sets are sequential, forward-seeking only.
 * @link https://php.net/manual/en/ref.sqlite.php
 */
final class SQLiteUnbuffered
{
    /**
     * @param int $result_type [optional]
     * @param bool $decode_binary [optional]
     */
    public function fetch($result_type, $decode_binary) {}

    /**
     * @link https://www.php.net/manual/en/function.sqlite-fetch-object.php
     * @param string $class_name [optional]
     * @param array $ctor_params [optional]
     * @param bool $decode_binary [optional]
     */
    public function fetchObject($class_name, $ctor_params, $decode_binary) {}

    /**
     * @param bool $decode_binary [optional]
     */
    public function fetchSingle($decode_binary) {}

    /**
     * @param int $result_type [optional]
     * @param bool $decode_binary [optional]
     */
    public function fetchAll($result_type, $decode_binary) {}

    /**
     * @param $index_or_name
     * @param $decode_binary [optional]
     */
    public function column($index_or_name, $decode_binary) {}

    public function numFields() {}

    /**
     * @param int $field_index
     */
    public function fieldName($field_index) {}

    /**
     * @param int $result_type [optional]
     * @param bool $decode_binary [optional]
     */
    public function current($result_type, $decode_binary) {}

    public function next() {}

    public function valid() {}
}

final class SQLiteException extends RuntimeException
{
    protected $message;
    protected $code;
    protected $file;
    protected $line;

    /**
     * Clone the exception
     * @link https://php.net/manual/en/exception.clone.php
     * @return void
     * @since 5.1.0
     */
    final private function __clone() {}

    /**
     * Construct the exception
     * @link https://php.net/manual/en/exception.construct.php
     * @param $message [optional]
     * @param $code [optional]
     * @param $previous [optional]
     * @since 5.1.0
     */
    #[Pure]
    public function __construct($message, $code, $previous) {}

    /**
     * String representation of the exception
     * @link https://php.net/manual/en/exception.tostring.php
     * @return string the string representation of the exception.
     * @since 5.1.0
     */
    public function __toString() {}
}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Opens a SQLite database and create the database if it does not exist
 * @link https://php.net/manual/en/function.sqlite-open.php
 * @param string $filename <p>
 * The filename of the SQLite database. If the file does not exist, SQLite
 * will attempt to create it. PHP must have write permissions to the file
 * if data is inserted, the database schema is modified or to create the
 * database if it does not exist.
 * </p>
 * @param int $mode [optional] <p>
 * The mode of the file. Intended to be used to open the database in
 * read-only mode. Presently, this parameter is ignored by the sqlite
 * library. The default value for mode is the octal value
 * 0666 and this is the recommended value.
 * </p>
 * @param string &$error_message [optional] <p>
 * Passed by reference and is set to hold a descriptive error message
 * explaining why the database could not be opened if there was an error.
 * </p>
 * @return resource|false a resource (database handle) on success, false on error.
 */
function sqlite_open($filename, $mode = null, &$error_message = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Opens a persistent handle to an SQLite database and create the database if it does not exist
 * @link https://php.net/manual/en/function.sqlite-popen.php
 * @param string $filename <p>
 * The filename of the SQLite database. If the file does not exist, SQLite
 * will attempt to create it. PHP must have write permissions to the file
 * if data is inserted, the database schema is modified or to create the
 * database if it does not exist.
 * </p>
 * @param int $mode [optional] <p>
 * The mode of the file. Intended to be used to open the database in
 * read-only mode. Presently, this parameter is ignored by the sqlite
 * library. The default value for mode is the octal value
 * 0666 and this is the recommended value.
 * </p>
 * @param string &$error_message [optional] <p>
 * Passed by reference and is set to hold a descriptive error message
 * explaining why the database could not be opened if there was an error.
 * </p>
 * @return resource|false <p>a resource (database handle) on success, false on error.</p>
 */
function sqlite_popen($filename, $mode = null, &$error_message = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Closes an open SQLite database
 * @link https://php.net/manual/en/function.sqlite-close.php
 * @param resource $dbhandle <p>
 * The SQLite Database resource; returned from sqlite_open
 * when used procedurally.
 * </p>
 * @return void
 */
function sqlite_close($dbhandle) {}

/**
 * (PHP 5 &lt; 5.4.0, PECL sqlite &gt;= 1.0.0)<br/>
 * Executes a query against a given database and returns a result handle
 * there are two signatures with <i>$query</i> first and with <i>$dbhandle</i> first.
 * @link https://php.net/manual/en/function.sqlite-query.php
 * @param string|resource $query <p>
 * The query to be executed.
 * </p>
 * <p>
 * Data inside the query should be properly escaped.
 * </p>
 * @param resource|string $dbhandle The SQLite Database resource; returned from sqlite_open() when used procedurally. This parameter is not required when using the object-oriented method.
 * @param int $result_type [optional] <p>The optional <i>result_type</i>
 * parameter accepts a constant and determines how the returned array will be
 * indexed. Using <b>SQLITE_ASSOC</b> will return only associative
 * indices (named fields) while <b>SQLITE_NUM</b> will return
 * only numerical indices (ordinal field numbers). <b>SQLITE_BOTH</b>
 * will return both associative and numerical indices.
 * <b>SQLITE_BOTH</b> is the default for this function.</p>
 * @param string &$error_msg [optional] <p>
 * The specified variable will be filled if an error occurs. This is
 * specially important because SQL syntax errors can't be fetched using
 * the
 * {@see sqlite_last_error} function.
 * </p>
 * @return resource|false  This function will return a result handle or <b>FALSE</b> on failure.
 * For queries that return rows, the result handle can then be used with
 * functions such as
 * {@see sqlite_fetch_array} and
 * {@see sqlite_seek}.
 * </p>
 * <p>
 * Regardless of the query type, this function will return false if the
 * query failed.
 * </p>
 * <p>
 * {@see sqlite_query} returns a buffered, seekable result
 * handle. This is useful for reasonably small queries where you need to
 * be able to randomly access the rows. Buffered result handles will
 * allocate memory to hold the entire result and will not return until it
 * has been fetched. If you only need sequential access to the data, it is
 * recommended that you use the much higher performance
 * {@see sqlite_unbuffered_query} instead.
 */
function sqlite_query($query, $dbhandle, $result_type = SQLITE_BOTH, &$error_msg = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.3)<br/>
 * Executes a result-less query against a given database
 * @link https://php.net/manual/en/function.sqlite-exec.php
 * @param string $query <p>
 * The query to be executed.
 * </p>
 * <p>
 * Data inside the query should be properly escaped.
 * </p>
 * @param resource $dbhandle <p>
 * The SQLite Database resource; returned from
 * {@see sqlite_open()} when used procedurally. This parameter
 * is not required when using the object-oriented method.
 * </p>
 * @param string &$error_msg [optional] <p>
 * The specified variable will be filled if an error occurs. This is
 * specially important because SQL syntax errors can't be fetched using
 * the
 * {@see sqlite_last_error} function.
 * </p>
 * @return bool <p>This function will return a boolean result; true for success or false for failure.
 * If you need to run a query that returns rows, see sqlite_query.</p>
 */
function sqlite_exec($dbhandle, $query, &$error_msg = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Execute a query against a given database and returns an array
 * @link https://php.net/manual/en/function.sqlite-array-query.php
 * @param string $query <p>
 * The query to be executed.
 * </p>
 * <p>
 * Data inside the query should be properly escaped.
 * </p>
 * @param resource $dbhandle <p>
 * The SQLite Database resource; returned from
 * {@see sqlite_open()}
 * when used procedurally.  This parameter is not required
 * when using the object-oriented method.
 * </p>
 * @param int $result_type [optional] <p>The optional <i>result_type</i>
 * parameter accepts a constant and determines how the returned array will be
 * indexed. Using <b>SQLITE_ASSOC</b> will return only associative
 * indices (named fields) while <b>SQLITE_NUM</b> will return
 * only numerical indices (ordinal field numbers). <b>SQLITE_BOTH</b>
 * will return both associative and numerical indices.
 * <b>SQLITE_BOTH</b> is the default for this function.</p>
 * @param bool $decode_binary [optional] <p>When the <i>decode_binary</i>
 * parameter is set to <b>TRUE</b> (the default), PHP will decode the binary encoding
 * it applied to the data if it was encoded using the
 * {@link sqlite_escape_string()}.  You should normally leave this
 * value at its default, unless you are interoperating with databases created by
 * other sqlite capable applications.</p>
 * @return array|false an array of the entire result set; false otherwise.
 * <p>The column names returned by
 * <b>SQLITE_ASSOC</b> and <b>SQLITE_BOTH</b> will be
 * case-folded according to the value of the
 * {@link php.net/en/sqlite.configuration.php#ini.sqlite.assoc-case sqlite.assoc_case} configuration
 * option.</p>
 */
function sqlite_array_query($dbhandle, $query, $result_type = null, $decode_binary = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.1)<br/>
 * Executes a query and returns either an array for one single column or the value of the first row
 * @link https://php.net/manual/en/function.sqlite-single-query.php
 * @param resource $db
 * @param string $query
 * @param bool $first_row_only [optional]
 * @param bool $decode_binary [optional]
 * @return array
 */
function sqlite_single_query($db, $query, $first_row_only = null, $decode_binary = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Fetches the next row from a result set as an array
 * @link https://php.net/manual/en/function.sqlite-fetch-array.php
 * @param resource $result <p>The SQLite result resource. This parameter is not required when using the object-oriented method.</p>
 * @param int $result_type [optional]
 * @param bool $decode_binary [optional]
 * @return array|false <p>an array of the next row from a result set; false if the
 * next position is beyond the final row.</p>
 */
function sqlite_fetch_array($result, $result_type = SQLITE_BOTH, $decode_binary = null) {}

/**
 * Fetches the next row from a result set as an object
 * @link https://php.net/manual/en/function.sqlite-fetch-object.php
 * @param resource $result
 * @param string $class_name [optional]
 * @param null|array $ctor_params [optional]
 * @param bool $decode_binary [optional]
 * @return object
 */
function sqlite_fetch_object($result, $class_name = null, ?array $ctor_params = null, $decode_binary = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.1)<br/>
 * Fetches the first column of a result set as a string
 * @link https://php.net/manual/en/function.sqlite-fetch-single.php
 * @param resource $result <p>The SQLite result resource. This parameter is not required when using the object-oriented method.</p>
 * @param bool $decode_binary [optional] <p>When the <b>decode_binary</b>
 * parameter is set to <b>TRUE</b> (the default), PHP will decode the binary encoding
 * it applied to the data if it was encoded using the
 * {@see sqlite_escape_string()}.  You should normally leave this
 * value at its default, unless you are interoperating with databases created by
 * other sqlite capable applications.</p>
 * @return string <p>the first column value, as a string.</p>
 */
function sqlite_fetch_single($result, $decode_binary = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Alias:
 * {@see sqlite_fetch_single}
 * @link https://php.net/manual/en/function.sqlite-fetch-string.php
 * @param resource $result <p>The SQLite result resource. This parameter is not required when using the object-oriented method.</p>
 * @param bool $decode_binary [optional] <p>When the <b>decode_binary</b>
 * parameter is set to <b>TRUE</b> (the default), PHP will decode the binary encoding
 * it applied to the data if it was encoded using the
 * {@see sqlite_escape_string()}.  You should normally leave this
 * value at its default, unless you are interoperating with databases created by
 * other sqlite capable applications.</p>
 * @return string <p>the first column value, as a string.</p>
 */
function sqlite_fetch_string($result, $decode_binary) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Fetches all rows from a result set as an array of arrays
 * @link https://php.net/manual/en/function.sqlite-fetch-all.php
 * @param int $result_type [optional]
 * @param bool $decode_binary [optional]
 * @return array <p>an array of the remaining rows in a result set. If called right
 * after
 * {@see sqlite_query}, it returns all rows. If called
 * after
 * {@see sqlite_fetch_array}, it returns the rest. If
 * there are no rows in a result set, it returns an empty array.</p>
 * <p>The column names returned by <b>SQLITE_ASSOC</b> and <b>SQLITE_BOTH</b> will be case-folded according to the value of the
 * {@link https://php.net/manual/en/sqlite.configuration.php#ini.sqlite.assoc-case sqlite.assoc_case} configuration option.</p>
 */
function sqlite_fetch_all($result_type = null, $decode_binary = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Fetches the current row from a result set as an array
 * @link https://php.net/manual/en/function.sqlite-current.php
 * @param resource $result <p>The SQLite result resource. This parameter is not required when using the object-oriented method.</p>
 * @param int $result_type [optional] <p>The optional result_type parameter accepts a constant and determines how the returned array will be indexed. Using <b>SQLITE_ASSOC</b> will return only associative indices (named fields) while <b>SQLITE_NUM</b> will return only numerical indices (ordinal field numbers). <b>SQLITE_BOTH</b> will return both associative and numerical indices. <b>SQLITE_BOTH</b> is the default for this function.</p>
 * @param bool $decode_binary [optional] <p>When the decode_binary parameter is set to <b>TRUE</b> (the default), PHP will decode the binary encoding it applied to the data if it was encoded using the sqlite_escape_string(). You should normally leave this value at its default, unless you are interoperating with databases created by other sqlite capable applications.</p>
 * @return array|false an array of the current row from a result set; false if the
 * current position is beyond the final row.
 */
function sqlite_current($result, $result_type = null, $decode_binary = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Fetches a column from the current row of a result set
 * @link https://php.net/manual/en/function.sqlite-column.php
 * @param resource $result <p>The SQLite result resource. This parameter is not required when using the object-oriented method.</p>
 * @param mixed $index_or_name <p>
 * The column index or name to fetch.
 * </p>
 * @param bool $decode_binary [optional] <p>When the <b>decode_binary</b>
 * parameter is set to <b>TRUE</b> (the default), PHP will decode the binary encoding
 * it applied to the data if it was encoded using the
 * {@see sqlite_escape_string()}.  You should normally leave this
 * value at its default, unless you are interoperating with databases created by
 * other sqlite capable applications.</p>
 * @return mixed the column value.
 */
function sqlite_column($result, $index_or_name, $decode_binary = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Returns the version of the linked SQLite library
 * @link https://php.net/manual/en/function.sqlite-libversion.php
 * @return string the library version, as a string.
 */
function sqlite_libversion() {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Returns the encoding of the linked SQLite library
 * @link https://php.net/manual/en/function.sqlite-libencoding.php
 * @return string the library encoding.
 */
function sqlite_libencoding() {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Returns the number of rows that were changed by the most
 * recent SQL statement
 * @link https://php.net/manual/en/function.sqlite-changes.php
 * @param $db
 * @return int the number of changed rows.
 */
function sqlite_changes($db) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Returns the rowid of the most recently inserted row
 * @link https://php.net/manual/en/function.sqlite-last-insert-rowid.php
 * @param resource $dbhandle <p>The SQLite Database resource; returned from
 * {@see sqlite_open()} when used procedurally. This parameter is not required when using the object-oriented method.</p>
 * @return int the row id, as an integer.
 */
function sqlite_last_insert_rowid($dbhandle) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Returns the number of rows in a buffered result set
 * @link https://php.net/manual/en/function.sqlite-num-rows.php
 * @param resource $result <p>
 * The SQLite result resource.  This parameter is not required when using
 * the object-oriented method.
 * </p>
 * <blockquote><p><b>Note</b>: </p><p>This function cannot be used with
 * unbuffered result handles.</p></blockquote>
 * @return int the number of rows, as an integer.
 */
function sqlite_num_rows($result) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Returns the number of fields in a result set
 * @link https://php.net/manual/en/function.sqlite-num-fields.php
 * @param resource $result <p>The SQLite result resource. This parameter is not required when using the object-oriented method.</p>
 * @return int the number of fields, as an integer.
 */
function sqlite_num_fields($result) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Returns the name of a particular field
 * @link https://php.net/manual/en/function.sqlite-field-name.php
 * @param resource $result <p>The SQLite result resource. This parameter is not required when using the object-oriented method.</p>
 * @param int $field_index <p>
 * The ordinal column number in the result set.
 * </p>
 * @return string the name of a field in an SQLite result set, given the ordinal
 * column number; false on error.
 */
function sqlite_field_name($result, $field_index) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Seek to a particular row number of a buffered result set
 * @link https://php.net/manual/en/function.sqlite-seek.php
 * @param resource $result <p>
 * The SQLite result resource.  This parameter is not required when using
 * the object-oriented method.
 * </p>
 * <blockquote><p><b>Note</b>: </p><p>This function cannot be used with
 * unbuffered result handles.</p></blockquote>
 * @param int $rownum <p>
 * The ordinal row number to seek to. The row number is zero-based (0 is
 * the first row).
 * </p>
 * @return bool false if the row does not exist, true otherwise.
 */
function sqlite_seek($result, $rownum) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Seek to the first row number
 * @link https://php.net/manual/en/function.sqlite-rewind.php
 * @param resource $result <p>
 * The SQLite result resource.  This parameter is not required when using
 * the object-oriented method.
 * </p>
 * <blockquote><p><b>Note</b>: </p><p>This function cannot be used with
 * unbuffered result handles.</p></blockquote>
 * @return bool false if there are no rows in the result set, true otherwise.
 */
function sqlite_rewind($result) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Seek to the next row number
 * @link https://php.net/manual/en/function.sqlite-next.php
 * @param resource $result <p>
 * The SQLite result resource.  This parameter is not required when using
 * the object-oriented method.
 * </p>
 * <blockquote><p><b>Note</b>: </p><p>This function cannot be used with
 * unbuffered result handles.</p></blockquote>
 * @return bool <b>TRUE</b> on success, or <b>FALSE</b> if there are no more rows.
 */
function sqlite_next($result) {}

/**
 * Seek to the previous row number of a result set
 * @link https://php.net/manual/en/function.sqlite-prev.php
 * @param resource $result <p>
 * The SQLite result resource.  This parameter is not required when using
 * the object-oriented method.
 * </p>
 * <blockquote><p><b>Note</b>: </p><p>This function cannot be used with
 * unbuffered result handles.</p></blockquote>
 * @return bool true on success, or false if there are no more previous rows.
 */
function sqlite_prev($result) {}

/**
 * Returns whether more rows are available
 * @link https://php.net/manual/en/function.sqlite-valid.php
 * @param resource $result <p>
 * The SQLite result resource.  This parameter is not required when using
 * the object-oriented method.
 * </p>
 * <blockquote><p><b>Note</b>: </p><p>This function cannot be used with
 * unbuffered result handles.</p></blockquote>
 * @return bool <b>TRUE</b> if there are more rows available from the
 * result handle, or <b>FALSE</b> otherwise.
 */
function sqlite_valid($result) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Finds whether or not more rows are available
 * @link https://php.net/manual/en/function.sqlite-has-more.php
 * @param resource $result <p>
 * The SQLite result resource.
 * </p>
 * @return bool <b>TRUE</b> if there are more rows available from the
 * result handle, or <b>FALSE</b> otherwise.
 */
function sqlite_has_more($result) {}

/**
 * Returns whether or not a previous row is available
 * @link https://php.net/manual/en/function.sqlite-has-prev.php
 * @param resource $result <p>
 * The SQLite result resource.  This parameter is not required when using
 * the object-oriented method.
 * </p>
 * @return bool <b>TRUE</b> if there are more previous rows available from the
 * result handle, or <b>FALSE</b> otherwise.
 */
function sqlite_has_prev($result) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Escapes a string for use as a query parameter
 * @link https://php.net/manual/en/function.sqlite-escape-string.php
 * @param string $item <p>
 * The string being quoted.
 * </p>
 * <p>
 * If the item contains a NUL
 * character, or if it begins with a character whose ordinal value is
 * 0x01, PHP will apply a binary encoding scheme so that
 * you can safely store and retrieve binary data.
 * </p>
 * @return string an escaped string for use in an SQLite SQL statement.
 */
function sqlite_escape_string($item) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Set busy timeout duration, or disable busy handlers
 * @link https://php.net/manual/en/function.sqlite-busy-timeout.php
 * @param resource $dbhandle <p>The SQLite Database resource; returned from
 * {@see sqlite_open()} when used procedurally.
 * This parameter is not required when using the object-oriented method.</p>
 * @param int $milliseconds <p>
 * The number of milliseconds. When set to
 * 0, busy handlers will be disabled and SQLite will
 * return immediately with a <b>SQLITE_BUSY</b> status code
 * if another process/thread has the database locked for an update.
 * </p>
 * <p>
 * PHP sets the default busy timeout to be 60 seconds when the database is
 * opened.
 * </p>
 * <p>
 * There are one thousand (1000) milliseconds in one second.
 * </p>
 * @return void
 */
function sqlite_busy_timeout($dbhandle, $milliseconds) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Returns the error code of the last error for a database
 * @link https://php.net/manual/en/function.sqlite-last-error.php
 * @param resource $dbhandle <p>The SQLite Database resource; returned from
 * {@see sqlite_open()} when used procedurally.
 * This parameter is not required when using the object-oriented method.</p>
 * @return int an error code, or 0 if no error occurred.
 */
function sqlite_last_error($dbhandle) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Returns the textual description of an error code
 * @link https://php.net/manual/en/function.sqlite-error-string.php
 * @param int $error_code <p>
 * The error code being used, which might be passed in from
 * {@see sqlite_last_error}.
 * </p>
 * @return string a human readable description of the error_code,
 * as a string.
 */
function sqlite_error_string($error_code) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Execute a query that does not prefetch and buffer all data
 * @link https://php.net/manual/en/function.sqlite-unbuffered-query.php
 * @param resource $dbhandle <p>The SQLite Database resource; returned from
 * {@see sqlite_open()} when used procedurally.
 * This parameter is not required when using the object-oriented method.</p>
 * @param string $query <p>
 * The query to be executed.
 * </p>
 * <p>
 * Data inside the query should be properly escaped.
 * </p>
 * @param int $result_type [optional]
 * @param string &$error_msg [optional] <p>
 * The specified variable will be filled if an error occurs. This is
 * specially important because SQL syntax errors can't be fetched using
 * the sqlite_last_error function.
 * </p>
 * @return SQLiteUnbuffered|false a result handle or false on failure.
 * <p>
 * sqlite_unbuffered_query returns a sequential
 * forward-only result set that can only be used to read each row, one after
 * the other.
 * </p>
 */
function sqlite_unbuffered_query($dbhandle, $query, $result_type = SQLITE_BOTH, &$error_msg = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Register an aggregating UDF for use in SQL statements
 * @link https://php.net/manual/en/function.sqlite-create-aggregate.php
 * @param resource $dbhandle <p>The SQLite Database resource; returned from
 * {@see sqlite_open()} when used procedurally.
 * This parameter is not required when using the object-oriented method.</p>
 * @param string $function_name <p>
 * The name of the function used in SQL statements.
 * </p>
 * @param callable $step_func <p>
 * Callback function called for each row of the result set.
 * </p>
 * @param callable $finalize_func <p>
 * Callback function to aggregate the "stepped" data from each row.
 * </p>
 * @param int $num_args [optional] <p>
 * Hint to the SQLite parser if the callback function accepts a
 * predetermined number of arguments.
 * </p>
 * @return void
 */
function sqlite_create_aggregate($dbhandle, $function_name, $step_func, $finalize_func, $num_args = null) {}

/**
 * (PHP 5, sqlite &gt;= 1.0.0)<br/>
 * Registers a "regular" User Defined Function for use in SQL statements
 * @link https://php.net/manual/en/function.sqlite-create-function.php
 * @param resource $dbhandle <p>The SQLite Database resource; returned from
 * {@see sqlite_open()} when used procedurally.
 * This parameter is not required when using the object-oriented method.</p>
 * @param string $function_name <p>
 * The name of the function used in SQL statements.
 * </p>
 * @param callable $callback <p>
 * Callback function to handle the defined SQL function.
 * </p>
 * Callback functions should return a type understood by SQLite (i.e.
 * scalar type).
 * @param int $num_args [optional] <p>
 * Hint to the SQLite parser if the callback function accepts a
 * predetermined number of arguments.
 * </p>
 * @return void
 */
function sqlite_create_function($dbhandle, $function_name, $callback, $num_args = null) {}

/**
 * Opens a SQLite database and returns a SQLiteDatabase object
 * @link https://php.net/manual/en/function.sqlite-factory.php
 * @param string $filename <p>
 * The filename of the SQLite database.
 * </p>
 * @param int $mode [optional] <p>
 * The mode of the file. Intended to be used to open the database in
 * read-only mode. Presently, this parameter is ignored by the sqlite
 * library. The default value for mode is the octal value
 * 0666 and this is the recommended value.
 * </p>
 * @param string &$error_message [optional] <p>
 * Passed by reference and is set to hold a descriptive error message
 * explaining why the database could not be opened if there was an error.
 * </p>
 * @return SQLiteDatabase|null a SQLiteDatabase object on success, null on error.
 */
function sqlite_factory($filename, $mode = null, &$error_message = null) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Encode binary data before returning it from an UDF
 * @link https://php.net/manual/en/function.sqlite-udf-encode-binary.php
 * @param string $data <p>
 * The string being encoded.
 * </p>
 * @return string The encoded string.
 */
function sqlite_udf_encode_binary($data) {}

/**
 * (PHP 5, PECL sqlite &gt;= 1.0.0)<br/>
 * Decode binary data passed as parameters to an <acronym>UDF</acronym>
 * @link https://php.net/manual/en/function.sqlite-udf-decode-binary.php
 * @param string $data <p>
 * The encoded data that will be decoded, data that was applied by either
 * sqlite_udf_encode_binary or
 * sqlite_escape_string.
 * </p>
 * @return string The decoded string.
 */
function sqlite_udf_decode_binary($data) {}

/**
 * Return an array of column types from a particular table
 * @link https://php.net/manual/en/function.sqlite-fetch-column-types.php
 * @param string $table_name <p>
 * The table name to query.
 * </p>
 * @param resource $dbhandle <p>The SQLite Database resource; returned from
 * {@see sqlite_open()} when used procedurally.
 * This parameter is not required when using the object-oriented method.</p>
 * @param int $result_type [optional] <p>
 * The optional result_type parameter accepts a
 * constant and determines how the returned array will be indexed. Using
 * <b>SQLITE_ASSOC</b> will return only associative indices
 * (named fields) while <b>SQLITE_NUM</b> will return only
 * numerical indices (ordinal field numbers).
 * SQLITE_BOTH will return both associative and
 * numerical indices. <b>SQLITE_ASSOC</b> is the default for
 * this function.
 * </p>
 * @return array|false an array of column data types; false on error.
 */
function sqlite_fetch_column_types($dbhandle, $table_name, $result_type = null) {}

/**
 * Columns are returned into the array having both a numerical index
 * and the field name as the array index.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_BOTH', 3);

/**
 * Columns are returned into the array having a numerical index to the
 * fields. This index starts with 0, the first field in the result.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_NUM', 2);

/**
 * Columns are returned into the array having the field name as the array
 * index.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_ASSOC', 1);

/**
 * Successful result.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_OK', 0);

/**
 * SQL error or missing database.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_ERROR', 1);

/**
 * An internal logic error in SQLite.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_INTERNAL', 2);

/**
 * Access permission denied.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_PERM', 3);

/**
 * Callback routine requested an abort.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_ABORT', 4);

/**
 * The database file is locked.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_BUSY', 5);

/**
 * A table in the database is locked.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_LOCKED', 6);

/**
 * Memory allocation failed.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_NOMEM', 7);

/**
 * Attempt to write a readonly database.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_READONLY', 8);

/**
 * Operation terminated internally.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_INTERRUPT', 9);

/**
 * Disk I/O error occurred.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_IOERR', 10);

/**
 * The database disk image is malformed.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_CORRUPT', 11);

/**
 * (Internal) Table or record not found.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_NOTFOUND', 12);

/**
 * Insertion failed because database is full.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_FULL', 13);

/**
 * Unable to open the database file.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_CANTOPEN', 14);

/**
 * Database lock protocol error.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_PROTOCOL', 15);

/**
 * (Internal) Database table is empty.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_EMPTY', 16);

/**
 * The database schema changed.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_SCHEMA', 17);

/**
 * Too much data for one row of a table.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_TOOBIG', 18);

/**
 * Abort due to constraint violation.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_CONSTRAINT', 19);

/**
 * Data type mismatch.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_MISMATCH', 20);

/**
 * Library used incorrectly.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_MISUSE', 21);

/**
 * Uses of OS features not supported on host.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_NOLFS', 22);

/**
 * Authorized failed.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_AUTH', 23);

/**
 * File opened that is not a database file.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_NOTADB', 26);

/**
 * Auxiliary database format error.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_FORMAT', 24);

/**
 * Internal process has another row ready.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_ROW', 100);

/**
 * Internal process has finished executing.
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE_DONE', 101);

/**
 * Specifies that a function created with {@see SQLite3::createFunction()} is deterministic,
 * i.e. it always returns the same result given the same inputs within a single SQL statement.
 * @since 7.1.4
 * @link https://php.net/manual/en/sqlite.constants.php
 */
define('SQLITE3_DETERMINISTIC', 2048);
