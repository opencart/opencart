<?php

// Start of judy.

/**
 * Class Judy.
 * @link https://php.net/manual/en/class.judy.php
 */
class Judy implements ArrayAccess
{
    /**
     * Define the Judy Array as a Bitset with keys as Integer and Values as a Boolean.
     * @link https://php.net/manual/en/class.judy.php#judy.constants.bitset
     */
    public const BITSET = 1;

    /**
     * Define the Judy Array with key/values as Integer, and Integer only.
     * @link https://php.net/manual/en/class.judy.php#judy.constants.int-to-int
     */
    public const INT_TO_INT = 2;

    /**
     * Define the Judy Array with keys as Integer and Values of any type.
     * @link https://php.net/manual/en/class.judy.php#judy.constants.int-to-mixed
     */
    public const INT_TO_MIXED = 3;

    /**
     * Define the Judy Array with keys as a String and Values as Integer, and Integer only.
     * @link https://php.net/manual/en/class.judy.php#judy.constants.string-to-int
     */
    public const STRING_TO_INT = 4;

    /**
     * Define the Judy Array with keys as a String and Values of any type.
     * @link https://php.net/manual/en/class.judy.php#judy.constants.string-to-mixed
     */
    public const STRING_TO_MIXED = 5;

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Construct a new Judy object. A Judy object can be accessed like a PHP Array.
     * @link https://php.net/manual/en/judy.construct.php
     * @param int $judy_type <p>The Judy type to be used.</p>
     */
    public function __construct($judy_type) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Destruct a Judy object.
     * @link https://php.net/manual/en/judy.destruct.php
     */
    public function __destruct() {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Locate the Nth index present in the Judy array.
     * @link https://php.net/manual/en/judy.bycount.php
     * @param int $nth_index <p>Nth index to return. If nth_index equal 1, then it will return the first index in the array.</p>
     * @return int <p>Return the index at the given Nth position.</p>
     */
    public function byCount($nth_index) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Count the number of elements in the Judy array.
     * @link https://php.net/manual/en/judy.count.php
     * @param int $index_start [optional] <p>Start counting from the given index. Default is first index.</p>
     * @param int $index_end [optional] <p>Stop counting when reaching this index. Default is last index.</p>
     * @return int <p>Return the number of elements.</p>
     */
    public function count($index_start = 0, $index_end = -1) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Search (inclusive) for the first index present that is equal to or greater than the passed Index.
     * @link https://php.net/manual/en/judy.first.php
     * @param mixed $index [optional] <p>The index can be an integer or a string corresponding to the index where to start the search.</p>
     * @return mixed <p>Return the corresponding index in the array.</p>
     */
    public function first($index = 0) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Search (inclusive) for the first absent index that is equal to or greater than the passed Index.
     * @link https://php.net/manual/en/judy.firstempty.php
     * @param mixed $index [optional] <p>The index can be an integer or a string corresponding to the index where to start the search.</p>
     * @return mixed <p>Return the corresponding index in the array.</p>
     */
    public function firstEmpty($index = 0) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Free the entire Judy array.
     * @link https://php.net/manual/en/judy.free.php
     */
    public function free() {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Return an integer corresponding to the Judy type of the current object.
     * @link https://php.net/manual/en/judy.gettype.php
     * @return int <p>Return an integer corresponding to a Judy type.</p>
     */
    public function getType() {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Search (inclusive) for the last index present that is equal to or less than the passed Index.
     * @link https://php.net/manual/en/judy.last.php
     * @param int|string $index [optional] <p>The index can be an integer or a string corresponding to the index where to start the search.</p>
     * @return mixed <p>Return the corresponding index in the array.</p>
     */
    public function last($index = -1) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Search (inclusive) for the last absent index that is equal to or less than the passed Index.
     * @link https://php.net/manual/en/judy.lastempty.php
     * @param int|string $index [optional] <p>The index can be an integer or a string corresponding to the index where to start the search.</p>
     * @return mixed <p>Return the corresponding index in the array.</p>
     */
    public function lastEmpty($index = -1) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Return the memory used by the Judy array.
     * @link https://php.net/manual/en/judy.memoryusage.php
     * @return int <p>Return the memory used in bytes.</p>
     */
    public function memoryUsage() {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Search (exclusive) for the next index present that is greater than the passed Index.
     * @link https://php.net/manual/en/judy.next.php
     * @param mixed $index <p>The index can be an integer or a string corresponding to the index where to start the search.</p>
     * @return mixed <p>Return the corresponding index in the array.</p>
     */
    public function next($index) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Search (exclusive) for the next absent index that is greater than the passed Index.
     * @link https://php.net/manual/en/judy.nextempty.php
     * @param int|string $index <p>The index can be an integer or a string corresponding to the index where to start the search.</p>
     * @return mixed <p>Return the corresponding index in the array.</p>
     */
    public function nextEmpty($index) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Whether or not an offset exists.
     * @link https://php.net/manual/en/judy.offsetexists.php
     * @param mixed $offset <p>An offset to check for.</p>
     * @return bool <p>Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.</p>
     */
    public function offsetExists($offset) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Returns the value at specified offset.
     * @link https://php.net/manual/en/judy.offsetget.php
     * @param mixed $offset <p>An offset to check for.</p>
     * @return mixed <p>Can return all value types.</p>
     */
    public function offsetGet($offset) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Assigns a value to the specified offset.
     * @link https://php.net/manual/en/judy.offsetset.php
     * @param mixed $offset <p>The offset to assign the value to.</p>
     * @param mixed $value <p>The value to set.</p>
     */
    public function offsetSet($offset, $value) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Unsets an offset.
     * @link https://php.net/manual/en/judy.offsetunset.php
     * @param mixed $offset <p>The offset to assign the value to.</p>
     */
    public function offsetUnset($offset) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Search (exclusive) for the previous index present that is less than the passed Index.
     * @link https://php.net/manual/en/judy.prev.php
     * @param mixed $index <p>The index can be an integer or a string corresponding to the index where to start the search.</p>
     * @return mixed <p>Return the corresponding index in the array.</p>
     */
    public function prev($index) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Search (exclusive) for the previous index absent that is less than the passed Index.
     * @link https://php.net/manual/en/judy.prevempty.php
     * @param mixed $index <p>The index can be an integer or a string corresponding to the index where to start the search.</p>
     * @return mixed <p>Return the corresponding index in the array.</p>
     */
    public function prevEmpty($index) {}

    /**
     * (PECL judy &gt;= 0.1.1)<br/>
     * Count the number of elements in the Judy array.<br/>
     * This method is an alias of      const count.
     * @link https://php.net/manual/en/judy.size.php
     * @param int $index_start [optional] <p>Start counting from the given index. Default is first index.</p>
     * @param int $index_end [optional] <p>Stop counting when reaching this index. Default is last index.</p>
     * @return int <p>Return the number of elements.</p>
     */
    public function size($index_start = 0, $index_end = -1) {}
}

// End of judy.
