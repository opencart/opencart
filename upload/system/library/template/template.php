<?php
namespace Opencart\System\Library\Template;
class Template {
	protected $path = [];
	protected $data = [];

	public function addPath($namespace, $directory) {
		$this->path[$namespace] = $directory;
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function render($filename, $code = '') {
		if (!$code) {
			$namespace = '';

			$parts = explode('\\', $filename);

			foreach ($parts as $part) {
				if (!$namespace) {
					$namespace .= $part;
				} else {
					$namespace .= '\\' . $part;
				}

				if (isset($this->path[$namespace])) {
					$file = $this->path[$namespace] . substr($filename, strlen($namespace)) . '.tpl';
				}
			}

			if (isset($file) && is_file($file)) {
				$code = file_get_contents($file);
			} else {
				error_log('Error: Could not load template ' . $filename . '!');
			}
		}

		if ($code) {
			ob_start();

			extract($this->data);

			include($this->compile($filename . '.tpl', $code));

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