<?php
namespace Template;
final class Template {

	private $filters = array();
	private $data = array();

	public function addFilter($key, $value) {
		$this->filters[$key] = $value;
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function render($filename) {
		$file = DIR_TEMPLATE . $filename . '.tpl';

		if (is_file($file)) {
			$code = file_get_conents($file);

			foreach ($this->filters as $key => $filter) {
				$filter->callback($code);
			}



			$handle = fopen(DIR_CACHE . md5($file), 'w+');

			fwrite($handle, $code);

			fclose($handle);




			ob_start();

			extract($this->data);

			/*
			if (function_exists('eval')) {
				eval('?>' . $content);
			} else {

			}
			*/



			include($file);

			$this->output = ob_get_clean();

			$this->write($this->template);

			//include($file);

			return $this->output;
		} else {
			throw new \Exception('Error: Could not load template ' . $file . '!');
			exit();
		}
	}

	public function parse($file) {

	}

	public function load($filename) {
		$output = file_get_contents(DIR_CACHE . $filename);

		return $output;
	}

	public function save($filename, $content) {
		$handle = fopen(DIR_CACHE . $filename, 'w+');

		fwrite($handle, $content);

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
