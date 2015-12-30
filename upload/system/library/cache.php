<?php
class Cache {
	private $adaptor;

	public function __construct($adaptor, $expire = 3600) {
		$class = 'Cache\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class($expire);
		} else {
			throw new \Exception('Error: Could not load cache adaptor ' . $adaptor . ' cache!');
		}
	}
	
	/**
	 * Register a binding with the container.
	 *
	 * @param  string               $abstract
	 * @param  Closure|string|null  $concrete
	 * @param  bool                 $shared
	 * @return mixed
	*/
	public function get($key) {
		return $this->adaptor->get($key);
	}

	public function set($key, $value) {
		return $this->adaptor->set($key, $value);
	}

	public function delete($key) {
		return $this->adaptor->delete($key);
	}
}
