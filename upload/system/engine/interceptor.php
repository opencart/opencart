<?php
class Interceptor {
	private $object; 
	private $pre_action = array();
	private $post_action = array();
	
	public function __construct($object) {
		$this->object = $object;
	}	
	
	public function addPreAction($method, $action) {
		$this->pre_action[$method][] = $action;
	}
		
	public function addPostAction($method, $action) {
		$this->post_action[$method][] = $action;
	}
	
	public function __call($method, $args = array()) {
		if (isset($this->pre_action[$method])) {
			foreach ($this->pre_action[$method] as $pre_action) {
				$result = $pre_action->execute($args);
				
				if (!is_null($result)) {
					return $result;
				}
			}
		}
		
		$output = call_user_func_array(array($this->object, $method), $args);
		
		if (isset($this->post_action[$method])) {
			foreach ($this->post_action[$method] as $post_action) {
				$result = $post_action->execute($output);
				
				if (!is_null($result)) {
					return $result;
				}
			}
		}
		
		return $output;
	}
}



/*
class Mock {
	$this->code = '';
	
	public function __construct($object) {
		$this->object = $object;
		
		$code =  'Class ' . get_class($object) . ' extends ' . "\n";
		
		$code .=  'public function __construct() {' . "\n";
			
		$code .=  '}' . "\n";		
		
		
		foreach (get_class_methods($this->object) as $method) {
			$code .=  'public function ' . $method . '() {' . "\n";
			
			$code .=  '}' . "\n";
		}
		
		
		$code .=  '}';
	}
	
	function render() {
		eval($this->code);
		
		$reflection = new ReflectionClass();
		
		return 	
	}
	
	//eval();	
}
*/


class Mock {
	public function __construct($registry) {
		$this->registry = $registry;
	}		
	
	function method() {
		// Get args by reference
		$trace = debug_backtrace();

		$args = $trace[0]['args'];		
		
		// Trigger the pre events
		$result = $this->registry->get('event')->trigger('model/' . $model . '/before', $args);
		
		if (!is_null($result)) {
			return $result;
		}
		
		
		
		// Trigger the post events
		$result = $this->registry->get('event')->trigger('model/' . $route . '/after', array(&$output));					
		
		if (!is_null($result)) {
			return $result;
		}	
	}	
}