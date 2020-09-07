<?php
namespace Opencart\Application\Controller\Event;
class Debug extends \Opencart\System\Engine\Controller {
	public function before(&$route, &$args) {
		// add the route you want to test
		//if ($route == 'common/home') {
			$this->session->data['debug'][$route] = microtime(true);
		//}
	}
	
	public function after($route, &$args, &$output) {
		// add the route you want to test
		//if ($route == 'common/home') {
			if (isset($this->session->data['debug'][$route])) {
				$log_data = [
					'route' => $route,
					'time'  => microtime(true) - $this->session->data['debug'][$route]
				];
				
				$this->log->write($log_data);
			}
		//}
	}	
}