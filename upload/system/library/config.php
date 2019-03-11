<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
 */

/**
 * Config class
 */
class Config implements ArrayAccess, Iterator {
    private $data = array();

    /**
     *
     *
     * @param	string	$key
     *
     * @return	mixed
     */
    public function get($key) {
        return (isset($this->data[$key]) ? $this->data[$key] : null);
    }

    /**
     *
     *
     * @param	string	$key
     * @param	string	$value
     */
    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     *
     *
     * @param	string	$key
     *
     * @return	mixed
     */
    public function has($key) {
        return isset($this->data[$key]);
    }

    /**
     *
     *
     * @param	string	$filename
     */
    public function load($filename) {
        $file = DIR_CONFIG . $filename . '.php';

        if (file_exists($file)) {
            $_ = array();

            require($file);

            $this->data = array_merge($this->data, $_);
        } else {
            trigger_error('Error: Could not load config ' . $filename . '!');
            exit();
        }
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->data);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->key() !== null;
    }

    /**
     * @return mixed
     */
    public function rewind()
    {
        return reset($this->data);
    }

    /**
     * @param  string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @param  string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->data[$key];
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    public function offsetSet($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @param  string $key
     * @return $this
     */
    public function offsetUnset($key)
    {
        unset($this->data[$key]);
        return $this;
    }
}