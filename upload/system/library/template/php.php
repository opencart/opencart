<?php
namespace Template;
final class PHP {
	private $directory;
	private $data = array();
	
	public function __construct($directory) {
		$this->directory = $directory;
	}
		
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function render($template) {
		$file = $this->directory . $template . '.tpl';

		if (is_file($file)) {
			extract($this->data);

			ob_start();

			require($file);

			return ob_get_clean();
		}

		trigger_error('Error: Could not load template ' . $file . '!');
		exit();
	}	
}
