<?php

// Start of Zend Cache v.

/**
 * Stores a serializable variable into Shared Memory Cache
 *
 * @param string $key the data's key. Possibly prefixed with namespace
 * @param mixed $value can be any PHP object that can be serialized.
 * @param int $ttl [optional] time to live in seconds. ZendCache keeps the objects in the cache as long as the TTL is no expired, once expired it will be removed from the cache
 *
 * @return bool FALSE when stored failed, TRUE otherwise
 */
function zend_shm_cache_store($key, $value, $ttl = 0) {}

/**
 * Retrieves data stored in the Shared Memory Cache.
 * If the key is not found, returns null.
 *
 * @param string $key the data's key. Possibly prefixed with namespace
 *
 * @return mixed|null NULL when no data matching the key is found, else it returns the stored data
 */
function zend_shm_cache_fetch($key) {}

/**
 * Delete a key from the Shared Memory cache
 *
 * @param string $key the data's key. Possibly prefixed with namespace
 *
 * @return mixed|null when no data matching the key is found, else it returns the stored data
 */
function zend_shm_cache_delete($key) {}

/**
 * Clear the entire Shared Memory cache or just the provided namespace.
 *
 * @param string $namespace [optional] Namespace to clear. If blank or is not passed, it will clear entire cache.
 *
 * @return bool TRUE on success, FALSE otherwise
 */
function zend_shm_cache_clear($namespace = '') {}

/**
 * Provide the user information about the memory data cache
 *
 * @return array|false FALSE when on failure
 */
function zend_shm_cache_info() {}

/**
 * Stores a serializable variable into Disk Cache
 *
 * @param string $key the data's key. Possibly prefixed with namespace
 * @param mixed $value can be any PHP object that can be serialized
 * @param int $ttl [optional] time to live in seconds. ZendCache keeps the objects in the cache as long as the TTL is no expired, once expired it will be removed from the cache
 *
 * @return bool FALSE when stored failed, TRUE otherwise
 */
function zend_disk_cache_store($key, $value, $ttl = 0) {}

/**
 * Retrieves data stored in the Shared Memory Cache
 *
 * @param string $key the data's key. Possibly prefixed with namespace
 *
 * @return mixed|null NULL when no data matching the key is found, else it returns the stored data
 */
function zend_disk_cache_fetch($key) {}

/**
 * Delete a key from the cache
 *
 * @param string $key the data's key. Possibly prefixed with namespace
 *
 * @return mixed|null when no data matching the key is found, else it returns the stored data
 */
function zend_disk_cache_delete($key) {}

/**
 * Clear the entire Disk Memory cache or just the provided namespace.
 *
 * @param string $namespace [optional] Namespace to clear. If blank or is not passed, it will clear entire cache.
 *
 * @return bool TRUE on success, FALSE otherwise
 */
function zend_disk_cache_clear($namespace = '') {}

/**
 * Provide the user information about the memory data cache
 *
 * @return array|false FALSE when on failure
 */
function zend_disk_cache_info() {}

// End of Zend Cache v.
