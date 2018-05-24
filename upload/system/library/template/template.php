<?php
namespace Template;
final class Template {
	private $data = array();
	private $filters = array();

	public function addFilter($value) {
		$this->filters[] = $value;
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function render($filename) {
		$file = DIR_TEMPLATE . $filename . '.tpl';

		if (is_file($file)) {
			$code = file_get_conents($file);

			foreach ($this->filters as $filter) {
				$code = $filter->callback($code);
			}

			ob_start();

			extract($this->data);

			if (function_exists('eval')) {
				eval('?>' . $content);
			} else {
				//$this->write($this->template);

				//include($file);
			}

			return $this->output;
		} else {
			throw new \Exception('Error: Could not load template ' . $file . '!');
			exit();
		}
	}

	public function write($file) {
		$handle = fopen(DIR_CACHE . md5($file), 'w+');

		fwrite($handle, $this->output);

		fclose($handle);
	}

	/*
	public function render($filename) {
		$file = DIR_TEMPLATE . $filename . '.tpl';

		if (is_file($file)) {
			ob_start();

			extract($this->data);

			if (function_exists('eval')) {
				eval('?>' . $this->template);

			} else {
				$this->write($this->template);

				include($file);
			}

			$this->output = ob_get_clean();
		}

		return $this->output;
	}
	*/
}
