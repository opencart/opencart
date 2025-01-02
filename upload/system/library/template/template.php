<?php
namespace Opencart\System\Library\Template;
/**
 * Class Template
 *
 * @package Opencart\System\Library\Template
 */
class Template {
	protected string $directory = '';
	/**
	 * @var array<string, string>
	 */
	protected array $path = [];

	/**
	 * Add Path
	 *
	 * @param string $namespace
	 * @param string $directory
	 *
	 * @return void
	 */
	public function addPath(string $namespace, string $directory = ''): void {
		if (!$directory) {
			$this->directory = $namespace;
		} else {
			$this->path[$namespace] = $directory;
		}
	}

	/**
	 * Render
	 *
	 * @param string               $filename
	 * @param array<string, mixed> $data
	 * @param string               $code
	 *
	 * @return string
	 */
	public function render(string $filename, array $data = [], string $code = ''): string {
		if (!$code) {
			$file = $this->directory . $filename . '.tpl';

			$namespace = '';

			$parts = explode('/', $filename);

			foreach ($parts as $part) {
				if (!$namespace) {
					$namespace .= $part;
				} else {
					$namespace .= '/' . $part;
				}

				if (isset($this->path[$namespace])) {
					$file = $this->path[$namespace] . substr($filename, strlen($namespace) + 1) . '.tpl';
				}
			}

			if (is_file($file)) {
				$code = file_get_contents($file);
			} else {
				throw new \Exception('Error: Could not load template ' . $filename . '!');
			}
		}

		if ($code) {
			ob_start();

			extract($data);

			include($this->compile($filename, $code));

			return ob_get_clean();
		} else {
			return '';
		}
	}

	/**
	 * Compile
	 *
	 * @param string $filename
	 * @param string $code
	 *
	 * @return string
	 */
	protected function compile(string $filename, string $code): string {
		$file = DIR_CACHE . 'template/' . hash('md5', $filename . $code) . '.php';

		if (!is_file($file)) {
			file_put_contents($file, $code, LOCK_EX);
		}

		return $file;
	}
}
