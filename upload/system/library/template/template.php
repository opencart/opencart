<?php
namespace Template;
final class Template {
	protected $code;
	protected $data = array();

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function load($filename) {
		$file = DIR_TEMPLATE . $filename . '.tpl';

		if (is_file($file)) {
			$this->code = file_get_contents($file);
		}
	}

	public function render($filename, $code = '') {
		if ($code) {
			$this->code = $code;
		}

		$file = DIR_TEMPLATE . $filename . '.tpl';

		if (is_file($file)) {
			$this->code = file_get_contents($file);

			ob_start();

			extract($this->data);

			include($this->compile($file, $this->code));

			return ob_get_clean();
		} else {
			throw new \Exception('Error: Could not load template ' . $file . '!');
			exit();
		}

	}

	public function compile($file, $code) {
		$file = DIR_CACHE . 'template/' . hash('md5', $file . $code) . '.php';

		if (!is_file($file)) {
			// Build the directories if they don't exist
			$directory = '';

			$parts = explode('/', substr(dirname($file), 1));

			foreach ($parts as $part) {
				$directory .= '/' . $part;

				if (!is_dir($directory)) {
					if (!mkdir($directory, 0777, true)) {
						clearstatcache(true, $directory);
					}
				}
			}


			file_put_content($file, $code, LOCK_EX);
		}

		return $file;
	}
}