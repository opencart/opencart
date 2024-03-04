<?php

// Start of ibm_db2 v.1.6.0

/**
 * Returns a connection to a database
 * @link https://php.net/manual/en/function.db2-connect.php
 * @param string $database <p>
 * For a cataloged connection to a database, database
 * represents the database alias in the DB2 client catalog.
 * </p>
 * <p>
 * For an uncataloged connection to a database,
 * database represents a complete connection
 * string in the following format:
 * DATABASE=database;HOSTNAME=hostname;PORT=port;PROTOCOL=TCPIP;UID=username;PWD=password;
 * where the parameters represent the following values:
 * database
 * <p>
 * The name of the database.
 * </p>
 * @param string $username <p>
 * The username with which you are connecting to the database.
 * </p>
 * <p>
 * For uncataloged connections, you must pass a null value or empty
 * string.
 * </p>
 * @param string $password <p>
 * The password with which you are connecting to the database.
 * </p>
 * <p>
 * For uncataloged connections, you must pass a null value or empty
 * string.
 * </p>
 * @param array $options <p>
 * An associative array of connection options that affect the behavior
 * of the connection, where valid array keys include:
 * autocommit
 * <p>
 * Passing the DB2_AUTOCOMMIT_ON value turns
 * autocommit on for this connection handle.
 * </p>
 * <p>
 * Passing the DB2_AUTOCOMMIT_OFF value turns
 * autocommit off for this connection handle.
 * </p>
 * @return resource|false A connection handle resource if the connection attempt is
 * successful. If the connection attempt fails, db2_connect
 * returns false.
 */
function db2_connect($database, $username, $password, array $options = null) {}

/**
 * Commits a transaction
 * @link https://php.net/manual/en/function.db2-commit.php
 * @param resource $connection <p>
 * A valid database connection resource variable as returned from
 * db2_connect or db2_pconnect.
 * </p>
 * @return bool true on success or false on failure.
 */
function db2_commit($connection) {}

/**
 * Returns a persistent connection to a database
 * @link https://php.net/manual/en/function.db2-pconnect.php
 * @param string $database <p>
 * The database alias in the DB2 client catalog.
 * </p>
 * @param string $username <p>
 * The username with which you are connecting to the database.
 * </p>
 * @param string $password <p>
 * The password with which you are connecting to the database.
 * </p>
 * @param array $options <p>
 * An associative array of connection options that affect the behavior
 * of the connection, where valid array keys include:
 * autocommit
 * </p>
 * <p>
 * Passing the DB2_AUTOCOMMIT_ON value turns
 * autocommit on for this connection handle.
 * </p>
 * <p>
 * Passing the DB2_AUTOCOMMIT_OFF value turns
 * autocommit off for this connection handle.
 * </p>
 * @return resource|false A connection handle resource if the connection attempt is
 * successful. db2_pconnect tries to reuse an existing
 * connection resource that exactly matches the
 * database, username, and
 * password parameters. If the connection attempt fails,
 * db2_pconnect returns false.
 */
function db2_pconnect($database, $username, $password, array $options = null) {}

/**
 * Returns or sets the AUTOCOMMIT state for a database connection
 * @link https://php.net/manual/en/function.db2-autocommit.php
 * @param resource $connection <p>
 * A valid database connection resource variable as returned from
 * db2_connect or db2_pconnect.
 * </p>
 * @param bool $value <p>
 * One of the following constants:</p>
 * <p>
 * DB2_AUTOCOMMIT_OFF
 * Turns AUTOCOMMIT off.
 * </p>
 * <p>
 * DB2_AUTOCOMMIT_ON
 * Turns AUTOCOMMIT on.
 * </p>
 * @return mixed <p>When db2_autocommit receives only the
 * connection parameter, it returns the current state
 * of AUTOCOMMIT for the requested connection as an integer value. A value of
 * 0 indicates that AUTOCOMMIT is off, while a value of 1 indicates that
 * AUTOCOMMIT is on.
 * </p>
 * <p>
 * When db2_autocommit receives both the
 * connection parameter and
 * autocommit parameter, it attempts to set the
 * AUTOCOMMIT state of the requested connection to the corresponding state.
 * true on success or false on failure.</p>
 */
function db2_autocommit($connection, $value = null) {}

/**
 * Binds a PHP variable to an SQL statement parameter
 * @link https://php.net/manual/en/function.db2-bind-param.php
 * @param resource $stmt <p>
 * A prepared statement returned from db2_prepare.
 * </p>
 * @param int $parameter_number
 * @param string $variable_name
 * @param int $parameter_type
 * @param int $data_type
 * @param int $precision <p>
 * Specifies the precision with which the variable should be bound to the
 * database. This parameter can also be used for retrieving XML output values
 * from stored procedures. A non-negative value specifies the maximum size of
 * the XML data that will be retrieved from the database. If this parameter
 * is not used, a default of 1MB will be assumed for retrieving the XML
 * output value from the stored procedure.
 * </p>
 * @param int $scale <p>
 * Specifies the scale with which the variable should be bound to the
 * database.
 * </p>
 * @return bool true on success or false on failure.
 */
function db2_bind_param($stmt, $parameter_number, $variable_name, $parameter_type = null, $data_type = null, $precision = null, $scale = null) {}

/**
 * Closes a database connection
 * @link https://php.net/manual/en/function.db2-close.php
 * @param resource $connection <p>
 * Specifies an active DB2 client connection.
 * </p>
 * @return bool true on success or false on failure.
 */
function db2_close($connection) {}

/**
 * Returns a result set listing the columns and associated privileges for a table
 * @link https://php.net/manual/en/function.db2-column-privileges.php
 * @param resource $connection <p>
 * A valid connection to an IBM DB2, Cloudscape, or Apache Derby database.
 * </p>
 * @param string $qualifier <p>
 * A qualifier for DB2 databases running on OS/390 or z/OS servers. For
 * other databases, pass null or an empty string.
 * </p>
 * @param string $schema <p>
 * The schema which contains the tables. To match all schemas, pass null
 * or an empty string.
 * </p>
 * @param string $table_name
 * @param string $column_name
 * @return resource|false a statement resource with a result set containing rows describing
 * the column privileges for columns matching the specified parameters. The
 * rows are composed of the following columns:
 * <tr valign="top">
 * <td>Column name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_CAT</td>
 * <td>Name of the catalog. The value is NULL if this table does not
 * have catalogs.</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_SCHEM</td>
 * <td>Name of the schema.</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_NAME</td>
 * <td>Name of the table or view.</td>
 * </tr>
 * <tr valign="top">
 * <td>COLUMN_NAME</td>
 * <td>Name of the column.</td>
 * </tr>
 * <tr valign="top">
 * <td>GRANTOR</td>
 * <td>Authorization ID of the user who granted the privilege.</td>
 * </tr>
 * <tr valign="top">
 * <td>GRANTEE</td>
 * <td>Authorization ID of the user to whom the privilege was
 * granted.</td>
 * </tr>
 * <tr valign="top">
 * <td>PRIVILEGE</td>
 * <td>The privilege for the column.</td>
 * </tr>
 * <tr valign="top">
 * <td>IS_GRANTABLE</td>
 * <td>Whether the GRANTEE is permitted to grant this privilege to
 * other users.</td>
 * </tr>
 */
function db2_column_privileges($connection, $qualifier = null, $schema = null, $table_name = null, $column_name = null) {}

function db2_columnprivileges() {}

/**
 * Returns a result set listing the columns and associated metadata for a table
 * @link https://php.net/manual/en/function.db2-columns.php
 * @param resource $connection <p>
 * A valid connection to an IBM DB2, Cloudscape, or Apache Derby database.
 * </p>
 * @param string $qualifier <p>
 * A qualifier for DB2 databases running on OS/390 or z/OS servers. For
 * other databases, pass null or an empty string.
 * </p>
 * @param string $schema <p>
 * The schema which contains the tables. To match all schemas, pass
 * '%'.
 * </p>
 * @param string $table_name
 * @param string $column_name
 * @return resource|false A statement resource with a result set containing rows describing
 * the columns matching the specified parameters. The rows are composed of
 * the following columns:
 * <tr valign="top">
 * <td>Column name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_CAT</td>
 * <td>Name of the catalog. The value is NULL if this table does not
 * have catalogs.</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_SCHEM</td>
 * <td>Name of the schema.</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_NAME</td>
 * <td>Name of the table or view.</td>
 * </tr>
 * <tr valign="top">
 * <td>COLUMN_NAME</td>
 * <td>Name of the column.</td>
 * </tr>
 * <tr valign="top">
 * <td>DATA_TYPE</td>
 * <td>The SQL data type for the column represented as an integer value.</td>
 * </tr>
 * <tr valign="top">
 * <td>TYPE_NAME</td>
 * <td>A string representing the data type for the column.</td>
 * </tr>
 * <tr valign="top">
 * <td>COLUMN_SIZE</td>
 * <td>An integer value representing the size of the column.</td>
 * </tr>
 * <tr valign="top">
 * <td>BUFFER_LENGTH</td>
 * <td>
 * Maximum number of bytes necessary to store data from this column.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>DECIMAL_DIGITS</td>
 * <td>
 * The scale of the column, or null where scale is not applicable.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>NUM_PREC_RADIX</td>
 * <td>
 * An integer value of either 10 (representing
 * an exact numeric data type), 2 (representing an
 * approximate numeric data type), or null (representing a data type for
 * which radix is not applicable).
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>NULLABLE</td>
 * <td>An integer value representing whether the column is nullable or
 * not.</td>
 * </tr>
 * <tr valign="top">
 * <td>REMARKS</td>
 * <td>Description of the column.</td>
 * </tr>
 * <tr valign="top">
 * <td>COLUMN_DEF</td>
 * <td>Default value for the column.</td>
 * </tr>
 * <tr valign="top">
 * <td>SQL_DATA_TYPE</td>
 * <td>An integer value representing the size of the column.</td>
 * </tr>
 * <tr valign="top">
 * <td>SQL_DATETIME_SUB</td>
 * <td>
 * Returns an integer value representing a datetime subtype code,
 * or null for SQL data types to which this does not apply.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>CHAR_OCTET_LENGTH</td>
 * <td>
 * Maximum length in octets for a character data type column, which
 * matches COLUMN_SIZE for single-byte character set data, or null for
 * non-character data types.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>ORDINAL_POSITION</td>
 * <td>The 1-indexed position of the column in the table.</td>
 * </tr>
 * <tr valign="top">
 * <td>IS_NULLABLE</td>
 * <td>
 * A string value where 'YES' means that the column is nullable and
 * 'NO' means that the column is not nullable.
 * </td>
 * </tr>
 */
function db2_columns($connection, $qualifier = null, $schema = null, $table_name = null, $column_name = null) {}

/**
 * Returns a result set listing the foreign keys for a table
 * @link https://php.net/manual/en/function.db2-foreign-keys.php
 * @param resource $connection <p>
 * A valid connection to an IBM DB2, Cloudscape, or Apache Derby database.
 * </p>
 * @param string $qualifier <p>
 * A qualifier for DB2 databases running on OS/390 or z/OS servers. For
 * other databases, pass null or an empty string.
 * </p>
 * @param string $schema <p>
 * The schema which contains the tables. If schema
 * is null, db2_foreign_keys matches the schema for
 * the current connection.
 * </p>
 * @param string $table_name
 * @return resource|false A statement resource with a result set containing rows describing
 * the foreign keys for the specified table. The result set is composed of the
 * following columns:
 * <tr valign="top">
 * <td>Column name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>PKTABLE_CAT</td>
 * <td>
 * Name of the catalog for the table containing the primary key. The
 * value is NULL if this table does not have catalogs.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>PKTABLE_SCHEM</td>
 * <td>
 * Name of the schema for the table containing the primary key.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>PKTABLE_NAME</td>
 * <td>Name of the table containing the primary key.</td>
 * </tr>
 * <tr valign="top">
 * <td>PKCOLUMN_NAME</td>
 * <td>Name of the column containing the primary key.</td>
 * </tr>
 * <tr valign="top">
 * <td>FKTABLE_CAT</td>
 * <td>
 * Name of the catalog for the table containing the foreign key. The
 * value is NULL if this table does not have catalogs.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>FKTABLE_SCHEM</td>
 * <td>
 * Name of the schema for the table containing the foreign key.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>FKTABLE_NAME</td>
 * <td>Name of the table containing the foreign key.</td>
 * </tr>
 * <tr valign="top">
 * <td>FKCOLUMN_NAME</td>
 * <td>Name of the column containing the foreign key.</td>
 * </tr>
 * <tr valign="top">
 * <td>KEY_SEQ</td>
 * <td>1-indexed position of the column in the key.</td>
 * </tr>
 * <tr valign="top">
 * <td>UPDATE_RULE</td>
 * <td>
 * Integer value representing the action applied to the foreign key
 * when the SQL operation is UPDATE.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>DELETE_RULE</td>
 * <td>
 * Integer value representing the action applied to the foreign key
 * when the SQL operation is DELETE.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>FK_NAME</td>
 * <td>The name of the foreign key.</td>
 * </tr>
 * <tr valign="top">
 * <td>PK_NAME</td>
 * <td>The name of the primary key.</td>
 * </tr>
 * <tr valign="top">
 * <td>DEFERRABILITY</td>
 * <td>
 * An integer value representing whether the foreign key deferrability is
 * SQL_INITIALLY_DEFERRED, SQL_INITIALLY_IMMEDIATE, or
 * SQL_NOT_DEFERRABLE.
 * </td>
 * </tr>
 */
function db2_foreign_keys($connection, $qualifier, $schema, $table_name) {}

function db2_foreignkeys() {}

/**
 * Returns a result set listing primary keys for a table
 * @link https://php.net/manual/en/function.db2-primary-keys.php
 * @param resource $connection <p>
 * A valid connection to an IBM DB2, Cloudscape, or Apache Derby database.
 * </p>
 * @param string $qualifier <p>
 * A qualifier for DB2 databases running on OS/390 or z/OS servers. For
 * other databases, pass null or an empty string.
 * </p>
 * @param string $schema <p>
 * The schema which contains the tables. If schema
 * is null, db2_primary_keys matches the schema for
 * the current connection.
 * </p>
 * @param string $table_name
 * @return resource|false A statement resource with a result set containing rows describing
 * the primary keys for the specified table. The result set is composed of the
 * following columns:
 * <tr valign="top">
 * <td>Column name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_CAT</td>
 * <td>
 * Name of the catalog for the table containing the primary key. The
 * value is NULL if this table does not have catalogs.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_SCHEM</td>
 * <td>
 * Name of the schema for the table containing the primary key.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_NAME</td>
 * <td>Name of the table containing the primary key.</td>
 * </tr>
 * <tr valign="top">
 * <td>COLUMN_NAME</td>
 * <td>Name of the column containing the primary key.</td>
 * </tr>
 * <tr valign="top">
 * <td>KEY_SEQ</td>
 * <td>1-indexed position of the column in the key.</td>
 * </tr>
 * <tr valign="top">
 * <td>PK_NAME</td>
 * <td>The name of the primary key.</td>
 * </tr>
 */
function db2_primary_keys($connection, $qualifier, $schema, $table_name) {}

function db2_primarykeys() {}

/**
 * Returns a result set listing stored procedure parameters
 * @link https://php.net/manual/en/function.db2-procedure-columns.php
 * @param resource $connection <p>
 * A valid connection to an IBM DB2, Cloudscape, or Apache Derby database.
 * </p>
 * @param string $qualifier <p>
 * A qualifier for DB2 databases running on OS/390 or z/OS servers. For
 * other databases, pass null or an empty string.
 * </p>
 * @param string $schema <p>
 * The schema which contains the procedures. This parameter accepts a
 * search pattern containing _ and %
 * as wildcards.
 * </p>
 * @param string $procedure <p>
 * The name of the procedure. This parameter accepts a
 * search pattern containing _ and %
 * as wildcards.
 * </p>
 * @param string $parameter <p>
 * The name of the parameter. This parameter accepts a search pattern
 * containing _ and % as wildcards.
 * If this parameter is null, all parameters for the specified stored
 * procedures are returned.
 * </p>
 * @return resource|false A statement resource with a result set containing rows describing
 * the parameters for the stored procedures matching the specified parameters.
 * The rows are composed of the following columns:
 * <tr valign="top">
 * <td>Column name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>PROCEDURE_CAT</td>
 * <td>The catalog that contains the procedure. The value is null if
 * this table does not have catalogs.</td>
 * </tr>
 * <tr valign="top">
 * <td>PROCEDURE_SCHEM</td>
 * <td>Name of the schema that contains the stored procedure.</td>
 * </tr>
 * <tr valign="top">
 * <td>PROCEDURE_NAME</td>
 * <td>Name of the procedure.</td>
 * </tr>
 * <tr valign="top">
 * <td>COLUMN_NAME</td>
 * <td>Name of the parameter.</td>
 * </tr>
 * <tr valign="top">
 * <td>COLUMN_TYPE</td>
 * <td>
 * <p>
 * An integer value representing the type of the parameter:
 * <tr valign="top">
 * <td>Return value</td>
 * <td>Parameter type</td>
 * </tr>
 * <tr valign="top">
 * <td>1 (SQL_PARAM_INPUT)</td>
 * <td>Input (IN) parameter.</td>
 * </tr>
 * <tr valign="top">
 * <td>2 (SQL_PARAM_INPUT_OUTPUT)</td>
 * <td>Input/output (INOUT) parameter.</td>
 * </tr>
 * <tr valign="top">
 * <td>3 (SQL_PARAM_OUTPUT)</td>
 * <td>Output (OUT) parameter.</td>
 * </tr>
 * </p>
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>DATA_TYPE</td>
 * <td>The SQL data type for the parameter represented as an integer
 * value.</td>
 * </tr>
 * <tr valign="top">
 * <td>TYPE_NAME</td>
 * <td>A string representing the data type for the parameter.</td>
 * </tr>
 * <tr valign="top">
 * <td>COLUMN_SIZE</td>
 * <td>An integer value representing the size of the parameter.</td>
 * </tr>
 * <tr valign="top">
 * <td>BUFFER_LENGTH</td>
 * <td>
 * Maximum number of bytes necessary to store data for this parameter.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>DECIMAL_DIGITS</td>
 * <td>
 * The scale of the parameter, or null where scale is not applicable.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>NUM_PREC_RADIX</td>
 * <td>
 * An integer value of either 10 (representing
 * an exact numeric data type), 2 (representing an
 * approximate numeric data type), or null (representing a data type for
 * which radix is not applicable).
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>NULLABLE</td>
 * <td>An integer value representing whether the parameter is nullable
 * or not.</td>
 * </tr>
 * <tr valign="top">
 * <td>REMARKS</td>
 * <td>Description of the parameter.</td>
 * </tr>
 * <tr valign="top">
 * <td>COLUMN_DEF</td>
 * <td>Default value for the parameter.</td>
 * </tr>
 * <tr valign="top">
 * <td>SQL_DATA_TYPE</td>
 * <td>An integer value representing the size of the parameter.</td>
 * </tr>
 * <tr valign="top">
 * <td>SQL_DATETIME_SUB</td>
 * <td>
 * Returns an integer value representing a datetime subtype code,
 * or null for SQL data types to which this does not apply.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>CHAR_OCTET_LENGTH</td>
 * <td>
 * Maximum length in octets for a character data type parameter, which
 * matches COLUMN_SIZE for single-byte character set data, or null for
 * non-character data types.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>ORDINAL_POSITION</td>
 * <td>The 1-indexed position of the parameter in the CALL
 * statement.</td>
 * </tr>
 * <tr valign="top">
 * <td>IS_NULLABLE</td>
 * <td>
 * A string value where 'YES' means that the parameter accepts or
 * returns null values and 'NO' means that the parameter does not
 * accept or return null values.
 * </td>
 * </tr>
 */
function db2_procedure_columns($connection, $qualifier, $schema, $procedure, $parameter) {}

function db2_procedurecolumns() {}

/**
 * Returns a result set listing the stored procedures registered in a database
 * @link https://php.net/manual/en/function.db2-procedures.php
 * @param resource $connection <p>
 * A valid connection to an IBM DB2, Cloudscape, or Apache Derby database.
 * </p>
 * @param string $qualifier <p>
 * A qualifier for DB2 databases running on OS/390 or z/OS servers. For
 * other databases, pass null or an empty string.
 * </p>
 * @param string $schema <p>
 * The schema which contains the procedures. This parameter accepts a
 * search pattern containing _ and %
 * as wildcards.
 * </p>
 * @param string $procedure <p>
 * The name of the procedure. This parameter accepts a
 * search pattern containing _ and %
 * as wildcards.
 * </p>
 * @return resource|false A statement resource with a result set containing rows describing
 * the stored procedures matching the specified parameters. The rows are
 * composed of the following columns:
 * <tr valign="top">
 * <td>Column name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>PROCEDURE_CAT</td>
 * <td>The catalog that contains the procedure. The value is null if
 * this table does not have catalogs.</td>
 * </tr>
 * <tr valign="top">
 * <td>PROCEDURE_SCHEM</td>
 * <td>Name of the schema that contains the stored procedure.</td>
 * </tr>
 * <tr valign="top">
 * <td>PROCEDURE_NAME</td>
 * <td>Name of the procedure.</td>
 * </tr>
 * <tr valign="top">
 * <td>NUM_INPUT_PARAMS</td>
 * <td>Number of input (IN) parameters for the stored procedure.</td>
 * </tr>
 * <tr valign="top">
 * <td>NUM_OUTPUT_PARAMS</td>
 * <td>Number of output (OUT) parameters for the stored procedure.</td>
 * </tr>
 * <tr valign="top">
 * <td>NUM_RESULT_SETS</td>
 * <td>Number of result sets returned by the stored procedure.</td>
 * </tr>
 * <tr valign="top">
 * <td>REMARKS</td>
 * <td>Any comments about the stored procedure.</td>
 * </tr>
 * <tr valign="top">
 * <td>PROCEDURE_TYPE</td>
 * <td>Always returns 1, indicating that the stored
 * procedure does not return a return value.</td>
 * </tr>
 */
function db2_procedures($connection, $qualifier, $schema, $procedure) {}

/**
 * Returns a result set listing the unique row identifier columns for a table
 * @link https://php.net/manual/en/function.db2-special-columns.php
 * @param resource $connection <p>
 * A valid connection to an IBM DB2, Cloudscape, or Apache Derby database.
 * </p>
 * @param string $qualifier <p>
 * A qualifier for DB2 databases running on OS/390 or z/OS servers. For
 * other databases, pass null or an empty string.
 * </p>
 * @param string $schema <p>
 * The schema which contains the tables.
 * </p>
 * @param string $table_name <p>
 * The name of the table.
 * </p>
 * @param int $scope <p>
 * Integer value representing the minimum duration for which the
 * unique row identifier is valid. This can be one of the following
 * values:
 * <tr valign="top">
 * <td>Integer value</td>
 * <td>SQL constant</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>0</td>
 * <td>SQL_SCOPE_CURROW</td>
 * <td>Row identifier is valid only while the cursor is positioned
 * on the row.</td>
 * </tr>
 * <tr valign="top">
 * <td>1</td>
 * <td>SQL_SCOPE_TRANSACTION</td>
 * <td>Row identifier is valid for the duration of the
 * transaction.</td>
 * </tr>
 * <tr valign="top">
 * <td>2</td>
 * <td>SQL_SCOPE_SESSION</td>
 * <td>Row identifier is valid for the duration of the
 * connection.</td>
 * </tr>
 * </p>
 * @return resource|false A statement resource with a result set containing rows with unique
 * row identifier information for a table. The rows are composed of the
 * following columns:
 * <tr valign="top">
 * <td>Column name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>SCOPE</td>
 * <td>
 * <p>
 * <tr valign="top">
 * <td>Integer value</td>
 * <td>SQL constant</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>0</td>
 * <td>SQL_SCOPE_CURROW</td>
 * <td>Row identifier is valid only while the cursor is positioned
 * on the row.</td>
 * </tr>
 * <tr valign="top">
 * <td>1</td>
 * <td>SQL_SCOPE_TRANSACTION</td>
 * <td>Row identifier is valid for the duration of the
 * transaction.</td>
 * </tr>
 * <tr valign="top">
 * <td>2</td>
 * <td>SQL_SCOPE_SESSION</td>
 * <td>Row identifier is valid for the duration of the
 * connection.</td>
 * </tr>
 * </p>
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>COLUMN_NAME</td>
 * <td>Name of the unique column.</td>
 * </tr>
 * <tr valign="top">
 * <td>DATA_TYPE</td>
 * <td>SQL data type for the column.</td>
 * </tr>
 * <tr valign="top">
 * <td>TYPE_NAME</td>
 * <td>Character string representation of the SQL data type for the
 * column.</td>
 * </tr>
 * <tr valign="top">
 * <td>COLUMN_SIZE</td>
 * <td>An integer value representing the size of the column.</td>
 * </tr>
 * <tr valign="top">
 * <td>BUFFER_LENGTH</td>
 * <td>
 * Maximum number of bytes necessary to store data from this column.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>DECIMAL_DIGITS</td>
 * <td>
 * The scale of the column, or null where scale is not applicable.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>NUM_PREC_RADIX</td>
 * <td>
 * An integer value of either 10 (representing
 * an exact numeric data type), 2 (representing an
 * approximate numeric data type), or null (representing a data type for
 * which radix is not applicable).
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>PSEUDO_COLUMN</td>
 * <td>Always returns 1.</td>
 * </tr>
 */
function db2_special_columns($connection, $qualifier, $schema, $table_name, $scope) {}

function db2_specialcolumns() {}

/**
 * Returns a result set listing the index and statistics for a table
 * @link https://php.net/manual/en/function.db2-statistics.php
 * @param resource $connection <p>
 * A valid connection to an IBM DB2, Cloudscape, or Apache Derby database.
 * </p>
 * @param string $qualifier <p>
 * A qualifier for DB2 databases running on OS/390 or z/OS servers. For
 * other databases, pass null or an empty string.
 * </p>
 * @param string $schema <p>
 * The schema that contains the targeted table. If this parameter is
 * null, the statistics and indexes are returned for the schema of the
 * current user.
 * </p>
 * @param string $table_name <p>
 * The name of the table.
 * </p>
 * @param bool $unique <p>
 * An integer value representing the type of index information to return.
 * 0
 * </p>
 * <p>
 * Return only the information for unique indexes on the table.
 * </p>
 * @return resource|false A statement resource with a result set containing rows describing
 * the statistics and indexes for the base tables matching the specified
 * parameters. The rows are composed of the following columns:
 * <table>
 * <tr valign="top">
 * <td>Column name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_CAT</td>
 * <td>The catalog that contains the table. The value is null if
 * this table does not have catalogs.</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_SCHEM</td>
 * <td>Name of the schema that contains the table.</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_NAME</td>
 * <td>Name of the table.</td>
 * </tr>
 * <tr valign="top">
 * <td>NON_UNIQUE</td>
 * <td>
 * <table>
 * <p>
 * An integer value representing whether the index prohibits unique
 * values, or whether the row represents statistics on the table itself:</p>
 * <tr valign="top">
 * <td>Return value</td>
 * <td>Parameter type</td>
 * </tr>
 * <tr valign="top">
 * <td>0 (SQL_FALSE)</td>
 * <td>The index allows duplicate values.</td>
 * </tr>
 * <tr valign="top">
 * <td>1 (SQL_TRUE)</td>
 * <td>The index values must be unique.</td>
 * </tr>
 * <tr valign="top">
 * <td>null</td>
 * <td>This row is statistics information for the table itself.</td>
 * </tr>
 * </table>
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>INDEX_QUALIFIER</td>
 * <td>A string value representing the qualifier that would have to be
 * prepended to INDEX_NAME to fully qualify the index.</td>
 * </tr>
 * <tr valign="top">
 * <td>INDEX_NAME</td>
 * <td>A string representing the name of the index.</td>
 * </tr>
 * <tr valign="top">
 * <td>TYPE</td>
 * <td>
 * <p>
 * An integer value representing the type of information contained in
 * this row of the result set:</p>
 * <table>
 * <tr valign="top">
 * <td>Return value</td>
 * <td>Parameter type</td>
 * </tr>
 * <tr valign="top">
 * <td>0 (SQL_TABLE_STAT)</td>
 * <td>The row contains statistics about the table itself.</td>
 * </tr>
 * <tr valign="top">
 * <td>1 (SQL_INDEX_CLUSTERED)</td>
 * <td>The row contains information about a clustered index.</td>
 * </tr>
 * <tr valign="top">
 * <td>2 (SQL_INDEX_HASH)</td>
 * <td>The row contains information about a hashed index.</td>
 * </tr>
 * <tr valign="top">
 * <td>3 (SQL_INDEX_OTHER)</td>
 * <td>The row contains information about a type of index that
 * is neither clustered nor hashed.</td>
 * </tr>
 * </table>
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>ORDINAL_POSITION</td>
 * <td>The 1-indexed position of the column in the index. null if
 * the row contains statistics information about the table itself.</td>
 * </tr>
 * <tr valign="top">
 * <td>COLUMN_NAME</td>
 * <td>The name of the column in the index. null if the row
 * contains statistics information about the table itself.</td>
 * </tr>
 * <tr valign="top">
 * <td>ASC_OR_DESC</td>
 * <td>
 * A if the column is sorted in ascending order,
 * D if the column is sorted in descending order,
 * null if the row contains statistics information about the table
 * itself.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>CARDINALITY</td>
 * <td>
 * <p>
 * If the row contains information about an index, this column contains
 * an integer value representing the number of unique values in the
 * index.
 * </p>
 * <p>
 * If the row contains information about the table itself, this column
 * contains an integer value representing the number of rows in the
 * table.
 * </p>
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>PAGES</td>
 * <td>
 * <p>
 * If the row contains information about an index, this column contains
 * an integer value representing the number of pages used to store the
 * index.
 * </p>
 * <p>
 * If the row contains information about the table itself, this column
 * contains an integer value representing the number of pages used to
 * store the table.
 * </p>
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>FILTER_CONDITION</td>
 * <td>Always returns null.</td>
 * </tr>
 * </table>
 */
function db2_statistics($connection, $qualifier, $schema, $table_name, $unique) {}

/**
 * Returns a result set listing the tables and associated privileges in a database
 * @link https://php.net/manual/en/function.db2-table-privileges.php
 * @param resource $connection <p>
 * A valid connection to an IBM DB2, Cloudscape, or Apache Derby database.
 * </p>
 * @param string $qualifier <p>
 * A qualifier for DB2 databases running on OS/390 or z/OS servers. For
 * other databases, pass null or an empty string.
 * </p>
 * @param string $schema <p>
 * The schema which contains the tables. This parameter accepts a
 * search pattern containing _ and %
 * as wildcards.
 * </p>
 * @param string $table_name <p>
 * The name of the table. This parameter accepts a search pattern
 * containing _ and % as wildcards.
 * </p>
 * @return resource|false A statement resource with a result set containing rows describing
 * the privileges for the tables that match the specified parameters. The rows
 * are composed of the following columns:
 * <tr valign="top">
 * <td>Column name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_CAT</td>
 * <td>The catalog that contains the table. The value is null if
 * this table does not have catalogs.</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_SCHEM</td>
 * <td>Name of the schema that contains the table.</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_NAME</td>
 * <td>Name of the table.</td>
 * </tr>
 * <tr valign="top">
 * <td>GRANTOR</td>
 * <td>Authorization ID of the user who granted the privilege.</td>
 * </tr>
 * <tr valign="top">
 * <td>GRANTEE</td>
 * <td>Authorization ID of the user to whom the privilege was
 * granted.</td>
 * </tr>
 * <tr valign="top">
 * <td>PRIVILEGE</td>
 * <td>
 * The privilege that has been granted. This can be one of ALTER,
 * CONTROL, DELETE, INDEX, INSERT, REFERENCES, SELECT, or UPDATE.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>IS_GRANTABLE</td>
 * <td>
 * A string value of "YES" or "NO" indicating whether the grantee
 * can grant the privilege to other users.
 * </td>
 * </tr>
 */
function db2_table_privileges($connection, $qualifier = null, $schema = null, $table_name = null) {}

function db2_tableprivileges() {}

/**
 * Returns a result set listing the tables and associated metadata in a database
 * @link https://php.net/manual/en/function.db2-tables.php
 * @param resource $connection <p>
 * A valid connection to an IBM DB2, Cloudscape, or Apache Derby database.
 * </p>
 * @param string $qualifier <p>
 * A qualifier for DB2 databases running on OS/390 or z/OS servers. For
 * other databases, pass null or an empty string.
 * </p>
 * @param string $schema <p>
 * The schema which contains the tables. This parameter accepts a
 * search pattern containing _ and %
 * as wildcards.
 * </p>
 * @param string $table_name
 * @param string $table_type
 * @return resource|false A statement resource with a result set containing rows describing
 * the tables that match the specified parameters. The rows are composed of
 * the following columns:
 * <tr valign="top">
 * <td>Column name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_CAT</td>
 * <td>The catalog that contains the table. The value is null if
 * this table does not have catalogs.</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_SCHEM</td>
 * <td>Name of the schema that contains the table.</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_NAME</td>
 * <td>Name of the table.</td>
 * </tr>
 * <tr valign="top">
 * <td>TABLE_TYPE</td>
 * <td>Table type identifier for the table.</td>
 * </tr>
 * <tr valign="top">
 * <td>REMARKS</td>
 * <td>Description of the table.</td>
 * </tr>
 */
function db2_tables($connection, $qualifier = null, $schema = null, $table_name = null, $table_type = null) {}

/**
 * Executes an SQL statement directly
 * @link https://php.net/manual/en/function.db2-exec.php
 * @param resource $connection <p>
 * A valid database connection resource variable as returned from
 * db2_connect or db2_pconnect.
 * </p>
 * @param string $statement <p>
 * An SQL statement. The statement cannot contain any parameter markers.
 * </p>
 * @param array $options <p>
 * An associative array containing statement options. You can use this
 * parameter to request a scrollable cursor on database servers that
 * support this functionality.
 * cursor
 * </p>
 * <p>
 * Passing the DB2_FORWARD_ONLY value requests a
 * forward-only cursor for this SQL statement. This is the default
 * type of cursor, and it is supported by all database servers. It is
 * also much faster than a scrollable cursor.
 * </p>
 * <p>
 * Passing the DB2_SCROLLABLE value requests a
 * scrollable cursor for this SQL statement. This type of cursor
 * enables you to fetch rows non-sequentially from the database
 * server. However, it is only supported by DB2 servers, and is much
 * slower than forward-only cursors.
 * </p>
 * @return resource|false A statement resource if the SQL statement was issued successfully,
 * or false if the database failed to execute the SQL statement.
 */
function db2_exec($connection, $statement, array $options = null) {}

/**
 * Prepares an SQL statement to be executed
 * @link https://php.net/manual/en/function.db2-prepare.php
 * @param resource $connection <p>
 * A valid database connection resource variable as returned from
 * db2_connect or db2_pconnect.
 * </p>
 * @param string $statement <p>
 * An SQL statement, optionally containing one or more parameter markers..
 * </p>
 * @param array $options <p>
 * An associative array containing statement options. You can use this
 * parameter to request a scrollable cursor on database servers that
 * support this functionality.
 * cursor
 * </p>
 * </p>
 * Passing the DB2_FORWARD_ONLY value requests a
 * forward-only cursor for this SQL statement. This is the default
 * type of cursor, and it is supported by all database servers. It is
 * also much faster than a scrollable cursor.
 * </p>
 * <p>
 * Passing the DB2_SCROLLABLE value requests a
 * scrollable cursor for this SQL statement. This type of cursor
 * enables you to fetch rows non-sequentially from the database
 * server. However, it is only supported by DB2 servers, and is much
 * slower than forward-only cursors.
 * </p>
 * @return resource|false A statement resource if the SQL statement was successfully parsed and
 * prepared by the database server. Returns false if the database server
 * returned an error. You can determine which error was returned by calling
 * db2_stmt_error or db2_stmt_errormsg.
 */
function db2_prepare($connection, $statement, array $options = null) {}

/**
 * Executes a prepared SQL statement
 * @link https://php.net/manual/en/function.db2-execute.php
 * @param resource $stmt <p>
 * A prepared statement returned from db2_prepare.
 * </p>
 * @param array $parameters <p>
 * An array of input parameters matching any parameter markers contained
 * in the prepared statement.
 * </p>
 * @return bool true on success or false on failure.
 */
function db2_execute($stmt, array $parameters = null) {}

/**
 * Returns a string containing the last SQL statement error message
 * @link https://php.net/manual/en/function.db2-stmt-errormsg.php
 * @param resource $stmt <p>
 * A valid statement resource.
 * </p>
 * @return string a string containing the error message and SQLCODE value for the
 * last error that occurred issuing an SQL statement.
 */
function db2_stmt_errormsg($stmt = null) {}

/**
 * Returns the last connection error message and SQLCODE value
 * @link https://php.net/manual/en/function.db2-conn-errormsg.php
 * @param resource $connection <p>
 * A connection resource associated with a connection that initially
 * succeeded, but which over time became invalid.
 * </p>
 * @return string a string containing the error message and SQLCODE value resulting
 * from a failed connection attempt. If there is no error associated with the last
 * connection attempt, db2_conn_errormsg returns an empty
 * string.
 */
function db2_conn_errormsg($connection = null) {}

/**
 * Returns a string containing the SQLSTATE returned by the last connection attempt
 * @link https://php.net/manual/en/function.db2-conn-error.php
 * @param resource $connection <p>
 * A connection resource associated with a connection that initially
 * succeeded, but which over time became invalid.
 * </p>
 * @return string the SQLSTATE value resulting from a failed connection attempt.
 * Returns an empty string if there is no error associated with the last
 * connection attempt.
 */
function db2_conn_error($connection = null) {}

/**
 * Returns a string containing the SQLSTATE returned by an SQL statement
 * @link https://php.net/manual/en/function.db2-stmt-error.php
 * @param resource $stmt <p>
 * A valid statement resource.
 * </p>
 * @return string a string containing an SQLSTATE value.
 */
function db2_stmt_error($stmt = null) {}

/**
 * Requests the next result set from a stored procedure
 * @link https://php.net/manual/en/function.db2-next-result.php
 * @param resource $stmt <p>
 * A prepared statement returned from db2_exec or
 * db2_execute.
 * </p>
 * @return resource|false A new statement resource containing the next result set if the
 * stored procedure returned another result set. Returns false if the stored
 * procedure did not return another result set.
 */
function db2_next_result($stmt) {}

/**
 * Returns the number of fields contained in a result set
 * @link https://php.net/manual/en/function.db2-num-fields.php
 * @param resource $stmt <p>
 * A valid statement resource containing a result set.
 * </p>
 * @return int|false An integer value representing the number of fields in the result
 * set associated with the specified statement resource. Returns false if
 * the statement resource is not a valid input value.
 */
function db2_num_fields($stmt) {}

/**
 * Returns the number of rows affected by an SQL statement
 * @link https://php.net/manual/en/function.db2-num-rows.php
 * @param resource $stmt <p>
 * A valid stmt resource containing a result set.
 * </p>
 * @return int the number of rows affected by the last SQL statement issued by
 * the specified statement handle.
 */
function db2_num_rows($stmt) {}

/**
 * Returns the name of the column in the result set
 * @link https://php.net/manual/en/function.db2-field-name.php
 * @param resource $stmt <p>
 * Specifies a statement resource containing a result set.
 * </p>
 * @param mixed $column <p>
 * Specifies the column in the result set. This can either be an integer
 * representing the 0-indexed position of the column, or a string
 * containing the name of the column.
 * </p>
 * @return string|false A string containing the name of the specified column. If the
 * specified column does not exist in the result
 * set, db2_field_name returns false.
 */
function db2_field_name($stmt, $column) {}

/**
 * Returns the maximum number of bytes required to display a column
 * @link https://php.net/manual/en/function.db2-field-display-size.php
 * @param resource $stmt <p>
 * Specifies a statement resource containing a result set.
 * </p>
 * @param mixed $column <p>
 * Specifies the column in the result set. This can either be an integer
 * representing the 0-indexed position of the column, or a string
 * containing the name of the column.
 * </p>
 * @return int|false An integer value with the maximum number of bytes required to
 * display the specified column. If the column does not exist in the result
 * set, db2_field_display_size returns false.
 */
function db2_field_display_size($stmt, $column) {}

/**
 * Returns the position of the named column in a result set
 * @link https://php.net/manual/en/function.db2-field-num.php
 * @param resource $stmt <p>
 * Specifies a statement resource containing a result set.
 * </p>
 * @param mixed $column <p>
 * Specifies the column in the result set. This can either be an integer
 * representing the 0-indexed position of the column, or a string
 * containing the name of the column.
 * </p>
 * @return int|false An integer containing the 0-indexed position of the named column in
 * the result set. If the specified column does not exist in the result set,
 * db2_field_num returns false.
 */
function db2_field_num($stmt, $column) {}

/**
 * Returns the precision of the indicated column in a result set
 * @link https://php.net/manual/en/function.db2-field-precision.php
 * @param resource $stmt <p>
 * Specifies a statement resource containing a result set.
 * </p>
 * @param mixed $column <p>
 * Specifies the column in the result set. This can either be an integer
 * representing the 0-indexed position of the column, or a string
 * containing the name of the column.
 * </p>
 * @return int|false An integer containing the precision of the specified column. If the
 * specified column does not exist in the result set,
 * db2_field_precision returns false.
 */
function db2_field_precision($stmt, $column) {}

/**
 * Returns the scale of the indicated column in a result set
 * @link https://php.net/manual/en/function.db2-field-scale.php
 * @param resource $stmt <p>
 * Specifies a statement resource containing a result set.
 * </p>
 * @param mixed $column <p>
 * Specifies the column in the result set. This can either be an integer
 * representing the 0-indexed position of the column, or a string
 * containing the name of the column.
 * </p>
 * @return int|false An integer containing the scale of the specified column. If the
 * specified column does not exist in the result set,
 * db2_field_scale returns false.
 */
function db2_field_scale($stmt, $column) {}

/**
 * Returns the data type of the indicated column in a result set
 * @link https://php.net/manual/en/function.db2-field-type.php
 * @param resource $stmt <p>
 * Specifies a statement resource containing a result set.
 * </p>
 * @param mixed $column <p>
 * Specifies the column in the result set. This can either be an integer
 * representing the 0-indexed position of the column, or a string
 * containing the name of the column.
 * </p>
 * @return string|false A string containing the defined data type of the specified column.
 * If the specified column does not exist in the result set,
 * db2_field_type returns false.
 */
function db2_field_type($stmt, $column) {}

/**
 * Returns the width of the current value of the indicated column in a result set
 * @link https://php.net/manual/en/function.db2-field-width.php
 * @param resource $stmt <p>
 * Specifies a statement resource containing a result set.
 * </p>
 * @param mixed $column <p>
 * Specifies the column in the result set. This can either be an integer
 * representing the 0-indexed position of the column, or a string
 * containing the name of the column.
 * </p>
 * @return int|false An integer containing the width of the specified character or
 * binary data type column in a result set. If the specified column does not
 * exist in the result set, db2_field_width returns
 * false.
 */
function db2_field_width($stmt, $column) {}

/**
 * Returns the cursor type used by a statement resource
 * @link https://php.net/manual/en/function.db2-cursor-type.php
 * @param resource $stmt <p>
 * A valid statement resource.
 * </p>
 * @return int either DB2_FORWARD_ONLY if the statement
 * resource uses a forward-only cursor or DB2_SCROLLABLE if
 * the statement resource uses a scrollable cursor.
 */
function db2_cursor_type($stmt) {}

/**
 * Rolls back a transaction
 * @link https://php.net/manual/en/function.db2-rollback.php
 * @param resource $connection <p>
 * A valid database connection resource variable as returned from
 * db2_connect or db2_pconnect.
 * </p>
 * @return bool true on success or false on failure.
 */
function db2_rollback($connection) {}

/**
 * Frees resources associated with the indicated statement resource
 * @link https://php.net/manual/en/function.db2-free-stmt.php
 * @param resource $stmt <p>
 * A valid statement resource.
 * </p>
 * @return bool true on success or false on failure.
 */
function db2_free_stmt($stmt) {}

/**
 * Returns a single column from a row in the result set
 * @link https://php.net/manual/en/function.db2-result.php
 * @param resource $stmt <p>
 * A valid stmt resource.
 * </p>
 * @param mixed $column <p>
 * Either an integer mapping to the 0-indexed field in the result set, or
 * a string matching the name of the column.
 * </p>
 * @return mixed the value of the requested field if the field exists in the result
 * set. Returns NULL if the field does not exist, and issues a warning.
 */
function db2_result($stmt, $column) {}

/**
 * Sets the result set pointer to the next row or requested row
 * @link https://php.net/manual/en/function.db2-fetch-row.php
 * @param resource $stmt <p>
 * A valid stmt resource.
 * </p>
 * @param int $row_number <p>
 * With scrollable cursors, you can request a specific row number in the
 * result set. Row numbering is 1-indexed.
 * </p>
 * @return bool true if the requested row exists in the result set. Returns
 * false if the requested row does not exist in the result set.
 */
function db2_fetch_row($stmt, $row_number = null) {}

/**
 * Returns an array, indexed by column name, representing a row in a result set
 * @link https://php.net/manual/en/function.db2-fetch-assoc.php
 * @param resource $stmt <p>
 * A valid stmt resource containing a result set.
 * </p>
 * @param int $row_number <p>
 * Requests a specific 1-indexed row from the result set. Passing this
 * parameter results in a PHP warning if the result set uses a
 * forward-only cursor.
 * </p>
 * @return array|false An associative array with column values indexed by the column name
 * representing the next or requested row in the result set. Returns false if
 * there are no rows left in the result set, or if the row requested by
 * row_number does not exist in the result set.
 */
function db2_fetch_assoc($stmt, $row_number = null) {}

/**
 * Returns an array, indexed by column position, representing a row in a result set
 * @link https://php.net/manual/en/function.db2-fetch-array.php
 * @param resource $stmt <p>
 * A valid stmt resource containing a result set.
 * </p>
 * @param int $row_number <p>
 * Requests a specific 1-indexed row from the result set. Passing this
 * parameter results in a PHP warning if the result set uses a
 * forward-only cursor.
 * </p>
 * @return array|false A 0-indexed array with column values indexed by the column position
 * representing the next or requested row in the result set. Returns false if
 * there are no rows left in the result set, or if the row requested by
 * row_number does not exist in the result set.
 */
function db2_fetch_array($stmt, $row_number = null) {}

/**
 * Returns an array, indexed by both column name and position, representing a row in a result set
 * @link https://php.net/manual/en/function.db2-fetch-both.php
 * @param resource $stmt <p>
 * A valid stmt resource containing a result set.
 * </p>
 * @param int $row_number <p>
 * Requests a specific 1-indexed row from the result set. Passing this
 * parameter results in a PHP warning if the result set uses a
 * forward-only cursor.
 * </p>
 * @return array|false An associative array with column values indexed by both the column
 * name and 0-indexed column number. The array represents the next or
 * requested row in the result set. Returns false if there are no rows left
 * in the result set, or if the row requested by
 * row_number does not exist in the result set.
 */
function db2_fetch_both($stmt, $row_number = null) {}

/**
 * Frees resources associated with a result set
 * @link https://php.net/manual/en/function.db2-free-result.php
 * @param resource $stmt <p>
 * A valid statement resource.
 * </p>
 * @return bool true on success or false on failure.
 */
function db2_free_result($stmt) {}

/**
 * Set options for connection or statement resources
 * @link https://php.net/manual/en/function.db2-set-option.php
 * @param resource $resource <p>
 * A valid statement resource as returned from
 * db2_prepare or a valid connection resource as
 * returned from db2_connect or
 * db2_pconnect.
 * </p>
 * @param array $options <p>
 * An associative array containing valid statement or connection
 * options. This parameter can be used to change autocommit values,
 * cursor types (scrollable or forward), and to specify the case of
 * the column names (lower, upper, or natural) that will appear in a
 * result set.
 * autocommit
 * <p>
 * Passing DB2_AUTOCOMMIT_ON turns
 * autocommit on for the specified connection resource.
 * </p>
 * <p>
 * Passing DB2_AUTOCOMMIT_OFF turns
 * autocommit off for the specified connection resource.
 * </p>
 * @param int $type <p>
 * An integer value that specifies the type of resource that was
 * passed into the function. The type of resource and this value
 * must correspond.
 * <p>
 * Passing 1 as the value specifies that
 * a connection resource has been passed into the function.
 * </p>
 * <p>
 * Passing any integer not equal to 1 as
 * the value specifies that a statement resource has been
 * passed into the function.
 * </p>
 * @return bool true on success or false on failure.
 */
function db2_set_option($resource, array $options, $type) {}

function db2_setoption() {}

/**
 * Returns an object with properties representing columns in the fetched row
 * @link https://php.net/manual/en/function.db2-fetch-object.php
 * @param resource $stmt <p>
 * A valid stmt resource containing a result set.
 * </p>
 * @param int $row_number <p>
 * Requests a specific 1-indexed row from the result set. Passing this
 * parameter results in a PHP warning if the result set uses a
 * forward-only cursor.
 * </p>
 * @return object|false An object representing a single row in the result set. The
 * properties of the object map to the names of the columns in the result set.
 * </p>
 * <p>
 * The IBM DB2, Cloudscape, and Apache Derby database servers typically fold
 * column names to upper-case, so the object properties will reflect that case.
 * </p>
 * <p>
 * If your SELECT statement calls a scalar function to modify the value
 * of a column, the database servers return the column number as the name of
 * the column in the result set. If you prefer a more descriptive column name
 * and object property, you can use the AS clause to assign a name to the
 * column in the result set.
 * </p>
 * <p>
 * Returns false if no row was retrieved.
 */
function db2_fetch_object($stmt, $row_number = null) {}

/**
 * Returns an object with properties that describe the DB2 database server
 * @link https://php.net/manual/en/function.db2-server-info.php
 * @param resource $connection <p>
 * Specifies an active DB2 client connection.
 * </p>
 * @return object|false An object on a successful call. Returns false on failure.
 */
function db2_server_info($connection) {}

/**
 * Returns an object with properties that describe the DB2 database client
 * @link https://php.net/manual/en/function.db2-client-info.php
 * @param resource $connection <p>
 * Specifies an active DB2 client connection.
 * </p>
 * @return object|false An object on a successful call. Returns false on failure.
 */
function db2_client_info($connection) {}

/**
 * Used to escape certain characters
 * @link https://php.net/manual/en/function.db2-escape-string.php
 * @param string $string_literal <p>
 * The string that contains special characters that need to be modified.
 * Characters that are prepended with a backslash are \x00,
 * \n, \r, \,
 * ', " and \x1a.
 * </p>
 * @return string string_literal with the special characters
 * noted above prepended with backslashes.
 */
function db2_escape_string($string_literal) {}

/**
 * Gets a user defined size of LOB files with each invocation
 * @link https://php.net/manual/en/function.db2-lob-read.php
 * @param resource $stmt <p>
 * A valid stmt resource containing LOB data.
 * </p>
 * @param int $colnum <p>
 * A valid column number in the result set of the stmt resource.
 * </p>
 * @param int $length <p>
 * The size of the LOB data to be retrieved from the stmt resource.
 * </p>
 * @return string|false The amount of data the user specifies. Returns
 * false if the data cannot be retrieved.
 */
function db2_lob_read($stmt, $colnum, $length) {}

/**
 * Retrieves an option value for a statement resource or a connection resource
 * @link https://php.net/manual/en/function.db2-get-option.php
 * @param resource $resource <p>
 * A valid statement resource as returned from
 * db2_prepare or a valid connection resource as
 * returned from db2_connect or
 * db2_pconnect.
 * </p>
 * @param string $option <p>
 * A valid statement or connection options. The following new options are available
 * as of ibm_db2 version 1.6.0. They provide useful tracking information
 * that can be set during execution with db2_get_option.
 * </p>
 * <p>
 * Note: Prior versions of ibm_db2 do not support these new options.
 * </p>
 * <p>
 * When the value in each option is being set, some servers might not handle
 * the entire length provided and might truncate the value.
 * </p>
 * <p>
 * To ensure that the data specified in each option is converted correctly
 * when transmitted to a host system, use only the characters A through Z,
 * 0 through 9, and the underscore (_) or period (.).
 * </p>
 * <p>
 * SQL_ATTR_INFO_USERID - A pointer to a null-terminated
 * character string used to identify the client user ID sent to the host
 * database server when using DB2 Connect.
 * </p>
 * <p>
 * Note: DB2 for z/OS and OS/390 servers support up to a length of 16 characters.
 * This user-id is not to be confused with the authentication user-id, it is for
 * identification purposes only and is not used for any authorization.
 * </p>
 * @return string|false The current setting of the connection attribute provided on success
 * or false on failure.
 */
function db2_get_option($resource, $option) {}

/**
 * Returns the auto generated ID of the last insert query that successfully executed on this connection.
 * @link https://php.net/manual/en/function.db2-last-insert-id.php
 * The result of this function is not affected by any of the following:
 * <ul><li>A single row INSERT statement with a VALUES clause for a table without an identity column.
 * <li>A multiple row INSERT statement with a VALUES clause.
 * <li>An INSERT statement with a fullselect.
 * <li>A ROLLBACK TO SAVEPOINT statement.
 * </ul>
 * @param resource $resource A valid connection resource as returned from db2_connect() or db2_pconnect().
 * The value of this parameter cannot be a statement resource or result set resource.
 * @return string|null Returns the auto generated ID of last insert query that successfully executed on this connection
 *                     or NULL if no ID was found.
 */
function db2_last_insert_id($resource) {}

/**
 * Specifies that binary data shall be returned as is. This is the default
 * mode.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_BINARY', 1);

/**
 * Specifies that binary data shall be converted to a hexadecimal encoding
 * and returned as an ASCII string.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_CONVERT', 2);

/**
 * Specifies that binary data shall be converted to a null value.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_PASSTHRU', 3);

/**
 * Specifies a scrollable cursor for a statement resource. This mode enables
 * random access to rows in a result set, but currently is supported only by
 * IBM DB2 Universal Database.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_SCROLLABLE', 1);

/**
 * Specifies a forward-only cursor for a statement resource. This is the
 * default cursor type and is supported on all database servers.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_FORWARD_ONLY', 0);

/**
 * Specifies the PHP variable should be bound as an IN parameter for a
 * stored procedure.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_PARAM_IN', 1);

/**
 * Specifies the PHP variable should be bound as an OUT parameter for a
 * stored procedure.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_PARAM_OUT', 4);

/**
 * Specifies the PHP variable should be bound as an INOUT parameter for a
 * stored procedure.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_PARAM_INOUT', 2);

/**
 * Specifies that the column should be bound directly to a file for input.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_PARAM_FILE', 11);

/**
 * Specifies that autocommit should be turned on.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_AUTOCOMMIT_ON', 1);

/**
 * Specifies that autocommit should be turned off.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_AUTOCOMMIT_OFF', 0);

/**
 * Specifies that deferred prepare should be turned on for the specified statement resource.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_DEFERRED_PREPARE_ON', 1);

/**
 * Specifies that deferred prepare should be turned off for the specified statement resource.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_DEFERRED_PREPARE_OFF', 0);

/**
 * Specifies that the variable should be bound as a DOUBLE, FLOAT, or REAL
 * data type.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_DOUBLE', 8);

/**
 * Specifies that the variable should be bound as a SMALLINT, INTEGER, or
 * BIGINT data type.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_LONG', 4);

/**
 * Specifies that the variable should be bound as a CHAR or VARCHAR data type.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_CHAR', 1);
define('DB2_XML', -370);

/**
 * Specifies that column names will be returned in their natural case.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_CASE_NATURAL', 0);

/**
 * Specifies that column names will be returned in lower case.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_CASE_LOWER', 1);

/**
 * Specifies that column names will be returned in upper case.
 * @link https://php.net/manual/en/ibm-db2.constants.php
 */
define('DB2_CASE_UPPER', 2);

// End of ibm_db2 v.1.6.0
