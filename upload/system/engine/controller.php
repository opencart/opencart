<?php
abstract class Controller {
	protected $id;
	protected $template;
	protected $children = array();
	protected $data = array();
	protected $output;
	
	public function __get($key) {
		return Registry::get($key);
	}
	
	public function __set($key, $value) {
		Registry::set($key, $value);
	}
			
	protected function forward($route, $args = array()) {
		return new Action($route, $args);
	}

	protected function redirect($url) {
		header('Location: ' . str_replace('&amp;', '&', $url));
		exit();
	}
	
	protected function render($return = FALSE) {
		foreach ($this->children as $child) {
			$file = DIR_APPLICATION . 'controller/' . str_replace('../', '', $child) . '.php';
			$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $child);
		
			if (file_exists($file)) {
				require_once($file);

				$controller = new $class();
				
				$controller->index();
				
				$this->data[$controller->id] = $controller->output;
			} else {
				exit('Error: Could not load controller ' . $child . '!');
			}
		}
		
		if ($return) {
			return $this->fetch($this->template);
		} else {
			$this->output = $this->fetch($this->template);
		}
	}
	
	protected function fetch($filename) {
		$file = DIR_TEMPLATE . $filename;
    
		if (file_exists($file)) {
			extract($this->data);
			
      		ob_start();
      
	  		require($file);
      
	  		$content = ob_get_contents();

      		ob_end_clean();

      		return $content;
    	} else {
      		exit('Error: Could not load template ' . $file . '!');
    	}
	}
}
?>