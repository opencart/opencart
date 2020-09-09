<?php
namespace Opencart\System\Engine;
class Autoloader {
	private $loaded = array();
	private $data = array();

	public function __construct() {
		spl_autoload_register([$this, 'load']);
		spl_autoload_extensions('.php');
	}

	public function register($namespace, $directory) {
		$this->data[$namespace] = $directory;
	}

	public function load($class) {
		// No need to go through the whole process if the class has already been loaded.
		if (isset($this->loaded[$class])) {
			return true;
		}

		$namespace = '';

		$parts = explode('\\', $class);

		foreach ($parts as $part) {
			if (!$namespace) {
				$namespace .= $part;
			} else {
				$namespace .= '\\' . $part;
			}

			if (isset($this->data[$namespace])) {
				$file = $this->data[$namespace] . trim(str_replace('\\', '/', strtolower(preg_replace('~([a-z])([A-Z]|[0-9])~', '\\1_\\2', substr($class, strlen($namespace))))), '/') . '.php';
			}
		}

		if (isset($file) && is_file($file)) {
			include_once($file);

			$this->loaded[$class] = $file;

			return true;
		} else {
			return false;
		}
	}
}