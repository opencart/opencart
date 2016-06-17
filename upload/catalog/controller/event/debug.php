<?php
class ControllerEventDebug extends Controller {
	public function before(&$route, &$data) {
		$this->session->data['debug'][$route] = microtime();
	}
	
	public function after(&$route, &$data, &$output) {
		//if ($route == '') {
			if (isset($this->session->data['debug'][$route])) {
				$data = array(
					'route' => $route,
					'time'  => microtime() - $this->session->data['debug'][$route]
				);
				
				$this->log->write($data);
			}
		//}
	}	
}
