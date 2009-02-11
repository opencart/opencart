<?php
final class Front {
	protected $pre_action = array();
	protected $error;

	public function addPreAction($pre_action) {
		$this->pre_action[] = $pre_action;
	}
	
  	public function dispatch($action, $error) {
		$this->error = $error;

		while ($action) {
			foreach ($this->pre_action as $pre_action) {
				$result = $this->execute($pre_action);
						
				if ($result) {
					$action = $result;
					
					break;
				}
			}
			
			$action = $this->execute($action);
		}
  	}
    
	private function execute($action) {
		$file   = DIR_APPLICATION . 'controller/' . $action->getClass() . '.php';
		$class  = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $action->getClass());
		$method = $action->getMethod();
		$args   = $action->getArgs();

		$action = NULL;

		if (file_exists($file)) {
			require_once($file);

			$controller = new $class();
			
			if (is_callable(array($controller, $method))) {
				$action = call_user_func_array(array($controller, $method), $args);
			} else {
				$action = $this->error;
			
				$this->error = NULL;
			}
		} else {
			$action = $this->error;
			
			$this->error = NULL;
		}
		
		return $action;
	}
}
?>