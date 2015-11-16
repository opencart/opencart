<?php
/*

*/
class Aspect {
	private $registry;
	private $data = array();

	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function factory($object, $args = array()) {
		$code = 'Class Aspect' . get_class($object) . ' extends ' . get_parent_class($object) . ' {' . "\n";
		
		foreach (get_class_methods($object) as $method) {
			if (substr($method, 0, 2) != '__') {
				$code .= '	public function ' .  $method . '() {' . "\n";
				$code .= '		$this->event->trigger($route, $data);' . "\n";
				$code .= '		$this->object->method();' . "\n";
				$code .= '		$this->event->trigger($data);' . "\n";
				$code .= '	}' . "\n\n";
			}
		}
		
		$code .= '}' . "\n";
		
		echo $code;
		
		eval($code);
		
		$class = 'Aspect' . get_class($object);
		
		return new $class($this->registry);
	}
		
	public function __call($method, $args = array()) {
		echo __METHOD__;
		
		return call_user_func_array($this->{$method}, $args);
	}
}

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
		
	public function &__call($method, $args = array()) {
		foreach ($this->pre_action[$method] as $pre_action) {
			$pre_action->execute($data);
		}
		
		$output = call_user_func_array(array($this->object, $method), $args);
		
		foreach ($this->post_action[$method] as $post_action) {
			$post_action->execute($output);
		}
				
		return $output;
	}	
}
