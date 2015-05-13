<?php
class Bag implements ArrayAccess {
	protected $data;

	public function __construct($data = array()) {
		$this->data = $data;
	}

	public function get($key, $default = null) {
		return (isset($this->data[$key]) ? $this->data[$key] : $default);
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function has($key) {
		return isset($this->data[$key]);
	}

	public function delete($key) {
		unset($this->data[$key]);
	}

	public function offsetGet($key) {
		return $this->get($key);
	}

	public function offsetSet($key, $value) {
		$this->set($key, $value);
	}

	public function offsetExists($key) {
		return $this->has($key);
	}

	public function offsetUnset($key) {
		$this->delete($key);
	}
}
