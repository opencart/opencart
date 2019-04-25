<?php
namespace Template;
final class Template {
	protected $data = array();

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function render($filename, $code = '') {
		if (!$code) {
			$file = DIR_TEMPLATE . $filename . '.tpl';

			if (is_file($file)) {
				$code = file_get_contents($file);
			} else {
				throw new \Exception('Error: Could not load template ' . $file . '!');
				exit();
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