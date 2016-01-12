<?php
namespace Template;
final class Basic {
	private $data = array();
	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function render($template) {
		$file = DIR_TEMPLATE . $template;

		if (file_exists($file)) {
			extract($this->data);

			ob_start();

			require($file);

			$output = ob_get_contents();

			ob_end_clean();

			return $output;
		} else {
			trigger_error('Error: Could not load template ' . $file . '!');
			exit();
		}
	}	
}