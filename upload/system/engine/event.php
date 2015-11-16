<?php
class Event {
	protected $registry;
	public $data = array();

	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function register($trigger, $action) {
		$this->data[$trigger][] = $action;
	}
	
	public function unregister($trigger, $action) {
		if (isset($this->data[$trigger])) {
			foreach ($this->data[$trigger] as $key => $event) {
				if ($event == $action) {
					unset($this->data[$trigger][$key]);
				}
			}
		}
	}

	public function trigger($trigger, $args = array()) {
		
		foreach ($this->data as $key => $event) {
			//echo $key . "\n";
			//'catalog/view/*/before'
			
			//$keywords = preg_quote($keywords, '/');
			//'/^view\/(.*)$/i'
			$matches = array();
			
			if (preg_match('/^({' . preg_quote($key, '/') . '})/', $trigger, $matches)) {
				echo 'Trigger: ' . $trigger . "\n";
				echo 'Matched: ' . $key . "\n";
				
				print_r($matches);
				exit();
								
				
				//'/#' . preg_quote('view/', '/') . '#/'
				

								
				//$result = $event->execute($args);
			
				//if (!is_null($result)) {
				//	return $result;
				//}				
			}
		}
	}
}