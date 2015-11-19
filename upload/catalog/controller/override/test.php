<?php
/* 
Overriding

* Controllers
- All data is passed into the controller by reference.
- The output shoudl be returned
- the first arg should always be the method being called

* Models
- All data is passed into the Models by reference.
- Returning a value returns the output
- the first arg should be the method being called

* views

problem how to handle the front controller.
*/
class ControllerOverrideTest extends Controller {
	public function index() {
		echo __FUNCTION__;
	}

	/**
	*
	* @param $controller String structure to count the elements of.
	*
	* @return value the output
	*
	*/	
	public function controller(&$controller, &$data) {
		//$controller = 'test';
				
		//$data['test'] = 'This is a test var!';
		
		//echo 'Controller method args:' . "\n";

		//print_r($args);
		
		//return 'return controller test';
	}

	public function model(&$model, &$data) {
		//$this->event->trigger($model . '/' . __METHOD__ . '/before', $data);
		
		//echo __METHOD__;
		
		//$this->event->trigger($model . '/' . __METHOD__ . '/after', $data);
		
		//return $output;
		
		//return array('return model test');
	}
	
	public function view(&$view, &$data) {
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $view)) {
			$view = $this->config->get('config_template') . '/template/' . $view;
		} else {
			$view = 'default/template/' . $view;
		}
	}	
	
	public function after(&$output) {
		//$output = 1;
		
		//return 'hi';
	}	
}
