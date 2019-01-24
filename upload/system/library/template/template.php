<?php
namespace Template;
final class Template {
	protected $code;
	protected $filters = array();
	protected $data = array();

	public function addFilter($key, $value) {
		$this->filters[$key] = $value;
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function render($filename, $cache = true) {
		$file = DIR_TEMPLATE . $filename . '.tpl';

		if (is_file($file)) {
			$this->code = file_get_contents($file);

			foreach ($this->filters as $filter) {
				$filter->callback($this->code);
			}

			ob_start();

			if (!$cache && function_exists('eval')) {
				extract($this->data);

				echo eval('?>' . $this->code);
			} else {
				extract($this->data);

				include($this->compile($file, $this->code));
			}

			return ob_get_clean();
		} else {
			throw new \Exception('Error: Could not load template ' . $file . '!');
			exit();
		}
	}

	public function compile($file, $code) {
		$hash = hash('sha256', $file . __CLASS__ . preg_replace('/[^0-9a-zA-Z_]/', '_', implode('_', array_keys($this->filters))));

		$file = DIR_CACHE . substr($hash, 0, 2) . '/' . $hash . '.php';

		if (!is_file($file)) {
			$directory = dirname($file);

			if (!is_dir($directory)) {
				if (!mkdir($directory, 0777, true)) {
					clearstatcache(true, $directory);
				}
			}

			$handle = fopen($file, 'w+');

			fwrite($handle, $code);

			fclose($handle);
		}

		return $file;
	}
}