<?php
class ControllerStep3 extends Controller {
	public function index() {
		$this->id       = 'content';
		$this->template = 'step_3.tpl';
		$this->layout   = 'layout';
		$this->render();
	}
}
?>