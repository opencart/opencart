<?php

class CacheFile {
    private $expire;

    public function __construct($expire = 3600, $probability = 25)
    {
        $this->expire = $expire;
        $this->purgeExpired($probability);
    }

    public function get($key)
    {
        $files = glob($this->getPath($key) . '.*');

        if ($files && substr(strrchr($files[0], '.'), 1) > time()) {
            return unserialize(file_get_contents($files[0]));
        }

        return null;
    }

    public function set($key, $value, $expire = null)
    {
        $this->delete($key);

        if (null === $expire) {
            $expire = $this->expire;
        }

        $file = $this->getPath($key) . '.' . (time() + $expire);

        file_put_contents($file, serialize($value), LOCK_EX);
    }

    public function delete($key = '')
    {
        $files = glob($this->getPath($key) . '.*');

        if ($files) {
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }

    private function purgeExpired($probability)
    {
        if (rand(0,100) < $probability && $files = glob(DIR_CACHE . 'cache.*')) {
            foreach ($files as $file) {
                if (substr(strrchr($file, '.'), 1) < time()) {
                    unlink($file);
                }
            }
        }
    }

    private function getPath($key)
    {
        return DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key);
    }
}
