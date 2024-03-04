<?php

// Start of oci8 v.2.0.7
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

/**
 * OCI8 LOB functionality for large binary (BLOB) and character (CLOB) objects.
 * @link https://php.net/manual/en/class.OCI-Lob.php
 * @removed 8.0
 */
class OCI_Lob
{
    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Returns large object's contents
     * @link https://php.net/manual/en/oci-lob.load.php
     * @return string|false The contents of the object, or <b>FALSE</b> on errors.
     */
    public function load() {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Returns the current position of internal pointer of large object
     * @link https://php.net/manual/en/oci-lob.tell.php
     * @return int|false Current position of a LOB's internal pointer or <b>FALSE</b> if an
     * error occurred.
     */
    public function tell() {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Truncates large object
     * @link https://php.net/manual/en/oci-lob.truncate.php
     * @param int $length [optional] <p>
     * If provided, this method will truncate the LOB to
     * <i>length</i> bytes. Otherwise, it will completely
     * purge the LOB.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function truncate($length = 0) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Erases a specified portion of the internal LOB data
     * @link https://php.net/manual/en/oci-lob.erase.php
     * @param int $offset [optional]
     * @param int $length [optional]
     * @return int|false The actual number of characters/bytes erased or <b>FALSE</b> on failure.
     */
    public function erase($offset = null, $length = null) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Flushes/writes buffer of the LOB to the server
     * @link https://php.net/manual/en/oci-lob.flush.php
     * @param int $flag [optional] <p>
     * By default, resources are not freed, but using flag
     * <b>OCI_LOB_BUFFER_FREE</b> you can do it explicitly.
     * Be sure you know what you're doing - next read/write operation to the
     * same part of LOB will involve a round-trip to the server and initialize
     * new buffer resources. It is recommended to use
     * <b>OCI_LOB_BUFFER_FREE</b> flag only when you are not
     * going to work with the LOB anymore.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     * <p>
     * Returns <b>FALSE</b> if buffering was not enabled or an error occurred.
     */
    public function flush($flag = null) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Changes current state of buffering for the large object
     * @link https://php.net/manual/en/oci-lob.setbuffering.php
     * @param bool $on_off <p>
     * <b>TRUE</b> for on and <b>FALSE</b> for off.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure. Repeated calls to this method with the same flag will
     * return <b>TRUE</b>.
     */
    public function setbuffering($on_off) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Returns current state of buffering for the large object
     * @link https://php.net/manual/en/oci-lob.getbuffering.php
     * @return bool <b>FALSE</b> if buffering for the large object is off and <b>TRUE</b> if
     * buffering is used.
     */
    public function getbuffering() {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Moves the internal pointer to the beginning of the large object
     * @link https://php.net/manual/en/oci-lob.rewind.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function rewind() {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Reads part of the large object
     * @link https://php.net/manual/en/oci-lob.read.php
     * @param int $length <p>
     * The length of data to read, in bytes. Large values will be rounded down to 1 MB.
     * </p>
     * @return string|false The contents as a string, or <b>FALSE</b> on failure.
     */
    public function read($length) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Tests for end-of-file on a large object's descriptor
     * @link https://php.net/manual/en/oci-lob.eof.php
     * @return bool <b>TRUE</b> if internal pointer of large object is at the end of LOB.
     * Otherwise returns <b>FALSE</b>.
     */
    public function eof() {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Sets the internal pointer of the large object
     * @link https://php.net/manual/en/oci-lob.seek.php
     * @param int $offset <p>
     * Indicates the amount of bytes, on which internal pointer should be
     * moved from the position, pointed by <i>whence</i>.
     * </p>
     * @param int $whence [optional] <p>
     * May be one of:
     * <b>OCI_SEEK_SET</b> - sets the position equal to
     * <i>offset</i>
     * <b>OCI_SEEK_CUR</b> - adds <i>offset</i>
     * bytes to the current position
     * <b>OCI_SEEK_END</b> - adds <i>offset</i>
     * bytes to the end of large object (use negative value to move to a position
     * before the end of large object)
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function seek($offset, $whence = OCI_SEEK_SET) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Writes data to the large object
     * @link https://php.net/manual/en/oci-lob.write.php
     * @param string $data <p>
     * The data to write in the LOB.
     * </p>
     * @param int $length [optional] <p>
     * If this parameter is given, writing will stop after
     * <i>length</i> bytes have been written or the end of
     * <i>data</i> is reached, whichever comes first.
     * </p>
     * @return int|false The number of bytes written or <b>FALSE</b> on failure.
     */
    public function write($data, $length = null) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Appends data from the large object to another large object
     * @link https://php.net/manual/en/oci-lob.append.php
     * @param OCI_Lob $lob_from <p>
     * The copied LOB.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function append(#[LanguageLevelTypeAware(['8.0' => 'OCILob'], default: 'OCI_Lob')] $lob_from) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Returns size of large object
     * @link https://php.net/manual/en/oci-lob.size.php
     * @return int|false Length of large object value or <b>FALSE</b> on failure.
     * Empty objects have zero length.
     */
    public function size() {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Alias of {@see OCI_Lob::export}
     * @link https://php.net/manual/en/oci-lob.writetofile.php
     * @param $filename
     * @param $start [optional]
     * @param $length [optional]
     * @return bool TRUE on success or FALSE on failure.
     */
    public function writetofile($filename, $start, $length) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Exports LOB's contents to a file
     * @link https://php.net/manual/en/oci-lob.export.php
     * @param string $filename <p>
     * Path to the file.
     * </p>
     * @param int $start [optional] <p>
     * Indicates from where to start exporting.
     * </p>
     * @param int $length [optional] <p>
     * Indicates the length of data to be exported.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function export($filename, $start = null, $length = null) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Imports file data to the LOB
     * @link https://php.net/manual/en/oci-lob.import.php
     * @param string $filename <p>
     * Path to the file.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function import($filename) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Writes a temporary large object
     * @link https://php.net/manual/en/oci-lob.writetemporary.php
     * @param string $data <p>
     * The data to write.
     * </p>
     * @param int $lob_type [optional] <p>
     * Can be one of the following:
     * <b>OCI_TEMP_BLOB</b> is used to create temporary BLOBs
     * <b>OCI_TEMP_CLOB</b> is used to create
     * temporary CLOBs
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function writeTemporary($data, $lob_type = OCI_TEMP_CLOB) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Closes LOB descriptor
     * @link https://php.net/manual/en/oci-lob.close.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function close() {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Saves data to the large object
     * @link https://php.net/manual/en/oci-lob.save.php
     * @param string $data <p>
     * The data to be saved.
     * </p>
     * @param int $offset [optional] <p>
     * Can be used to indicate offset from the beginning of the large object.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function save($data, $offset = null) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Alias of {@see OCI_Lob::import}
     * @link https://php.net/manual/en/oci-lob.savefile.php
     * @param $filename
     * @return bool Return true on success and false on failure
     */
    public function savefile($filename) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Frees resources associated with the LOB descriptor
     * @link https://php.net/manual/en/oci-lob.free.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function free() {}
}

/**
 * OCI8 Collection functionality.
 * @link https://php.net/manual/en/class.OCI-Collection.php
 * @removed 8.0
 */
class OCI_Collection
{
    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Appends element to the collection
     * @link https://php.net/manual/en/oci-collection.append.php
     * @param mixed $value <p>
     * The value to be added to the collection. Can be a string or a number.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function append($value) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Returns value of the element
     * @link https://php.net/manual/en/oci-collection.getelem.php
     * @param int $index <p>
     * The element index. First index is 0.
     * </p>
     * @return mixed <b>FALSE</b> if such element doesn't exist; <b>NULL</b> if element is <b>NULL</b>;
     * string if element is column of a string datatype or number if element is
     * numeric field.
     */
    public function getelem($index) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Assigns a value to the element of the collection
     * @link https://php.net/manual/en/oci-collection.assignelem.php
     * @param int $index <p>
     * The element index. First index is 0.
     * </p>
     * @param mixed $value <p>
     * Can be a string or a number.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function assignelem($index, $value) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Assigns a value to the collection from another existing collection
     * @link https://php.net/manual/en/oci-collection.assign.php
     * @param OCI_Collection $from <p>
     * An instance of OCI-Collection.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function assign(#[LanguageLevelTypeAware(['8.0' => 'OCICollection'], default: 'OCI_Collection')] $from) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Returns size of the collection
     * @link https://php.net/manual/en/oci-collection.size.php
     * @return int|false The number of elements in the collection or <b>FALSE</b> on error.
     */
    public function size() {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Returns the maximum number of elements in the collection
     * @link https://php.net/manual/en/oci-collection.max.php
     * @return int|false The maximum number as an integer, or <b>FALSE</b> on errors.
     * </p>
     * <p>
     * If the returned value is 0, then the number of elements is not limited.
     */
    public function max() {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Trims elements from the end of the collection
     * @link https://php.net/manual/en/oci-collection.trim.php
     * @param int $num <p>
     * The number of elements to be trimmed.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function trim($num) {}

    /**
     * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
     * Frees the resources associated with the collection object
     * @link https://php.net/manual/en/oci-collection.free.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function free() {}
}

/**
 * Register a user-defined callback function for Oracle Database TAF.
 * @link https://www.php.net/manual/en/function.oci-register-taf-callback.php
 * @param resource $connection <p>
 * An Oracle connection identifier.
 * </p>
 * @param mixed $callbackFn [optional] <p>
 * A user-defined callback to register for Oracle TAF. It can be a string of the function name or a Closure (anonymous function).<br/>
 * The interface of a TAF user-defined callback function is as follows:<br/>
 * <i>userCallbackFn ( resource $connection , int $event , int $type ) : int</i><br/>
 * See the parameter description and an example on <a href="https://www.php.net/manual/en/oci8.taf.php">OCI8 Transparent Application Failover (TAF) Support</a> page.
 * </p>
 * @return bool TRUE on success or FALSE on failure.
 * @since 7.2
 */
function oci_register_taf_callback($connection, $callbackFn) {}

/**
 * Unregister a user-defined callback function for Oracle Database TAF.
 * @link https://www.php.net/manual/en/function.oci-unregister-taf-callback.php
 * @param resource $connection <p>
 * An Oracle connection identifier.
 * </p>
 * @return bool TRUE on success or FALSE on failure.
 * @since 7.2
 */
function oci_unregister_taf_callback($connection) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Associates a PHP variable with a column for query fetches
 * @link https://php.net/manual/en/function.oci-define-by-name.php
 * @param resource $statement <p>A valid OCI8 statement
 * identifier created by {@see oci_parse} and executed
 * by {@see oci_execute}, or a REF
 * CURSOR statement identifier.</p>
 * @param string $column_name <p>
 * The column name used in the query.
 * </p>
 * <p>
 * Use uppercase for Oracle's default, non-case sensitive column
 * names. Use the exact column name case for case-sensitive
 * column names.
 * </p>
 * @param mixed &$variable <p>
 * The PHP variable that will contain the returned column value.
 * </p>
 * @param int $type [optional] <p>
 * The data type to be returned. Generally not needed. Note that
 * Oracle-style data conversions are not performed. For example,
 * SQLT_INT will be ignored and the returned
 * data type will still be SQLT_CHR.
 * </p>
 * <p>
 * You can optionally use {@see oci_new_descriptor}
 * to allocate LOB/ROWID/BFILE descriptors.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_define_by_name($statement, $column_name, &$variable, $type = SQLT_CHR) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Binds a PHP variable to an Oracle placeholder
 * @link https://php.net/manual/en/function.oci-bind-by-name.php
 * @param resource $statement <p>
 * A valid OCI8 statement identifier.
 * </p>
 * @param string $bv_name <p>
 * The colon-prefixed bind variable placeholder used in the
 * statement. The colon is optional
 * in <i>bv_name</i>. Oracle does not use question
 * marks for placeholders.
 * </p>
 * @param mixed &$variable <p>
 * The PHP variable to be associated with <i>bv_name</i>
 * </p>
 * @param int $maxlength [optional] <p>
 * Sets the maximum length for the data. If you set it to -1, this
 * function will use the current length
 * of <i>variable</i> to set the maximum
 * length. In this case the <i>variable</i> must
 * exist and contain data
 * when {@see oci_bind_by_name} is called.
 * </p>
 * @param int $type [optional] <p>
 * The datatype that Oracle will treat the data as. The
 * default <i>type</i> used
 * is <b>SQLT_CHR</b>. Oracle will convert the data
 * between this type and the database column (or PL/SQL variable
 * type), when possible.
 * </p>
 * <p>
 * If you need to bind an abstract datatype (LOB/ROWID/BFILE) you
 * need to allocate it first using the
 * {@see oci_new_descriptor} function. The
 * <i>length</i> is not used for abstract datatypes
 * and should be set to -1.
 * </p>
 * <p>
 * Possible values for <i>type</i> are:
 * </p>
 * <p>
 * <b>SQLT_BFILEE</b> or <b>OCI_B_BFILE</b>
 * - for BFILEs;
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_bind_by_name($statement, $bv_name, &$variable, $maxlength = -1, $type = SQLT_CHR) {}

/**
 * (PHP 5 &gt;= 5.1.2, PECL OCI8 &gt;= 1.2.0)<br/>
 * Binds a PHP array to an Oracle PL/SQL array parameter
 * @link https://php.net/manual/en/function.oci-bind-array-by-name.php
 * @param resource $statement <p>
 * A valid OCI statement identifier.
 * </p>
 * @param string $name <p>
 * The Oracle placeholder.
 * </p>
 * @param array &$var_array <p>
 * An array.
 * </p>
 * @param int $max_table_length <p>
 * Sets the maximum length both for incoming and result arrays.
 * </p>
 * @param int $max_item_length [optional] <p>
 * Sets maximum length for array items. If not specified or equals to -1,
 * {@see oci_bind_array_by_name} will find the longest
 * element in the incoming array and will use it as the maximum length.
 * </p>
 * @param int $type [optional] <p>
 * Should be used to set the type of PL/SQL array items. See list of
 * available types below:
 * </p>
 * <p>
 * <b>SQLT_NUM</b> - for arrays of NUMBER.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_bind_array_by_name($statement, $name, array &$var_array, $max_table_length, $max_item_length = -1, $type = SQLT_AFC) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Checks if a field in the currently fetched row <b>NULL</b>
 * @link https://php.net/manual/en/function.oci-field-is-null.php
 * @param resource $statement <p>
 * A valid OCI statement identifier.
 * </p>
 * @param mixed $field <p>
 * Can be a field's index or a field's name (uppercased).
 * </p>
 * @return bool <b>TRUE</b> if <i>field</i> is <b>NULL</b>, <b>FALSE</b> otherwise.
 */
function oci_field_is_null($statement, $field) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns the name of a field from the statement
 * @link https://php.net/manual/en/function.oci-field-name.php
 * @param resource $statement <p>
 * A valid OCI statement identifier.
 * </p>
 * @param string|int $field <p>
 * Can be the field's index (1-based) or name.
 * </p>
 * @return string|false The name as a string, or <b>FALSE</b> on errors.
 */
function oci_field_name($statement, $field) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns field's size
 * @link https://php.net/manual/en/function.oci-field-size.php
 * @param resource $statement <p>
 * A valid OCI statement identifier.
 * </p>
 * @param mixed $field <p>
 * Can be the field's index (1-based) or name.
 * </p>
 * @return int|false The size of a <i>field</i> in bytes, or <b>FALSE</b> on
 * errors.
 */
function oci_field_size($statement, $field) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Tell the scale of the field
 * @link https://php.net/manual/en/function.oci-field-scale.php
 * @param resource $statement <p>
 * A valid OCI statement identifier.
 * </p>
 * @param string|int $field <p>
 * Can be the field's index (1-based) or name.
 * </p>
 * @return int|false The scale as an integer, or <b>FALSE</b> on errors.
 */
function oci_field_scale($statement, $field) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Tell the precision of a field
 * @link https://php.net/manual/en/function.oci-field-precision.php
 * @param resource $statement <p>
 * A valid OCI statement identifier.
 * </p>
 * @param string|int $field <p>
 * Can be the field's index (1-based) or name.
 * </p>
 * @return int|false The precision as an integer, or <b>FALSE</b> on errors.
 */
function oci_field_precision($statement, $field) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns field's data type
 * @link https://php.net/manual/en/function.oci-field-type.php
 * @param resource $statement <p>
 * A valid OCI statement identifier.
 * </p>
 * @param string|int $field <p>
 * Can be the field's index (1-based) or name.
 * </p>
 * @return mixed the field data type as a string, or <b>FALSE</b> on errors.
 */
function oci_field_type($statement, $field) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Tell the raw Oracle data type of the field
 * @link https://php.net/manual/en/function.oci-field-type-raw.php
 * @param resource $statement <p>
 * A valid OCI statement identifier.
 * </p>
 * @param int $field <p>
 * Can be the field's index (1-based) or name.
 * </p>
 * @return int|false Oracle's raw data type as a string, or <b>FALSE</b> on errors.
 */
function oci_field_type_raw($statement, $field) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Executes a statement
 * @link https://php.net/manual/en/function.oci-execute.php
 * @param resource $statement <p>
 * A valid OCI statement identifier.
 * </p>
 * @param int $mode [optional] <p>
 * An optional second parameter can be one of the following constants:
 * <table>
 * Execution Modes
 * <tr valign="top">
 * <td>Constant</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td><b>OCI_COMMIT_ON_SUCCESS</b></td>
 * <td>Automatically commit all outstanding changes for
 * this connection when the statement has succeeded. This
 * is the default.</td>
 * </tr>
 * <tr valign="top">
 * <td><b>OCI_DESCRIBE_ONLY</b></td>
 * <td>Make query meta data available to functions
 * like {@see oci_field_name} but do not
 * create a result set. Any subsequent fetch call such
 * as {@see oci_fetch_array} will
 * fail.</td>
 * </tr>
 * <tr valign="top">
 * <td><b>OCI_NO_AUTO_COMMIT</b></td>
 * <td>Do not automatically commit changes. Prior to PHP
 * 5.3.2 (PECL OCI8 1.4)
 * use <b>OCI_DEFAULT</b> which is equivalent
 * to <b>OCI_NO_AUTO_COMMIT</b>.</td>
 * </tr>
 * </table>
 * </p>
 * <p>
 * Using <b>OCI_NO_AUTO_COMMIT</b> mode starts or continues a
 * transaction. Transactions are automatically rolled back when
 * the connection is closed, or when the script ends. Explicitly
 * call {@see oci_commit} to commit a transaction,
 * or {@see oci_rollback} to abort it.
 * </p>
 * <p>
 * When inserting or updating data, using transactions is
 * recommended for relational data consistency and for performance
 * reasons.
 * </p>
 * <p>
 * If <b>OCI_NO_AUTO_COMMIT</b> mode is used for any
 * statement including queries, and
 * {@see oci_commit}
 * or {@see oci_rollback} is not subsequently
 * called, then OCI8 will perform a rollback at the end of the
 * script even if no data was changed. To avoid an unnecessary
 * rollback, many scripts do not
 * use <b>OCI_NO_AUTO_COMMIT</b> mode for queries or
 * PL/SQL. Be careful to ensure the appropriate transactional
 * consistency for the application when
 * using {@see oci_execute} with different modes in
 * the same script.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_execute($statement, $mode = OCI_COMMIT_ON_SUCCESS) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Cancels reading from cursor
 * @link https://php.net/manual/en/function.oci-cancel.php
 * @param resource $statement <p>
 * An OCI statement.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_cancel($statement) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Fetches the next row from a query into internal buffers
 * @link https://php.net/manual/en/function.oci-fetch.php
 * @param resource $statement <p>A valid OCI8 statement
 * identifier created by {@see oci_parse} and executed
 * by {@see oci_execute}, or a REF
 * CURSOR statement identifier.</p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> if there are no more rows in the
 * <i>statement</i>.
 */
function oci_fetch($statement) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns the next row from a query as an object
 * @link https://php.net/manual/en/function.oci-fetch-object.php
 * @param resource $statement <p>A valid OCI8 statement
 * identifier created by {@see oci_parse} and executed
 * by {@see oci_execute}, or a REF
 * CURSOR statement identifier.</p>
 * @return object|false <p>An object. Each attribute of the object corresponds to a
 * column of the row. If there are no more rows in
 * the <i>statement</i> then <b>FALSE</b> is returned.
 * </p>
 * <p>
 * Any LOB columns are returned as LOB descriptors.
 * </p>
 * <p>
 * DATE columns are returned as strings formatted
 * to the current date format. The default format can be changed with
 * Oracle environment variables such as NLS_LANG or
 * by a previously executed ALTER SESSION SET
 * NLS_DATE_FORMAT command.
 * </p>
 * <p>
 * Oracle's default, non-case sensitive column names will have
 * uppercase attribute names. Case-sensitive column names will have
 * attribute names using the exact column case.
 * Use <b>var_dump</b> on the result object to verify
 * the appropriate case for attribute access.
 * </p>
 * <p>
 * Attribute values will be <b>NULL</b> for any NULL
 * data fields.
 * </p>
 */
function oci_fetch_object($statement) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns the next row from a query as a numeric array
 * @link https://php.net/manual/en/function.oci-fetch-row.php
 * @param resource $statement <p>A valid OCI8 statement
 * identifier created by {@see oci_parse} and executed
 * by {@see oci_execute}, or a REF
 * CURSOR statement identifier.</p>
 * @return array|false A numerically indexed array. If there are no more rows in
 * the <i>statement</i> then <b>FALSE</b> is returned.
 */
function oci_fetch_row($statement) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns the next row from a query as an associative array
 * @link https://php.net/manual/en/function.oci-fetch-assoc.php
 * @param resource $statement <p>A valid OCI8 statement
 * identifier created by {@see oci_parse} and executed
 * by {@see oci_execute}, or a REF
 * CURSOR statement identifier.</p>
 * @return array|false An associative array. If there are no more rows in
 * the <i>statement</i> then <b>FALSE</b> is returned.
 */
function oci_fetch_assoc($statement) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns the next row from a query as an associative or numeric array
 * @link https://php.net/manual/en/function.oci-fetch-array.php
 * @param resource $statement <p>A valid OCI8 statement
 * identifier created by {@see oci_parse} and executed
 * by {@see oci_execute}, or a REF
 * CURSOR statement identifier.</p>
 * <p>
 * Can also be a statement identifier returned by {@see oci_get_implicit_resultset}.
 * </p>
 * @param int $mode [optional] <p>
 * An optional second parameter can be any combination of the following
 * constants:
 * <table>
 * {@see oci_fetch_array} Modes
 * <tr valign="top">
 * <td>Constant</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td><b>OCI_BOTH</b></td>
 * <td>Returns an array with both associative and numeric
 * indices. This is the same
 * as <b>OCI_ASSOC</b>
 * + <b>OCI_NUM</b> and is the default
 * behavior.</td>
 * </tr>
 * <tr valign="top">
 * <td><b>OCI_ASSOC</b></td>
 * <td>Returns an associative array.</td>
 * </tr>
 * <tr valign="top">
 * <td><b>OCI_NUM</b></td>
 * <td>Returns a numeric array.</td>
 * </tr>
 * <tr valign="top">
 * <td><b>OCI_RETURN_NULLS</b></td>
 * <td>Creates elements for <b>NULL</b> fields. The element
 * values will be a PHP <b>NULL</b>.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>OCI_RETURN_LOBS</b></td>
 * <td>Returns the contents of LOBs instead of the LOB
 * descriptors.</td>
 * </tr>
 * </table>
 * </p>
 * <p>
 * The default <i>mode</i> is <b>OCI_BOTH</b>.
 * </p>
 * <p>
 * Use the addition operator &#x00022;+&#x00022; to specify more than
 * one mode at a time.
 * </p>
 * @return array|false <p>An array with associative and/or numeric indices. If there
 * are no more rows in the <i>statement</i> then
 * <b>FALSE</b> is returned.
 * </p>
 * <p>
 * By default, LOB columns are returned as LOB descriptors.
 * </p>
 * <p>
 * DATE columns are returned as strings formatted
 * to the current date format. The default format can be changed with
 * Oracle environment variables such as NLS_LANG or
 * by a previously executed ALTER SESSION SET
 * NLS_DATE_FORMAT command.
 * </p>
 * <p>
 * Oracle's default, non-case sensitive column names will have
 * uppercase associative indices in the result array. Case-sensitive
 * column names will have array indices using the exact column case.
 * Use <b>var_dump</b> on the result array to verify the
 * appropriate case to use for each query.
 * </p>
 * <p>
 * The table name is not included in the array index. If your query
 * contains two different columns with the same name,
 * use <b>OCI_NUM</b> or add a column alias to the query
 * to ensure name uniqueness, see example #7. Otherwise only one
 * column will be returned via PHP.
 * </p>
 */
function oci_fetch_array($statement, $mode = null) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Obsolete variant of {@see oci_fetch_array}, {@see oci_fetch_object},
 * {@see oci_fetch_assoc} and
 * {@see oci_fetch_row}
 * @link https://php.net/manual/en/function.ocifetchinto.php
 * @param resource $statement_resource
 * @param array &$result
 * @param int $mode [optional]
 * @return int|bool
 */
#[Deprecated(since: "5.4")]
function ocifetchinto($statement_resource, &$result, $mode = null) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Fetches multiple rows from a query into a two-dimensional array
 * @link https://php.net/manual/en/function.oci-fetch-all.php
 * @param resource $statement <p>A valid OCI8 statement
 * identifier created by {@see oci_parse} and executed
 * by {@see oci_execute}, or a REF
 * CURSOR statement identifier.</p>
 * @param array &$output <p>
 * The variable to contain the returned rows.
 * </p>
 * <p>
 * LOB columns are returned as strings, where Oracle supports
 * conversion.
 * </p>
 * <p>
 * See {@see oci_fetch_array} for more information
 * on how data and types are fetched.
 * </p>
 * @param int $skip [optional] <p>
 * The number of initial rows to discard when fetching the
 * result. The default value is 0, so the first row onwards is
 * returned.
 * </p>
 * @param int $maxrows [optional] <p>
 * The number of rows to return. The default is -1 meaning return
 * all the rows from <i>skip</i> + 1 onwards.
 * </p>
 * @param int $flags [optional] <p>
 * Parameter <i>flags</i> indicates the array
 * structure and whether associative arrays should be used.
 * <table>
 * {@see oci_fetch_all} Array Structure Modes
 * <tr valign="top">
 * <td>Constant</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td><b>OCI_FETCHSTATEMENT_BY_ROW</b></td>
 * <td>The outer array will contain one sub-array per query
 * row.</td>
 * </tr>
 * <tr valign="top">
 * <td><b>OCI_FETCHSTATEMENT_BY_COLUMN</b></td>
 * <td>The outer array will contain one sub-array per query
 * column. This is the default.</td>
 * </tr>
 * </table>
 * </p>
 * <p>
 * Arrays can be indexed by column heading or numerically.
 * <table>
 * {@see oci_fetch_all} Array Index Modes
 * <tr valign="top">
 * <td>Constant</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td><b>OCI_NUM</b></td>
 * <td>Numeric indexes are used for each column's array.</td>
 * </tr>
 * <tr valign="top">
 * <td><b>OCI_ASSOC</b></td>
 * <td>Associative indexes are used for each column's
 * array. This is the default.</td>
 * </tr>
 * </table>
 * </p>
 * <p>
 * Use the addition operator &#x00022;+&#x00022; to choose a combination
 * of array structure and index modes.
 * </p>
 * <p>
 * Oracle's default, non-case sensitive column names will have
 * uppercase array keys. Case-sensitive column names will have
 * array keys using the exact column case.
 * Use <b>var_dump</b>
 * on <i>output</i> to verify the appropriate case
 * to use for each query.
 * </p>
 * <p>
 * Queries that have more than one column with the same name
 * should use column aliases. Otherwise only one of the columns
 * will appear in an associative array.
 * </p>
 * @return int|false The number of rows in <i>output</i>, which
 * may be 0 or more, or <b>FALSE</b> on failure.
 */
function oci_fetch_all($statement, array &$output, $skip = 0, $maxrows = -1, $flags = OCI_FETCHSTATEMENT_BY_COLUMN|OCI_ASSOC) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Frees all resources associated with statement or cursor
 * @link https://php.net/manual/en/function.oci-free-statement.php
 * @param resource $statement <p>
 * A valid OCI statement identifier.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_free_statement($statement) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Enables or disables internal debug output
 * @link https://php.net/manual/en/function.oci-internal-debug.php
 * @param bool $onoff <p>
 * Set this to <b>FALSE</b> to turn debug output off or <b>TRUE</b> to turn it on.
 * </p>
 * @removed 8.0
 * @return void No value is returned.
 */
function oci_internal_debug($onoff) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns the number of result columns in a statement
 * @link https://php.net/manual/en/function.oci-num-fields.php
 * @param resource $statement <p>
 * A valid OCI statement identifier.
 * </p>
 * @return int|false The number of columns as an integer, or <b>FALSE</b> on errors.
 */
function oci_num_fields($statement) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Prepares an Oracle statement for execution
 * @link https://php.net/manual/en/function.oci-parse.php
 * @param resource $connection <p>
 * An Oracle connection identifier, returned by
 * {@see oci_connect}, {@see oci_pconnect}, or {@see oci_new_connect}.
 * </p>
 * @param string $sql_text <p>
 * The SQL or PL/SQL statement.
 * </p>
 * <p>
 * SQL statements should not end with a
 * semi-colon (&#x00022;;&#x00022;). PL/SQL
 * statements should end with a semi-colon
 * (&#x00022;;&#x00022;).
 * </p>
 * @return resource|false A statement handle on success, or <b>FALSE</b> on error.
 */
function oci_parse($connection, $sql_text) {}

/**
 * (PECL OCI8 &gt;= 2.0.0)<br/>
 * Returns the next child statement resource from a parent statement resource that has Oracle Database 12c Implicit Result Sets
 * @link https://php.net/manual/en/function.oci-get-implicit-resultset.php
 * @param resource $statement <p>A valid OCI8 statement identifier created
 * by {@see oci_parse} and executed
 * by {@see oci_execute}. The statement
 * identifier may or may not be associated with a SQL statement
 * that returns Implicit Result Sets.
 * </p>
 * @return resource|false A statement handle for the next child statement available
 * on <i>statement</i>. Returns <b>FALSE</b> when child
 * statements do not exist, or all child statements have been returned
 * by previous calls
 * to {@see oci_get_implicit_resultset}.
 */
function oci_get_implicit_resultset($statement) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Allocates and returns a new cursor (statement handle)
 * @link https://php.net/manual/en/function.oci-new-cursor.php
 * @param resource $connection <p>
 * An Oracle connection identifier, returned by
 * {@see oci_connect} or {@see oci_pconnect}.
 * </p>
 * @return resource|false A new statement handle, or <b>FALSE</b> on error.
 */
function oci_new_cursor($connection) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns field's value from the fetched row
 * @link https://php.net/manual/en/function.oci-result.php
 * @param resource $statement
 * @param mixed $field <p>
 * Can be either use the column number (1-based) or the column name.
 * The case of the column name must be the case that Oracle meta data
 * describes the column as, which is uppercase for columns created
 * case insensitively.
 * </p>
 * @return mixed everything as strings except for abstract types (ROWIDs, LOBs and
 * FILEs). Returns <b>FALSE</b> on error.
 */
function oci_result($statement, $field) {}

/**
 * (PHP 5.3.7, PECL OCI8 &gt;= 1.4.6)<br/>
 * Returns the Oracle client library version
 * @link https://php.net/manual/en/function.oci-client-version.php
 * @return string the version number as a string.
 */
function oci_client_version() {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns the Oracle Database version
 * @link https://php.net/manual/en/function.oci-server-version.php
 * @param resource $connection
 * @return string|false The version information as a string or <b>FALSE</b> on error.
 */
function oci_server_version($connection) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns the type of a statement
 * @link https://php.net/manual/en/function.oci-statement-type.php
 * @param resource $statement <p>
 * A valid OCI8 statement identifier from {@see oci_parse}.
 * </p>
 * @return string|false The type of <i>statement</i> as one of the
 * following strings.
 * <table>
 * Statement type
 * <tr valign="top">
 * <td>Return String</td>
 * <td>Notes</td>
 * </tr>
 * <tr valign="top">
 * <td>ALTER</td>
 * <td></td>
 * </tr>
 * <tr valign="top">
 * <td>BEGIN</td>
 * <td></td>
 * </tr>
 * <tr valign="top">
 * <td>CALL</td>
 * <td>Introduced in PHP 5.2.1 (PECL OCI8 1.2.3)</td>
 * </tr>
 * <tr valign="top">
 * <td>CREATE</td>
 * <td></td>
 * </tr>
 * <tr valign="top">
 * <td>DECLARE</td>
 * <td></td>
 * </tr>
 * <tr valign="top">
 * <td>DELETE</td>
 * <td></td>
 * </tr>
 * <tr valign="top">
 * <td>DROP</td>
 * <td></td>
 * </tr>
 * <tr valign="top">
 * <td>INSERT</td>
 * <td></td>
 * </tr>
 * <tr valign="top">
 * <td>SELECT</td>
 * <td></td>
 * </tr>
 * <tr valign="top">
 * <td>UPDATE</td>
 * <td></td>
 * </tr>
 * <tr valign="top">
 * <td>UNKNOWN</td>
 * <td></td>
 * </tr>
 * </table>
 * </p>
 * <p>
 * Returns <b>FALSE</b> on error.
 */
function oci_statement_type($statement) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns number of rows affected during statement execution
 * @link https://php.net/manual/en/function.oci-num-rows.php
 * @param resource $statement <p>
 * A valid OCI statement identifier.
 * </p>
 * @return int|false The number of rows affected as an integer, or <b>FALSE</b> on errors.
 */
function oci_num_rows($statement) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Closes an Oracle connection
 * @link https://php.net/manual/en/function.oci-close.php
 * @param resource $connection <p>
 * An Oracle connection identifier returned by
 * {@see oci_connect}, {@see oci_pconnect},
 * or {@see oci_new_connect}.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_close($connection) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Connect to an Oracle database
 * @link https://php.net/manual/en/function.oci-connect.php
 * @param string $username <p>
 * The Oracle user name.
 * </p>
 * @param string $password <p>
 * The password for <i>username</i>.
 * </p>
 * @param string $connection_string [optional] <p>Contains
 * the Oracle instance to connect to. It can be
 * an Easy Connect
 * string, or a Connect Name from
 * the tnsnames.ora file, or the name of a local
 * Oracle instance.
 * </p>
 * <p>
 * If not specified, PHP uses
 * environment variables such as <b>TWO_TASK</b> (on Linux)
 * or <b>LOCAL</b> (on Windows)
 * and <b>ORACLE_SID</b> to determine the
 * Oracle instance to connect to.
 * </p>
 * <p>
 * To use the Easy Connect naming method, PHP must be linked with Oracle
 * 10g or greater Client libraries. The Easy Connect string for Oracle
 * 10g is of the form:
 * [//]host_name[:port][/service_name]. From Oracle
 * 11g, the syntax is:
 * [//]host_name[:port][/service_name][:server_type][/instance_name].
 * Service names can be found by running the Oracle
 * utility lsnrctl status on the database server
 * machine.
 * </p>
 * <p>
 * The tnsnames.ora file can be in the Oracle Net
 * search path, which
 * includes $ORACLE_HOME/network/admin
 * and /etc. Alternatively
 * set TNS_ADMIN so
 * that $TNS_ADMIN/tnsnames.ora is read. Make sure
 * the web daemon has read access to the file.
 * </p>
 * @param string $character_set [optional] <p>Determines
 * the character set used by the Oracle Client libraries. The character
 * set does not need to match the character set used by the database. If
 * it doesn't match, Oracle will do its best to convert data to and from
 * the database character set. Depending on the character sets this may
 * not give usable results. Conversion also adds some time overhead.
 * </p>
 * <p>
 * If not specified, the
 * Oracle Client libraries determine a character set from
 * the <b>NLS_LANG</b> environment variable.
 * </p>
 * <p>
 * Passing this parameter can
 * reduce the time taken to connect.
 * </p>
 * <p>
 * @param int $session_mode [optional] This
 * parameter is available since version PHP 5 (PECL OCI8 1.1) and accepts the
 * following values: <b>OCI_DEFAULT</b>,
 * <b>OCI_SYSOPER</b> and <b>OCI_SYSDBA</b>.
 * If either <b>OCI_SYSOPER</b> or
 * <b>OCI_SYSDBA</b> were specified, this function will try
 * to establish privileged connection using external credentials.
 * Privileged connections are disabled by default. To enable them you
 * need to set oci8.privileged_connect
 * to On.
 * </p>
 * <p>
 * PHP 5.3 (PECL OCI8 1.3.4) introduced the
 * <b>OCI_CRED_EXT</b> mode value. This tells Oracle to use
 * External or OS authentication, which must be configured in the
 * database. The <b>OCI_CRED_EXT</b> flag can only be used
 * with username of &#x00022;/&#x00022; and a empty password.
 * oci8.privileged_connect
 * may be On or Off.
 * </p>
 * <p>
 * <b>OCI_CRED_EXT</b> may be combined with the
 * <b>OCI_SYSOPER</b> or
 * <b>OCI_SYSDBA</b> modes.
 * </p>
 * <p>
 * <b>OCI_CRED_EXT</b> is not supported on Windows for
 * security reasons.
 * </p>
 * @return resource|false A connection identifier or <b>FALSE</b> on error.
 */
function oci_connect($username, $password, $connection_string = null, $character_set = null, $session_mode = null) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Connect to the Oracle server using a unique connection
 * @link https://php.net/manual/en/function.oci-new-connect.php
 * @param string $username <p>
 * The Oracle user name.
 * </p>
 * @param string $password <p>
 * The password for <i>username</i>.
 * </p>
 * @param string $connection_string [optional] <p>Contains
 * the Oracle instance to connect to. It can be
 * an Easy Connect
 * string, or a Connect Name from
 * the tnsnames.ora file, or the name of a local
 * Oracle instance.
 * </p>
 * <p>
 * If not specified, PHP uses
 * environment variables such as <b>TWO_TASK</b> (on Linux)
 * or <b>LOCAL</b> (on Windows)
 * and <b>ORACLE_SID</b> to determine the
 * Oracle instance to connect to.
 * </p>
 * <p>
 * To use the Easy Connect naming method, PHP must be linked with Oracle
 * 10g or greater Client libraries. The Easy Connect string for Oracle
 * 10g is of the form:
 * [//]host_name[:port][/service_name]. From Oracle
 * 11g, the syntax is:
 * [//]host_name[:port][/service_name][:server_type][/instance_name].
 * Service names can be found by running the Oracle
 * utility lsnrctl status on the database server
 * machine.
 * </p>
 * <p>
 * The tnsnames.ora file can be in the Oracle Net
 * search path, which
 * includes $ORACLE_HOME/network/admin
 * and /etc. Alternatively
 * set TNS_ADMIN so
 * that $TNS_ADMIN/tnsnames.ora is read. Make sure
 * the web daemon has read access to the file.
 * </p>
 * @param string $character_set [optional] <p>Determines
 * the character set used by the Oracle Client libraries. The character
 * set does not need to match the character set used by the database. If
 * it doesn't match, Oracle will do its best to convert data to and from
 * the database character set. Depending on the character sets this may
 * not give usable results. Conversion also adds some time overhead.
 * </p>
 * <p>
 * If not specified, the
 * Oracle Client libraries determine a character set from
 * the <b>NLS_LANG</b> environment variable.
 * </p>
 * <p>
 * Passing this parameter can
 * reduce the time taken to connect.
 * </p>
 * @param int $session_mode [optional] <p>This
 * parameter is available since version PHP 5 (PECL OCI8 1.1) and accepts the
 * following values: <b>OCI_DEFAULT</b>,
 * <b>OCI_SYSOPER</b> and <b>OCI_SYSDBA</b>.
 * If either <b>OCI_SYSOPER</b> or
 * <b>OCI_SYSDBA</b> were specified, this function will try
 * to establish privileged connection using external credentials.
 * Privileged connections are disabled by default. To enable them you
 * need to set oci8.privileged_connect
 * to On.
 * </p>
 * <p>
 * PHP 5.3 (PECL OCI8 1.3.4) introduced the
 * <b>OCI_CRED_EXT</b> mode value. This tells Oracle to use
 * External or OS authentication, which must be configured in the
 * database. The <b>OCI_CRED_EXT</b> flag can only be used
 * with username of &#x00022;/&#x00022; and a empty password.
 * oci8.privileged_connect
 * may be On or Off.
 * </p>
 * <p>
 * <b>OCI_CRED_EXT</b> may be combined with the
 * <b>OCI_SYSOPER</b> or
 * <b>OCI_SYSDBA</b> modes.
 * </p>
 * <p>
 * <b>OCI_CRED_EXT</b> is not supported on Windows for
 * security reasons.
 * </p>
 * @return resource|false A connection identifier or <b>FALSE</b> on error.
 */
function oci_new_connect($username, $password, $connection_string = null, $character_set = null, $session_mode = null) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Connect to an Oracle database using a persistent connection
 * @link https://php.net/manual/en/function.oci-pconnect.php
 * @param string $username <p>
 * The Oracle user name.
 * </p>
 * @param string $password <p>
 * The password for <i>username</i>.
 * </p>
 * @param string $connection_string [optional] <p>Contains
 * the Oracle instance to connect to. It can be
 * an Easy Connect
 * string, or a Connect Name from
 * the tnsnames.ora file, or the name of a local
 * Oracle instance.
 * </p>
 * <p>
 * If not specified, PHP uses
 * environment variables such as <b>TWO_TASK</b> (on Linux)
 * or <b>LOCAL</b> (on Windows)
 * and <b>ORACLE_SID</b> to determine the
 * Oracle instance to connect to.
 * </p>
 * <p>
 * To use the Easy Connect naming method, PHP must be linked with Oracle
 * 10g or greater Client libraries. The Easy Connect string for Oracle
 * 10g is of the form:
 * [//]host_name[:port][/service_name]. From Oracle
 * 11g, the syntax is:
 * [//]host_name[:port][/service_name][:server_type][/instance_name].
 * Service names can be found by running the Oracle
 * utility lsnrctl status on the database server
 * machine.
 * </p>
 * <p>
 * The tnsnames.ora file can be in the Oracle Net
 * search path, which
 * includes $ORACLE_HOME/network/admin
 * and /etc. Alternatively
 * set TNS_ADMIN so
 * that $TNS_ADMIN/tnsnames.ora is read. Make sure
 * the web daemon has read access to the file.
 * </p>
 * @param string $character_set [optional] <p>Determines
 * the character set used by the Oracle Client libraries. The character
 * set does not need to match the character set used by the database. If
 * it doesn't match, Oracle will do its best to convert data to and from
 * the database character set. Depending on the character sets this may
 * not give usable results. Conversion also adds some time overhead.
 * </p>
 * <p>
 * If not specified, the
 * Oracle Client libraries determine a character set from
 * the <b>NLS_LANG</b> environment variable.
 * </p>
 * <p>
 * Passing this parameter can
 * reduce the time taken to connect.
 * </p>
 * @param int $session_mode [optional] <p>This
 * parameter is available since version PHP 5 (PECL OCI8 1.1) and accepts the
 * following values: <b>OCI_DEFAULT</b>,
 * <b>OCI_SYSOPER</b> and <b>OCI_SYSDBA</b>.
 * If either <b>OCI_SYSOPER</b> or
 * <b>OCI_SYSDBA</b> were specified, this function will try
 * to establish privileged connection using external credentials.
 * Privileged connections are disabled by default. To enable them you
 * need to set oci8.privileged_connect
 * to On.
 * </p>
 * <p>
 * PHP 5.3 (PECL OCI8 1.3.4) introduced the
 * <b>OCI_CRED_EXT</b> mode value. This tells Oracle to use
 * External or OS authentication, which must be configured in the
 * database. The <b>OCI_CRED_EXT</b> flag can only be used
 * with username of &#x00022;/&#x00022; and a empty password.
 * oci8.privileged_connect
 * may be On or Off.
 * </p>
 * <p>
 * <b>OCI_CRED_EXT</b> may be combined with the
 * <b>OCI_SYSOPER</b> or
 * <b>OCI_SYSDBA</b> modes.
 * </p>
 * <p>
 * <b>OCI_CRED_EXT</b> is not supported on Windows for
 * security reasons.
 * </p>
 * @return resource|false A connection identifier or <b>FALSE</b> on error.
 */
function oci_pconnect($username, $password, $connection_string = null, $character_set = null, $session_mode = null) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Returns the last error found
 * @link https://php.net/manual/en/function.oci-error.php
 * @param resource $resource [optional] <p>
 * For most errors, <i>resource</i> is the
 * resource handle that was passed to the failing function call.
 * For connection errors with {@see oci_connect},
 * {@see oci_new_connect} or
 * {@see oci_pconnect} do not pass <i>resource</i>.
 * </p>
 * @return array|false If no error is found, {@see oci_error} returns
 * <b>FALSE</b>. Otherwise, {@see oci_error} returns the
 * error information as an associative array.
 * </p>
 * <p>
 * <table>
 * {@see oci_error} Array Description
 * <tr valign="top">
 * <td>Array key</td>
 * <td>Type</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>code</td>
 * <td>integer</td>
 * <td>
 * The Oracle error number.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>message</td>
 * <td>string</td>
 * <td>
 * The Oracle error text.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>offset</td>
 * <td>integer</td>
 * <td>
 * The byte position of an error in the SQL statement. If there
 * was no statement, this is 0
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>sqltext</td>
 * <td>string</td>
 * <td>
 * The SQL statement text. If there was no statement, this is
 * an empty string.
 * </td>
 * </tr>
 * </table>
 */
#[ArrayShape(["code" => "int", "message" => "string", "offset" => "int", "sqltext" => "string"])]
function oci_error($resource = null) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Frees a descriptor
 * @link https://php.net/manual/en/function.oci-free-descriptor.php
 * @param resource $descriptor
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_free_descriptor($descriptor) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Compares two LOB/FILE locators for equality
 * @link https://php.net/manual/en/function.oci-lob-is-equal.php
 * @param OCI_Lob $lob1 <p>
 * A LOB identifier.
 * </p>
 * @param OCI_Lob $lob2 <p>
 * A LOB identifier.
 * </p>
 * @return bool <b>TRUE</b> if these objects are equal, <b>FALSE</b> otherwise.
 */
function oci_lob_is_equal(
    #[LanguageLevelTypeAware(['8.0' => 'OCILob'], default: 'OCI_Lob')] $lob1,
    #[LanguageLevelTypeAware(['8.0' => 'OCILob'], default: 'OCI_Lob')] $lob2
) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Copies large object
 * @link https://php.net/manual/en/function.oci-lob-copy.php
 * @param OCI_Lob $lob_to <p>
 * The destination LOB.
 * </p>
 * @param OCI_Lob $lob_from <p>
 * The copied LOB.
 * </p>
 * @param int $length [optional] <p>
 * Indicates the length of data to be copied.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_lob_copy(
    #[LanguageLevelTypeAware(['8.0' => 'OCILob'], default: 'OCI_Lob')] $lob_to,
    #[LanguageLevelTypeAware(['8.0' => 'OCILob'], default: 'OCI_Lob')] $lob_from,
    $length = 0
) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Commits the outstanding database transaction
 * @link https://php.net/manual/en/function.oci-commit.php
 * @param resource $connection <p>
 * An Oracle connection identifier, returned by
 * {@see oci_connect}, {@see oci_pconnect}, or {@see oci_new_connect}.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_commit($connection) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Rolls back the outstanding database transaction
 * @link https://php.net/manual/en/function.oci-rollback.php
 * @param resource $connection <p>
 * An Oracle connection identifier, returned by
 * {@see oci_connect}, {@see oci_pconnect}
 * or {@see oci_new_connect}.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_rollback($connection) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Initializes a new empty LOB or FILE descriptor
 * @link https://php.net/manual/en/function.oci-new-descriptor.php
 * @param resource $connection <p>
 * An Oracle connection identifier, returned by
 * {@see oci_connect} or {@see oci_pconnect}.
 * </p>
 * @param int $type [optional] <p>
 * Valid values for <i>type</i> are:
 * <b>OCI_DTYPE_FILE</b>, <b>OCI_DTYPE_LOB</b> and
 * <b>OCI_DTYPE_ROWID</b>.
 * </p>
 * @return OCI_Lob|OCILob|false A new LOB or FILE descriptor on success, <b>FALSE</b> on error.
 */
#[LanguageLevelTypeAware(['8.0' => 'OCILob|false'], default: 'OCI_Lob|false')]
function oci_new_descriptor($connection, $type = OCI_DTYPE_LOB) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Sets number of rows to be prefetched by queries
 * @link https://php.net/manual/en/function.oci-set-prefetch.php
 * @param resource $statement <p>A valid OCI8 statement
 * identifier created by {@see oci_parse} and executed
 * by {@see oci_execute}, or a REF
 * CURSOR statement identifier.</p>
 * @param int $rows <p>
 * The number of rows to be prefetched, &gt;= 0
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_set_prefetch($statement, $rows) {}

/**
 * (PHP 5.3.2, PECL OCI8 &gt;= 1.4.0)<br/>
 * Sets the client identifier
 * @link https://php.net/manual/en/function.oci-set-client-identifier.php
 * @param resource $connection <p>An Oracle connection identifier,
 * returned by {@see oci_connect}, {@see oci_pconnect},
 * or {@see oci_new_connect}.</p>
 * @param string $client_identifier <p>
 * User chosen string up to 64 bytes long.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_set_client_identifier($connection, $client_identifier) {}

/**
 * (PHP 5.3.2, PECL OCI8 &gt;= 1.4.0)<br/>
 * Sets the database edition
 * @link https://php.net/manual/en/function.oci-set-edition.php
 * @param string $edition <p>
 * Oracle Database edition name previously created with the SQL
 * "CREATE EDITION" command.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_set_edition($edition) {}

/**
 * (PHP 5.3.2, PECL OCI8 &gt;= 1.4.0)<br/>
 * Sets the module name
 * @link https://php.net/manual/en/function.oci-set-module-name.php
 * @param resource $connection <p>An Oracle connection identifier,
 * returned by {@see oci_connect}, {@see oci_pconnect},
 * or {@see oci_new_connect}.</p>
 * @param string $module_name <p>
 * User chosen string up to 48 bytes long.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_set_module_name($connection, $module_name) {}

/**
 * (PHP 5.3.2, PECL OCI8 &gt;= 1.4.0)<br/>
 * Sets the action name
 * @link https://php.net/manual/en/function.oci-set-action.php
 * @param resource $connection <p>An Oracle connection identifier,
 * returned by {@see oci_connect}, {@see oci_pconnect},
 * or {@see oci_new_connect}.</p>
 * @param string $action_name <p>
 * User chosen string up to 32 bytes long.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_set_action($connection, $action_name) {}

/**
 * (PHP 5.3.2, PECL OCI8 &gt;= 1.4.0)<br/>
 * Sets the client information
 * @link https://php.net/manual/en/function.oci-set-client-info.php
 * @param resource $connection <p>An Oracle connection identifier,
 * returned by {@see oci_connect}, {@see oci_pconnect},
 * or {@see oci_new_connect}.</p>
 * @param string $client_info <p>
 * User chosen string up to 64 bytes long.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_set_client_info($connection, $client_info) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Changes password of Oracle's user
 * @link https://php.net/manual/en/function.oci-password-change.php
 * @param resource $connection <p>
 * An Oracle connection identifier, returned by
 * {@see oci_connect} or {@see oci_pconnect}.
 * </p>
 * @param string $username <p>
 * The Oracle user name.
 * </p>
 * @param string $old_password <p>
 * The old password.
 * </p>
 * @param string $new_password <p>
 * The new password to be set.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_password_change($connection, $username, $old_password, $new_password) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Allocates new collection object
 * @link https://php.net/manual/en/function.oci-new-collection.php
 * @param resource $connection <p>
 * An Oracle connection identifier, returned by
 * {@see oci_connect} or {@see oci_pconnect}.
 * </p>
 * @param string $tdo <p>
 * Should be a valid named type (uppercase).
 * </p>
 * @param string $schema [optional] <p>
 * Should point to the scheme, where the named type was created. The name
 * of the current user is the default value.
 * </p>
 * @return OCI_Collection|false A new <b>OCICollection</b> object or <b>FALSE</b> on
 * error.
 */
#[LanguageLevelTypeAware(['8.0' => 'OCICollection|false'], default: 'OCI_Collection|false')]
function oci_new_collection($connection, $tdo, $schema = null) {}

/**
 * Alias of {@see oci_free_statement()}
 * @link https://php.net/manual/en/function.ocifreecursor.php
 * @param $statement_resource
 * @return bool Returns TRUE on success or FALSE on failure.
 */
function oci_free_cursor($statement_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see oci_free_statement}
 * @link https://php.net/manual/en/function.ocifreecursor.php
 * @param resource $statement_resource
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
#[Deprecated(replacement: "oci_free_statement", since: "5.4")]
function ocifreecursor($statement_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_bind_by_name}
 * @link https://php.net/manual/en/function.ocibindbyname.php
 * @param resource $statement
 * @param string $column_name
 * @param mixed &$variable
 * @param int $maximum_length [optional]
 * @param int $type [optional]
 * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
#[Deprecated(replacement: "oci_bind_by_name", since: "5.4")]
function ocibindbyname($statement, $column_name, &$variable, $maximum_length = -1, $type = SQLT_CHR) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_define_by_name}
 * @link https://php.net/manual/en/function.ocidefinebyname.php
 * @param resource $statement <p>A valid OCI8 statement identifier created by {@see oci_parse()} and executed by {@see oci_execute()}, or a REF CURSOR statement identifier.</p>
 * @param string $column_name <p>The column name used in the query. Use uppercase for Oracle's default, non-case sensitive column names. Use the exact column name case for case-sensitive column names.</p>
 * @param mixed &$variable <p>The PHP variable that will contain the returned column value.</p>
 * @param int $type [optional] <p>The data type to be returned. Generally not needed. Note that Oracle-style data conversions are not performed. For example, SQLT_INT will be ignored and the returned data type will still be SQLT_CHR.
 * You can optionally use {@see oci_new_descriptor()} to allocate LOB/ROWID/BFILE descriptors.</p>
 * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
#[Deprecated(replacement: "oci_define_by_name", since: "5.4")]
function ocidefinebyname($statement, $column_name, &$variable, $type = SQLT_CHR) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_field_is_null}
 * @link https://php.net/manual/en/function.ocicolumnisnull.php
 * @param resource $statement
 * @param mixed $column_number_or_name
 * @return bool Returns TRUE if field is NULL, FALSE otherwise.
 */
#[Deprecated(replacement: "oci_field_is_null", since: "5.4")]
function ocicolumnisnull($statement, $column_number_or_name) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_field_name}
 * @link https://php.net/manual/en/function.ocicolumnname.php
 * @param resource $statement
 * @param mixed $column_number
 * @return string|false Returns the name as a string, or FALSE on errors.
 */
#[Deprecated(replacement: "oci_field_name", since: "5.4")]
function ocicolumnname($statement, $column_number) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_field_size}
 * @link https://php.net/manual/en/function.ocicolumnsize.php
 * @param resource $statement
 * @param mixed $column_number_or_name
 * @return int|false Returns the size of a field in bytes, or <b>FALSE</b> on errors.
 */
#[Deprecated(replacement: "oci_field_size", since: "5.4")]
function ocicolumnsize($statement, $column_number_or_name) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_field_scale}
 * @link https://php.net/manual/en/function.ocicolumnscale.php
 * @param resource $statement_resource
 * @param $column_number
 * @return int|false Returns the scale as an integer, or <b>FALSE</b> on errors.
 */
#[Deprecated(replacement: "oci_field_scale", since: "5.4")]
function ocicolumnscale($statement_resource, $column_number) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_field_precision}
 * @link https://php.net/manual/en/function.ocicolumnprecision.php
 * @param resource $statement_resource
 * @param string|int $column_number
 * @return int|false Returns the precision  as an integer, or <b>FALSE</b> on errors.
 */
#[Deprecated(replacement: "oci_field_precision", since: "5.4")]
function ocicolumnprecision($statement_resource, $column_number) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_field_type}
 * @link https://php.net/manual/en/function.ocicolumntype.php
 * @param resource $statement_resource
 * @param string|int $column_number
 * @return mixed|false Returns the field data type as a string, or FALSE on errors.
 */
#[Deprecated(replacement: "oci_field_type", since: "5.4")]
function ocicolumntype($statement_resource, $column_number) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_field_type_raw}
 * @link https://php.net/manual/en/function.ocicolumntyperaw.php
 * @param resource $statement_resource
 * @param string|int $column_number
 * @return int|false Returns Oracle's raw data type as a number, or FALSE on errors.
 */
#[Deprecated(replacement: "oci_field_type_raw", since: "5.4")]
function ocicolumntyperaw($statement_resource, $column_number) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_execute}
 * @link https://php.net/manual/en/function.ociexecute.php
 * @param $statement_resource
 * @param $mode [optional]
 * @return bool Returns TRUE on success or FALSE on failure
 */
#[Deprecated(replacement: "oci_execute", since: "5.4")]
function ociexecute($statement_resource, $mode = OCI_COMMIT_ON_SUCCESS) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_cancel}
 * @link https://php.net/manual/en/function.ocicancel.php
 * @param resource $statement_resource
 * @return bool Returns TRUE on success or FALSE on failure
 */
#[Deprecated(replacement: 'oci_cancel', since: "5.4")]
function ocicancel($statement_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_fetch}
 * @link https://php.net/manual/en/function.ocifetch.php
 * @param resource $statement_resource
 * @return bool Returns TRUE on success or FALSE if there are no more rows in the statement.
 */
#[Deprecated(replacement: "oci_fetch", since: "5.4")]
function ocifetch($statement_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_fetch_all}
 * @link https://php.net/manual/en/function.ocifetchstatement.php
 * @param resource $statement_resource
 * @param array &$output
 * @param int $skip [optional]
 * @param int $maximum_rows [optional]
 * @param int $flags [optional]
 * @return int|false Returns the number of rows in output, which may be 0 or more, or FALSE on failure.
 */
#[Deprecated(replacement: "oci_fetch_all", since: "5.4")]
function ocifetchstatement($statement_resource, &$output, $skip, $maximum_rows, $flags) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_free_statement}
 * @link https://php.net/manual/en/function.ocifreestatement.php
 * @param resource $statement_resource
 * @return bool Returns TRUE on success or FALSE on failure.
 */
#[Deprecated(replacement: "oci_free_statement", since: "5.4")]
function ocifreestatement($statement_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_internal_debug}
 * @link https://php.net/manual/en/function.ociinternaldebug.php
 * @param bool $mode
 * @removed 8.0
 */
#[Deprecated(replacement: "oci_internal_debug", since: "5.4")]
function ociinternaldebug($mode) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_num_fields}
 * @link https://php.net/manual/en/function.ocinumcols.php
 * @param resource $statement_resource
 * @return int|false Returns the number of columns as an integer, or FALSE on errors.
 */
#[Deprecated(replacement: "oci_num_fields", since: "5.4")]
function ocinumcols($statement_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_parse}
 * @link https://php.net/manual/en/function.ociparse.php
 * @param resource $connection_resource
 * @param string $sql_text
 * @return resource|false Returns a statement handle on success, or FALSE on error.
 */
#[Deprecated(replacement: "oci_parse", since: "5.4")]
function ociparse($connection_resource, $sql_text) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_new_cursor}
 * @link https://php.net/manual/en/function.ocinewcursor.php
 * @param resource $connection_resource
 * @return resource|false Returns a new statement handle, or FALSE on error.
 */
#[Deprecated(replacement: "oci_new_cursor", since: "5.4")]
function ocinewcursor($connection_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_result}
 * @link https://php.net/manual/en/function.ociresult.php
 * @param resource $statement_resource
 * @param $column_number_or_name
 * @return false|mixed Returns everything as strings except for abstract types (ROWIDs, LOBs and FILEs). Returns FALSE on error.
 */
#[Deprecated(replacement: "oci_result", since: "5.4")]
function ociresult($statement_resource, $column_number_or_name) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_server_version}
 * @link https://php.net/manual/en/function.ociserverversion.php
 * @param $connection_resource
 * @return string|false Returns the version information as a string or FALSE on error.
 */
#[Deprecated(replacement: "oci_server_version", since: "5.4")]
function ociserverversion($connection_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_statement_type}
 * @link https://php.net/manual/en/function.ocistatementtype.php
 * @param resource $statement_resource
 * @return string|false Returns everything as strings except for abstract types (ROWIDs, LOBs and FILEs). Returns FALSE on error.
 */
#[Deprecated(replacement: "oci_statement_type", since: "5.4")]
function ocistatementtype($statement_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_num_rows}
 * @link https://php.net/manual/en/function.ocirowcount.php
 * @param resource $statement_resource
 * @return int|false Returns the number of rows affected as an integer, or FALSE on errors.
 */
#[Deprecated(replacement: "oci_num_rows", since: "5.4")]
function ocirowcount($statement_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_close}
 * @link https://php.net/manual/en/function.ocilogoff.php
 * @param resource $connection_resource
 * @return bool Returns TRUE on success or FALSE on failure.
 */
#[Deprecated(replacement: "oci_close", since: "5.4")]
function ocilogoff($connection_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_connect}
 * @link https://php.net/manual/en/function.ocilogon.php
 * @param string $username
 * @param string $password
 * @param string $connection_string [optional]
 * @param string $character_set [optional]
 * @param int $session_mode [optional]
 * @return resource|false Returns a connection identifier or FALSE on error.
 */
#[Deprecated(replacement: "oci_connect", since: "5.4")]
function ocilogon($username, $password, $connection_string, $character_set, $session_mode) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see oci_new_connect}
 * @link https://php.net/manual/en/function.ocinlogon.php
 * @param $username
 * @param $password
 * @param $connection_string [optional]
 * @param $character_set [optional]
 * @param $session_mode [optional]
 * @return resource|false <p>Returns a connection identifier or <b>FALSE</b> on error.</p>
 */
#[Deprecated(replacement: "oci_new_connect", since: "5.4")]
function ocinlogon($username, $password, $connection_string, $character_set, $session_mode) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see oci_pconnect}
 * @link https://php.net/manual/en/function.ociplogon.php
 * @param string $username <p>The Oracle user name.</p>
 * @param string $password <p> The password for username</p>
 * @param $connection_string [optional]
 * @param $character_set [optional]
 * @param $session_mode [optional]
 * @return resource|false <p>Returns a connection identifier or <b>FALSE</b> on error.</p>
 */
#[Deprecated(replacement: "oci_pconnect", since: "5.4")]
function ociplogon($username, $password, $connection_string, $character_set, $session_mode) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see oci_error}
 * @link https://php.net/manual/en/function.ocierror.php
 * @param resource $connection_or_statement_resource [optional] For most errors, resource is the resource handle that was passed to the failing function call.
 * For connection errors with oci_connect(), oci_new_connect() or oci_pconnect() do not pass resource.
 * @return array|false If no error is found, oci_error() returns FALSE. Otherwise, oci_error() returns the error information as an associative array.
 */
#[Deprecated(replacement: "oci_error", since: "5.4")]
function ocierror($connection_or_statement_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see OCI-Lob::free}
 * @link https://php.net/manual/en/function.ocifreedesc.php
 * @param $lob_descriptor
 * @return bool <p>Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.</p>
 */
#[Deprecated(replacement: "OCI-Lob::free", since: "5.4")]
function ocifreedesc($lob_descriptor) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see OCI-Lob::save}
 * @link https://php.net/manual/en/function.ocisavelob.php
 * @param OCI_Lob|OCILob $lob_descriptor
 * @param string $data
 * @param int $offset [optional]
 * @return bool
 */
#[Deprecated(replacement: "OCI-Lob::save", since: "5.4")]
function ocisavelob(#[LanguageLevelTypeAware(['8.0' => 'OCILob'], default: 'OCI_Lob')] $lob_descriptor, $data, $offset) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see OCI_Lob::import}
 * @link https://php.net/manual/en/function.ocisavelobfile.php
 * @param OCI_Lob|OCILob $lob_descriptor
 * @param string $filename
 * @return bool
 */
#[Deprecated(replacement: "OCI_Lob::import", since: "5.4")]
function ocisavelobfile(#[LanguageLevelTypeAware(['8.0' => 'OCILob'], default: 'OCI_Lob')] $lob_descriptor, $filename) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see OCI_Lob::export}
 * @link https://php.net/manual/en/function.ociwritelobtofile.php
 * @param OCI_Lob|OCILob $lob_descriptor
 * @param string $filename <p>Path to the file.</p>
 * @param int $start [optional] <p>Indicates from where to start exporting.</p>
 * @param int $length [optional] <p>Indicates the length of data to be exported.</p>
 * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
#[Deprecated(replacement: "OCI_Lob::export", since: "5.4")]
function ociwritelobtofile(
    #[LanguageLevelTypeAware(['8.0' => 'OCILob'], default: 'OCI_Lob')] $lob_descriptor,
    $filename,
    $start,
    $length
) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see OCI_Lob::load}
 * @link https://php.net/manual/en/function.ociloadlob.php
 * @param OCI_Lob|OCILob $lob_descriptor
 * @return string|false <p>Returns the contents of the object, or <b>FALSE</b> on errors.</p>
 */
#[Deprecated(replacement: "OCI_Lob::load", since: "5.4")]
function ociloadlob(#[LanguageLevelTypeAware(['8.0' => 'OCILob'], default: 'OCI_Lob')] $lob_descriptor) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see oci_commit}
 * @link https://php.net/manual/en/function.ocicommit.php
 * @param $connection_resource <p>
 * An Oracle connection identifier, returned by
 * {@see oci_connect()},
 * {@see oci_pconnect()}, or
 * {@see oci_new_connect()}.
 * </p>
 * @return bool <p>Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.</p>
 */
#[Deprecated(replacement: "oci_commit", since: "5.4")]
function ocicommit($connection_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see oci_rollback}
 * @link https://php.net/manual/en/function.ocirollback.php
 * @param resource $connection_resource
 * @return bool <p>Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.</p>
 */
#[Deprecated(replacement: "oci_rollback", since: "5.4")]
function ocirollback($connection_resource) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see oci_new_descriptor}
 * @link https://php.net/manual/en/function.ocinewdescriptor.php
 * @param resource $connection_resource <p>
 * An Oracle connection identifier, returned by
 * {@see oci_connect()} or {@see oci_pconnect()}.
 * </p>
 * @param $type [optional] <p>Valid values for type are: <b>OCI_DTYPE_FILE</b>, <b>OCI_DTYPE_LOB</b> and <b>OCI_DTYPE_ROWID</b>.</p>
 * @return OCI_LOB|false Returns a new LOB or FILE descriptor on success, FALSE on error.
 */
#[Deprecated(replacement: "oci_new_descriptor", since: "5.4")]
#[LanguageLevelTypeAware(['8.0' => 'OCILob|false'], default: 'OCI_Lob|false')]
function ocinewdescriptor($connection_resource, $type = OCI_DTYPE_LOB) {}

/**
 * (PHP 4, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see oci_set_prefetch}
 * @link https://php.net/manual/en/function.ocisetprefetch.php
 * @param resource $statement_resource <p>A valid OCI8 statement
 * identifier created by
 * {@see oci_parse()} and executed
 * by
 * {@see oci_execute()}, or a <em>REF CURSOR</em> statement identifier.</p>
 * @param $number_of_rows
 * @return bool <p>Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.</p>
 */
#[Deprecated(replacement: "oci_set_prefetch", since: "5.4")]
function ocisetprefetch($statement_resource, $number_of_rows) {}

/**
 * (PHP 5, PECL OCI8 &gt;= 1.1.0)<br/>
 * Changes password of Oracle's user
 * @param resource|string $connection_resource_or_connection_string_or_dbname <p>An Oracle connection identifier, returned by
 * {@see oci_connect()} or
 * {@see oci_pconnect()}.</p>
 * @param string $username <p>The Oracle user name.</p>
 * @param string $old_password <p>The new password to be set.</p>
 * @param string $new_password <p>The new password to be set.</p>
 * @return resource|bool <p>Returns <b>TRUE</b> on success or <b>FALSE</b> on failure or <b>resource</b>, depending on the function parameters.</p>
 */
function ocipasswordchange($connection_resource_or_connection_string_or_dbname, $username, $old_password, $new_password) {}

/**
 * (PHP 4 &gt;= 4.0.7, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see OCI_Collection::free}
 * @link https://php.net/manual/en/function.ocifreecollection.php
 * @param OCI_Collection|OCICollection $collection
 * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
#[Deprecated(replacement: "OCI_Collection::free", since: "5.4")]
function ocifreecollection(#[LanguageLevelTypeAware(['8.0' => 'OCICollection'], default: 'OCI_Collection')] $collection) {}

/**
 * (PHP 4 &gt;= 4.0.6, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see oci_new_collection}
 * @link https://php.net/manual/en/function.ocinewcollection.php
 * @param $connection_resource <p>
 * An Oracle connection identifier, returned by
 * {@see oci_connect()} or
 * {@see oci_pconnect()}.
 * @param $tdo <p>Should be a valid named type (uppercase).</p>
 * @param $schema <p>Should point to the scheme, where the named type was created. The name of the current user is the default value.</p>
 * </p>
 * @return OCI_Collection|false <p>Returns a new OCI_Collection object or FALSE on error.</p>
 */
#[Deprecated(replacement: "oci_new_collection", since: "5.4")]
#[LanguageLevelTypeAware(['8.0' => 'OCICollection|false'], default: 'OCI_Collection|false')]
function ocinewcollection($connection_resource, $tdo, $schema = null) {}

/**
 * (PHP 4 &gt;= 4.0.6, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * (@see OCI_Collection::append)
 * @link https://php.net/manual/en/function.ocicollappend.php
 * @param OCI_Collection $collection
 * @param mixed $value <p>The value to be added to the collection. Can be a string or a number.</p>
 * @return bool <p>Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.</p>
 */
#[Deprecated(replacement: "OCI_Collection::append", since: "5.4")]
function ocicollappend(#[LanguageLevelTypeAware(['8.0' => 'OCICollection'], default: 'OCI_Collection')] $collection, $value) {}

/**
 * (PHP 4 &gt;= 4.0.6, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see OCI_COLLection::getElem}
 * @link https://php.net/manual/en/function.ocicollgetelem.php
 * @param OCI_Collection $collection
 * @param int $index <p>The element index. First index is 0.</p>
 * @return mixed <p>Returns <b>FALSE</b> if such element doesn't exist; <b>NULL</b> if element is <b>NULL</b>; string if element is column of a string datatype or number if element is numeric field.</p>
 */
#[Deprecated(replacement: "OCI_COLLection::getElem", since: "5.4")]
function ocicollgetelem(#[LanguageLevelTypeAware(['8.0' => 'OCICollection'], default: 'OCI_Collection')] $collection, $index) {}

/**
 * (PHP 4 &gt;= 4.0.6, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of {@see OCI_Collection::assignElem}
 * @link https://php.net/manual/en/function.ocicollassignelem.php
 * @param OCI_Collection $collection
 * @param $index <p>The element index. First index is 0.</p>
 * @param $value <p>Can be a string or a number.</p>
 * @return bool <p>Returns TRUE on success or FALSE on failure.</p>
 */
#[Deprecated(replacement: "OCI_Collection::assignElem", since: "5.4")]
function ocicollassignelem(#[LanguageLevelTypeAware(['8.0' => 'OCICollection'], default: 'OCI_Collection')] $collection, $index, $value) {}

/**
 * (PHP 4 &gt;= 4.0.6, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see OCI_COLLection::size}
 * @link https://php.net/manual/en/function.ocicollsize.php
 * @param OCI_Collection $collection
 * @return int|false <p>Returns the number of elements in the collection or <b>FALSE</b> on error.</p>
 */
#[Deprecated(replacement: "OCI_COLLection::size", since: "5.4")]
function ocicollsize(#[LanguageLevelTypeAware(['8.0' => 'OCICollection'], default: 'OCI_Collection')] $collection) {}

/**
 * (PHP 4 &gt;= 4.0.6, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see OCI_COLLection::max}
 * @link https://php.net/manual/en/function.ocicollmax.php
 * @param OCI_Collection $collection
 * @return int|false <p> Returns the maximum number as an integer, or <b>FALSE</b> on errors.
 * If the returned value is 0, then the number of elements is not limited.</p>
 */
#[Deprecated(replacement: "OCI_COLLection::max", since: "5.4")]
function ocicollmax(#[LanguageLevelTypeAware(['8.0' => 'OCICollection'], default: 'OCI_Collection')] $collection) {}

/**
 * (PHP 4 &gt;= 4.0.6, PHP 5, PECL OCI8 &gt;= 1.0.0)<br/>
 * Alias of
 * {@see OCI_Collection::trim}
 * @link https://php.net/manual/en/function.ocicolltrim.php
 * @param OCI_Collection $collection
 * @param int|float $number
 * @return bool Returns <b>TRUE</b> or <b>FALSE</b> on failure.
 */
#[Deprecated(replacement: "OCI_Collection::trim", since: "5.4")]
function ocicolltrim(#[LanguageLevelTypeAware(['8.0' => 'OCICollection'], default: 'OCI_Collection')] $collection, $number) {}

/**
 * (PHP 4 &gt;= 4.0.6, PECL OCI8 1.0)
 * Writes a temporary large object
 * Alias of {@see OCI-Lob::writeTemporary()}
 * @link https://php.net/manual/en/function.ociwritetemporarylob.php
 * @param OCI_Lob|OCILob $lob_descriptor
 * @param string $data <p>The data to write.</p>
 * @param int $lob_type <p>
 * Can be one of the following:
 * </p><ul>
 * <li>
 * <b>OCI_TEMP_BLOB</b> is used to create temporary BLOBs
 * </li>
 * <li>
 * <b>OCI_TEMP_CLOB</b> is used to create
 * temporary CLOBs
 * </li>
 * </ul>
 * @return bool <p>Returns TRUE on success or FALSE on failure.</p>
 */
#[Deprecated(replacement: "OCI-Lob::writeTemporary", since: "5.4")]
function ociwritetemporarylob(
    #[LanguageLevelTypeAware(['8.0' => 'OCILob'], default: 'OCI_Lob')] $lob_descriptor,
    $data,
    $lob_type = OCI_TEMP_CLOB
) {}

/**
 * (PHP 4 &gt;= 4.0.6, PECL OCI8 1.0)
 * Alias of {@see OCI-Lob::close()}
 * @link https://php.net/manual/en/function.ocicloselob.php
 * @param OCI_Lob|OCILob $lob_descriptor
 * @return bool <p>Returns TRUE on success or FALSE on failure.</p>
 */
#[Deprecated(replacement: "OCI-Lob::close()", since: "5.4")]
function ocicloselob(#[LanguageLevelTypeAware(['8.0' => 'OCILob'], default: 'OCI_Lob')] $lob_descriptor) {}

/**
 * (PHP 4 >= 4.0.6, PECL OCI8 1.0)
 * Alias of {@see OCI-Collection::assign()}
 * Assigns a value to the collection from another existing collection
 * @link https://php.net/manual/en/function.ocicollassign.php
 * @param OCI_Collection $to
 * @param OCI_Collection $from An instance of OCI-Collection.
 * @return bool <p>Returns TRUE on success or FALSE on failure.</p>
 */
#[Deprecated(replacement: "OCI-Collection::assign", since: "5.4")]
function ocicollassign(
    #[LanguageLevelTypeAware(['8.0' => 'OCICollection'], default: 'OCI_Collection')] $to,
    #[LanguageLevelTypeAware(['8.0' => 'OCICollection'], default: 'OCI_Collection')] $from
) {}
/**
 * See <b>OCI_NO_AUTO_COMMIT</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_DEFAULT', 0);

/**
 * Used with {@see oci_connect} to connect with
 * the SYSOPER privilege. The <i>php.ini</i> setting
 * oci8.privileged_connect
 * should be enabled to use this.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_SYSOPER', 4);

/**
 * Used with {@see oci_connect} to connect with
 * the SYSDBA privilege. The <i>php.ini</i> setting
 * oci8.privileged_connect
 * should be enabled to use this.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_SYSDBA', 2);

/**
 * Used with {@see oci_connect} for using
 * Oracles' External or OS authentication. Introduced in PHP
 * 5.3 and PECL OCI8 1.3.4.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_CRED_EXT', -2147483648);

/**
 * Statement execution mode
 * for {@see oci_execute}. Use this mode if you
 * want meta data such as the column names but don't want to
 * fetch rows from the query.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_DESCRIBE_ONLY', 16);

/**
 * Statement execution mode for {@see oci_execute}
 * call. Automatically commit changes when the statement has
 * succeeded.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_COMMIT_ON_SUCCESS', 32);

/**
 * Statement execution mode
 * for {@see oci_execute}. The transaction is not
 * automatically committed when using this mode. For
 * readability in new code, use this value instead of the
 * older, equivalent <b>OCI_DEFAULT</b> constant.
 * Introduced in PHP 5.3.2 (PECL OCI8 1.4).
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_NO_AUTO_COMMIT', 0);

/**
 * Obsolete. Statement fetch mode. Used when the application
 * knows in advance exactly how many rows it will be fetching.
 * This mode turns prefetching off for Oracle release 8 or
 * later mode. The cursor is canceled after the desired rows
 * are fetched which may result in reduced server-side
 * resource usage.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_EXACT_FETCH', 2);

/**
 * Used with to set the seek position.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_SEEK_SET', 0);

/**
 * Used with to set the seek position.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_SEEK_CUR', 1);

/**
 * Used with to set the seek position.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_SEEK_END', 2);

/**
 * Used with to free
 * buffers used.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_LOB_BUFFER_FREE', 1);

/**
 * The same as <b>OCI_B_BFILE</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_BFILEE', 114);

/**
 * The same as <b>OCI_B_CFILEE</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_CFILEE', 115);

/**
 * The same as <b>OCI_B_CLOB</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_CLOB', 112);

/**
 * The same as <b>OCI_B_BLOB</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_BLOB', 113);

/**
 * The same as <b>OCI_B_ROWID</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_RDD', 104);

/**
 * The same as <b>OCI_B_INT</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_INT', 3);

/**
 * The same as <b>OCI_B_NUM</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_NUM', 2);

/**
 * The same as <b>OCI_B_CURSOR</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_RSET', 116);

/**
 * Used with {@see oci_bind_array_by_name} to bind arrays of
 * CHAR.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_AFC', 96);

/**
 * Used with {@see oci_bind_array_by_name} to bind arrays of
 * VARCHAR2.
 * Also used with {@see oci_bind_by_name}.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_CHR', 1);

/**
 * Used with {@see oci_bind_array_by_name} to bind arrays of
 * VARCHAR.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_VCS', 9);

/**
 * Used with {@see oci_bind_array_by_name} to bind arrays of
 * VARCHAR2.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_AVC', 97);

/**
 * Used with {@see oci_bind_array_by_name} to bind arrays of
 * STRING.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_STR', 5);

/**
 * Used with {@see oci_bind_array_by_name} to bind arrays of
 * LONG VARCHAR.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_LVC', 94);

/**
 * Used with {@see oci_bind_array_by_name} to bind arrays of
 * FLOAT.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_FLT', 4);

/**
 * Not supported.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_UIN', 68);

/**
 * Used with {@see oci_bind_by_name} to bind LONG values.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_LNG', 8);

/**
 * Used with {@see oci_bind_by_name} to bind LONG RAW values.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_LBI', 24);

/**
 * The same as <b>OCI_B_BIN</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_BIN', 23);

/**
 * Used with {@see oci_bind_array_by_name} to bind arrays of
 * LONG.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_ODT', 156);

/**
 * Not supported.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_BDOUBLE', 22);

/**
 * Not supported.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_BFLOAT', 21);

/**
 * Used with {@see oci_bind_by_name} when binding
 * named data types. Note: in PHP &lt; 5.0 it was called
 * <b>OCI_B_SQLT_NTY</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_B_NTY', 108);

/**
 * The same as <b>OCI_B_NTY</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_NTY', 108);

/**
 * Obsolete.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_SYSDATE', "SYSDATE");

/**
 * Used with {@see oci_bind_by_name} when binding
 * BFILEs.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_B_BFILE', 114);

/**
 * Used with {@see oci_bind_by_name} when binding
 * CFILEs.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_B_CFILEE', 115);

/**
 * Used with {@see oci_bind_by_name} when binding
 * CLOBs.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_B_CLOB', 112);

/**
 * Used with {@see oci_bind_by_name} when
 * binding BLOBs.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_B_BLOB', 113);

/**
 * Used with {@see oci_bind_by_name} when binding
 * ROWIDs.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_B_ROWID', 104);

/**
 * Used with {@see oci_bind_by_name} when binding
 * cursors, previously allocated
 * with {@see oci_new_descriptor}.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_B_CURSOR', 116);

/**
 * Used with {@see oci_bind_by_name} to bind RAW values.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_B_BIN', 23);

/**
 * Used with {@see oci_bind_array_by_name} to bind arrays of
 * INTEGER.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_B_INT', 3);

/**
 * Used with {@see oci_bind_array_by_name} to bind arrays of
 * NUMBER.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_B_NUM', 2);

/**
 * Default mode of {@see oci_fetch_all}.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_FETCHSTATEMENT_BY_COLUMN', 16);

/**
 * Alternative mode of {@see oci_fetch_all}.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_FETCHSTATEMENT_BY_ROW', 32);

/**
 * Used with {@see oci_fetch_all} and
 * {@see oci_fetch_array} to get results as an associative
 * array.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_ASSOC', 1);

/**
 * Used with {@see oci_fetch_all} and
 * {@see oci_fetch_array} to get results as an
 * enumerated array.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_NUM', 2);

/**
 * Used with {@see oci_fetch_all} and
 * {@see oci_fetch_array} to get results as an
 * array with both associative and number indices.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_BOTH', 3);

/**
 * Used with {@see oci_fetch_array} to get empty
 * array elements if the row items value is <b>NULL</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_RETURN_NULLS', 4);

/**
 * Used with {@see oci_fetch_array} to get the
 * data value of the LOB instead of the descriptor.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_RETURN_LOBS', 8);

/**
 * This flag tells {@see oci_new_descriptor} to
 * initialize a new FILE descriptor.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_DTYPE_FILE', 56);

/**
 * This flag tells {@see oci_new_descriptor} to
 * initialize a new LOB descriptor.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_DTYPE_LOB', 50);

/**
 * This flag tells {@see oci_new_descriptor} to
 * initialize a new ROWID descriptor.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_DTYPE_ROWID', 54);

/**
 * The same as <b>OCI_DTYPE_FILE</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_D_FILE', 56);

/**
 * The same as <b>OCI_DTYPE_LOB</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_D_LOB', 50);

/**
 * The same as <b>OCI_DTYPE_ROWID</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_D_ROWID', 54);

/**
 * Used with
 * to indicate that a temporary CLOB should be created.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_TEMP_CLOB', 2);

/**
 * Used with
 * to indicate that a temporary BLOB should be created.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_TEMP_BLOB', 1);

/**
 * (PECL OCI8 &gt;= 2.0.7)<br/>
 * The same as <b>OCI_B_BOL</b>.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('SQLT_BOL', 252);

/**
 * (PECL OCI8 &gt;= 2.0.7)<br/>
 * Used with {@see oci_bind_by_name} when
 * binding PL/SQL BOOLEAN.
 * @link https://php.net/manual/en/oci8.constants.php
 */
define('OCI_B_BOL', 252);

// End of oci8 v.2.0.7
