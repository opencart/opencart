<?php
abstract class Controller {
	protected $id;
	protected $template;
	protected $layout;
	protected $children = array();
	protected $data = array();
	protected $output;
	
	protected function __get($key) {
		return Registry::get($key);
	}
	
	protected function __set($key, $value) {
		Registry::set($key, $value);
	}
			
	protected function forward($class, $method, $args = array()) {
		return new Action($class, $method, $args);
	}

	protected function redirect($url) {
		header('Location: ' . html_entity_decode($url));
		exit();
	}
	
	protected function render() {
		foreach ($this->children as $child) {
			$file  = DIR_APPLICATION . 'controller/' . $child . '.php';
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
		
		$this->output = $this->fetch($this->template);
		
		if ($this->layout) {
			$file  = DIR_APPLICATION . 'controller/' . $this->layout . '.php';
			$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $this->layout);
			
			if (file_exists($file)) {
				require_once($file);
				
				$controller = new $class();
				
				$controller->data[$this->id] = $this->output;
				
				$controller->index();
				
				$this->output = $controller->output;
			} else {
				exit('Error: Could not load controller ' . $this->layout . '!');
			}	
		}
		
		$this->response->setOutput($this->output);
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