<?php
class Autoloader {
	private $data = array();

	public function __construct() {
		spl_autoload_register([$this, 'load']);
		spl_autoload_extensions('.php');
	}

	public function register($namespace, $directory) {
		$this->data[$namespace] = $directory;
	}

	public function load($class) {
		$namespace = '';

		$parts = explode('\\', $class);

		foreach ($parts as $part) {
			if (!$namespace) {
				$namespace .= $part;
			} else {
				$namespace .= '\\' . $part;
			}

			if (isset($this->data[$namespace])) {
				$file = $this->data[$namespace] . strtolower(preg_replace('~([a-z])([A-Z]|[0-9])~', '\\1_\\2', substr($class, strlen($namespace)))) . '.php';
			}
		}

		if (isset($file) && is_file($file)) {
			include_once($file);

			return true;
		} else {
			return false;
		}
	}
}
