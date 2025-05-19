<?php
/**
 * @package        OpenCart
 *
 * @author         Daniel Kerr
 * @copyright      Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license        https://opensource.org/licenses/GPL-3.0
 *
 * @see           https://www.opencart.com
 */
namespace Opencart\System\Engine;
/**
 * Class Config
 */
class Config {
	/**
	 * @var string
	 */
	protected string $directory;
	/**
	 * @var array<string, string>
	 */
	private array $path = [];
	/**
	 * @var array<string, string>
	 */
	private array $data = [];

	/**
	 * Add Path
	 *
	 * @param string $namespace
	 * @param string $directory
	 */
	public function addPath(string $namespace, string $directory = ''): void {
		if (!$directory) {
			$this->directory = $namespace;
		} else {
			$this->path[$namespace] = $directory;
		}
	}

	/**
	 * Get
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get(string $key) {
		return $this->data[$key] ?? '';
	}

	/**
	 * Set
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function set(string $key, $value): void {
		$this->data[$key] = $value;
	}

	/**
	 * Has
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has(string $key): bool {
		return isset($this->data[$key]);
	}

	/**
	 * Load
	 *
	 * @param string $filename
	 *
	 * @return array<string, string>
	 */
	public function load(string $filename): array {
		$file = $this->directory . $filename . '.php';

		$namespace = '';

		$parts = explode('/', $filename);

		foreach ($parts as $part) {
			if (!$namespace) {
				$namespace .= $part;
			} else {
				$namespace .= '/' . $part;
			}

			if (isset($this->path[$namespace])) {
				$file = $this->path[$namespace] . substr($filename, strlen($namespace)) . '.php';
			}
		}

		if (is_file($file)) {
			$_ = [];

			require($file);

			$this->data = array_merge($this->data, $_);

			return $this->data;
		} else {
			return [];
		}
	}
}
