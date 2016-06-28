<?php
namespace Template;
final class PHP {
	private $data = array();
	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function render($template) {
		$loader = new Twig_Loader_Array(array(
			'index' => 'Hello {{ name }}!',
		));
		
		$twig = new Twig_Environment($loader);
		
		$file = DIR_TEMPLATE . $template;

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
