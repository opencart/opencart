<?php
/**
 * Stubs for WinCache extension by Microsoft (v1.1.0)
 * @link https://secure.php.net/manual/en/book.wincache.php
 * Windows Cache Extension for PHP is a PHP accelerator that is used to increase
 * the speed of PHP applications running on Windows and Windows Server.
 * Requirements:
 *   IIS WebServer
 *   FastCGI mode
 *   PHP 5.2.X, Non-thread-safe build
 *   PHP 5.3 X86, Non-thread-safe VC9 build
 *
 * Author: Andriy Bazanov
 * Date  : 2010-09-30
 */

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.0.0)<br/>
 * Retrieves information about files cached in the file cache
 * @link https://secure.php.net/manual/en/function.wincache-fcache-fileinfo.php
 * @param bool $summaryonly [optional]
 * <p>Controls whether the returned array will contain information about individual
 * cache entries along with the file cache summary.</p>
 * @return array|false Array of meta data about file cache or FALSE on failure
 * <p>The array returned by this function contains the following elements:
 * <ul>
 * <li><em>total_cache_uptime</em> - total time in seconds that the file cache has been active</li>
 * <li><em>total_file_count</em> - total number of files that are currently in the file cache</li>
 * <li><em>total_hit_count</em> - number of times the files have been served from the file cache</li>
 * <li><em>total_miss_count</em> - number of times the files have not been found in the file cache</li>
 * <li><em>file_entries</em> - an array that contains the information about all the cached files:
 * <ul>
 * <li><em>file_name</em> - absolute file name of the cached file</li>
 * <li><em>add_time</em> - time in seconds since the file has been added to the file cache</li>
 * <li><em>use_time</em> - time in seconds since the file has been accessed in the file cache</li>
 * <li><em>last_check</em> - time in seconds since the file has been checked for modifications</li>
 * <li><em>hit_count</em> - number of times the file has been served from the cache</li>
 * <li><em>file_size</em> - size of the cached file in bytes</li>
 * </ul></li>
 * </ul></p>
 */
function wincache_fcache_fileinfo($summaryonly = false) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.0.0)<br/>
 * Retrieves information about memory usage by file cache.
 * @link https://secure.php.net/manual/en/function.wincache-fcache-meminfo.php
 * @return array|false Array of meta data about file cache memory usage or FALSE on failure
 * <p>The array returned by this function contains the following elements:
 * <ul>
 * <li><em>memory_total</em> - amount of memory in bytes allocated for the file cache</li>
 * <li><em>memory_free</em> - amount of free memory in bytes available for the file cache</li>
 * <li><em>num_used_blks</em> - number of memory blocks used by the file cache</li>
 * <li><em>num_free_blks</em> - number of free memory blocks available for the file cache</li>
 * <li><em>memory_overhead</em> - amount of memory in bytes used for the file cache internal structures</li>
 * </ul></p>
 */
function wincache_fcache_meminfo() {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Obtains an exclusive lock on a given key.
 * <p>The execution of the current script will be blocked until the lock can be
 * obtained. Once the lock is obtained, the other scripts that try to request the
 * lock by using the same key will be blocked, until the current script releases
 * the lock by using wincache_unlock().</p>
 * @link https://secure.php.net/manual/en/function.wincache-lock.php
 * @param string $key Name of the key in the cache to get the lock on.
 * @param bool $isglobal [optional]
 * <p>Controls whether the scope of the lock is system-wide or local. Local locks
 * are scoped to the application pool in IIS FastCGI case or to all php processes
 * that have the same parent process identifier. </p>
 * @return bool Returns TRUE on success or FALSE on failure.
 */
function wincache_lock($key, $isglobal = false) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.0.0)<br/>
 * Retrieves information about opcode cache content and its usage
 * @link https://secure.php.net/manual/en/function.wincache-ocache-fileinfo.php
 * @param bool $summaryonly [optional]
 * <p>Controls whether the returned array will contain information about individual
 * cache entries along with the opcode cache summary.</p>
 * @return array|false Array of meta data about opcode cache or FALSE on failure
 * <p>The array returned by this function contains the following elements:
 * <ul>
 * <li><em>total_cache_uptime</em> - total time in seconds that the opcode cache has been active</li>
 * <li><em>total_file_count</em> - total number of files that are currently in the opcode cache</li>
 * <li><em>total_hit_count</em> - number of times the compiled opcode have been served from the cache</li>
 * <li><em>total_miss_count</em> - number of times the compiled opcode have not been found in the cache</li>
 * <li><em>is_local_cache</em> - true is the cache metadata is for a local cache instance, false
 * if the metadata is for the global cache</li>
 * <li><em>file_entries</em> - an array that contains the information about all the cached files:
 * <ul>
 * <li><em>file_name</em> - absolute file name of the cached file</li>
 * <li><em>add_time</em> - time in seconds since the file has been added to the opcode cache</li>
 * <li><em>use_time</em> - time in seconds since the file has been accessed in the opcode cache</li>
 * <li><em>last_check</em> - time in seconds since the file has been checked for modifications</li>
 * <li><em>hit_count</em> - number of times the file has been served from the cache</li>
 * <li><em>function_count</em> - number of functions in the cached file</li>
 * <li><em>class_count</em> - number of classes in the cached file</li>
 * </ul></li>
 * </ul></p>
 */
function wincache_ocache_fileinfo($summaryonly = false) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.0.0)<br/>
 * Retrieves information about memory usage by opcode cache.
 * @link https://secure.php.net/manual/en/function.wincache-ocache-meminfo.php
 * @return array|false Array of meta data about opcode cache memory usage or FALSE on failure
 * <p>The array returned by this function contains the following elements:
 * <ul>
 * <li><em>memory_total</em> - amount of memory in bytes allocated for the opcode cache</li>
 * <li><em>memory_free</em> - amount of free memory in bytes available for the opcode cache</li>
 * <li><em>num_used_blks</em> - number of memory blocks used by the opcode cache</li>
 * <li><em>num_free_blks</em> - number of free memory blocks available for the opcode cache</li>
 * <li><em>memory_overhead</em> - amount of memory in bytes used for the opcode cache internal structures</li>
 * </ul></p>
 */
function wincache_ocache_meminfo() {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.0.0)<br/>
 * Refreshes the cache entries for the files, whose names were passed in the input argument.
 * <p>If no argument is specified then refreshes all the entries in the cache.</p>
 * @link https://secure.php.net/manual/en/function.wincache-refresh-if-changed.php
 * @param array $files [optional]
 * <p>An array of file names for files that need to be refreshed. An absolute
 * or relative file paths can be used.</p>
 * @return bool Returns TRUE on success or FALSE on failure.
 */
function wincache_refresh_if_changed(array $files) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.0.0)<br/>
 * Retrieves information about cached mappings between relative file paths and
 * corresponding absolute file paths.
 * @link https://secure.php.net/manual/en/function.wincache-rplist-fileinfo.php
 * @return array|false Array of meta data about the resolve file path cache or FALSE on failure
 * <p>The array returned by this function contains the following elements:
 * <ul>
 * <li><em>total_file_count</em> - total number of file path mappings stored in the cache</li>
 * <li><em>rplist_entries</em> - an array that contains the information about all the cached file paths:
 * <ul>
 * <li><em>resolve_path</em> - path to a file</li>
 * <li><em>subkey_data</em> - corresponding absolute path to a file</li>
 * </ul></li>
 * </ul></p>
 */
function wincache_rplist_fileinfo() {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.0.0)<br/>
 * Retrieves information about memory usage by resolve file path cache.
 * @link https://secure.php.net/manual/en/function.wincache-rplist-meminfo.php
 * @return array|false Array of meta data that describes memory usage by resolve file path cache. or FALSE on failure
 * <p>The array returned by this function contains the following elements:
 * <ul>
 * <li><em>memory_total</em> - amount of memory in bytes allocated for the resolve file path cache</li>
 * <li><em>memory_free</em> - amount of free memory in bytes available for the resolve file path cache</li>
 * <li><em>num_used_blks</em> - number of memory blocks used by the resolve file path cache</li>
 * <li><em>num_free_blks</em> - number of free memory blocks available for the resolve file path cache</li>
 * <li><em>memory_overhead</em> - amount of memory in bytes used for the internal structures of resolve file path cache</li>
 * </ul></p>
 */
function wincache_rplist_meminfo() {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Retrieves information about session cache content and its usage.
 * @link https://secure.php.net/manual/en/function.wincache-scache-info.php
 * @param bool $summaryonly [optional]
 * <p>Controls whether the returned array will contain information about individual
 * cache entries along with the session cache summary.</p>
 * @return array|false Array of meta data about session cache or FALSE on failure
 * <p>The array returned by this function contains the following elements:
 * <ul>
 * <li><em>total_cache_uptime</em> - total time in seconds that the session cache has been active</li>
 * <li><em>total_item_count</em> - total number of elements that are currently in the session cache</li>
 * <li><em>is_local_cache</em> - true is the cache metadata is for a local cache instance, false
 * if the metadata is for the global cache</li>
 * <li><em>total_hit_count</em> - number of times the data has been served from the cache</li>
 * <li><em>total_miss_count</em> - number of times the data has not been found in the cache</li>
 * <li><em>scache_entries</em> - an array that contains the information about all the cached items:
 * <ul>
 * <li><em>key_name</em> - name of the key which is used to store the data</li>
 * <li><em>value_type</em> - type of value stored by the key</li>
 * <li><em>use_time</em> - time in seconds since the file has been accessed in the opcode cache</li>
 * <li><em>last_check</em> - time in seconds since the file has been checked for modifications</li>
 * <li><em>ttl_seconds</em> - time remaining for the data to live in the cache, 0 meaning infinite</li>
 * <li><em>age_seconds</em> - time elapsed from the time data has been added in the cache</li>
 * <li><em>hitcount</em> - number of times data has been served from the cache</li>
 * </ul></li>
 * </ul></p>
 */
function wincache_scache_info($summaryonly = false) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Retrieves information about memory usage by session cache.
 * @link https://secure.php.net/manual/en/function.wincache-scache-meminfo.php
 * @return array|false Array of meta data about session cache memory usage or FALSE on failure
 * <p>The array returned by this function contains the following elements:
 * <ul>
 * <li><em>memory_total</em> - amount of memory in bytes allocated for the session cache</li>
 * <li><em>memory_free</em> - amount of free memory in bytes available for the session cache</li>
 * <li><em>num_used_blks</em> - number of memory blocks used by the session cache</li>
 * <li><em>num_free_blks</em> - number of free memory blocks available for the session cache</li>
 * <li><em>memory_overhead</em> - amount of memory in bytes used for the session cache internal structures</li>
 * </ul></p>
 */
function wincache_scache_meminfo() {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Adds a variable in user cache, only if this variable doesn't already exist in the cache.
 * <p>The added variable remains in the user cache unless its time to live expires
 * or it is deleted by using wincache_ucache_delete() or wincache_ucache_clear() functions.</p>
 * @link https://secure.php.net/manual/en/function.wincache-ucache-add.php
 * @param string $key <p>Store the variable using this key name. If a variable with
 * same key is already present the function will fail and return FALSE. key is case
 * sensitive. To override the value even if key is present use wincache_ucache_set()
 * function instad. key can also take array of name =&gt; value pairs where names will
 * be used as keys. This can be used to add multiple values in the cache in one
 * operation, thus avoiding race condition.</p>
 * @param mixed $value <p>Value of a variable to store. Value supports all data
 * types except resources, such as file handles. This parameter is ignored if
 * first argument is an array. A general guidance is to pass NULL as value while
 * using array as key.</p>
 * @param int $ttl [optional]
 * <p>Time for the variable to live in the cache in seconds. After the value
 * specified in ttl has passed the stored variable will be deleted from the
 * cache. This parameter takes a default value of 0 which means the variable
 * will stay in the cache unless explicitly deleted by using wincache_ucache_delete()
 * or wincache_ucache_clear() functions.</p>
 * @return bool If key is string, the function returns TRUE on success and FALSE on failure.
 * <p>If key is an array, the function returns:
 * <ul>
 * <li>If all the name =&gt; value pairs in the array can be set, function returns an empty array;</li>
 * <li>If all the name =&gt; value pairs in the array cannot be set, function returns FALSE;</li>
 * <li>If some can be set while others cannot, function returns an array with name=&gt;value pair
 * for which the addition failed in the user cache.</li>
 * </ul></p>
 */
function wincache_ucache_add($key, $value, $ttl = 0) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Compares the variable associated with the <em>key</em> with <em>old_value</em>
 * and if it matches then assigns the <em>new_value</em> to it.
 * @link https://secure.php.net/manual/en/function.wincache-ucache-cas.php
 * @param string $key The key that is used to store the variable in the cache. key is case sensitive.
 * @param int $old_value Old value of the variable pointed by key in the user cache.
 * The value should be of type long, otherwise the function returns FALSE.
 * @param int $new_value New value which will get assigned to variable pointer by key
 * if a match is found. The value should be of type long, otherwise the function returns FALSE.
 * @return bool Returns TRUE on success or FALSE on failure.
 */
function wincache_ucache_cas($key, $old_value, $new_value) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Clears/deletes all the values stored in the user cache.
 * @link https://secure.php.net/manual/en/function.wincache-ucache-clear.php
 * @return bool Returns TRUE on success or FALSE on failure.
 */
function wincache_ucache_clear() {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Decrements the value associated with the key by 1 or as specified by dec_by.
 * @link https://secure.php.net/manual/en/function.wincache-ucache-dec.php
 * @param string $key <p>The key that was used to store the variable in the cache.
 * key is case sensitive.</p>
 * @param int $dec_by <p>The value by which the variable associated with the key will
 * get decremented. If the argument is a floating point number it will be truncated
 * to nearest integer. The variable associated with the key should be of type long,
 * otherwise the function fails and returns FALSE.</p>
 * @param bool|null &$success [optional]
 * <p>Will be set to TRUE on success and FALSE on failure.</p>
 * @return int|false Returns the decremented value on success and FALSE on failure.
 */
function wincache_ucache_dec($key, $dec_by = 1, &$success) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Deletes the elements in the user cache pointed by key.
 * @link https://secure.php.net/manual/en/function.wincache-ucache-delete.php
 * @param string|string[] $key <p>The key that was used to store the variable in the cache.
 * key is case sensitive. key can be an array of keys.</p>
 * @return bool Returns TRUE on success or FALSE on failure.
 * <p>If key is an array then the function returns FALSE if every element of
 * the array fails to get deleted from the user cache, otherwise returns an
 * array which consists of all the keys that are deleted.</p>
 */
function wincache_ucache_delete($key) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Checks if a variable with the key exists in the user cache or not.
 * @link https://secure.php.net/manual/en/function.wincache-ucache-exists.php
 * @param string $key The key that was used to store the variable in the cache. key is case sensitive.
 * @return bool Returns TRUE if variable with the key exitsts, otherwise returns FALSE.
 */
function wincache_ucache_exists($key) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Gets a variable stored in the user cache.
 * @link https://secure.php.net/manual/en/function.wincache-ucache-get.php
 * @param string|string[] $key <p>The key that was used to store the variable in the cache.
 * key is case sensitive. key can be an array of keys. In this case the return
 * value will be an array of values of each element in the key array.</p>
 * @param bool|null &$success [optional]
 * <p>Will be set to TRUE on success and FALSE on failure.</p>
 * @return mixed <p>If key is a string, the function returns the value of the variable
 * stored with that key. The success is set to TRUE on success and to FALSE on failure.</p>
 * <p>The key is an array, the parameter success is always set to TRUE. The returned array
 * (name =&gt; value pairs) will contain only those name =&gt; value pairs for which the get
 * operation in user cache was successful. If none of the keys in the key array finds a
 * match in the user cache an empty array will be returned.</p>
 */
function wincache_ucache_get($key, &$success) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Increments the value associated with the key by 1 or as specified by inc_by.
 * @link https://secure.php.net/manual/en/function.wincache-ucache-inc.php
 * @param string $key <p>The key that was used to store the variable in the cache.
 * key is case sensitive.</p>
 * @param int $inc_by <p>The value by which the variable associated with the key will
 * get incremented. If the argument is a floating point number it will be truncated
 * to nearest integer. The variable associated with the key should be of type long,
 * otherwise the function fails and returns FALSE.</p>
 * @param bool|null &$success [optional]
 * <p>Will be set to TRUE on success and FALSE on failure.</p>
 * @return int|false Returns the incremented value on success and FALSE on failure.
 */
function wincache_ucache_inc($key, $inc_by = 1, &$success) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Retrieves information about data stored in the user cache.
 * @link https://secure.php.net/manual/en/function.wincache-ucache-info.php
 * @param bool $summaryonly [optional]
 * <p>Controls whether the returned array will contain information about
 * individual cache entries along with the user cache summary.</p>
 * @param null|string $key [optional]
 * <p>The key of an entry in the user cache. If specified then the returned array
 * will contain information only about that cache entry. If not specified and
 * summaryonly is set to false then the returned array will contain information
 * about all entries in the cache.</p>
 * @return array|false Array of meta data about user cache or FALSE on failure
 * <p>The array returned by this function contains the following elements:
 * <ul>
 * <li><em>total_cache_uptime</em> - total time in seconds that the user cache has been active</li>
 * <li><em>total_item_count</em> - total number of elements that are currently in the user cache</li>
 * <li><em>is_local_cache</em> - true is the cache metadata is for a local cache instance, false
 * if the metadata is for the global cache</li>
 * <li><em>total_hit_count</em> - number of times the data has been served from the cache</li>
 * <li><em>total_miss_count</em> - number of times the data has not been found in the cache</li>
 * <li><em>ucache_entries</em> - an array that contains the information about all the cached items:
 * <ul>
 * <li><em>key_name</em> - name of the key which is used to store the data</li>
 * <li><em>value_type</em> - type of value stored by the key</li>
 * <li><em>use_time</em> - time in seconds since the file has been accessed in the opcode cache</li>
 * <li><em>last_check</em> - time in seconds since the file has been checked for modifications</li>
 * <li><em>is_session</em> - indicates if the data is a session variable</li>
 * <li><em>ttl_seconds</em> - time remaining for the data to live in the cache, 0 meaning infinite</li>
 * <li><em>age_seconds</em> - time elapsed from the time data has been added in the cache</li>
 * <li><em>hitcount</em> - number of times data has been served from the cache</li>
 * </ul></li>
 * </ul></p>
 */
function wincache_ucache_info(bool $summaryonly = false, $key = null) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Retrieves information about memory usage by user cache.
 * @link https://secure.php.net/manual/en/function.wincache-ucache-meminfo.php
 * @return array|false Array of meta data about user cache memory usage or FALSE on failure
 * <p>The array returned by this function contains the following elements:
 * <ul>
 * <li><em>memory_total</em> - amount of memory in bytes allocated for the user cache</li>
 * <li><em>memory_free</em> - amount of free memory in bytes available for the user cache</li>
 * <li><em>num_used_blks</em> - number of memory blocks used by the user cache</li>
 * <li><em>num_free_blks</em> - number of free memory blocks available for the user cache</li>
 * <li><em>memory_overhead</em> - amount of memory in bytes used for the user cache internal structures</li>
 * </ul></p>
 */
function wincache_ucache_meminfo() {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Adds a variable in user cache and overwrites a variable if it already exists in the cache.
 * <p>The added or updated variable remains in the user cache unless its time to
 * live expires or it is deleted by using wincache_ucache_delete() or
 * wincache_ucache_clear() functions.</p>
 * @link https://secure.php.net/manual/en/function.wincache-ucache-set.php
 * @param string|string[] $key <p>
 * Store the variable using this key name. If a variable with same key is already
 * present the function will overwrite the previous value with the new one. key
 * is case sensitive. key can also take array of name =&gt; value pairs where
 * names will be used as keys. This can be used to add multiple values in the
 * cache in one operation, thus avoiding race condition.</p>
 * @param mixed $value <p>
 * Value of a variable to store. Value supports all data types except resources,
 * such as file handles. This parameter is ignored if first argument is an array.
 * A general guidance is to pass NULL as value while using array as key.</p>
 * @param int $ttl [optional]<p>
 * Time for the variable to live in the cache in seconds. After the value specified
 * in ttl has passed the stored variable will be deleted from the cache. This
 * parameter takes a default value of 0 which means the variable will stay in the
 * cache unless explicitly deleted by using wincache_ucache_delete() or
 * wincache_ucache_clear() functions.</p>
 * @return bool <p>
 * If key is string, the function returns TRUE on success and FALSE on failure.</p>
 * <p>If key is an array, the function returns:
 * <ul style="list-style: square;">
 * <li>If all the name =&gt; value pairs in the array can be set, function
 * returns an empty array;</li>
 * <li>If all the name =&gt; value pairs in the array cannot be set, function
 * returns FALSE;</li>
 * <li>If some can be set while others cannot, function returns an array with
 * name=&gt;value pair for which the addition failed in the user cache.</li>
 * </ul></p>
 */
function wincache_ucache_set($key, $value, $ttl = 0) {}

/**
 * (PHP 5.2+; PECL wincache &gt;= 1.1.0)<br/>
 * Releases an exclusive lock that was obtained on a given key by using wincache_lock().
 * <p>If any other process was blocked waiting for the lock on this key, that process will be able to obtain the lock.</p>
 * @link https://secure.php.net/manual/en/function.wincache-unlock.php
 * @param string $key Name of the key in the cache to release the lock on.
 * @return bool Returns TRUE on success or FALSE on failure.
 */
function wincache_unlock($key) {}
