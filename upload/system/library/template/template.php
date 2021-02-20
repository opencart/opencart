<?php
namespace Opencart\System\Library\Template;
class Template {
	protected $directory;
	protected $path = [];

	/**
	 * addPath
	 *
	 * @param    string $namespace
	 * @param    string $directory
	 */
	public function addPath($namespace, $directory = '') {
		if (!$directory) {
			$this->directory = $namespace;
		} else {
			$this->path[$namespace] = $directory;
		}
	}

	/**
	 * Render
	 *
	 * @param	string	$filename
	 * @param	array	$data
	 * @param	string	$code
	 *
	 * @return	array
	 */
	public function render($filename, $data = [], $code = '') {
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
					$file = $this->path[$namespace] . substr($filename, strlen($namespace)) . '.tpl';
				}
			}

			if (isset($file) && is_file($file)) {
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
		}
	}

	protected function compile($filename, $code) {
		$file = DIR_CACHE . 'template/' . hash('md5', $filename . $code) . '.php';

		if (!is_file($file)) {
			file_put_contents($file, $code, LOCK_EX);
		}

		return $file;
	}
}