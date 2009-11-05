<?php
class ControllerStep4 extends Controller {
	public function index() {
		$this->children = array(
			'header',
			'footer'
		);
		
		$this->template = 'step_4.tpl';

		$this->response->setOutput($this->render(TRUE));
	}
}
?>