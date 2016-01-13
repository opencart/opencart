<?php
class ControllerEventDebug extends Controller {
	public function index($route) {
		if ($route == '') {
			$this->log->write(func_get_args());
		}
	}
}
