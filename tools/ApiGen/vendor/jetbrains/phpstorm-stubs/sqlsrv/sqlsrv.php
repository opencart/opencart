<?php
/**
 * SQLSRV Extension Stub File.
 *
 * Current through version 3.0.1 (rel March 22, 2012) of Microsoft Drivers for PHP for SQL Server.
 *
 * Documentation taken from {@link http://msdn.microsoft.com/en-us/library/ee229547(v=sql.10).aspx} on Mar 22, 2012.
 * Additional information from using Reflection.
 */

/**
 * Errors generated on the last sqlsrv function call are returned.
 *
 * <br />Used to specify if {@link sqlsrv_errors() sqlsrv_errors} returns errors, warnings, or both.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_ERR_ERRORS', 0);

/**
 * Warnings generated on the last sqlsrv function call are returned.
 *
 * <br />Used to specify if {@link sqlsrv_errors() sqlsrv_errors} returns errors, warnings, or both.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_ERR_WARNINGS', 1);

/**
 * Errors and warnings generated on the last sqlsrv function call are returned.
 *
 * <br />This is the default value.<br />
 *
 * Used to specify if {@link sqlsrv_errors() sqlsrv_errors} returns errors, warnings, or both.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_ERR_ALL', 2);

/**
 * Turns on logging of all subsystems.
 *
 * <br />Used as the value for the LogSubsystems setting with
 * {@link sqlsrv_configure() sqlsrv_configure}.<br />
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_LOG_SYSTEM_ALL', -1);

/**
 * Turns logging off.
 *
 * <br />Used as the value for the LogSubsystems setting with  {@link sqlsrv_configure() sqlsrv_configure}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_LOG_SYSTEM_OFF', 0);

/**
 * Turns on logging of initialization activity.
 *
 * <br />Used as the value for the LogSubsystems setting with {@link sqlsrv_configure() sqlsrv_configure}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_LOG_SYSTEM_INIT', 1);

/**
 * Turns on logging of connection activity.
 *
 * <br />Used as the value for the LogSubsystems setting with {@link sqlsrv_configure() sqlsrv_configure}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_LOG_SYSTEM_CONN', 2);

/**
 * Turns on logging of statement activity.
 *
 * <br />Used as the value for the LogSubsystems setting with {@link sqlsrv_configure() sqlsrv_configure}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_LOG_SYSTEM_STMT', 4);

/**
 * Turns on logging of error functions activity (such as handle_error and handle_warning).
 *
 * <br />Used as the value for the
 * LogSubsystems setting with {@link sqlsrv_configure() sqlsrv_configure}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_LOG_SYSTEM_UTIL', 8);

/**
 * Specifies that errors, warnings, and notices will be logged.
 *
 * <br />Used as the value for the LogSeverity setting with {@link sqlsrv_configure() sqlsrv_configure}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_LOG_SEVERITY_ALL', -1);

/**
 * Specifies that errors will be logged.
 *
 * <br />Used as the value for the LogSeverity setting with {@link sqlsrv_configure() sqlsrv_configure}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_LOG_SEVERITY_ERROR', 1);

/**
 * Specifies that notices will be logged.
 *
 * <br />Used as the value for the LogSeverity setting with {@link sqlsrv_configure() sqlsrv_configure}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_LOG_SEVERITY_NOTICE', 4);

/**
 * Specifies that warnings will be logged.
 *
 * <br />Used as the value for the LogSeverity setting with {@link sqlsrv_configure() sqlsrv_configure}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_LOG_SEVERITY_WARNING', 2);

/**
 * Returns numerically indexed array.
 *
 * <br />{@link sqlsrv_fetch_array() sqlsrv_fetch_array} returns the next row of data as a numerically indexed array.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_FETCH_NUMERIC', 1);

/**
 * Returns an associative array.
 *
 * <br />{@link sqlsrv_fetch_array() sqlsrv_fetch_array} returns the next row of data as an associative array.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_FETCH_ASSOC', 2);

/**
 * Returns both a numeric and associative array.
 *
 * <br />{@link sqlsrv_fetch_array() sqlsrv_fetch_array} returns the next row of data as an array with both numeric and
 * associative keys.<br />
 *
 * This is the default value.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_FETCH_BOTH', 3);

/**
 * Null
 *
 * <br />Used with {@link sqlsrv_prepare() sqlsrv_prepare},
 * {@link sqlsrv_query() sqlsrv_query}
 * and {@link sqlsrv_get_field() sqlsrv_get_field} to request a field be return as a specific PHP type.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_PHPTYPE_NULL', 1);

/**
 * Integer
 *
 * <br />Used with {@link sqlsrv_prepare() sqlsrv_prepare},
 * {@link sqlsrv_query() sqlsrv_query}
 * and {@link sqlsrv_get_field() sqlsrv_get_field} to request a field be return as a specific PHP type.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_PHPTYPE_INT', 2);

/**
 * Float
 *
 * <br />Used with {@link sqlsrv_prepare() sqlsrv_prepare},
 * {@link sqlsrv_query() sqlsrv_query}
 * and {@link sqlsrv_get_field() sqlsrv_get_field} to request a field be return as a specific PHP type.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_PHPTYPE_FLOAT', 3);

/**
 * Datetime
 *
 * <br />Used with {@link sqlsrv_prepare() sqlsrv_prepare},
 * {@link sqlsrv_query() sqlsrv_query}
 * and {@link sqlsrv_get_field() sqlsrv_get_field} to request a field be return as a specific PHP type.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_PHPTYPE_DATETIME', 4);

/**
 * Binary Encoding
 *
 * <br />Data is returned as a raw byte stream from the server without performing encoding or translation.<br />
 *
 * Used with {@link sqlsrv_prepare() sqlsrv_prepare},
 * {@link sqlsrv_query() sqlsrv_query}
 * and {@link sqlsrv_get_field() sqlsrv_get_field} to request a field be return as a specific PHP type.<br />
 *
 * This is used with {@link SQLSRV_PHPTYPE_STREAM() SQLSRV_PHPTYPE_STREAM} and
 * {@link SQLSRV_PHPTYPE_STRING() SQLSRV_PHPTYPE_STRING} to specify the encoding of those PHP types types.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_ENC_BINARY', 'binary');

/**
 * Character Encoding
 *
 * <br />Data is returned in 8-bit characters as specified in the code page of the Windows locale that is set on the
 * system. Any multi-byte characters or characters that do not map into this code page are substituted with a single
 * byte question mark (?) character.<br />
 *
 * This is the default encoding.<br />
 *
 * Used with {@link sqlsrv_prepare() sqlsrv_prepare},
 * {@link sqlsrv_query() sqlsrv_query}
 * and {@link sqlsrv_get_field() sqlsrv_get_field} to request a field be return as a specific PHP type.<br />
 *
 * This is used with {@link SQLSRV_PHPTYPE_STREAM() SQLSRV_PHPTYPE_STREAM} and
 * {@link SQLSRV_PHPTYPE_STRING() SQLSRV_PHPTYPE_STRING} to specify the encoding of those PHP types types.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_ENC_CHAR', 'char');

/**
 * The column is not nullable.
 *
 * <br />You can compare the value of the Nullable key that is returned by
 * {@link sqlsrv_field_metadata() sqlsrv_field_metadata} to determine the column's nullable status.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_NULLABLE_NO', 0);

/**
 * The column is nullable.
 *
 * <br />You can compare the value of the Nullable key that is returned by
 * {@link sqlsrv_field_metadata() sqlsrv_field_metadata} to determine the column's nullable status.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_NULLABLE_YES', 1);

/**
 * It is not known if the column is nullable.
 *
 * <br />You can compare the value of the Nullable key that is returned by
 * {@link sqlsrv_field_metadata() sqlsrv_field_metadata} to determine the column's nullable status.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_NULLABLE_UNKNOWN', 2);

/**
 * bigint.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_BIGINT', -5);
/**
 * bit.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_BIT', -7);
/**
 * char.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_CHAR', 1);
/**
 * datetime.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_DATETIME', 25177693);
/**
 * decimal.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_DECIMAL', 3);
/**
 * float.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_FLOAT', 6);
/**
 * image.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_IMAGE', -4);
/**
 * int.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_INT', 4);
/**
 * money.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_MONEY', 33564163);
/**
 * nchar.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_NCHAR', -8);
/**
 * ntext.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_NTEXT', -10);
/**
 * numeric.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_NUMERIC', 2);
/**
 * nvarchar.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_NVARCHAR', -9);
/**
 * text.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_TEXT', -1);
/**
 * real.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_REAL', 7);
/**
 * smalldatetime.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_SMALLDATETIME', 8285);
/**
 * smallint.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_SMALLINT', 5);
/**
 * smallmoney.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_SMALLMONEY', 33559555);
/**
 * timestamp.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_TIMESTAMP', 4606);
/**
 * tinyint.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_TINYINT', -6);
/**
 * udt.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_UDT', -151);
/**
 * uniqueidentifier.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_UNIQUEIDENTIFIER', -11);
/**
 * varbinary.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_VARBINARY', -3);
/**
 * varchar.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_VARCHAR', 12);
/**
 * xml.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_XML', -152);
/**
 * date.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_DATE', 5211);
/**
 * time.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_TIME', 58728806);
/**
 * datetimeoffset.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_DATETIMEOFFSET', 58738021);
/**
 * datetime2.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the SQL Server data type of a parameter.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SQLTYPE_DATETIME2', 58734173);

/**
 * Indicates an input parameter.
 *
 * <br />Used for specifying parameter direction when you call {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_PARAM_IN', 1);

/**
 * Indicates a bidirectional parameter.
 *
 * <br />Used for specifying parameter direction when you call {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_PARAM_INOUT', 2);

/**
 * Indicates an output parameter.
 *
 * <br />Used for specifying parameter direction when you call {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_PARAM_OUT', 4);

/**
 * Read Uncommitted.
 *
 * <br />Specifies that statements can read rows that have been modified by other transactions but not yet committed.<br />
 *
 * Transactions running at the READ UNCOMMITTED level do not issue shared locks to prevent other transactions from
 * modifying data read by the current transaction. READ UNCOMMITTED transactions are also not blocked by exclusive locks
 * that would prevent the current transaction from reading rows that have been modified but not committed by other
 * transactions. When this option is set, it is possible to read uncommitted modifications, which are called dirty reads.
 * Values in the data can be changed and rows can appear or disappear in the data set before the end of the transaction.
 * This option has the same effect as setting NOLOCK on all tables in all SELECT statements in a transaction. This is
 * the least restrictive of the isolation levels.<br />
 *
 * Used with the TransactionIsolation key when calling {@link sqlsrv_connect() sqlsrv_connect}. For information on using
 * these constants, see {@link http://msdn.microsoft.com/en-us/library/ms173763(v=sql.110).aspx SET TRANSACTION ISOLATION LEVEL (Transact-SQL)}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_TXN_READ_UNCOMMITTED', 1);
/**
 * Read Committed.
 *
 * <br />Specifies that statements cannot read data that has been modified but not committed by other transactions.
 * This prevents dirty reads. Data can be changed by other transactions between individual statements within the current
 * transaction, resulting in nonrepeatable reads or phantom data. This option is the SQL Server default.<br />
 *
 * The behavior of READ COMMITTED depends on the setting of the READ_COMMITTED_SNAPSHOT database option.<br />
 *
 * Used with the TransactionIsolation key when calling {@link sqlsrv_connect() sqlsrv_connect}. For information on using
 * these constants, see {@link http://msdn.microsoft.com/en-us/library/ms173763(v=sql.110).aspx SET TRANSACTION ISOLATION LEVEL (Transact-SQL)}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_TXN_READ_COMMITTED', 2);
/**
 * Repeatable Read.
 *
 * <br />Specifies that statements cannot read data that has been modified but not yet committed by other transactions and
 * that no other transactions can modify data that has been read by the current transaction until the current transaction
 * completes.<br />
 *
 * Shared locks are placed on all data read by each statement in the transaction and are held until the transaction
 * completes. This prevents other transactions from modifying any rows that have been read by the current transaction.
 * Other transactions can insert new rows that match the search conditions of statements issued by the current transaction.
 * If the current transaction then retries the statement it will retrieve the new rows, which results in phantom reads.
 * Because shared locks are held to the end of a transaction instead of being released at the end of each statement,
 * concurrency is lower than the default READ COMMITTED isolation level.<br />
 *
 * Use this option only when necessary.<br />
 *
 * Used with the TransactionIsolation key when calling {@link sqlsrv_connect() sqlsrv_connect}. For information on using
 * these constants, see {@link http://msdn.microsoft.com/en-us/library/ms173763(v=sql.110).aspx SET TRANSACTION ISOLATION LEVEL (Transact-SQL)}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_TXN_REPEATABLE_READ', 4);
/**
 * Serializable.
 *
 * <br />Specifies the following:
 * <ul><li>Statements cannot read data that has been modified but not yet committed by other transactions.</li>
 * <li>No other transactions can modify data that has been read by the current transaction until the current
 * transaction completes.</li>
 * <li>Other transactions cannot insert new rows with key values that would fall in the range of keys read by any
 * statements in the current transaction until the current transaction completes.</li></ul>
 *
 * Range locks are placed in the range of key values that match the search conditions of each statement executed in a
 * transaction. This blocks other transactions from updating or inserting any rows that would qualify for any of the
 * statements executed by the current transaction. This means that if any of the statements in a transaction are
 * executed a second time, they will read the same set of rows. The range locks are held until the transaction completes.
 * This is the most restrictive of the isolation levels because it locks entire ranges of keys and holds the locks until
 * the transaction completes. Because concurrency is lower, use this option only when necessary. This option has the same
 * effect as setting HOLDLOCK on all tables in all SELECT statements in a transaction.<br />
 *
 * Used with the TransactionIsolation key when calling {@link sqlsrv_connect() sqlsrv_connect}. For information on using
 * these constants, see {@link http://msdn.microsoft.com/en-us/library/ms173763(v=sql.110).aspx SET TRANSACTION ISOLATION LEVEL (Transact-SQL)}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_TXN_SERIALIZABLE', 8);
/**
 * Snapshot.
 *
 * <br />Specifies that data read by any statement in a transaction will be the transactionally consistent version of
 * the data that existed at the start of the transaction. The transaction can only recognize data modifications that
 * were committed before the start of the transaction. Data modifications made by other transactions after the start of
 * the current transaction are not visible to statements executing in the current transaction. The effect is as if the
 * statements in a transaction get a snapshot of the committed data as it existed at the start of the transaction.<br />
 *
 * Except when a database is being recovered, SNAPSHOT transactions do not request locks when reading data. SNAPSHOT
 * transactions reading data do not block other transactions from writing data. Transactions writing data do not block
 * SNAPSHOT transactions from reading data.<br />
 *
 * During the roll-back phase of a database recovery, SNAPSHOT transactions will request a lock if an attempt is made to
 * read data that is locked by another transaction that is being rolled back. The SNAPSHOT transaction is blocked until
 * that transaction has been rolled back. The lock is released immediately after it has been granted.<br />
 *
 * The ALLOW_SNAPSHOT_ISOLATION database option must be set to ON before you can start a transaction that uses the
 * SNAPSHOT isolation level. If a transaction using the SNAPSHOT isolation level accesses data in multiple databases,
 * ALLOW_SNAPSHOT_ISOLATION must be set to ON in each database.<br />
 *
 * A transaction cannot be set to SNAPSHOT isolation level that started with another isolation level; doing so will
 * cause the transaction to abort. If a transaction starts in the SNAPSHOT isolation level, you can change it to another
 * isolation level and then back to SNAPSHOT. A transaction starts the first time it accesses data.<br />
 *
 * A transaction running under SNAPSHOT isolation level can view changes made by that transaction. For example, if the
 * transaction performs an UPDATE on a table and then issues a SELECT statement against the same table, the modified
 * data will be included in the result set.<br />
 *
 * Used with the TransactionIsolation key when calling {@link sqlsrv_connect() sqlsrv_connect}. For information on using
 * these constants, see {@link http://msdn.microsoft.com/en-us/library/ms173763(v=sql.110).aspx SET TRANSACTION ISOLATION LEVEL (Transact-SQL)}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_TXN_SNAPSHOT', 32);

/**
 * Specifies the next row.
 *
 * <br />This is the default value, if you do not specify the row parameter for a scrollable result set.<br />
 *
 * Used with {@link sqlsrv_fetch() sqlsrv_fetch},
 * {@link sqlsrv_fetch_array() sqlsrv_fetch_array},
 * or {@link sqlsrv_fetch_object() sqlsrv_fetch_object} to specify a row.<br />
 *
 * Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify which row to select in the result set. For
 * information on using these constants, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SCROLL_NEXT', 1);
/**
 * Specifies the row before the current row.
 *
 * <br />Used with {@link sqlsrv_fetch() sqlsrv_fetch},
 * {@link sqlsrv_fetch_array() sqlsrv_fetch_array},
 * or {@link sqlsrv_fetch_object() sqlsrv_fetch_object} to specify a row.<br />
 *
 * Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify which row to select in the result set. For
 * information on using these constants, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SCROLL_PRIOR', 4);
/**
 * Specifies the first row in the result set.
 *
 * <br />Used with {@link sqlsrv_fetch() sqlsrv_fetch},
 * {@link sqlsrv_fetch_array() sqlsrv_fetch_array},
 * or {@link sqlsrv_fetch_object() sqlsrv_fetch_object} to specify a row.<br />
 *
 * Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify which row to select in the result set. For
 * information on using these constants, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SCROLL_FIRST', 2);
/**
 * Specifies the last row in the result set.
 *
 * <br />Used with {@link sqlsrv_fetch() sqlsrv_fetch},
 * {@link sqlsrv_fetch_array() sqlsrv_fetch_array},
 * or {@link sqlsrv_fetch_object() sqlsrv_fetch_object} to specify a row.<br />
 *
 * Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify which row to select in the result set. For
 * information on using these constants, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SCROLL_LAST', 3);
/**
 * Specifies the row specified with the offset parameter.
 *
 * <br />Used with {@link sqlsrv_fetch() sqlsrv_fetch},
 * {@link sqlsrv_fetch_array() sqlsrv_fetch_array},
 * or {@link sqlsrv_fetch_object() sqlsrv_fetch_object} to specify a row.<br />
 *
 * Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify which row to select in the result set. For
 * information on using these constants, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SCROLL_ABSOLUTE', 5);
/**
 * Specifies the row specified with the offset parameter from the current row.
 *
 * <br />Used with {@link sqlsrv_fetch() sqlsrv_fetch},
 * {@link sqlsrv_fetch_array() sqlsrv_fetch_array},
 * or {@link sqlsrv_fetch_object() sqlsrv_fetch_object} to specify a row.<br />
 *
 * Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify which row to select in the result set. For
 * information on using these constants, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_SCROLL_RELATIVE', 6);

/**
 * Lets you move one row at a time starting at the first row of the result set until you reach the end of
 * the result set.
 *
 * <br />This is the default cursor type.<br />
 *
 * {@link sqlsrv_num_rows() sqlsrv_num_rows} returns an error for result sets created with this cursor type.<br />
 *
 * Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the kind of cursor that you can use in a result
 * set. For information on using these constants, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_CURSOR_FORWARD', 'forward');
/**
 * Lets you access rows in any order but will not reflect changes in the database.
 *
 * <br />Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the kind of cursor that you can use in a result
 * set. For information on using these constants, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_CURSOR_STATIC', 'static');
/**
 * Lets you access rows in any order and will reflect changes in the database.
 *
 * <br />{@link sqlsrv_num_rows() sqlsrv_num_rows} returns an error for result sets created with this cursor type.<br />
 *
 * Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the kind of cursor that you can use in a result
 * set. For information on using these constants, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_CURSOR_DYNAMIC', 'dynamic');
/**
 * Lets you access rows in any order.
 *
 * <br />However, a keyset cursor does not update the row count if a row is deleted from the table (a deleted row is
 * returned with no values).<br />
 *
 * Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the kind of cursor that you can use in a result
 * set. For information on using these constants, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_CURSOR_KEYSET', 'keyset');
/**
 * Lets you access rows in any order.
 *
 * <br />Creates a client-side cursor query.<br />
 *
 * Used when calling {@link sqlsrv_query() sqlsrv_query} or
 *{@link sqlsrv_prepare() sqlsrv_prepare} to specify the kind of cursor that you can use in a result
 * set. For information on using these constants, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 */
define('SQLSRV_CURSOR_CLIENT_BUFFERED', 'buffered');

/**
 * Creates and opens a connection.
 *
 * <br />Creates a connection resource and opens a connection. By default, the connection is attempted using Windows
 * Authentication.<br />
 *
 * If values for the UID and PWD keys are not specified in the optional $connectionInfo parameter, the connection will
 * be attempted using Windows Authentication. For more information about connecting to the server,
 * see {@link http://msdn.microsoft.com/en-us/library/cc296205.aspx How to: Connect Using Windows Authentication}
 * and {@link http://msdn.microsoft.com/en-us/library/cc296182.aspx How to: Connect Using SQL Server Authentication.}<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-connect
 * @param string $server_name A string specifying the name of the server to which a connection is being established.
 * An instance name (for example, "myServer\instanceName") or port number (for example, "myServer, 1521") can be
 * included as part of this string. For a complete description of the options available for this parameter, see the
 * Server keyword in the ODBC Driver Connection String Keywords section
 * of {@link http://go.microsoft.com/fwlink/?LinkId=105504 Using Connection String Keywords with SQL Native Client}.<br />
 *
 * Beginning in version 3.0 of the Microsoft Drivers for PHP for SQL Server, you can also specify a LocalDB instance
 * with "(localdb)\instancename". For more information,
 * see {@link http://msdn.microsoft.com/en-us/library/hh487161.aspx PHP Driver for SQL Server Support for LocalDB} .<br />
 *
 * Also beginning in version 3.0 of the Microsoft Drivers for PHP for SQL Server, you can specify a virtual network name,
 * to connect to an AlwaysOn availability group. For more information about Microsoft Drivers for PHP for SQL Server
 * support for AlwaysOn Availability Groups,
 * see {@link http://msdn.microsoft.com/en-us/library/hh487159.aspx PHP Driver for SQL Server Support for High Availability, Disaster Recovery}.
 * @param array $connection_info [optional] An associative array that contains connection attributes (for example, array("Database" => "AdventureWorks")).
 * See {@link http://msdn.microsoft.com/en-us/library/ff628167.aspx Connection Options} for a list of the supported keys for the array.
 * @return resource|false A PHP connection resource. If a connection cannot be successfully created and opened, false is returned.
 */
function sqlsrv_connect($server_name, $connection_info = []) {}

/**
 * Closes a connection. Frees all resources associated with the connection.
 *
 * <br />Null is a valid parameter for this function. This allows the function to be called multiple times in a script. For
 * example, if you close a connection in an error condition and close it again at the end of the script, the second call
 * to sqlsrv_close will return true because the first call to sqlsrv_close (in the error condition) sets the connection
 * resource to null.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-close
 * @param resource|null $conn The connection to be closed.
 * @return bool The Boolean value true unless the function is called with an invalid parameter. If the function is called with an invalid parameter, false is returned.
 */
function sqlsrv_close($conn) {}

/**
 * Commits a transaction that was begun with sqlsrv_begin_transaction.
 *
 * <br />Commits the current transaction on the specified connection and returns the connection to the auto-commit mode.
 * The current transaction includes all statements on the specified connection that were executed after the call to
 * sqlsrv_begin_transaction and before any calls to sqlsrv_rollback or sqlsrv_commit.<br />
 *
 * The Microsoft Drivers for PHP for SQL Server is in auto-commit mode by default. This means that all queries are
 * automatically committed upon success unless they have been designated as part of an explicit transaction by using
 * sqlsrv_begin_transaction.<br />
 *
 * If sqlsrv_commit is called on a connection that is not in an active transaction and that was initiated with
 * sqlsrv_begin_transaction, the call returns false and a Not in Transaction error is added to the error collection.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-commit
 * @param resource $conn The connection on which the transaction is active.
 * @return bool A Boolean value: true if the transaction was successfully committed. Otherwise, false.
 */
function sqlsrv_commit($conn) {}

/**
 * Begins a database transaction.
 *
 * <br />Begins a transaction on a specified connection. The current transaction includes all statements on the specified
 * connection that were executed after the call to sqlsrv_begin_transaction and before any calls to sqlsrv_rollback or
 * sqlsrv_commit.<br />
 *
 * The Microsoft Drivers for PHP for SQL Server is in auto-commit mode by default. This means that all queries are
 * automatically committed upon success unless they have been designated as part of an explicit transaction by using
 * sqlsrv_begin_transaction.<br />
 *
 * If sqlsrv_begin_transaction is called after a transaction has already been initiated on the connection but not
 * completed by calling either sqlsrv_commit or sqlsrv_rollback, the call returns false and an Already in Transaction
 * error is added to the error collection.<br />
 *
 * Do not use embedded Transact-SQL to perform transactions. For example, do not execute a statement with
 * "BEGIN TRANSACTION" as the Transact-SQL query to begin a transaction. The expected transactional behavior cannot be
 * guaranteed when using embedded Transact-SQL to perform transactions.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296206.aspx How to Perform Transactions}
 * and {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-begin-transaction
 * @param resource $conn The connection with which the transaction is associated.
 * @return bool A Boolean value: true if the transaction was successfully begun. Otherwise, false.
 */
function sqlsrv_begin_transaction($conn) {}

/**
 * Rolls back a transaction that was begun with {@see sqlsrv_begin_transaction}.
 *
 * <br />Rolls back the current transaction on the specified connection and returns the connection to the auto-commit mode.
 * The current transaction includes all statements on the specified connection that were executed after the call to
 * sqlsrv_begin_transaction and before any calls to {@link sqlsrv_rollback() sqlsrv_rollback} or
 * {@link sqlsrv_commit() sqlsrv_commit}.<br />
 *
 * The Microsoft Drivers for PHP for SQL Server is in auto-commit mode by default. This means that all queries are
 * automatically committed upon success unless they have been designated as part of an explicit transaction by using
 * {@link sqlsrv_begin_transaction() sqlsrv_begin_transaction}.<br />
 *
 * If sqlsrv_rollback is called on a connection that is not in an active transaction that was initiated with
 * {@link sqlsrv_begin_transaction() sqlsrv_begin_transaction}, the call returns false and a Not in Transaction error
 * is added to the error collection.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296206.aspx How to Perform Transactions}
 * and {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-rollback
 * @param resource $conn The connection on which the transaction is active.
 * @return bool A Boolean value: true if the transaction was successfully rolled back. Otherwise, false.
 */
function sqlsrv_rollback($conn) {}

/**
 * Returns error and/or warning information about the last operation.
 *
 * <br />Returns extended error and/or warning information about the last sqlsrv operation performed. <br />
 *
 * The sqlsrv_errors function can return error and/or warning information by calling it with one of the parameter values
 * specified in the Parameters section below. <br />
 *
 * By default, warnings generated on a call to any sqlsrv function are treated as errors; if a warning occurs on a call
 * to a sqlsrv function, the function returns false. However, warnings that correspond to SQLSTATE values 01000, 01001,
 * 01003, and 01S02 are never treated as errors. <br />
 *
 * The following line of code turns off the behavior mentioned above; a warning generated by a call to a sqlsrv function
 * does not cause the function to return false: <br />
 *
 * <code>{@link sqlsrv_configure() sqlsrv_configure}("WarningsReturnAsErrors", 0);</code>
 *
 * The following line of code reinstates the default behavior; warnings (with exceptions, noted above) are treated as
 * errors: <br />
 *
 * <code>{@link sqlsrv_configure() sqlsrv_configure}("WarningsReturnAsErrors", 1);</code>
 *
 * Regardless of the setting, warnings can only be retrieved by calling sqlsrv_errors with either the SQLSRV_ERR_ALL or
 * SQLSRV_ERR_WARNINGS parameter value (see Parameters section below for details). <br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-errors
 * @param int $errorsAndOrWarnings [optional] A predefined constant. This parameter can take one of the values in the
 * following list: SQLSRV_ERR_ALL, SQLSRV_ERR_ERRORS, SQLSRV_ERR_WARNINGS. If no parameter value is supplied, both
 * errors and warnings generated by the last sqlsrv function call are returned.
 * @return array|null An array of arrays, or null. Each array in the returned array contains three key-value pairs. The
 * following table lists each key and its description: <br />
 * SQLSTATE:
 * <ul>
 * <li> For errors that originate from the ODBC driver, the SQLSTATE returned by ODBC.For information about SQLSTATE
 * values for ODBC, see {@link http://go.microsoft.com/fwlink/?linkid=119618 ODBC Error Codes}.</li>
 * <li> For errors that originate from the Microsoft Drivers for PHP for SQL Server, a SQLSTATE of IMSSP.</li>
 * <li> For warnings that originate from the Microsoft Drivers for PHP for SQL Server, a SQLSTATE of 01SSP.</li>
 * </ul>
 * code:
 * <ul>
 * <li>For errors that originate from SQL Server, the native SQL Server error code.</li>
 * <li>For errors that originate from the ODBC driver, the error code returned by ODBC.</li>
 * <li>For errors that originate from the Microsoft Drivers for PHP for SQL Server, the Microsoft Drivers for PHP for SQL Server error code. For more information, see {@link http://msdn.microsoft.com/en-us/library/cc626302.aspx Handling Errors and Warnings}.</li>
 * </ul>
 * message: A description of the error.<br />
 *
 * The array values can also be accessed with numeric keys 0, 1, and 2.<br /><br />
 *
 * If no errors or warnings occur, null is returned.<br />
 */
function sqlsrv_errors($errorsAndOrWarnings = SQLSRV_ERR_ALL) {}

/**
 * Changes the driver error handling and logging configurations.
 *
 * <br />Changes the settings for error handling and logging options.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-configure
 * @param string $setting The name of the setting to be configured. See table below for list of settings.
 * @param mixed $value The value to be applied to the setting specified in the $setting parameter. The possible values for this parameter depend on which setting is specified. The following table lists the possible combinations.<br />
 * ClientBufferMaxKBSize (Default: 10240)<br />
 * For more information about client-side queries, see {@link http://msdn.microsoft.com/en-us/library/hh487160.aspx Cursor Types (SQLSRV Driver)}.
 * <ul>
 * <li>A non negative number up to the PHP memory limit.</li>
 * <li>Zero (0) means no limit to the buffer size.</li>
 * </ul>
 * LogSeverity (Default: SQLSRV_LOG_SEVERITY_ERROR )<br />
 * For more information about logging activity, see {@link http://msdn.microsoft.com/en-us/library/cc296188.aspx Logging Activity}.
 * <ul><li>SQLSRV_LOG_SEVERITY_ALL (-1)</li>
 * <li>SQLSRV_LOG_SEVERITY_ERROR (1)</li>
 * <li> SQLSRV_LOG_SEVERITY_NOTICE (4)</li>
 * <li>SQLSRV_LOG_SEVERITY_WARNING (2)</li></ul>
 * WarningsReturnAsErrors (Default: true )<br />
 * For more information about configuring error and warning handling, see {@link http://msdn.microsoft.com/en-us/library/cc626306.aspx How to: Configure Error and Warning Handling Using the SQLSRV Driver}.
 * <ul><li>true (1)</li>
 * <li>false (0)</li></ul>
 * @return bool If sqlsrv_configure is called with an unsupported setting or value, the function returns false. Otherwise, the function returns true.
 */
function sqlsrv_configure($setting, $value) {}

/**
 * Returns the current value of the specified configuration setting.
 *
 * <br />If false is returned by sqlsrv_get_config, you must call {@link sqlsrv_errors() sqlsrv_errors} to determine if an error occurred or
 * if false is the value of the setting specified by the $setting parameter.<br />
 *
 * For a list of configurable settings, see {@link sqlsrv_configure() sqlsrv_configure}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-get-config
 * @param string $setting The configuration setting for which the value is returned.
 * @return mixed|false The value of the setting specified by the $setting parameter. If an invalid setting is specified, false is returned and an error is added to the error collection.
 */
function sqlsrv_get_config($setting) {}

/**
 * Prepares a Transact-SQL query without executing it. Implicitly binds parameters.
 *
 * <br />Creates a statement resource associated with the specified connection. This function is useful for execution of
 * multiple queries.<br />
 *
 * Variables passed as query parameters should be passed by reference instead of by value. For example, pass
 * &$myVariable instead of $myVariable. A PHP warning will be raised when a query with by-value parameters is
 * executed.<br />
 *
 * When you prepare a statement that uses variables as parameters, the variables are bound to the statement. That means
 * that if you update the values of the variables, the next time you execute the statement it will run with updated
 * parameter values.<br />
 *
 * The combination of sqlsrv_prepare and {@link sqlsrv_execute() sqlsrv_execute} separates statement preparation and
 * statement execution in to two function calls and can be used to execute parameterized queries. This function is ideal
 * to execute a statement multiple times with different parameter values for each execution.<br />
 *
 * For alternative strategies for writing and reading large amounts of information, see
 * {@link http://go.microsoft.com/fwlink/?LinkId=104225 Batches of SQL Statements} and
 * {@link http://go.microsoft.com/fwlink/?LinkId=104226 BULK INSERT}.<br />
 *
 * For more information, see
 * {@link http://msdn.microsoft.com/en-us/library/cc626303.aspx How to: Retrieve Output Parameters Using the SQLSRV Driver.}<br />
 *
 * For additional Information see:
 * <ul><li>{@link http://msdn.microsoft.com/en-us/library/cc644934.aspx Using Directional Parameters}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296202.aspx Updating Data (Microsoft Drivers for PHP for SQL Server)}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296201.aspx How to: Perform Parameterized Queries}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296191.aspx How to: Send Data as a Stream}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}</li></ul>
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-prepare
 * @param resource $conn The connection resource associated with the created statement.
 * @param string $tsql The Transact-SQL expression that corresponds to the created statement.
 * @param array $params [optional]: An array of values that correspond to parameters in a parameterized query. Each
 * element of the array can be one of the following: a literal value, a reference to a PHP variable, or an array with
 * the following structure:
 * <code>array(&$value [, $direction [, $phpType [, $sqlType]]])</code>
 * The following table describes these array elements:
 * <ul><li>&$value - A literal value or a reference to a PHP variable.</li>
 * <li>$direction[optional] - One of the following SQLSRV_PARAM_* constants used to indicate the parameter direction:
 * SQLSRV_PARAM_IN, SQLSRV_PARAM_OUT, SQLSRV_PARAM_INOUT. The default value is SQLSRV_PARAM_IN. For more information
 * about PHP constants, see
 * {@link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server Constants (Microsoft Drivers for PHP for SQL Server)}.</li>
 * <li>$phpType[optional] - A SQLSRV_PHPTYPE_* constant that specifies PHP data type of the returned value.</li>
 * <li>$sqlType[optional] - A SQLSRV_SQLTYPE_* constant that specifies the SQL Server data type of the input value.</li></ul>
 * @param array $options [optional]: An associative array that sets query properties. The table below lists the
 * supported keys and corresponding values:<br />
 *
 * QueryTimeout (int) - Sets the query timeout in seconds. By default, the driver will wait indefinitely for results.
 * Any positive integer value.<br />
 *
 * SendStreamParamsAtExec (bool) - Configures the driver to send all stream data at execution (true), or to send stream
 * data in chunks (false). By default, the value is set to true. For more information, see
 * {@link sqlsrv_send_stream_data() sqlsrv_send_stream_data}.<br />
 *
 * Scrollable - For more information about these values, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.
 * <ul><li>SQLSRV_CURSOR_FORWARD</li>
 * <li>SQLSRV_CURSOR_STATIC</li>
 * <li>SQLSRV_CURSOR_DYNAMIC</li>
 * <li>SQLSRV_CURSOR_KEYSET</li>
 * <li>SQLSRV_CURSOR_CLIENT_BUFFERED</li></ul>
 * @return resource|false A statement resource. If the statement resource cannot be created, false is returned.
 */
function sqlsrv_prepare($conn, $tsql, $params = [], $options = []) {}

/**
 * Executes a statement prepared with {@see sqlsrv_prepare}
 *
 * <br />Executes a previously prepared statement. See {@link sqlsrv_prepare() sqlsrv_prepare} for information on preparing a statement
 * for execution.<br />
 *
 * This function is ideal for executing a prepared statement multiple times with different parameter values.<br />
 *
 * For additional Information see:
 * <ul><li>{@link sqlsrv_query() sqlsrv_query}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296201.aspx How to: Perform Parameterized Queries}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}</li></ul>
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-execute
 * @param resource $stmt A resource specifying the statement to be executed. For more information about how to create a
 * statement resource, see {@link sqlsrv_prepare() sqlsrv_prepare}.
 * @return bool A Boolean value: true if the statement was successfully executed. Otherwise, false.
 */
function sqlsrv_execute($stmt) {}

/**
 * Prepares and executes a Transact-SQL query.
 *
 * <br />Prepares and executes a statement.<br />
 *
 * The sqlsrv_query function is well-suited for one-time queries and should be the default choice to execute queries
 * unless special circumstances apply. This function provides a streamlined method to execute a query with a minimum
 * amount of code. The sqlsrv_query function does both statement preparation and statement execution, and can be used to
 * execute parameterized queries.<br />
 *
 * For more information, see
 * {@link http://msdn.microsoft.com/en-us/library/cc626303.aspx How to: Retrieve Output Parameters Using the SQLSRV Driver.}<br />
 *
 * For additional Information see:
 * <ul><li>{@link http://msdn.microsoft.com/en-us/library/cc644934.aspx Using Directional Parameters}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296202.aspx Updating Data (Microsoft Drivers for PHP for SQL Server)}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296201.aspx How to: Perform Parameterized Queries}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296191.aspx How to: Send Data as a Stream}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}</li></ul>
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-query
 * @param resource $conn The connection resource associated with the prepared statement.
 * @param string $tsql The Transact-SQL expression that corresponds to the prepared statement.
 * @param array $params [optional]: An array of values that correspond to parameters in a parameterized query. Each
 * element of the array can be one of the following: a literal value, a reference to a PHP variable, or an array with
 * the following structure:
 * <code>array($value [, $direction [, $phpType [, $sqlType]]])</code>
 * The following table describes these array elements:
 * <ul><li>&$value - A literal value, a PHP variable, or a PHP by-reference variable.</li>
 * <li>$direction[optional] - One of the following SQLSRV_PARAM_* constants used to indicate the parameter direction:
 * SQLSRV_PARAM_IN, SQLSRV_PARAM_OUT, SQLSRV_PARAM_INOUT. The default value is SQLSRV_PARAM_IN. For more information
 * about PHP constants, see
 * {@link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server Constants (Microsoft Drivers for PHP for SQL Server)}.</li>
 * <li>$phpType[optional] - A SQLSRV_PHPTYPE_* constant that specifies PHP data type of the returned value.</li>
 * <li>$sqlType[optional] - A SQLSRV_SQLTYPE_* constant that specifies the SQL Server data type of the input value.</li></ul>
 * @param array $options [optional]: An associative array that sets query properties. The table below lists the
 * supported keys and corresponding values:<br />
 *
 * QueryTimeout (int) - Sets the query timeout in seconds. By default, the driver will wait indefinitely for results.
 * Any positive integer value.<br />
 *
 * SendStreamParamsAtExec (bool) - Configures the driver to send all stream data at execution (true), or to send stream
 * data in chunks (false). By default, the value is set to true. For more information, see
 * {@link sqlsrv_send_stream_data() sqlsrv_send_stream_data}.<br />
 *
 * Scrollable - For more information about these values, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.
 * <ul><li>SQLSRV_CURSOR_FORWARD</li>
 * <li>SQLSRV_CURSOR_STATIC</li>
 * <li>SQLSRV_CURSOR_DYNAMIC</li>
 * <li>SQLSRV_CURSOR_KEYSET</li>
 * <li>SQLSRV_CURSOR_CLIENT_BUFFERED</li></ul>
 * @return resource|false A statement resource. If the statement cannot be created and/or executed, false is returned.
 */
function sqlsrv_query($conn, $tsql, $params = [], $options = []) {}

/**
 * Makes the next row in a result set available for reading.
 *
 * <br />Makes the next row of a result set available for reading. Use {@link sqlsrv_get_field() sqlsrv_get_field} to read fields of
 * the row.<br />
 *
 * A statement must be executed before results can be retrieved. For information on executing a statement, see {@link sqlsrv_query() sqlsrv_query}
 * and {@link sqlsrv_execute() sqlsrv_execute}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-fetch
 * @param resource|null $stmt A statement resource corresponding to an executed statement.
 * @param int|null $row [optional]: One of the following values, specifying the row to access in a result set that uses a
 * scrollable cursor: SQLSRV_SCROLL_NEXT, SQLSRV_SCROLL_PRIOR, SQLSRV_SCROLL_FIRST, SQLSRV_SCROLL_LAST,
 * SQLSRV_SCROLL_ABSOLUTE, SQLSRV_SCROLL_RELATIVE. <br />
 *
 * For more information on these values, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.
 * @param int|null $offset [optional] Used with SQLSRV_SCROLL_ABSOLUTE and SQLSRV_SCROLL_RELATIVE to specify the row to
 * retrieve. The first record in the result set is 0.
 * @return bool|null If the next row of the result set was successfully retrieved, true is returned. If there are
 * no more results in the result set, null is returned. If an error occurred, false is returned.
 */
function sqlsrv_fetch($stmt, $row = null, $offset = null) {}

/**
 * Retrieves a field in the current row by index. The PHP return type can be specified.
 *
 * <br />Retrieves data from the specified field of the current row. Field data must be accessed in order. For example,
 * data from the first field cannot be accessed after data from the second field has been accessed.<br />
 *
 * The combination of {@link sqlsrv_fetch() sqlsrv_fetch} and
 * {@link sqlsrv_get_field() sqlsrv_get_field} provides forward-only access to data.<br />
 *
 * The combination of {@link sqlsrv_fetch() sqlsrv_fetch} and
 * {@link sqlsrv_get_field() sqlsrv_get_field} loads only one
 * field of a result set row into script memory and allows PHP return type specification. (For information about how to
 * specify the PHP return type, see {@link http://msdn.microsoft.com/en-us/library/cc296208.aspx How to: Specify PHP Data Types}.)
 * This combination of functions also allows data to be retrieved as a stream. (For information about retrieving data
 * as a stream, see {@link http://msdn.microsoft.com/en-us/library/cc296155.aspx Retrieving Data as a Stream Using the SQLSRV Driver}.)<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-get-field
 * @param resource $stmt A statement resource corresponding to an executed statement.
 * @param int $field_index The index of the field to be retrieved. Indexes begin at zero.
 * @param int $get_as_type [optional] A SQLSRV constant (SQLSRV_PHPTYPE_*) that determines the PHP data type for the returned
 * data. For information about supported data types, see
 * {@link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server Constants (Microsoft Drivers for PHP for SQL Server)}.
 * If no return type is specified, a default PHP type will be returned. For information about default PHP types, see
 * {@link http://msdn.microsoft.com/en-us/library/cc296193.aspx Default PHP Data Types}. For information about
 * specifying PHP data types, see {@link http://msdn.microsoft.com/en-us/library/cc296208.aspx How to: Specify PHP Data Types}.
 * @return mixed The field data. You can specify the PHP data type of the returned data by using the $getAsType
 * parameter. If no return data type is specified, the default PHP data type will be returned. For information about
 * default PHP types, see {@link http://msdn.microsoft.com/en-us/library/cc296193.aspx Default PHP Data Types}. For
 * information about specifying PHP data types,
 * see {@link http://msdn.microsoft.com/en-us/library/cc296208.aspx How to: Specify PHP Data Types}.
 */
function sqlsrv_get_field($stmt, $field_index, $get_as_type = null) {}

/**
 * Retrieves the next row of data as a numerically indexed array, an associative array, or both.
 *
 * <br />If a column with no name is returned, the associative key for the array element will be an empty string (""). For
 * example, consider this Transact-SQL statement that inserts a value into a database table and retrieves the
 * server-generated primary key:
 * <code>  INSERT INTO Production.ProductPhoto (LargePhoto) VALUES (?);
 * SELECT SCOPE_IDENTITY()</code>
 * If the result set returned by the SELECT SCOPE_IDENTITY() portion of this statement is retrieved as an associative
 * array, the key for the returned value will be an empty string ("") because the returned column has no name. To avoid
 * this, you can retrieve the result as a numeric array, or you can specify a name for the returned column in the
 * Transact-SQL statement. The following is one way to specify a column name in Transact-SQL:
 * <code>SELECT SCOPE_IDENTITY() AS PictureID</code>
 * If a result set contains multiple columns without names, the value of the last unnamed column will be assigned to the
 * empty string ("") key.<br />
 *
 * The sqlsrv_fetch_array function always returns data according to the
 * {@link http://msdn.microsoft.com/en-us/library/cc296193.aspx Default PHP Data Types}. For information about
 * how to specify the PHP data type,
 * see {@link http://msdn.microsoft.com/en-us/library/cc296208.aspx How to: Specify PHP Data Types}.<br />
 *
 * If a field with no name is retrieved, the associative key for the array element will be an empty string (""). For
 * more information, see {@link sqlsrv_fetch_array() sqlsrv_fetch_array}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296160.aspx Retrieving Data} and
 * {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-fetch-array
 * @param resource|null $stmt A statement resource corresponding to an executed statement.
 * @param int $fetch_type [optional] A predefined constant. This parameter can take on one of the values listed in the
 * following table:
 * <ul><li>SQLSRV_FETCH_NUMERIC - The next row of data is returned as a numeric array.</li>
 * <li>SQLSRV_FETCH_ASSOC - The next row of data is returned as an associative array. The array keys are the column
 * names in the result set.</li>
 * <li>SQLSRV_FETCH_BOTH - The next row of data is returned as both a numeric array and an associative array. This is
 * the default value. </li></ul>
 * @param int|null $row [optional]: One of the following values, specifying the row to access in a result set that uses a
 * scrollable cursor: SQLSRV_SCROLL_NEXT, SQLSRV_SCROLL_PRIOR, SQLSRV_SCROLL_FIRST, SQLSRV_SCROLL_LAST,
 * SQLSRV_SCROLL_ABSOLUTE, SQLSRV_SCROLL_RELATIVE. <br />
 *
 * For more information on these values, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.
 * @param int|null $offset [optional] Used with SQLSRV_SCROLL_ABSOLUTE and SQLSRV_SCROLL_RELATIVE to specify the row to
 * retrieve. The first record in the result set is 0.
 * @return array|null|false If a row of data is retrieved, an array is returned. If there are no more rows to retrieve, null is returned. If an error occurs, false is returned.
 */
function sqlsrv_fetch_array($stmt, $fetch_type = null, $row = null, $offset = null) {}

/**
 * Retrieves the next row of data as an object.
 *
 * <br />Retrieves the next row of data as a PHP object.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296160.aspx Retrieving Data} and
 * {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-fetch-object
 * @param resource $stmt A statement resource corresponding to an executed statement.
 * @param string|null $class_name [optional] A string specifying the name of the class to instantiate. If a value for the
 * $className parameter is not specified, an instance of the PHP {@link stdClass} is instantiated.
 * @param array|null $ctor_params [optional]  An array that contains values passed to the constructor of the class
 * specified with the $className parameter. If the constructor of the specified class accepts parameter values, the
 * $ctorParams parameter must be used when calling sqlsrv_fetch_object.
 * @param int|null $row [optional] One of the following values, specifying the row to access in a result set that uses a
 * scrollable cursor: SQLSRV_SCROLL_NEXT, SQLSRV_SCROLL_PRIOR, SQLSRV_SCROLL_FIRST, SQLSRV_SCROLL_LAST,
 * SQLSRV_SCROLL_ABSOLUTE, SQLSRV_SCROLL_RELATIVE. <br />
 *
 * For more information on these values, see
 * {@link http://msdn.microsoft.com/en-us/library/ee376927.aspx Specifying a Cursor Type and Selecting Rows}.
 * @param int|null $offset [optional] Used with SQLSRV_SCROLL_ABSOLUTE and SQLSRV_SCROLL_RELATIVE to specify the row to
 * retrieve. The first record in the result set is 0.
 * @return object|false|null A PHP object with properties that correspond to result set field names. Property values are
 * populated with the corresponding result set field values. If the class specified with the optional $className
 * parameter does not exist or if there is no active result set associated with the specified statement, false is
 * returned. If there are no more rows to retrieve, null is returned.<br /><br />
 *
 * The data type of a value in the returned object will be the default PHP data type. For information on default PHP data
 * types, see {@link http://msdn.microsoft.com/en-us/library/cc296193.aspx Default PHP Data Types}.<br />
 */
function sqlsrv_fetch_object($stmt, $class_name = null, $ctor_params = null, $row = null, $offset = null) {}

/**
 * Detects if a result set has one or more rows.
 *
 * <br />Indicates if the result set has one or more rows.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-has-rows
 * @param resource $stmt The executed statement.
 * @return bool If there are rows in the result set, the return value will be true. If there are no rows, or if the
 * function call fails, the return value will be false.
 */
function sqlsrv_has_rows($stmt) {}

/**
 * Retrieves the number of fields (columns) on a statemen.
 *
 * <br />Retrieves the number of fields in an active result set. Note that sqlsrv_num_fields can be called on any
 * prepared statement, before or after execution.<br />
 *
 * Additional Information at {@link sqlsrv_field_metadata() sqlsrv_field_metadata} and
 * {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-num-fields
 * @param resource $stmt The statement on which the targeted result set is active.
 * @return int|false An integer value that represents the number of fields in the active result set. If an error occurs,
 * the Boolean value false is returned.
 */
function sqlsrv_num_fields($stmt) {}

/**
 * Makes the next result of the specified statement active.
 *
 * <br />Makes the next result (result set, row count, or output parameter) of the specified statement active.<br />
 *
 * The first (or only) result returned by a batch query or stored procedure is active without a call to sqlsrv_next_result.<br />
 *
 * Additional Information at
 * {@link http://msdn.microsoft.com/en-us/library/cc296202.aspx Updating Data (Microsoft Drivers for PHP for SQL Server)} and
 * {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-next-result
 * @param resource $stmt The executed statement on which the next result is made active.
 * @return bool|null If the next result was successfully made active, the Boolean value true is returned. If an error occurred in
 * making the next result active, false is returned. If no more results are available, null is returned.
 */
function sqlsrv_next_result($stmt) {}

/**
 * Retrieves the number of rows in a result set.
 *
 * <br />sqlsrv_num_rows requires a client-side, static, or keyset cursor, and will return false if you use a forward cursor
 * or a dynamic cursor. (A forward cursor is the default.) For more information about cursors, see
 * {@link sqlsrv_prepare() sqlsrv_prepare},
 * {@link sqlsrv_query() sqlsrv_query} and
 * {@link http://msdn.microsoft.com/en-us/library/hh487160.aspx Cursor Types (SQLSRV Driver)}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-num-rows
 * @param resource $stmt The result set for which to count the rows.
 * @return int|false False if there was an error calculating the number of rows. Otherwise, returns the number of rows in the result set.
 */
function sqlsrv_num_rows($stmt) {}

/**
 * Returns the number of modified rows.
 *
 * <br />Returns the number of rows modified by the last statement executed. This function does not return the number of
 * rows returned by a SELECT statement.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-rows-affected
 * @param resource $stmt A statement resource corresponding to an executed statement.
 * @return int|false An integer indicating the number of rows modified by the last executed statement. If no rows were
 * modified, zero (0) is returned. If no information about the number of modified rows is available, negative one (-1)
 * is returned. If an error occurred in retrieving the number of modified rows, false is returned.
 */
function sqlsrv_rows_affected($stmt) {}

/**
 * Provides information about the client.
 *
 * <br />Returns information about the connection and client stack.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-client-info
 * @param resource $conn The connection resource by which the client is connected.
 * @return array|false An associative array with keys described in the table below, or false if the connection resource
 * is null.<br />
 * <ul><li>DriverDllName - SQLNCLI10.DLL (Microsoft Drivers for PHP for SQL Server version 2.0)</li>
 * <li>DriverODBCVer - ODBC version (xx.yy)</li>
 * <li>DriverVer - SQL Server Native Client DLL version: 10.50.xxx (Microsoft Drivers for PHP for SQL Server version 2.0)</li>
 * <li>ExtensionVer - php_sqlsrv.dll version: 2.0.xxxx.x(Microsoft Drivers for PHP for SQL Server version 2.0)</li></ul>
 */
function sqlsrv_client_info($conn) {}

/**
 * Returns information about the server.
 *
 * <br />Returns information about the server. A connection must be established before calling this function.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-server-info
 * @param resource $conn The connection resource by which the client and server are connected.
 * @return array An associative array with the following keys:
 * <ul><li>CurrentDatabase -  The database currently being targeted.</li>
 * <li>SQLServerVersion - The version of SQL Server.</li>
 * <li>SQLServerName - The name of the server.</li></ul>
 */
function sqlsrv_server_info($conn) {}

/**
 * Cancels a statement; discards any pending results for the statement.
 *
 * <br />Cancels a statement. This means that any pending results for the statement are discarded. After this function
 * is called, the statement can be re-executed if it was prepared with {@link sqlsrv_prepare() sqlsrv_prepare}. Calling
 * this function is not necessary if all the results associated with the statement have been consumed.<br />
 *
 * A statement that is prepared and executed using the combination of {@link sqlsrv_prepare() sqlsrv_prepare} and
 * {@link sqlsrv_execute() sqlsrv_execute} can be re-executed
 * with {@link sqlsrv_execute() sqlsrv_execute} after calling sqlsrv_cancel. A statement that is executed with
 * {@link sqlsrv_query() sqlsrv_query} cannot be re-executed after calling sqlsrv_cancel.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-cancel
 * @param resource $stmt The statement to be canceled.
 * @return bool A Boolean value: true if the operation was successful. Otherwise, false.
 */
function sqlsrv_cancel($stmt) {}

/**
 * Closes a statement. Frees all resources associated with the statement.
 *
 * <br />Frees all resources associated with the specified statement. The statement cannot be used again after this function
 * has been called.<br />
 *
 * Null is a valid parameter for this function. This allows the function to be called multiple times in a script. For
 * example, if you free a statement in an error condition and free it again at the end of the script, the second call to
 * sqlsrv_free_stmt will return true because the first call to sqlsrv_free_stmt (in the error condition) sets the
 * statement resource to null.<br />
 *
 * Additional Information at {@link sqlsrv_cancel() sqlsrv_cancel} and
 * {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-free-stmt
 * @param resource|null $stmt The statement to be closed.
 * @return bool The Boolean value true unless the function is called with an invalid parameter. If the function is
 * called with an invalid parameter, false is returned.
 */
function sqlsrv_free_stmt($stmt) {}

/**
 * Returns field metadata.
 *
 * <br />Retrieves metadata for the fields of a prepared statement. For information about preparing a statement,
 * see {@link sqlsrv_query() sqlsrv_query}
 * or {@link sqlsrv_prepare() sqlsrv_prepare}. Note that sqlsrv_field_metadata can be called on any prepared statement,
 * pre- or post-execution.<br />
 *
 * Additional Information at {@link sqlsrv_cancel() sqlsrv_cancel} and
 * {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-field-metadata
 * @param resource $stmt A statement resource for which field metadata is sought.
 * @return array|false An array of arrays or false. The array consists of one array for each field in the result set.
 * Each sub-array has keys as described in the table below. If there is an error in retrieving field metadata, false is
 * returned.
 * <ul><li>Name - Name of the column to which the field corresponds.</li>
 * <li>Type - Numeric value that corresponds to a SQL type.</li>
 * <li>Size - Number of characters for fields of character type (char(n), varchar(n), nchar(n), nvarchar(n), XML).
 * Number of bytes for fields of binary type (binary(n), varbinary(n), UDT). NULL for other SQL Server data types.</li>
 * <li>Precision - The precision for types of variable precision (real, numeric, decimal, datetime2, datetimeoffset, and
 * time). NULL for other SQL Server data types.</li>
 * <li>Scale - The scale for types of variable scale (numeric, decimal, datetime2, datetimeoffset, and time). NULL for other
 * SQL Server data types.</li>
 * <li>Nullable - An enumerated value indicating whether the column is nullable (SQLSRV_NULLABLE_YES), the column is not
 * nullable (SQLSRV_NULLABLE_NO), or it is not known if the column is nullable (SQLSRV_NULLABLE_UNKNOWN).</li></ul>
 * See the {@link http://msdn.microsoft.com/en-us/library/cc296197.aspx function documentation} for more information on
 * the keys for each sub-array.
 */
function sqlsrv_field_metadata($stmt) {}

/**
 * Sends up to eight kilobytes (8 KB) of data to the server with each call to the function.
 *
 * <br />Sends data from parameter streams to the server. Up to eight kilobytes (8K) of data is sent with each call to
 * sqlsrv_send_stream_data.<br />
 *
 * By default, all stream data is sent to the server when a query is executed. If this default behavior is not changed,
 * you do not have to use sqlsrv_send_stream_data to send stream data to the server. For information about changing the
 * default behavior, see the Parameters section of {@link sqlsrv_query() sqlsrv_query}
 * or {@link sqlsrv_prepare() sqlsrv_prepare}.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-send-stream-data
 * @param resource $stmt A statement resource corresponding to an executed statement.
 * @return bool Boolean : true if there is more data to be sent. Otherwise, false.
 */
function sqlsrv_send_stream_data($stmt) {}

/**
 * Specifies the encoding of a stream of data from the server.
 *
 * <br />When specifying the PHP data type of a value being returned from the server, this allows you to specify the encoding
 * used to process the value if the value is a stream.<br />
 *
 * In the documentation this is presented as a constant that accepts an arguement.<br />
 *
 * When you use SQLSRV_PHPTYPE_STREAM, the encoding must be specified. If no parameter is supplied, an error will be
 * returned.<br />
 *
 * Additional Information at:
 * <ul>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296208.aspx How to: Specify PHP Data Types}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296163.aspx How to: Retrieve Character Data as a Stream Using the SQLSRV Driver.}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc626307.aspx How to: Send and Retrieve UTF-8 Data Using Built-In UTF-8 Support.}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}</li>
 * </ul>
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 *
 * @param string $encoding The encoding to use for the stream. The valid options are SQLSRV_ENC_BINARY, SQLSRV_ENC_CHAR
 * or "UTF-8".
 *
 * @return int Value to use in any place that accepts a SQLSRV_PHPTYPE_* constant to represent a PHP stream with the
 * given encoding.
 */
function SQLSRV_PHPTYPE_STREAM($encoding) {}

/**
 * Specifies the encoding of a string being received form the server.
 *
 * <br />When specifying the PHP data type of a value being returned from the server, this allows you to specify the
 * encoding used to process the value if the value is a string.<br />
 *
 * In the documentation this is presented as a constant that accepts an arguement.<br />
 *
 * When you use SQLSRV_PHPTYPE_STRING, the encoding must be specified. If no parameter is supplied, an error will be
 * returned.<br />
 *
 * Additional Information at:
 * <ul>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296208.aspx How to: Specify PHP Data Types}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296163.aspx How to: Retrieve Character Data as a Stream Using the SQLSRV Driver.}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc626307.aspx How to: Send and Retrieve UTF-8 Data Using Built-In UTF-8 Support.}</li>
 * <li>{@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}</li>
 * </ul>
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 *
 * @param string $encoding The encoding to use for the stream. The valid options are SQLSRV_ENC_BINARY, SQLSRV_ENC_CHAR
 * or "UTF-8".
 *
 * @return int Value to use in any place that accepts a SQLSRV_PHPTYPE_* constant to represent a PHP string with the
 * given encoding.
 */
function SQLSRV_PHPTYPE_STRING($encoding) {}

/**
 * Specifies a SQL Server binary field.
 *
 * <br />In the documentation this is presented as a constant that accepts an arguement.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 * @param int $byteCount Must be between 1 and 8000.
 * @return int Value to use in any place that accepts a SQLSRV_SQLTYPE_* constant to represent the 'binary' data type.
 */
function SQLSRV_SQLTYPE_BINARY($byteCount) {}

/**
 * Specifies a SQL Server varbinary field.
 *
 * <br />In the documentation this is presented as a constant that accepts an arguement.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 * @param int|string $byteCount Must be between 1 and 8000 or 'max'.
 * @return int Value to use in any place that accepts a SQLSRV_SQLTYPE_* constant to represent the varbinary data type.
 */
function SQLSRV_SQLTYPE_VARBINARY($byteCount) {}

/**
 * Specifies a SQL Server varchar filed.
 *
 * <br />In the documentation this is presented as a constant that accepts an arguement.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 *
 * @param int|string $charCount Must be between 1 and 8000 or 'max'.
 *
 * @return int Value to use in any place that accepts a SQLSRV_SQLTYPE_* constant to represent the varchar data type.
 */
function SQLSRV_SQLTYPE_VARCHAR($charCount) {}

/**
 * Specifies a SQL Server char field.
 *
 * <br />In the documentation this is presented as a constant that accepts an arguement.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 * @param int $charCount Must be between 1 and 8000.
 * @return int Value to use in any place that accepts a SQLSRV_SQLTYPE_* constant to represent the char data type.
 */
function SQLSRV_SQLTYPE_CHAR($charCount) {}

/**
 * Specifies a SQL Server nchar field.
 *
 * <br />In the documentation this is presented as a constant that accepts an arguement.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 * @param int $charCount Must be between 1 and 4000.
 * @return int Value to use in any place that accepts a SQLSRV_SQLTYPE_* constant to represent the nchar data type.
 */
function SQLSRV_SQLTYPE_NCHAR($charCount) {}

/**
 * Specifies a SQL Server nvarchar field.
 *
 * <br />In the documentation this is presented as a constant that accepts an arguement.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 * @param int|string $charCount Must be between 1 and 4000 or 'max'.
 * @return int Value to use in any place that accepts a SQLSRV_SQLTYPE_* constant to represent the nvarchar data type.
 */
function SQLSRV_SQLTYPE_NVARCHAR($charCount) {}

/**
 * Specifies a SQL Server decimal field.
 *
 * <br />In the documentation this is presented as a constant that accepts an arguement.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 * @param int $precision Must be between 1 and 38.
 * @param int $scale Must be between 1 and $precision.
 * @return int Value to use in any place that accepts a SQLSRV_SQLTYPE_* constant to represent the decimal data type.
 */
function SQLSRV_SQLTYPE_DECIMAL($precision, $scale) {}

/**
 * Specifies a SQL Server numeric field.
 *
 * <br />In the documentation this is presented as a constant that accepts an arguement.<br />
 *
 * Additional Information at {@link http://msdn.microsoft.com/en-us/library/cc296152.aspx SQLSRV Driver API Reference}<br />
 *
 * @link https://docs.microsoft.com/en-us/sql/connect/php/constants-microsoft-drivers-for-php-for-sql-server
 * @param int $precision Must be between 1 and 38.
 * @param int $scale Must be between 1 and $precision.
 * @return int Value to use in any place that accepts a SQLSRV_SQLTYPE_* constant to represent the numeric data type.
 */
function SQLSRV_SQLTYPE_NUMERIC($precision, $scale) {}
