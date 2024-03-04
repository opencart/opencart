<?php

// Start of oci8 v.3

/**
 * OCI8 LOB functionality for large binary (BLOB) and character (CLOB) objects.
 * @link https://php.net/manual/en/class.OCI-Lob.php
 * @since 8.0
 */
class OCILob
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
     * @param OCILob $lob_from <p>
     * The copied LOB.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function append(OCILob $lob_from) {}

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
     * Alias of {@see OCILob::export}
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
     * Alias of {@see OCILob::import}
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
 * @link https://php.net/manual/en/class.OCICollection.php
 * @since 8.0
 */
class OCICollection
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
     * @param OCICollection $from <p>
     * An instance of OCICollection.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function assign(OCICollection $from) {}

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
 * (PHP 7.2 &gt;= 7.2.14, PHP 8, PHP 7 &gt;= 7.3.1, PHP 8, PECL OCI8 &gt;= 2.2.0)<br/>
 * Sets a millisecond timeout for database calls
 * @link https://php.net/manual/en/function.oci-set-call-timout.php
 * @param resource $connection <p>An Oracle connection identifier,
 * returned by {@see oci_connect}, {@see oci_pconnect},
 * or {@see oci_new_connect}.</p>
 * @param int $time_out <p>The maximum time in milliseconds that any
 * single round-trip between PHP and Oracle Database may take.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_set_call_timeout($connection, int $time_out) {}

/**
 * (PHP 7 &gt;== 7.2.14, PHP 8, PHP 7 &gt;= 7.3.1, PHP 8, PECL OCI8 &gt;= 2.2.0)
 * Sets the database operation
 * @link https://www.php.net/manual/en/function.oci-set-db-operation.php
 * @param resource $connection <p>An Oracle connection identifier,
 * returned by {@see oci_connect}, {@see oci_pconnect},
 * or {@see oci_new_connect}.</p>
 * @param string $dbop <p>User chosen string.</p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function oci_set_db_operation($connection, string $dbop) {}
