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
		} else {
			throw new \Exception('Error: Could not load template ' . $file . '!');
			exit();
		}
	}

	public function render($filename, $code = '') {
		if (!$code) {
			$this->load($filename);
		} else {
			$this->code = $code;
		}

		ob_start();

		extract($this->data);

		include($this->compile(DIR_TEMPLATE . $filename . '.tpl', $this->code));

		return ob_get_clean();
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


			file_put_contents($file, $code, LOCK_EX);
		}

		return $file;
	}
}