<?php
class ControllerEventDebug extends Controller {
	public function before(&$route, &$data) {
		if ($route == '') {
			$this->log->write(func_get_args());
		}
	}
	
	public function after(&$route, &$data, &$output) {
		if ($route == '') {
			$this->log->write(func_get_args());
		}
	}	
}
