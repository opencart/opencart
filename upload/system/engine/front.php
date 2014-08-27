<?php
namespace Engine;
final class Front {
	private $registry;
	private $pre_action = array();
	private $error;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function addPreAction($pre_action) {
		$this->pre_action[] = $pre_action;
	}

	public function dispatch($action, $error) {
		$this->error = $error;

		foreach ($this->pre_action as $pre_action) {
			$result = $this->execute($pre_action);

			if ($result) {
				$action = $result;

				break;
			}
		}

		while ($action) {
			$action = $this->execute($action);
		}
	}

	private function execute($action) {
		$class = 'Controller\\' . $action->getClass();
		
		echo '<b>' . $action->getClass() . '</b><br>';
		
		if (class_exists($class)) {
			$controller = new $class($this->registry);
		
			if (is_callable(array($controller, $action->getMethod()))) {
				$action = call_user_func_array(array($controller, $action->getMethod()), $action->getArgs());
			
				
			
			} else {
				$action = $this->error;
	
				$this->error = '';
			}
		} else {
			$action = $this->error;

			$this->error = '';				
		}

		return $action;
	}
}