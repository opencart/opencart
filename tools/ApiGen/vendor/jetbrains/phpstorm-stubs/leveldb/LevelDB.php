<?php

define("LEVELDB_NO_COMPRESSION", 0);

define("LEVELDB_SNAPPY_COMPRESSION", 1);

class LevelDB
{
    /**
     * @param string $name Path to database
     * @param array  $options
     * @param array  $read_options
     * @param array  $write_options
     */
    public function __construct($name, array $options = [
        'create_if_missing' => true, // if the specified database does not exist will create a new one
        'error_if_exists' => false, // if the opened database exists will throw exception
        'paranoid_checks' => false,
        'block_cache_size' => 8 * (2 << 20),
        'write_buffer_size' => 4 << 20,
        'block_size' => 4096,
        'max_open_files' => 1000,
        'block_restart_interval' => 16,
        'compression' => LEVELDB_SNAPPY_COMPRESSION,
        'comparator' => null, // any callable parameter return 0, -1, 1
    ], array $read_options = [
        'verify_check_sum' => false, //may be set to true to force checksum verification of all data that is read from the file system on behalf of a particular read. By default, no such verification is done.
        'fill_cache' => true, //When performing a bulk read, the application may set this to false to disable the caching so that the data processed by the bulk read does not end up displacing most of the cached contents.
    ], array $write_options = [
        //Only one element named sync in the write option array. By default, each write to leveldb is asynchronous.
        'sync' => false
    ]) {}

    /**
     * @param string $key
     * @param array  $read_options
     *
     * @return string|false
     */
    public function get($key, array $read_options = []) {}

    /**
     * Alias of LevelDB::put()
     *
     * @param string $key
     * @param string $value
     * @param array  $write_options
     */
    public function set($key, $value, array $write_options = []) {}

    /**
     * @param string $key
     * @param string $value
     * @param array  $write_options
     */
    public function put($key, $value, array $write_options = []) {}

    /**
     * @param string $key
     * @param array  $write_options
     *
     * @return bool
     */
    public function delete($key, array $write_options = []) {}

    /**
     * Executes all of the operations added in the write batch.
     *
     * @param LevelDBWriteBatch $batch
     * @param array             $write_options
     */
    public function write(LevelDBWriteBatch $batch, array $write_options = []) {}

    /**
     * Valid properties:
     * - leveldb.stats: returns the status of the entire db
     * - leveldb.num-files-at-level: returns the number of files for each level. For example, you can use leveldb.num-files-at-level0 the number of files for zero level.
     * - leveldb.sstables: returns current status of sstables
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getProperty($name) {}

    public function getApproximateSizes($start, $limit) {}

    public function compactRange($start, $limit) {}

    public function close() {}

    /**
     * @param array $options
     *
     * @return LevelDBIterator
     */
    public function getIterator(array $options = []) {}

    /**
     * @return LevelDBSnapshot
     */
    public function getSnapshot() {}

    public static function destroy($name, array $options = []) {}

    public static function repair($name, array $options = []) {}
}

class LevelDBIterator implements Iterator
{
    public function __construct(LevelDB $db, array $read_options = []) {}

    public function valid() {}

    public function rewind() {}

    public function last() {}

    public function seek($key) {}

    public function next() {}

    public function prev() {}

    public function key() {}

    public function current() {}

    public function getError() {}

    public function destroy() {}
}

class LevelDBWriteBatch
{
    public function __construct() {}

    public function set($key, $value, array $write_options = []) {}

    public function put($key, $value, array $write_options = []) {}

    public function delete($key, array $write_options = []) {}

    public function clear() {}
}

class LevelDBSnapshot
{
    public function __construct(LevelDB $db) {}

    public function release() {}
}

class LevelDBException extends Exception {}
