<?php
class ControllerEventDebug extends Controller {
	public function before($route, &$args) {
		if ($route == '') { // add the route you want to test
			$this->session->data['debug'][$route] = microtime();
		}
	}
	
	public function after($route, &$args, &$output) {
		if ($route == '') { // add the route you want to test
			if (isset($this->session->data['debug'][$route])) {
				$data = array(
					'route' => $route,
					'time'  => microtime() - $this->session->data['debug'][$route]
				);
				
				$this->log->write($data);
			}
		}
	}	
}
