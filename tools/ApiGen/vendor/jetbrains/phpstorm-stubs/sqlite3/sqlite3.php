<?php

// Start of sqlite3 v.0.7-dev
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;

/**
 * A class that interfaces SQLite 3 databases.
 * @link https://php.net/manual/en/class.sqlite3.php
 */
class SQLite3
{
    public const OK = 0;
    public const DENY = 1;
    public const IGNORE = 2;
    public const CREATE_INDEX = 1;
    public const CREATE_TABLE = 2;
    public const CREATE_TEMP_INDEX = 3;
    public const CREATE_TEMP_TABLE = 4;
    public const CREATE_TEMP_TRIGGER = 5;
    public const CREATE_TEMP_VIEW = 6;
    public const CREATE_TRIGGER = 7;
    public const CREATE_VIEW = 8;
    public const DELETE = 9;
    public const DROP_INDEX = 10;
    public const DROP_TABLE = 11;
    public const DROP_TEMP_INDEX = 12;
    public const DROP_TEMP_TABLE = 13;
    public const DROP_TEMP_TRIGGER = 14;
    public const DROP_TEMP_VIEW = 15;
    public const DROP_TRIGGER = 16;
    public const DROP_VIEW = 17;
    public const INSERT = 18;
    public const PRAGMA = 19;
    public const READ = 20;
    public const SELECT = 21;
    public const TRANSACTION = 22;
    public const UPDATE = 23;
    public const ATTACH = 24;
    public const DETACH = 25;
    public const ALTER_TABLE = 26;
    public const REINDEX = 27;
    public const ANALYZE = 28;
    public const CREATE_VTABLE = 29;
    public const DROP_VTABLE = 30;
    public const FUNCTION = 31;
    public const SAVEPOINT = 32;
    public const COPY = 0;
    public const RECURSIVE = 33;

    /**
     * Opens an SQLite database
     * @link https://php.net/manual/en/sqlite3.open.php
     * @param string $filename <p>
     * Path to the SQLite database, or :memory: to use in-memory database.
     * </p>
     * @param int $flags <p>
     * Optional flags used to determine how to open the SQLite database. By
     * default, open uses SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE.
     * </p>
     * <p>
     * SQLITE3_OPEN_READONLY: Open the database for
     * reading only.
     * </p>
     * @param string $encryptionKey <p>
     * An optional encryption key used when encrypting and decrypting an
     * SQLite database.
     * </p>
     * @return void No value is returned.
     */
    #[TentativeType]
    public function open(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename,
        #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] $flags,
        #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] $encryptionKey,
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = SQLITE3_OPEN_READWRITE|SQLITE3_OPEN_CREATE,
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $encryptionKey = null
    ): void {}

    /**
     * Closes the database connection
     * @link https://php.net/manual/en/sqlite3.close.php
     * @return bool <b>TRUE</b> on success, <b>FALSE</b> on failure.
     */
    public function close() {}

    /**
     * Executes a result-less query against a given database
     * @link https://php.net/manual/en/sqlite3.exec.php
     * @param string $query <p>
     * The SQL query to execute (typically an INSERT, UPDATE, or DELETE
     * query).
     * </p>
     * @return bool <b>TRUE</b> if the query succeeded, <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function exec(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $query): bool {}

    /**
     * Returns the SQLite3 library version as a string constant and as a number
     * @link https://php.net/manual/en/sqlite3.version.php
     * @return array an associative array with the keys "versionString" and
     * "versionNumber".
     */
    #[ArrayShape(["versionString" => "string", "versionNumber" => "int"])]
    #[TentativeType]
    public static function version(): array {}

    /**
     * Returns the row ID of the most recent INSERT into the database
     * @link https://php.net/manual/en/sqlite3.lastinsertrowid.php
     * @return int the row ID of the most recent INSERT into the database
     */
    #[TentativeType]
    public function lastInsertRowID(): int {}

    /**
     * Returns the numeric result code of the most recent failed SQLite request
     * @link https://php.net/manual/en/sqlite3.lasterrorcode.php
     * @return int an integer value representing the numeric result code of the most
     * recent failed SQLite request.
     */
    #[TentativeType]
    public function lastErrorCode(): int {}

    /**
     * Returns English text describing the most recent failed SQLite request
     * @link https://php.net/manual/en/sqlite3.lasterrormsg.php
     * @return string an English string describing the most recent failed SQLite request.
     */
    #[TentativeType]
    public function lastErrorMsg(): string {}

    /**
     * Sets the busy connection handler
     * @link https://php.net/manual/en/sqlite3.busytimeout.php
     * @param int $milliseconds <p>
     * The milliseconds to sleep. Setting this value to a value less than
     * or equal to zero, will turn off an already set timeout handler.
     * </p>
     * @return bool <b>TRUE</b> on success, <b>FALSE</b> on failure.
     * @since 5.3.3
     */
    #[TentativeType]
    public function busyTimeout(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $milliseconds): bool {}

    /**
     * Attempts to load an SQLite extension library
     * @link https://php.net/manual/en/sqlite3.loadextension.php
     * @param string $name <p>
     * The name of the library to load. The library must be located in the
     * directory specified in the configure option sqlite3.extension_dir.
     * </p>
     * @return bool <b>TRUE</b> if the extension is successfully loaded, <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function loadExtension(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name): bool {}

    /**
     * Returns the number of database rows that were changed (or inserted or
     * deleted) by the most recent SQL statement
     * @link https://php.net/manual/en/sqlite3.changes.php
     * @return int an integer value corresponding to the number of
     * database rows changed (or inserted or deleted) by the most recent SQL
     * statement.
     */
    #[TentativeType]
    public function changes(): int {}

    /**
     * Returns a string that has been properly escaped
     * @link https://php.net/manual/en/sqlite3.escapestring.php
     * @param string $string <p>
     * The string to be escaped.
     * </p>
     * @return string a properly escaped string that may be used safely in an SQL
     * statement.
     */
    #[TentativeType]
    public static function escapeString(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $string): string {}

    /**
     * Prepares an SQL statement for execution
     * @link https://php.net/manual/en/sqlite3.prepare.php
     * @param string $query <p>
     * The SQL query to prepare.
     * </p>
     * @return SQLite3Stmt|false an <b>SQLite3Stmt</b> object on success or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function prepare(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $query): SQLite3Stmt|false {}

    /**
     * Executes an SQL query
     * @link https://php.net/manual/en/sqlite3.query.php
     * @param string $query <p>
     * The SQL query to execute.
     * </p>
     * @return SQLite3Result|false an <b>SQLite3Result</b> object, or <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function query(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $query): SQLite3Result|false {}

    /**
     * Executes a query and returns a single result
     * @link https://php.net/manual/en/sqlite3.querysingle.php
     * @param string $query <p>
     * The SQL query to execute.
     * </p>
     * @param bool $entireRow [optional] <p>
     * By default, <b>querySingle</b> returns the value of the
     * first column returned by the query. If
     * <i>entire_row</i> is <b>TRUE</b>, then it returns an array
     * of the entire first row.
     * </p>
     * @return mixed the value of the first column of results or an array of the entire
     * first row (if <i>entire_row</i> is <b>TRUE</b>).
     * </p>
     * <p>
     * If the query is valid but no results are returned, then <b>NULL</b> will be
     * returned if <i>entire_row</i> is <b>FALSE</b>, otherwise an
     * empty array is returned.
     * </p>
     * <p>
     * Invalid or failing queries will return <b>FALSE</b>.
     */
    #[TentativeType]
    public function querySingle(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $query,
        #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $entireRow = false
    ): mixed {}

    /**
     * Registers a PHP function for use as an SQL scalar function
     * @link https://php.net/manual/en/sqlite3.createfunction.php
     * @param string $name <p>
     * Name of the SQL function to be created or redefined.
     * </p>
     * @param mixed $callback <p>
     * The name of a PHP function or user-defined function to apply as a
     * callback, defining the behavior of the SQL function.
     * </p>
     * @param int $argCount <p>
     * The number of arguments that the SQL function takes. If
     * this parameter is negative, then the SQL function may take
     * any number of arguments.
     * </p>
     * @param int $flags
     * <p>A bitwise conjunction of flags.
     * Currently, only <b>SQLITE3_DETERMINISTIC</b> is supported, which specifies that the function always returns
     * the same result given the same inputs within a single SQL statement.</p>
     * @return bool <b>TRUE</b> upon successful creation of the function, <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function createFunction(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name,
        #[LanguageLevelTypeAware(['8.0' => 'callable'], default: '')] $callback,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $argCount = -1,
        #[PhpStormStubsElementAvailable(from: '7.1')] int $flags = 0
    ): bool {}

    /**
     * Registers a PHP function for use as an SQL aggregate function
     * @link https://php.net/manual/en/sqlite3.createaggregate.php
     * @param string $name <p>
     * Name of the SQL aggregate to be created or redefined.
     * </p>
     * @param mixed $stepCallback <p>
     * The name of a PHP function or user-defined function to apply as a
     * callback for every item in the aggregate.
     * </p>
     * @param mixed $finalCallback <p>
     * The name of a PHP function or user-defined function to apply as a
     * callback at the end of the aggregate data.
     * </p>
     * @param int $argCount [optional] <p>
     * The number of arguments that the SQL aggregate takes. If
     * this parameter is negative, then the SQL aggregate may take
     * any number of arguments.
     * </p>
     * @return bool <b>TRUE</b> upon successful creation of the aggregate, <b>FALSE</b> on
     * failure.
     */
    #[TentativeType]
    public function createAggregate(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name,
        #[LanguageLevelTypeAware(['8.0' => 'callable'], default: '')] $stepCallback,
        #[LanguageLevelTypeAware(['8.0' => 'callable'], default: '')] $finalCallback,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $argCount = -1
    ): bool {}

    /**
     * Registers a PHP function for use as an SQL collating function
     * @link https://php.net/manual/en/sqlite3.createcollation.php
     * @param string $name <p>
     * Name of the SQL collating function to be created or redefined
     * </p>
     * @param callable $callback <p>
     * The name of a PHP function or user-defined function to apply as a
     * callback, defining the behavior of the collation. It should accept two
     * strings and return as <b>strcmp</b> does, i.e. it should
     * return -1, 1, or 0 if the first string sorts before, sorts after, or is
     * equal to the second.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.3.11
     */
    #[TentativeType]
    public function createCollation(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $name, callable $callback): bool {}

    /**
     * Opens a stream resource to read a BLOB
     * @link https://php.net/manual/en/sqlite3.openblob.php
     * @param string $table <p>The table name.</p>
     * @param string $column <p>The column name.</p>
     * @param int $rowid <p>The row ID.</p>
     * @param string $database [optional] <p>The symbolic name of the DB</p>
     * @param int $flags [optional]
     * <p>Either <b>SQLITE3_OPEN_READONLY</b> or <b>SQLITE3_OPEN_READWRITE</b> to open the stream for reading only, or for reading and writing, respectively.</p>
     * @return resource|false Returns a stream resource, or FALSE on failure.
     */
    public function openBlob(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $table,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $column,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $rowid,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $database = 'main',
        #[PhpStormStubsElementAvailable(from: '7.2')] int $flags = SQLITE3_OPEN_READONLY
    ) {}

    /**
     * Enable throwing exceptions
     * @link https://www.php.net/manual/en/sqlite3.enableexceptions
     * @param bool $enable
     * @return bool Returns the old value; true if exceptions were enabled, false otherwise.
     */
    #[TentativeType]
    public function enableExceptions(
        #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] $enable,
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $enable = false
    ): bool {}

    /**
     * Instantiates an SQLite3 object and opens an SQLite 3 database
     * @link https://php.net/manual/en/sqlite3.construct.php
     * @param string $filename <p>
     * Path to the SQLite database, or :memory: to use in-memory database.
     * </p>
     * @param int $flags <p>
     * Optional flags used to determine how to open the SQLite database. By
     * default, open uses SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE.
     * </p>
     * <p>
     * SQLITE3_OPEN_READONLY: Open the database for
     * reading only.
     * </p>
     * @param string $encryptionKey <p>
     * An optional encryption key used when encrypting and decrypting an
     * SQLite database.
     * </p>
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $filename,
        #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] $flags,
        #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] $encryptionKey,
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = SQLITE3_OPEN_READWRITE|SQLITE3_OPEN_CREATE,
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $encryptionKey = null
    ) {}

    /**
     * @return int
     * @since 7.4
     */
    #[TentativeType]
    public function lastExtendedErrorCode(): int {}

    /**
     * @param bool $enable
     * @since 7.4
     */
    #[TentativeType]
    public function enableExtendedResultCodes(
        #[PhpStormStubsElementAvailable(from: '7.4', to: '7.4')] bool $enable,
        #[PhpStormStubsElementAvailable(from: '8.0')] bool $enable = true
    ): bool {}

    /**
     * @param SQLite3 $destination
     * @param string $sourceDatabase
     * @param string $destinationDatabase
     * @return bool
     * @since 7.4
     */
    #[TentativeType]
    public function backup(SQLite3 $destination, string $sourceDatabase = 'main', string $destinationDatabase = 'main'): bool {}

    /**
     * @param null|callable $callback
     * @return bool
     * @since 8.0
     */
    #[TentativeType]
    public function setAuthorizer(?callable $callback): bool {}
}

/**
 * A class that handles prepared statements for the SQLite 3 extension.
 * @link https://php.net/manual/en/class.sqlite3stmt.php
 */
class SQLite3Stmt
{
    /**
     * Returns the number of parameters within the prepared statement
     * @link https://php.net/manual/en/sqlite3stmt.paramcount.php
     * @return int the number of parameters within the prepared statement.
     */
    #[TentativeType]
    public function paramCount(): int {}

    /**
     * Closes the prepared statement
     * @link https://php.net/manual/en/sqlite3stmt.close.php
     * @return bool <b>TRUE</b>
     */
    #[TentativeType]
    public function close(): bool {}

    /**
     * Resets the prepared statement
     * @link https://php.net/manual/en/sqlite3stmt.reset.php
     * @return bool <b>TRUE</b> if the statement is successfully reset, <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function reset(): bool {}

    /**
     * Clears all current bound parameters
     * @link https://php.net/manual/en/sqlite3stmt.clear.php
     * @return bool <b>TRUE</b> on successful clearing of bound parameters, <b>FALSE</b> on
     * failure.
     */
    #[TentativeType]
    public function clear(): bool {}

    /**
     * Executes a prepared statement and returns a result set object
     * @link https://php.net/manual/en/sqlite3stmt.execute.php
     * @return SQLite3Result|false an <b>SQLite3Result</b> object on successful execution of the prepared
     * statement, <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function execute(): SQLite3Result|false {}

    /**
     * Binds a parameter to a statement variable
     * @link https://php.net/manual/en/sqlite3stmt.bindparam.php
     * @param string $param <p>
     * An string identifying the statement variable to which the
     * parameter should be bound.
     * </p>
     * @param mixed &$var <p>
     * The parameter to bind to a statement variable.
     * </p>
     * @param int $type [optional] <p>
     * The data type of the parameter to bind.
     * </p>
     * <p>
     * SQLITE3_INTEGER: The value is a signed integer,
     * stored in 1, 2, 3, 4, 6, or 8 bytes depending on the magnitude of
     * the value.
     * </p>
     * @return bool <b>TRUE</b> if the parameter is bound to the statement variable, <b>FALSE</b>
     * on failure.
     */
    #[TentativeType]
    public function bindParam(
        #[LanguageLevelTypeAware(['8.0' => 'string|int'], default: '')] $param,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] &$var,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $type = SQLITE3_TEXT
    ): bool {}

    /**
     * Binds the value of a parameter to a statement variable
     * @link https://php.net/manual/en/sqlite3stmt.bindvalue.php
     * @param string $param <p>
     * An string identifying the statement variable to which the
     * value should be bound.
     * </p>
     * @param mixed $value <p>
     * The value to bind to a statement variable.
     * </p>
     * @param int $type [optional] <p>
     * The data type of the value to bind.
     * </p>
     * <p>
     * SQLITE3_INTEGER: The value is a signed integer,
     * stored in 1, 2, 3, 4, 6, or 8 bytes depending on the magnitude of
     * the value.
     * </p>
     * @return bool <b>TRUE</b> if the value is bound to the statement variable, <b>FALSE</b>
     * on failure.
     */
    #[TentativeType]
    public function bindValue(
        #[LanguageLevelTypeAware(['8.0' => 'string|int'], default: '')] $param,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $type = SQLITE3_TEXT
    ): bool {}

    #[TentativeType]
    public function readOnly(): bool {}

    /**
     * @param SQLite3 $sqlite3
     * @param string $query
     */
    private function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'SQLite3'], default: '')] $sqlite3,
        #[PhpStormStubsElementAvailable(from: '8.0')] string $query
    ) {}

    /**
     * Retrieves the SQL of the prepared statement. If expanded is FALSE, the unmodified SQL is retrieved.
     * If expanded is TRUE, all query parameters are replaced with their bound values, or with an SQL NULL, if not already bound.
     * @param bool $expand Whether to retrieve the expanded SQL. Passing TRUE is only supported as of libsqlite 3.14.
     * @return string|false Returns the SQL of the prepared statement, or FALSE on failure.
     * @since 7.4
     */
    #[TentativeType]
    public function getSQL(bool $expand = false): string|false {}
}

/**
 * A class that handles result sets for the SQLite 3 extension.
 * @link https://php.net/manual/en/class.sqlite3result.php
 */
class SQLite3Result
{
    /**
     * Returns the number of columns in the result set
     * @link https://php.net/manual/en/sqlite3result.numcolumns.php
     * @return int the number of columns in the result set.
     */
    #[TentativeType]
    public function numColumns(): int {}

    /**
     * Returns the name of the nth column
     * @link https://php.net/manual/en/sqlite3result.columnname.php
     * @param int $column <p>
     * The numeric zero-based index of the column.
     * </p>
     * @return string|false the string name of the column identified by
     * <i>column_number</i>.
     */
    #[TentativeType]
    public function columnName(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $column): string|false {}

    /**
     * Returns the type of the nth column
     * @link https://php.net/manual/en/sqlite3result.columntype.php
     * @param int $column <p>
     * The numeric zero-based index of the column.
     * </p>
     * @return int|false the data type index of the column identified by
     * <i>column_number</i> (one of
     * <b>SQLITE3_INTEGER</b>, <b>SQLITE3_FLOAT</b>,
     * <b>SQLITE3_TEXT</b>, <b>SQLITE3_BLOB</b>, or
     * <b>SQLITE3_NULL</b>).
     */
    #[TentativeType]
    public function columnType(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $column): int|false {}

    /**
     * Fetches a result row as an associative or numerically indexed array or both
     * @link https://php.net/manual/en/sqlite3result.fetcharray.php
     * @param int $mode [optional] <p>
     * Controls how the next row will be returned to the caller. This value
     * must be one of either SQLITE3_ASSOC,
     * SQLITE3_NUM, or SQLITE3_BOTH.
     * </p>
     * <p>
     * SQLITE3_ASSOC: returns an array indexed by column
     * name as returned in the corresponding result set
     * </p>
     * @return array|false a result row as an associatively or numerically indexed array or
     * both. Alternately will return <b>FALSE</b> if there are no more rows.
     */
    #[TentativeType]
    public function fetchArray(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $mode = SQLITE3_BOTH): array|false {}

    /**
     * Resets the result set back to the first row
     * @link https://php.net/manual/en/sqlite3result.reset.php
     * @return bool <b>TRUE</b> if the result set is successfully reset
     * back to the first row, <b>FALSE</b> on failure.
     */
    #[TentativeType]
    public function reset(): bool {}

    /**
     * Closes the result set
     * @link https://php.net/manual/en/sqlite3result.finalize.php
     * @return bool <b>TRUE</b>.
     */
    public function finalize() {}

    private function __construct() {}
}

/**
 * Specifies that the <b>Sqlite3Result::fetchArray</b>
 * method shall return an array indexed by column name as returned in the
 * corresponding result set.
 * @link https://php.net/manual/en/sqlite3.constants.php
 */
define('SQLITE3_ASSOC', 1);

/**
 * Specifies that the <b>Sqlite3Result::fetchArray</b>
 * method shall return an array indexed by column number as returned in the
 * corresponding result set, starting at column 0.
 * @link https://php.net/manual/en/sqlite3.constants.php
 */
define('SQLITE3_NUM', 2);

/**
 * Specifies that the <b>Sqlite3Result::fetchArray</b>
 * method shall return an array indexed by both column name and number as
 * returned in the corresponding result set, starting at column 0.
 * @link https://php.net/manual/en/sqlite3.constants.php
 */
define('SQLITE3_BOTH', 3);

/**
 * Represents the SQLite3 INTEGER storage class.
 * @link https://php.net/manual/en/sqlite3.constants.php
 */
define('SQLITE3_INTEGER', 1);

/**
 * Represents the SQLite3 REAL (FLOAT) storage class.
 * @link https://php.net/manual/en/sqlite3.constants.php
 */
define('SQLITE3_FLOAT', 2);

/**
 * Represents the SQLite3 TEXT storage class.
 * @link https://php.net/manual/en/sqlite3.constants.php
 */
define('SQLITE3_TEXT', 3);

/**
 * Represents the SQLite3 BLOB storage class.
 * @link https://php.net/manual/en/sqlite3.constants.php
 */
define('SQLITE3_BLOB', 4);

/**
 * Represents the SQLite3 NULL storage class.
 * @link https://php.net/manual/en/sqlite3.constants.php
 */
define('SQLITE3_NULL', 5);

/**
 * Specifies that the SQLite3 database be opened for reading only.
 * @link https://php.net/manual/en/sqlite3.constants.php
 */
define('SQLITE3_OPEN_READONLY', 1);

/**
 * Specifies that the SQLite3 database be opened for reading and writing.
 * @link https://php.net/manual/en/sqlite3.constants.php
 */
define('SQLITE3_OPEN_READWRITE', 2);

/**
 * Specifies that the SQLite3 database be created if it does not already
 * exist.
 * @link https://php.net/manual/en/sqlite3.constants.php
 */
define('SQLITE3_OPEN_CREATE', 4);

// End of sqlite3 v.0.7-dev
