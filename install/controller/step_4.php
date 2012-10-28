<?php
class ControllerStep4 extends Controller {
	public function index() {
		$this->template = 'step_4.tpl';
		$this->children = array(
			'header',
			'footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>