<?php
namespace Opencart\System\Engine;
class Autoloader {
	private array $path = [];

	public function __construct() {
		spl_autoload_register([$this, 'load']);
		spl_autoload_extensions('.php');
	}

	public function register(string $namespace, string $directory): void {
		$this->path[$namespace] = $directory;
	}

	public function load(string $class): bool {
		$namespace = '';

		$parts = explode('\\', $class);

		foreach ($parts as $part) {
			if (!$namespace) {
				$namespace .= $part;
			} else {
				$namespace .= '\\' . $part;
			}

			if (isset($this->path[$namespace])) {
				$file = $this->path[$namespace] . trim(str_replace('\\', '/', strtolower(preg_replace('~([a-z])([A-Z]|[0-9])~', '\\1_\\2', substr($class, strlen($namespace))))), '/') . '.php';
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
