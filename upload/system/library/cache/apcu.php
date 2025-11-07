<?php
namespace Cache;

/**
 * APCu cache implementation based on APCIterator only.
 *
 * Suitable for PHP 7.4+. The class does not maintain a separate key registry —
 * all bulk operations (listing / clear) are performed via APCIterator,
 * which saves additional memory and keeps the operational logic at the APCu level.
 */
class APCu
{
    /** @var int ttl in seconds */
    private $expire;

    /** @var bool whether the cache is available */
    private $active = false;

    /** @var string key prefix */
    private $prefix;

    /**
     * @param int $expire
     * @param string|null $prefix
     */
    public function __construct($expire = 3600, $prefix = null)
    {
        $this->expire = (int)$expire;

        if ($prefix !== null) {
            $this->prefix = (string)$prefix;
        } elseif (defined('CACHE_PREFIX')) {
            $this->prefix = (string)CACHE_PREFIX;
        } else {
            $this->prefix = 'oc_';
        }

        $this->active = function_exists('apcu_fetch') && (bool)ini_get('apc.enabled');

        if (!$this->active) {
            // Do not throw an error: the application will continue to work without a cache.
            // For debugging, you can uncomment:
            // error_log('APCu is not available or disabled');
        }
    }

    /**
     * Builds the physical key in APCu.
     * The prefix is left as the root for APCIterator.
     *
     * @param string $key
     * @return string
     */
    private function key($key)
    {
        $clean = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', (string)$key);
        return $this->prefix . $clean;
    }

    /**
     * Get a value from the cache. Returns false on failure or if not found.
     *
     * @param string $key
     * @return mixed|false
     */
    public function get($key)
    {
        if (!$this->active) {
            return false;
        }

        $k = $this->key($key);
        $success = false;
        $value = apcu_fetch($k, $success);

        if ($success === false) {
            return false;
        }

        $un = @unserialize($value);
        if ($un === false && $value !== serialize(false)) {
            return $value; // not a serialized value
        }

        return $un;
    }

    /**
     * Save a value to the cache. Returns true on success.
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function set($key, $value)
    {
        if (!$this->active) {
            return false;
        }

        $k = $this->key($key);
        return (bool)apcu_store($k, serialize($value), $this->expire);
    }

    /**
     * Deletes cache entries by prefix. Supports wildcard-style deletion,
     * similar to the file-based cache driver in OpenCart.
     *
     * @param string $key Key or prefix to delete.
     * @return bool True on success, false on failure.
     */
    public function delete($key)
    {
        if (!$this->active) {
            return false;
        }
    
        $fullPrefix = $this->key($key);
    
        // 1) Preferred method — APCIterator (does not load values into memory).
        if (class_exists('\APCIterator')) {
            $pattern = '/^' . preg_quote($fullPrefix, '/') . '/';
    
            try {
                $it = new \APCIterator('user', $pattern, APC_ITER_KEY);
    
                $keys = [];
                foreach ($it as $entry) {
                    if (is_array($entry) && isset($entry['key'])) {
                        $keys[] = $entry['key'];
                    } elseif (is_string($entry)) {
                        $keys[] = $entry;
                    }
                }
    
                if (empty($keys)) {
                    return true;
                }
    
                // Delete in batches to avoid excessive memory usage on large caches.
                $batchSize = (int)(getenv('APCU_DELETE_BATCH') ?: 1000);
                for ($i = 0, $n = count($keys); $i < $n; $i += $batchSize) {
                    $chunk = array_slice($keys, $i, $batchSize);
                    @apcu_delete($chunk);
                }
    
                return true;
            } catch (\Throwable $e) {
                // For debugging: error_log($e->getMessage());
                return false;
            }
        }
    
        // 2) Fallback: apcu_cache_info — universal but potentially expensive.
        if (function_exists('apcu_cache_info')) {
            try {
                $info = @apcu_cache_info('user');
                if ($info === false || !isset($info['cache_list'])) {
                    // Invalid response — fallback to deleting a single key below.
                    return (bool) @apcu_delete($fullPrefix);
                }
    
                $keys = [];
                foreach ($info['cache_list'] as $entry) {
                    // Different APCu builds may use 'key' or 'info' fields.
                    if (isset($entry['key'])) {
                        $k = $entry['key'];
                    } elseif (isset($entry['info']) && isset($entry['info']['key'])) {
                        $k = $entry['info']['key'];
                    } else {
                        continue;
                    }
    
                    if (strpos($k, $fullPrefix) === 0) {
                        $keys[] = $k;
                    }
                }
    
                if (empty($keys)) {
                    return true;
                }
    
                // Delete in batches to reduce memory load.
                $batchSize = (int)(getenv('APCU_DELETE_BATCH') ?: 1000);
                for ($i = 0, $n = count($keys); $i < $n; $i += $batchSize) {
                    $chunk = array_slice($keys, $i, $batchSize);
                    @apcu_delete($chunk);
                }
    
                return true;
            } catch (\Throwable $e) {
                // For debugging: error_log($e->getMessage());
                // Fall through to single-key delete below.
            }
        }
    
        // 3) Last resort — delete the exact key only.
        // This ensures single-key deletion still works when other methods are unavailable.
        return (bool) @apcu_delete($fullPrefix);
    }

    /**
     * Clear all keys with the current prefix using APCIterator.
     * Returns true on execution (even if nothing is found).
     *
     * This method uses APC_ITER_KEY to avoid loading values into memory.
     * It is also protected by a try/catch block in case APCIterator is missing or fails.
     *
     * @return bool
     */
    public function clear()
    {
        if (!$this->active) {
            return false;
        }

        // Regular expression for APCIterator: find all keys starting with the prefix
        $pattern = '/^' . preg_quote($this->prefix, '/') . '/';

        if (!class_exists('\APCIterator')) {
            // If APCIterator is not available, do nothing, as there is no registry in this implementation.
            // This is an intentional design choice: bulk deletion is not safe without an iterator.
            // In such cases, I recommend using a version with a registry or enabling APCIterator.
            return false;
        }

        try {
            // APC_ITER_KEY — does not load values, only metadata (keys)
            $it = new \APCIterator('user', $pattern, APC_ITER_KEY);

            foreach ($it as $entry) {
                $k = null;
                if (is_array($entry) && isset($entry['key'])) {
                    $k = $entry['key'];
                } elseif (is_string($entry)) {
                    $k = $entry;
                }

                if ($k !== null) {
                    @apcu_delete($k);
                }
            }

            return true;
        } catch (\Throwable $e) {
            // In case APCIterator is unexpectedly unavailable or throws an exception.
            // For debugging, you can log it: error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Return a list of keys by prefix (without values). Useful for debugging/diagnostics.
     *
     * @param string|null $patternSuffix A regex suffix (will be appended to the prefix), or null to return all.
     * @return array
     */
    public function listKeys($patternSuffix = null)
    {
        if (!$this->active || !class_exists('\APCIterator')) {
            return [];
        }

        $m = '^' . preg_quote($this->prefix, '/') . ($patternSuffix !== null ? $patternSuffix : '');
        $pattern = '/' . $m . '/';

        $out = [];
        try {
            $it = new \APCIterator('user', $pattern, APC_ITER_KEY);
            foreach ($it as $entry) {
                if (is_array($entry) && isset($entry['key'])) {
                    $out[] = $entry['key'];
                } elseif (is_string($entry)) {
                    $out[] = $entry;
                }
            }
        } catch (\Throwable $e) {
            return [];
        }

        return $out;
    }
}
