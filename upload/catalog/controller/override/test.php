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
	public function controller($controller, &$data) {
		//$controller = 'test';
				
		//$data['test'] = 'This is a test var!';
		
		//echo 'Controller method args:' . "\n";

		//print_r($args);
		
		//return 'return controller test';
	}
	
	/**
	*
	* @param $controller String structure to count the elements of.
	*
	* @return value the output
	*
	*/	
	public function model(&$model, &$data) {
		// Get args by reference
		//$args = func_get_args();
		
		//print_r($args);
		
		$model = 'catalog/product/getProduct';			
		$data[0] = 3;
					
		//echo __METHOD__;
		
		//return $output;
		//'return model test'
		//return array();
	}
	
	/**
	*
	* @param $controller String structure to count the elements of.
	*
	* @return value the output
	*
	*/		
	public function view(&$view, &$data) {
	
	}	
}
